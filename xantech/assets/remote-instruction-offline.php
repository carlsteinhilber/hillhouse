<?php

header('Content-Type: application/json');

// include "PhpSerial.php";

$timeOut=30;



function ensureChar( $inStr ){
    $outStr = trim( $inStr );
    if( strlen( $outStr ) < 1 ){
        $outStr = " ";
    }
    return $outStr;
};

function pullChars( $inStr ){
   if($inStr[0]=="?")return substr($inStr,3);
   return substr($inStr,2);
};



$commandPrecursor="!";
$commandMode = trim(strtoupper($_GET["mode"]));
if($commandMode == "Q")$commandPrecursor="?";

$commandIn = trim(strtoupper($_GET["cmd"]));
$channelNum = $_GET["chan"];
    

if($channelNum==''||$channelNum<1){
	// global commands
	$xantechCmd = $commandPrecursor. $commandIn . "+";

	
} else {
	// channel specific commands
	$xantechCmd = $commandPrecursor. $channelNum . $commandIn . "+";

	
	
}
$statusStruct='{}';

switch($commandIn){
case 'PR':
	if($commandMode== "Q"){
		$outStatus="?".$channelNum."PR".mt_rand(0,1)."+";
	} else {
		$outStatus="OK";
	}
		
	break;	
case 'ZD':
	if($commandMode== "Q"){
		$devResponse='?PR'.mt_rand(0,1).' SS'.mt_rand(1,8).' VO'.mt_rand(0,38).' MU'.mt_rand(0,1).' TR'.mt_rand(0,14).' BS'.mt_rand(0,14).' BA'.mt_rand(0,63).' LS0 PS0+';
		$statusStruct='{ ';
		$statusArray=explode(' ',$devResponse);
		if( sizeof($statusArray) > 4){
		   $statusStruct.=' "power":' . pullChars($statusArray[0]). ',';
		   $statusStruct.=' "source":' . pullChars($statusArray[1]) . ',';
		   $statusStruct.=' "volume":' . pullChars($statusArray[2]) . ',';
		   $statusStruct.=' "mute":' . pullChars($statusArray[3]);
		}
		$statusStruct.=' }';

	
	
	} else {
		$outStatus="ERROR";
	}
		
	break;			
}




/*
Power – On
Source – 4
Volume – 8
Mute – Off
Treble – 7
Bass – 7
Balance – 32
Linked – No
Paged – No      

PR1 SS1 VO0 MU0 TR7 BS7 BA32 LS0 PS0
*/




echo '{ "channel":"' . $channelNum . '", "cmd":"' . $xantechCmd . '", "status":'.$statusStruct.' }';

?>    
