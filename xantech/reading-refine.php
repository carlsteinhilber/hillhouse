<!doctype html>
<html lang="en">
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include "assets/PhpSerial.php";

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our House</title>

</head>

<body>

<?php


$timeOut=90;



function ensureChar( $inStr ){
    $outStr = trim( $inStr );
    if( strlen( $outStr ) < 1 ){
        $outStr = " ";
    }
    return $outStr;
}

    
$serial = new PhpSerial;
$serial->deviceSet("/dev/ttyUSB0");
//$serial->confBaudRate(38400);

$serial->deviceOpen();

$serial->sendMessage("?1ZD+");

$devResponse="";
$revCount=0;
$needMoreChars=true;
$procStart=time();

while($needMoreChars){
    $devResponse.=strtoupper(trim($serial->readPort()));
    $devResponse=trim($devResponse);
    echo $devResponse . "<br />";
    if($devResponse=="OK"||$devResponse=="ERROR"||substr($devResponse,-1)=='+'){
        $needMoreChars=false;
    }
}
    
echo "<p>Performed in: ".(time()-$procStart)." seconds</p>";

$serial->deviceClose();

echo '{ "channel":"' . $channelNum . '", "cmd":"' . $xantechCmd . '", "status":"' . $devResponse . '" }';

?>    

    
</body>


</html>


