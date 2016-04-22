<?php  
  $light = $_GET['light'];
  if($light == "1") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '1');
    fclose($file);
  } 
  else if ($light == "2") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '2');
    fclose($file);
  }
    else if ($light == "3") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '3');
    fclose($file);
  }
    else if ($light == "4") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '4');
    fclose($file);
  }
    else if ($light == "5") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '5');
    fclose($file);
  }
    else if ($light == "6") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '6');
    fclose($file);
  }
    else if ($light == "7") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '7');
    fclose($file);
  }
    else if ($light == "8") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '8');
    fclose($file);
  }
    else if ($light == "9") {  
    $file = fopen("led.txt", "w") or die("can't open file");
    fwrite($file, '9');
    fclose($file);
  }
  $lightThreshold= intval(file_get_contents("http://www.jaimiederijk.nl/webofthings/lightThreshold.txt")); 
  echo $lightThreshold;
  $sensorlight=$_POST["sensorlight"];
  
  
  
    $lightJson= file_get_contents("http://www.jaimiederijk.nl/webofthings/light.json");
    $jsonArray = json_decode($lightJson,true);
  //  if (intval($sensorlight)>$lightThreshold) {
      $data['light']=$sensorlight;
      $data['time']=date("h:i:sa");
    // else {
    //   $data="";
    // }
    array_push($jsonArray,$data);
    // $arrlength = count($jsonArray);



    $json = json_encode($jsonArray);
    // var_dump($jsonArray) ;
    file_put_contents("light.json", $json);//,FILE_APPEND
  


    file_put_contents("lightcontol.json", $sensorlight);//,FILE_APPEND
?>

<html>  
  <head>      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>sleep timer</title>

  </head>
  <body>
    <h1>sleep timer</h1>
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-8 col-md-offset-2">
        <a href="?light=1" class="btn btn-success">1</a>
        <br />
        <a href="?light=2" class="led btn btn-danger">2</a>
        <br />
        <a href="?light=3" class="btn btn-success">3</a>
        <br />
        <a href="?light=4" class="led btn btn-danger">4</a>
        <a href="?light=5" class="led btn btn-danger">5</a>
        <br />
        <a href="?light=6" class="btn btn-success">6</a>
        <br />
        <a href="?light=7" class="led btn btn-danger">7</a>
        <br />
        <a href="?light=8" class="btn btn-success">8</a>
        <br />
        <a href="?light=9" class="led btn btn-danger">9</a>
        <br />

        <div class="light-status well" style="margin-top: 5px; text-align:center">
          <?php
            if($light=="on") {
              echo("Turn LED on.");
            }
            else if ($light=="off") {
              echo("Turn LED off.");
            }
            else {
              echo ("Do something.");
            }
            
          ?>
        </div>
      </div>
    </div>
  </body>

</html>  