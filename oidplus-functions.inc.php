<?php

namespace {
\defined('INSIDE_OIDPLUS') or die;	
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
		$q="SELECT table_name AS `table`,
ROUND(((data_length + index_length) / 1024 / 1024), 2) AS `megabyte`
FROM information_schema.TABLES
WHERE table_schema = ? AND table_name LIKE '".str_replace('_', '\_', \ViaThinkSoft\OIDplus\Core\OIDplus::baseConfig()->getValue('TABLENAME_PREFIX'))."%'
ORDER BY (data_length + index_length) DESC";
		
		//  die($q);
	
		$resQ = \ViaThinkSoft\OIDplus\Core\OIDplus::db()->query($q, [	
			\ViaThinkSoft\OIDplus\Core\OIDplus::baseConfig()->getValue('MYSQL_DATABASE'), 
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
