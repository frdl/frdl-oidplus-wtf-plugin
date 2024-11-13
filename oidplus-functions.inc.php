<?php

namespace {
	
	use ViaThinkSoft\OIDplus\Core\OIDplus;
	use ViaThinkSoft\OIDplus\Core\OIDplusException;
	
\defined('INSIDE_OIDPLUS') or die;
 
 /*
 * https://gist.github.com/hussnainsheikh/ea33936d469170d98628315043d9980f
 */
 function oidplus_prunde_dir(string $dir,int $max_age, ?bool $withRealpath = true,?array &$list = array()) : array {
  //  $list = array();
  
    $limit = time() - max(0,$max_age);
  
    $dir = true === $withRealpath ? realpath($dir) : $dir; 
    if (!is_dir($dir)) {
        return $list;
    }
  
    $dh = opendir($dir);
    if ($dh === false) {
        return $list;
    }
  
    while (($file = readdir($dh)) !== false) {

        if ($file != "." && $file != "..") {

            $file = $dir . '/' . $file;
            if (!is_file($file)) {
                if(count(glob("$file/*")) === 0)
                    rmdir($file);
                oidplus_prunde_dir($file, $max_age, $withRealpath, $list);
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
	
 function oidplus_prunde_cache(?string $subdir = null, ?int $max_age = 30879000, ?bool $withRealpath = true) : array {
	 $max_age = max(60,$max_age);
	 return oidplus_prunde_dir(oidplus_cache_dir($subdir), $max_age, $withRealpath);
 }
	
 function oidplus_cache_dir(?string $subdir = null) : string {
  $d = null === $subdir
	 ? rtrim(OIDplus::getUserDataDir("cache"),'/ \\ ').\DIRECTORY_SEPARATOR
	 : rtrim(OIDplus::getUserDataDir("cache"),'/ \\ ').\DIRECTORY_SEPARATOR.trim($subdir,'/ \\ ').\DIRECTORY_SEPARATOR;
  if(null !== $subdir && !is_dir($d)){
	  mkdir($d, 0755, true); 
  }
  return $d;
 }
	
 function oidplus_cache_file(string | array | stdclass $dataForKey, ?string $subdirCacheName = null, ?string $ext = 'txt') : string {
	 $d = serialize($dataForKey);
	 $file = sha1($d).'-'.strlen($d).(null===$ext ? '' : '.'.$ext);
     $dir = oidplus_cache_dir($subdirCacheName);	 
  return $dir.$file;
 }
	
	
 function oidplus_rdap_root_server() : string {
	 return OIDplus::baseConfig()->getValue('RDAP_ROOT_CLIENT_SERVER', 'https://oid.zone/rdap/');
 }
	
 function oidplus_rdap_root_request(string $objectType, string $name, int | null $cacheLimit = 300){
	 $timeout = 10 * 60;
	 set_time_limit($timeout + 10);
	 $dataForKey = [		
		 'server' => oidplus_rdap_root_server(),
		 'action' => __FUNCTION__,		
		 'type'=>$objectType,		 
		 'name'=>$name,
	];
	 $file = oidplus_cache_file($dataForKey, __FUNCTION__, 'json');
	 if(is_null($cacheLimit) || $cacheLimit < 0 || !file_exists($file) || filemtime($file) < time() - intval($cacheLimit) ){
	   $referer = oidplus_current_url();
	   $userAgent ='Rdap+Client (OIDplus/wtf-Plugin+'.$_SERVER['HTTP_HOST'].')';
       $stream_options = array(
           'http'=>array(		
			  'timeout' => $timeout, 
	          'ignore_errors' => true,			 
		     'follow_location' => true,
              'method'=>"GET",
              'header'=>"Accept-language: en\r\n" 
		   // ."Cookie: foo=bar\r\n"
                 . "User-Agent: $userAgent\r\n"  
		       ."Referer: $referer\r\n"
            )
        );	   
         $stream_context = stream_context_create($stream_options);		
		 $result = @file_get_contents(oidplus_rdap_root_server().'/'.$objectType.'/'.$name, false, $stream_context);	
		 file_put_contents($file, $result);
	 }//not in cache or skip cache
	 
	   if(!file_exists($file)){
		   throw new OIDplusException(sprintf('Error in function %s line %d with params "%s" and "%s"!', 
											  __FUNCTION__, 
											  __LINE__,
											 $objectType,
											 $name), 
									          'Could not request ROOT RDAP CLIENT SERVER',
									          409);
	   }	
	 $c = file_get_contents($file);
	 $data = json_decode($c);
	return $data;
 }

 function oidplus_current_url(){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName. $_SERVER['REQUEST_URI'];
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
