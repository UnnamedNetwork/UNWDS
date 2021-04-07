<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\scheduler;

use pocketmine\snooze\SleeperHandler;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\utils\Utils;
use function array_keys;
use function array_map;
use function assert;
use function count;
use function spl_object_id;
use function time;
use const PHP_INT_MAX;
use const PTHREADS_INHERIT_CONSTANTS;
use const PTHREADS_INHERIT_INI;

/**
 * Manages general-purpose worker threads used for processing asynchronous tasks, and the tasks submitted to those
 * workers.
 */
class AsyncPool{
	private const WORKER_START_OPTIONS = PTHREADS_INHERIT_INI | PTHREADS_INHERIT_CONSTANTS;

	/** @var \ClassLoader */
	private $classLoader;
	/** @var \ThreadedLogger */
	private $logger;
	/** @var int */
	protected $size;
	/** @var int */
	private $workerMemoryLimit;

	/**
	 * @var \SplQueue[]|AsyncTask[][]
	 * @phpstan-var array<int, \SplQueue<AsyncTask>>
	 */
	private $taskQueues = [];

	/**
	 * @var AsyncWorker[]
	 * @phpstan-var array<int, AsyncWorker>
	 */
	private $workers = [];
	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private $workerLastUsed = [];

	/**
	 * @var \Closure[]
	 * @phpstan-var (\Closure(int $workerId) : void)[]
	 */
	private $workerStartHooks = [];

	/** @var SleeperHandler */
	private $eventLoop;

	public function __construct(int $size, int $workerMemoryLimit, \ClassLoader $classLoader, \ThreadedLogger $logger, SleeperHandler $eventLoop){
		$this->size = $size;
		$this->workerMemoryLimit = $workerMemoryLimit;
		$this->classLoader = $classLoader;
		$this->logger = $logger;
		$this->eventLoop = $eventLoop;
	}

	/**
	 * Returns the maximum size of the pool. Note that there may be less active workers than this number.
	 */
	public function getSize() : int{
		return $this->size;
	}

	/**
	 * Increases the maximum size of the pool to the specified amount. This does not immediately start new workers.
	 */
	public function increaseSize(int $newSize) : void{
		if($newSize > $this->size){
			$this->size = $newSize;
		}
	}

	/**
	 * Registers a Closure callback to be fired whenever a new worker is started by the pool.
	 * The signature should be `function(int $worker) : void`
	 *
	 * This function will call the hook for every already-running worker.
	 *
	 * @phpstan-param \Closure(int $workerId) : void $hook
	 */
	public function addWorkerStartHook(\Closure $hook) : void{
		Utils::validateCallableSignature(function(int $worker) : void{}, $hook);
		$this->workerStartHooks[spl_object_id($hook)] = $hook;
		foreach($this->workers as $i => $worker){
			$hook($i);
		}
	}

	/**
	 * Removes a previously-registered callback listening for workers being started.
	 *
	 * @phpstan-param \Closure(int $workerId) : void $hook
	 */
	public function removeWorkerStartHook(\Closure $hook) : void{
		unset($this->workerStartHooks[spl_object_id($hook)]);
	}

	/**
	 * Returns an array of IDs of currently running workers.
	 *
	 * @return int[]
	 */
	public function getRunningWorkers() : array{
		return array_keys($this->workers);
	}

	/**
	 * Fetches the worker with the specified ID, starting it if it does not exist, and firing any registered worker
	 * start hooks.
	 */
	private function getWorker(int $worker) : AsyncWorker{
		if(!isset($this->workers[$worker])){
			$notifier = new SleeperNotifier();
			$this->workers[$worker] = new AsyncWorker($this->logger, $worker, $this->workerMemoryLimit, $notifier);
			$this->eventLoop->addNotifier($notifier, function() use ($worker) : void{
				$this->collectTasksFromWorker($worker);
			});
			$this->workers[$worker]->setClassLoader($this->classLoader);
			$this->workers[$worker]->start(self::WORKER_START_OPTIONS);

			$this->taskQueues[$worker] = new \SplQueue();

			foreach($this->workerStartHooks as $hook){
				$hook($worker);
			}
		}

		return $this->workers[$worker];
	}

	/**
	 * Submits an AsyncTask to an arbitrary worker.
	 */
	public function submitTaskToWorker(AsyncTask $task, int $worker) : void{
		if($worker < 0 or $worker >= $this->size){
			throw new \InvalidArgumentException("Invalid worker $worker");
		}
		if($task->isSubmitted()){
			throw new \InvalidArgumentException("Cannot submit the same AsyncTask instance more than once");
		}

		$task->progressUpdates = new \Threaded;
		$task->setSubmitted();

		$this->getWorker($worker)->stack($task);
		$this->taskQueues[$worker]->enqueue($task);
		$this->workerLastUsed[$worker] = time();
	}

	/**
	 * Selects a worker ID to run a task.
	 *
	 * - if an idle worker is found, it will be selected
	 * - else, if the worker pool is not full, a new worker will be selected
	 * - else, the worker with the smallest backlog is chosen.
	 */
	public function selectWorker() : int{
		$worker = null;
		$minUsage = PHP_INT_MAX;
		foreach($this->taskQueues as $i => $queue){
			if(($usage = $queue->count()) < $minUsage){
				$worker = $i;
				$minUsage = $usage;
				if($usage === 0){
					break;
				}
			}
		}
		if($worker === null or ($minUsage > 0 and count($this->workers) < $this->size)){
			//select a worker to start on the fly
			for($i = 0; $i < $this->size; ++$i){
				if(!isset($this->workers[$i])){
					$worker = $i;
					break;
				}
			}
		}

		assert($worker !== null);
		return $worker;
	}

	/**
	 * Submits an AsyncTask to the worker with the least load. If all workers are busy and the pool is not full, a new
	 * worker may be started.
	 */
	public function submitTask(AsyncTask $task) : int{
		if($task->isSubmitted()){
			throw new \InvalidArgumentException("Cannot submit the same AsyncTask instance more than once");
		}

		$worker = $this->selectWorker();
		$this->submitTaskToWorker($task, $worker);
		return $worker;
	}

	/**
	 * Collects finished and/or crashed tasks from the workers, firing their on-completion hooks where appropriate.
	 *
	 * @throws \ReflectionException
	 * @return bool whether there are tasks left to be collected
	 */
	public function collectTasks() : bool{
		$more = false;
		foreach($this->taskQueues as $worker => $queue){
			$more = $this->collectTasksFromWorker($worker) || $more;
		}
		return $more;
	}

	public function collectTasksFromWorker(int $worker) : bool{
		if(!isset($this->taskQueues[$worker])){
			throw new \InvalidArgumentException("No such worker $worker");
		}
		$queue = $this->taskQueues[$worker];
		$more = false;
		while(!$queue->isEmpty()){
			/** @var AsyncTask $task */
			$task = $queue->bottom();
			$task->checkProgressUpdates();
			if($task->isFinished()){ //make sure the task actually executed before trying to collect
				$queue->dequeue();

				if($task->isCrashed()){
					$this->logger->critical("Could not execute asynchronous task " . (new \ReflectionClass($task))->getShortName() . ": Task crashed");
					$task->onError();
				}elseif(!$task->hasCancelledRun()){
					/*
					 * It's possible for a task to submit a progress update and then finish before the progress
					 * update is detected by the parent thread, so here we consume any missed updates.
					 *
					 * When this happens, it's possible for a progress update to arrive between the previous
					 * checkProgressUpdates() call and the next isGarbage() call, causing progress updates to be
					 * lost. Thus, it's necessary to do one last check here to make sure all progress updates have
					 * been consumed before completing.
					 */
					$task->checkProgressUpdates();
					$task->onCompletion();
				}
			}else{
				$more = true;
				break; //current task is still running, skip to next worker
			}
		}
		$this->workers[$worker]->collect();
		return $more;
	}

	/**
	 * Returns an array of worker ID => task queue size
	 *
	 * @return int[]
	 * @phpstan-return array<int, int>
	 */
	public function getTaskQueueSizes() : array{
		return array_map(function(\SplQueue $queue) : int{ return $queue->count(); }, $this->taskQueues);
	}

	public function shutdownUnusedWorkers() : int{
		$ret = 0;
		$time = time();
		foreach($this->taskQueues as $i => $queue){
			if((!isset($this->workerLastUsed[$i]) or $this->workerLastUsed[$i] + 300 < $time) and $queue->isEmpty()){
				$this->workers[$i]->quit();
				$this->eventLoop->removeNotifier($this->workers[$i]->getNotifier());
				unset($this->workers[$i], $this->taskQueues[$i], $this->workerLastUsed[$i]);
				$ret++;
			}
		}

		return $ret;
	}

	/**
	 * Cancels all pending tasks and shuts down all the workers in the pool.
	 */
	public function shutdown() : void{
		$this->collectTasks();

		foreach($this->workers as $worker){
			/** @var AsyncTask $task */
			while(($task = $worker->unstack()) !== null){
				//NOOP: the below loop will deal with marking tasks as garbage
			}
		}
		foreach($this->taskQueues as $queue){
			while(!$queue->isEmpty()){
				/** @var AsyncTask $task */
				$task = $queue->dequeue();
				$task->cancelRun();
			}
		}

		foreach($this->workers as $worker){
			$worker->quit();
			$this->eventLoop->removeNotifier($worker->getNotifier());
		}
		$this->workers = [];
		$this->taskQueues = [];
		$this->workerLastUsed = [];
	}
}
