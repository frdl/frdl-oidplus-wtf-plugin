<?php

namespace {
	
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
