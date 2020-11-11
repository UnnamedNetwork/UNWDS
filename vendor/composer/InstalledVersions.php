<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '3.15.4',
    'version' => '3.15.4.0',
    'aliases' => 
    array (
    ),
    'reference' => 'e8b6b56330c8918b911ccc48e88a88b1e2cd201c',
    'name' => 'pocketmine/pocketmine-mp',
  ),
  'versions' => 
  array (
    'adhocore/json-comment' => 
    array (
      'pretty_version' => '0.1.0',
      'version' => '0.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8448076039389f558f39ad0553aab87db3f81614',
    ),
    'daverandom/callback-validator' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'pocketmine/binaryutils' => 
    array (
      'pretty_version' => '0.1.12',
      'version' => '0.1.12.0',
      'aliases' => 
      array (
      ),
      'reference' => '566fa87829e007eda0bd96e39fe20b9b0d638132',
    ),
    'pocketmine/callback-validator' => 
    array (
      'pretty_version' => '1.0.2',
      'version' => '1.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '8321aa3ccfe63639b0d08f0cbf270755cfc99fe2',
    ),
    'pocketmine/classloader' => 
    array (
      'pretty_version' => '0.1.1',
      'version' => '0.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '7c0363491d1ce8f914fe96d41a4338c982adedff',
    ),
    'pocketmine/log' => 
    array (
      'pretty_version' => '0.2.0',
      'version' => '0.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e59bedb5d4bbeb9a26647cb7c367cb2fa72addfa',
    ),
    'pocketmine/log-pthreads' => 
    array (
      'pretty_version' => '0.1.1',
      'version' => '0.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '9bbcef398b01487ab47c234a6a7054722abbe067',
    ),
    'pocketmine/math' => 
    array (
      'pretty_version' => '0.2.5',
      'version' => '0.2.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '8c46cfa95351fb0b2b30739a381310941934b55f',
    ),
    'pocketmine/nbt' => 
    array (
      'pretty_version' => '0.2.15',
      'version' => '0.2.15.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f8a81d37d24eb79fb77d985a52549d68955bc6a1',
    ),
    'pocketmine/pocketmine-mp' => 
    array (
      'pretty_version' => '3.15.4',
      'version' => '3.15.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e8b6b56330c8918b911ccc48e88a88b1e2cd201c',
    ),
    'pocketmine/raklib' => 
    array (
      'pretty_version' => '0.12.9',
      'version' => '0.12.9.0',
      'aliases' => 
      array (
      ),
      'reference' => '5f2a02009f486ca4d90892814570fa13ebdc078d',
    ),
    'pocketmine/snooze' => 
    array (
      'pretty_version' => '0.1.3',
      'version' => '0.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '849510fa62e57512b8467e3694e9b3add97038fd',
    ),
    'pocketmine/spl' => 
    array (
      'pretty_version' => '0.4.1',
      'version' => '0.4.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ff0579a0be41bbe65d3637607715c0f87728a838',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
