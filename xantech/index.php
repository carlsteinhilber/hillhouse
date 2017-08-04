<!doctype html>
<html lang="en">
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
	
	$remoteEndpoint="assets/remote-instruction.php";
	$remoteEndpoint="assets/remote-instruction-offline.php";

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
			// devChannel = statusArray.channel;
			// channelStatus = statusArray.status;

			console.log("setStatus: channel=" + chan);
			console.log(statusArray.status);
			
			// channelRow=$('.chan'.chan);
			
			// $('.chan'+chan+' .source-select').selectmenu();
			$('.chan'+chan+' .power-btn').removeClass('PR0').removeClass('PR1').addClass('PR'+statusArray["status"]["power"]);
			$('.chan'+chan+' .volume-slider').val(statusArray["status"]["volume"]).slider("refresh");
			
			$('.chan'+chan+' .source-select').selectedIndex=statusArray["status"]["source"];
			
			 
			$('.chan'+chan+' .source-select').val(statusArray["status"]["source"]).attr('selected', true).siblings('option').removeAttr('selected');
			
			$('.chan'+chan+' select.source-select').selectmenu("refresh");
			
			// devStatus = channelStatus.substring(channelStatus.indexOf(" "), (channelStatus.length - 1));
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

			// channelStatusArray = devStatus.split(' ');
			// console.log(devStatus);
			// $(".chan" + devChannel + " span").removeClass().addClass(devStatus);
        };
		
		function sendCommand(mode,chan,cmd,value,callback){
			console.log("sendCommand called:"+chan);
            $.getJSON("<?php echo $remoteEndpoint; ?>", {
                    mode: mode,
					chan: chan,
                    cmd: cmd,
					value: value
                }).done( function(retStatus){
					console.log("getJSON done");
                    console.log(retStatus);
					callback(chan,retStatus);
				}).fail( function(jqxhr, textStatus, error){
                    var err = textStatus + ", " + error;
                    console.log("Request Failed: " + err);
                });
		};
		
		
        
        function getStatus(chan,status) {
			console.log("getStatus called:"+chan);
            sendCommand('Q',chan,'ZD','',setStatus);
			return true;
        };
		
		function getAllStatus(chan,status){
			<?php
				for($chanNum = 1; $chanNum < sizeof($channelArray); $chanNum++) {
					$chanTitle=$channelArray[$chanNum];
					if(strlen($chanTitle)>0){

			?>
					console.log("getStatus(<?php echo $chanNum; ?>)");
					getStatus( <?php echo $chanNum; ?> );


			<?php
						}

				}

			?>
			
		};
		
		
        function rc(mode, cmd) {
            $.getJSON("<?php echo $remoteEndpoint; ?>", {
                    mode: "I",
                    cmd: cmd
                })
                .done(function(json) {
					console.log("rc done");
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
            width: 100%;
        }

        .PR0 .fa-power-off {
            color: #fff;
        }

        .PR1 .fa-power-off {
            color: #f00;
        }
		.ui-field-contain .sourceSelectLabel {
			margin:10px 0 6px 0;
			width:100%;
			text-align: center;
			float:none;
			
		}
		.ui-slider-track .ui-btn.ui-slider-handle {
			height:44px;
			width:44px;
			margin:-22px 0 0 -22px;
		}
		.volumeSliderLabel{
			text-align: center;
			width:100%;
			position:relative;
			padding-top:10px;
			top:8px;
		}
		
		@media (min-width: 28em) {
			.ui-field-contain>label~[class*=ui-], .ui-field-contain .ui-controlgroup-controls {
				width:100%;
			}
			.ui-field-contain .sourceSelectLabel {
				width:100%;
			}
		}

		
		
    </style>


</head>

<body>

<?php

    // echo sizeof($channelArray);
    // print_r($channelArray);
?>

    <div class="container">
        <div class="page-header">
            <h1>Music Control</h1>
        </div>
        <button type="button" class="btn btn-lg btn-primary btn-blue master-power-button">ALL OFF</button>
 
        <?php
            for($chanNum = 1; $chanNum < sizeof($channelArray); $chanNum++) {
                $chanTitle=$channelArray[$chanNum];
                    if(strlen($chanTitle)>0){
        ?>
        <div class="row">
            <div class="col-sm-12"><hr /><p><?php echo $chanTitle; ?></p></div>
        </div>
        
        <div class="row channel chan<?php echo $chanNum; ?>">
            <span>
                <div class="col-sm-12 col-md-1">
                    <button type="button" class="btn btn-lg btn-primary btn-blue power-btn" data-channel="<?php echo $chanNum; ?>"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="slider-fill" class="volumeSliderLabel">Volume:</label>
                    <input type="range" name="volumeLevel<?php echo $chanNum; ?>" id="volumeLevel<?php echo $chanNum; ?>" class="volume-slider" value="60" min="0" max="38" data-channel="<?php echo $chanNum; ?>" data-highlight="true">
                </div>   
                <div class="col-sm-12 col-md-5">
                    <div data-role="fieldcontain" style="margin:0;">
                       <label for="sourceSelect<?php echo $chanNum; ?>" class="sourceSelectLabel">Source:</label>
                        <select name="sourceSelect<?php echo $chanNum; ?>" id="sourceSelect<?php echo $chanNum; ?>" class="source-select" data-channel="<?php echo $chanNum; ?>" data-native-menu="false" data-theme="a" data-form="ui-btn-up-a">
                            <?php
                                for($sourceNum = 1; $sourceNum < sizeof($sourceArray); $sourceNum++) {
                                    $sourceTitle=$sourceArray[$sourceNum];
                                    if(strlen($sourceTitle)>0){
                            ?>
                            <option value="<?php echo $sourceNum; ?>"><?php echo $sourceTitle; ?></option>
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
 
	  
	  $( ".master-power-button" ).on( "click", function( event, ui ) { 
		  sendCommand("I","","AO","",getAllStatus);
	  });
	  
      $( ".power-btn" ).on( "click", function( event, ui ) { 
			channelId=$(event.currentTarget).data('channel');
			powerVal="";

			sendCommand("I",channelId,"PT",powerVal,getStatus);

		    console.log('toggle power for channel '+channelId);
      } );

	  $( ".source-select" ).on( "change", function( event, ui ) { 
			channelId=$(event.currentTarget).data('channel');
			sourceVal=$(event.currentTarget).val();

			sendCommand("I",channelId,"SS",sourceVal,getStatus);

		    console.log('set channel '+channelId+' to source: '+sourceVal);
      } );
       
      
      $( ".volume-slider" ).on( "slidestop", function( event, ui ) { 
			channelId=$(event.currentTarget).data('channel');
			volumeVal=$(event.currentTarget).val();

			sendCommand("I",channelId,"VO",volumeVal,getStatus);

		    console.log('set channel '+channelId+' to volume: '+volumeVal);
      } );
	  
	  getAllStatus();
  
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