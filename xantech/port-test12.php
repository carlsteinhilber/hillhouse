<!doctype html>
<html lang="en">
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our House</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/home-control.css" />
    <link rel="stylesheet" href="assets/css/jquery.mobile.icons.min.css" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <style>
        p {
            margin: 20px 0 0 0;
        }
    </style>

    <script>
        function setStatus(chan,statusArray){
            
            
        };
        
        function getStatus(chan) {
            $.getJSON("assets/remote-instruction.php", {
                    mode: "Q",
                    cmd: (chan + "ZD")
                })
                .done(function(iStatus) {
                    console.log("getJSON done");
                    console.log(iStatus);



                    devChannel = iStatus.channel;
                    channelStatus = iStatus.status;

                    console.log(channelStatus);

                    devStatus = channelStatus.substring(channelStatus.indexOf(" "), (channelStatus.length - 1));
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

                    channelStatusArray = devStatus.split(' ');
                    console.log(devStatus);
                    $(".chan" + devChannel + " span").removeClass().addClass(devStatus);
                })
                .fail(function(jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);
                });
        };
        function rc(mode, cmd) {
            $.getJSON("assets/remote-instruction.php", {
                    mode: "I",
                    cmd: cmd
                })
                .done(function(json) {
                    console.log(json);

                    devChannel = json.channel;
                    getStatus(devChannel);
                
                
                
                })
                .fail(function(jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);
                });
        }
    </script>

    <style>
        .ui-page-theme-a button.btn-blue {
            color: #fff;
            background-color: #3388cc;
            width: auto;
        }

        .PR0 .fa-power-off {
            color: #fff;
        }

        .PR1 .fa-power-off {
            color: #f00;
        }
    </style>


</head>

<body>

<?php
    $channelArray=array(
        "PRE",
        "LIVING ROOM",
        "KITCHEN",
        "DINING ROOM",
        "BACKYARD",
        "",
        "",
        "",
        ""
    );

    $sourceArray=array(
        "PRE",
        "RADIO",
        "TV",
        "LIBRARY",
        "SOURCE 4",
        "SOURCE 5",
        "SOURCE 6",
        "SOURCE 7",
        "SOURCE 8"
    );

    // echo sizeof($channelArray);
    // print_r($channelArray);
?>

    <div class="container">
        <div class="page-header">
            <h1>Music Control</h1>
        </div>
            <input type="text" id="statusField" value="" />
        <button type="button" class="btn btn-lg btn-primary btn-blue" onclick="rc('!AO+'); return false;">ALL OFF</button>
 
        <?php
            for($chanNum = 1; $chanNum < sizeof($channelArray); $chanNum++) {
                $chanTitle=$channelArray[$chanNum];
                    if(strlen($chanTitle)>0){
        ?>
        <div class="row">
            <div class="col-sm-12"><p><?php echo $chanTitle; ?></p><button class="btn btn-lg btn-primary btn-blue status-btn"  onclick="getStatus(<?php echo $chanNum; ?>);return false;">Status</button></div>
        </div>
        
        <div class="row channel chan<?php echo $chanNum; ?>">
            <span>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-lg btn-primary btn-blue power-btn" data-channel="<?php echo $chanNum; ?>" onclick="rc('I','<?php echo $chanNum; ?>PT'); return false;"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </div>
                <div class="col-sm-6">
                    <label for="slider-fill">Input slider:</label>
                    <input type="range" name="volumeLevel<?php echo $chanNum; ?>" id="volumeLevel<?php echo $chanNum; ?>" class="volume-slider" value="60" min="0" max="100" data-channel="<?php echo $chanNum; ?>" data-highlight="true">
                </div>   
                <div class="col-sm-5">
                    <div data-role="fieldcontain">
                        <select name="sourceSelect<?php echo $chanNum; ?>" id="sourceSelect<?php echo $chanNum; ?>" data-channel="<?php echo $chanNum; ?>" data-native-menu="false" data-theme="a" data-form="ui-btn-up-a">
                            <?php
                                for($sourceNum = 1; $sourceNum < sizeof($sourceArray); $sourceNum++) {
                                    $sourceTitle=$sourceArray[$sourceNum];
                                    if(strlen($sourceTitle)>0){
                            ?>
                            <option value="standard"><?php echo $sourceTitle; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>

            </span>
        </div>
        <?php
                }
            }
        ?>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--- script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script --->
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <script>
   ///  $(document).ready({
        /*
        $('input[type=range]').on('input', function () {
            // $(this).trigger('change');
           console.log("change");
        });
          */
  $( document ).ready(function() {    
      /*
      console.log("DOM is ready");
        $('input[type=range]').on('stop', function () {
            console.log(e);
        });
        */
 
<?php
    for($chanNum = 1; $chanNum < sizeof($channelArray); $chanNum++) {
        $chanTitle=$channelArray[$chanNum];
            if(strlen($chanTitle)>0){
               
?>
      
     chanStatus=getStatus( <?php echo $chanNum; ?> );
      console.log(chanStatus);
     
      
<?php
            }
       
    }
               
?>
       
      
      
      
      
      $("#setTest").on('click',function(){
          $("#volumeLevel1").val(50).slider("refresh");
      });
      
      
      $( ".volume-slider" ).on( "slidestop", function( event, ui ) { 
          console.log(
          'channel '+$(event.currentTarget).data('channel'));
           console.log(
          'volume '+$(event.currentTarget).val());
          
      } );
  
  });
        
        /*
        $(document).on('stop', 'input[type=range]', function() {
            console.log("change");
        });  
        */
      
        /*
        $( ".volume-slider" ).slider({
  stop: function( event, ui ) {  console.log("change"); }
});
        */
        /*
        $('.volume-slider').on('mouseup',function(){
            $("#statusField").val("change");
            
        })
        */
        
 ///   });
    
    
    
    </script>
    
    
</body>


</html>