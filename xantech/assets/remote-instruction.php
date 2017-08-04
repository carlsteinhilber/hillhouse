<?php

header('Content-Type: application/json');

include "PhpSerial.php";

$timeOut=30;



function ensureChar( $inStr ){
    $outStr = trim( $inStr );
    if( strlen( $outStr ) < 1 ){
        $outStr = " ";
    }
    return $outStr;
};

function pullChars( $inStr ){
   return substr($inStr,2);
};



$commandPrecursor="!";

$commandIn = trim(strtoupper($_GET["cmd"]));
$commandMode = trim(strtoupper($_GET["mode"]));
if($commandMode == "Q")$commandPrecursor="?";

$channelNum = $commandIn[0];
    
$xantechCmd = $commandPrecursor. $commandIn . "+";


$serial = new PhpSerial;
$serial->deviceSet("/dev/ttyUSB0");

$serial->confBaudRate(96);

$serial->deviceOpen();

$serial->sendMessage($xantechCmd);

$devResponse="";
$revCount=0;
$needMoreChars=true;
$procStart=time();

while($needMoreChars){
    $devResponse.=strtoupper(trim($serial->readPort()));
    $devResponse=trim($devResponse);
    if($devResponse=="OK"||$devResponse=="ERROR"||substr($devResponse,-1)=='+'||time()-$procStart>$timeOut){
        $needMoreChars=false;
    }
}
$serial->deviceClose();

$statusStruct='{ ';

$statusArray=explode(' ',$devResponse);
if( sizeof($statusArray) > 4){
   $statusStruct.=' "power":' . pullChars($statusArray[1]). ',';
   $statusStruct.=' "source":' . pullChars($statusArray[2]) . ',';
   $statusStruct.=' "volume":' . pullChars($$statusArray[3]) . ',';
   $statusStruct.=' "mute":' . pullChars(statusArray[4]);
}

$statusStruct.=' }';

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




echo '{ "channel":"' . $channelNum . '", "cmd":"' . $xantechCmd . '", "status":' . $statusStruct . ' }';

?>    
