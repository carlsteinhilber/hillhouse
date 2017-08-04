<?php

header('Content-Type: application/json');

include "PhpSerial.php";


function ensureChar( $inStr ){
    $outStr = trim( $inStr );
    if( strlen( $outStr ) < 1 ){
        $outStr = " ";
    }
    return $outStr;
}
$commandPrecursor="!";

$commandIn = trim(strtoupper($_GET["cmd"]));
$commandMode = trim(strtoupper($_GET["mode"]));
if($commandMode == "Q")$commandPrecursor="?";

$channelNum = $commandIn[0];
    
$xantechCmd = $commandPrecursor. $commandIn . "+";


$serial = new PhpSerial;
$serial->deviceSet("/dev/ttyUSB0");

$serial->deviceOpen();

$serial->sendMessage($xantechCmd);

$devResponse="";
$revCount=0;
$needMoreChars=true;
while($needMoreChars){
    $devResponse.=strtoupper(trim($serial->readPort(255)));
    $devResponse=trim($devResponse);
    if($devResponse=="OK"||$devResponse=="ERROR"||substr($devResponse,-1)=='+'||$revCount>999){
        $needMoreChars=false;
    }
    $revCount++;
}

$serial->deviceClose();

echo '{ "channel":"' . $channelNum . '", "cmd":"' . $xantechCmd . '", "status":"' . $devResponse . '" }';

?>    
