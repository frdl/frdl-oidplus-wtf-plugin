<?php

namespace {
	
	use ViaThinkSoft\OIDplus\Core\OIDplus;
	use ViaThinkSoft\OIDplus\Core\OIDplusException;
	
\defined('INSIDE_OIDPLUS') or die;
 
 /*
 * https://gist.github.com/hussnainsheikh/ea33936d469170d98628315043d9980f
 */
 function oidplus_prunde_dir(string $dir,int $max_age, ?bool $withRealpath = true) {
    $list = array();
  
    $limit = time() - max(0,$max_age);
  
    $dir = true === $withRealpath ? realpath($dir) : $dir; 
    if (!is_dir($dir)) {
        return;
    }
  
    $dh = opendir($dir);
    if ($dh === false) {
        return;
    }
  
    while (($file = readdir($dh)) !== false) {

        if ($file != "." && $file != "..") {

            $file = $dir . '/' . $file;
            if (!is_file($file)) {
                if(count(glob("$file/*")) === 0)
                    rmdir($file);
                oidplus_prunde_dir($file, $max_age, $withRealpath);
            }
        
            if (filemtime($file) < $limit) {
                $list[] = $file;
                unlink($file);
            }
        }
    }
    closedir($dh);
    return $list;
 }	
	
 function oidplus_prunde_cache(?string $subdir = null, int $max_age = 30879000) {
	 $max_age = max(60,$max_age);
	 oidplus_prunde_dir(oidplus_cache_dir($subdir), $max_age);
 }
	
 function oidplus_cache_dir(?string $subdir = null){
  $d = null === $subdir
	 ? rtrim(OIDplus::getUserDataDir("cache"),'/ \\ ').\DIRECTORY_SEPARATOR
	 : rtrim(OIDplus::getUserDataDir("cache"),'/ \\ ').\DIRECTORY_SEPARATOR.trim($subdir,'/ \\ ').\DIRECTORY_SEPARATOR;
  if(null !== $subdir && !is_dir($d)){
	  mkdir($d, 0755, true); 
  }
  return $d;
 }
	
 function oidplus_rdap_root_server(){
	 return OIDplus::baseConfig()->getValue('RDAP_ROOT_CLIENT_SERVER', 'https://oid.zone/rdap/');
 }
	
 function oidplus_format_bytes(int | float $bytes, int $precision = 2)
	{
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		  $bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . $units[$pow];
	}	
	
	
	
 function oidplus_quota_used_db(){
		$sum = 0;
	 if (OIDplus::db()->getSlang()->id() == 'mysql') {
			$q="SELECT table_name AS `table`,
ROUND(((data_length + index_length) / 1024 / 1024), 2) AS `megabyte`
FROM information_schema.TABLES
WHERE table_schema = ? AND table_name LIKE '".str_replace('_', '\_', OIDplus::baseConfig()->getValue('TABLENAME_PREFIX'))."%'
ORDER BY (data_length + index_length) DESC";
			} else if (OIDplus::db()->getSlang()->id() == 'mssql') {
				$q=false;
			} else if (OIDplus::db()->getSlang()->id() == 'oracle') {
				$q=false;
			} else if (OIDplus::db()->getSlang()->id() == 'pgsql') {
				$q=false;
			} else if (OIDplus::db()->getSlang()->id() == 'access') {
				$q=false;
			} else if (OIDplus::db()->getSlang()->id() == 'sqlite') {
				$q=false;
			} else if (OIDplus::db()->getSlang()->id() == 'firebird') {
				$q=false;
			} else {
				$q=false;
			}
		 
	   if(false === $q){
		   throw new OIDplusException(sprintf('SQLlang %s is not supported yet in function %s!', 
											  OIDplus::db()->getSlang()->id(), 
											  __FUNCTION__), 
									          'Unsupported SQL language for this service',
									          409);
	   }
	
		$resQ = OIDplus::db()->query($q, [	
			OIDplus::baseConfig()->getValue('MYSQL_DATABASE'), 
	  ]);
		$t = [];
		while ($row = $resQ->fetch_array()) { 
			$sum+=$row['megabyte'];
			$t[$row['table']] = $row['megabyte'];
		}
		
		return [
			'total'=>$sum,			
			'used'=>$t,			
		];
	}//oidplus_quota_used_db				
	
}
