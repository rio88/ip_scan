<?php
	
//SETTINGS
//Enter here the network IP Just like in the example below
$ip_area = "192.168.1.";
//Below eneter the file you are looking for in the network. You can leave this empty if you are not looking for a specific file
$file_to_scan = "index.html";


//Requirements
require_once '/Library/WebServer/Documents/ip_scan/src/PHP_Amp/PHP_Amp_Parallel_Functions/vendor/autoload.php';
use Amp\Promise;
use function Amp\ParallelFunctions\parallelMap;

//Variables
$results = array();
$urls_array = array();

//Intial Setup
for($i=1;$i<=256;$i++){
	$ipindex = $ip_area . $i;
	$urls_array[$ipindex] = "http://" . $ip_area . "$i/$file_to_scan";;	
}

// Executing functions
$devicesCurl = array();
		
	//Initialize multi_curl
	$curlMaster = curl_multi_init();
	//Only the TCP/IP Devices are included here
	foreach($urls_array as $deviceIp => $deviceUrl){
		//The device must be active to get real time values
				
				$devicesCurl[$deviceIp] = curl_init();
				curl_setopt($devicesCurl[$deviceIp], CURLOPT_URL, $deviceUrl);
				curl_setopt($devicesCurl[$deviceIp], CURLOPT_RETURNTRANSFER, true);
				curl_setopt($devicesCurl[$deviceIp],CURLOPT_FOLLOWLOCATION,true);
				curl_setopt($devicesCurl[$deviceIp],CURLOPT_CONNECTTIMEOUT_MS,5000);
				curl_multi_add_handle($curlMaster, $devicesCurl[$deviceIp]);
		
	}

	//Execute the multi_curl
do{
    $status = curl_multi_exec($curlMaster, $active);

	if ($active) {
	    curl_multi_select($curlMaster);
	}
}while($active && $status == CURLM_OK);
	

//Analyzing results
foreach($devicesCurl as $deviceIp => $callReturn){
	
	$resp = curl_getinfo($devicesCurl[$deviceIp], CURLINFO_HTTP_CODE);
	if($resp != 0){
		echo "Device found at $deviceIp. Response code: " . $resp;
		echo PHP_EOL;
	}
		
}
