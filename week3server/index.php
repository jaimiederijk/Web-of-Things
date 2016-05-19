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
  
  $sensorlight=$_POST["sensorlight"];

//  if (intval($sensorlight)>$lightThreshold) {
  if (!empty($sensorlight)) {
    file_put_contents("lightcontol.json", $sensorlight);

    $lightJson= file_get_contents("http://www.jaimiederijk.nl/webofthings/light.json");
    if (!empty($lightJson)) {
      
      $jsonArray = json_decode($lightJson,true);
    }
    else {
      $jsonArray = array();
    }
  //  
      $data['light']=$sensorlight;
      $data['time']=date("h:i:sa");
      $data['date']=date("Y-m-d");
    // else {
    //   $data="";
    // 
    array_push($jsonArray,$data);
    // $arrlength = count($jsonArray);

    $json = json_encode($jsonArray);//$jsonArray
    // var_dump($jsonArray) ;
    if (!empty($json)) {
      file_put_contents("light.json", $json);
    }
  }

    //,FILE_APPEND
  // } 
  // else {
  //   file_put_contents("lightcontol.json", $lightThreshold);
  // }

  $newThreshold = $_POST["quantity"];//strval($newThreshold)
  if (!empty($newThreshold)) {
    file_put_contents("lightThreshold.txt",  $newThreshold);
  }

    //,FILE_APPEND
?>

<html>  
  <head>      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./style.css">
    <title>sleep tracker</title>

  </head>
  <body>
    <h1>sleep tracker</h1>
    <div id="graph">
    </div>
    <section id="info">
      <p>Last night you slept for <span id="timeslept"></span> Hours</p>
    </section>
    <div class="row" style="margin-top: 20px;">

      <section class="settings" style="margin-top: 5px; text-align:center">
        <h2>settings</h2>
        <p>Here you can set the threshold. A sensor value below this value is counted as time you are sleeping. Use the graph to determine the right threshold.</p>
        <!-- action="index.php" -->
        <form method="post">
          light threshold
          <input type="number" name="quantity" min="1" max="100">
          <input type="submit">
        </form>

        <?php echo "<p>current threshold <span id='threshold'> $lightThreshold </span></p>"; ?>
        <div class="output">
          <h2>manual output</h2>
          <a href="?light=1" class="btn btn-success">1</a>
          <br />
          <a href="?light=2" class="led btn btn-danger">2</a>
          <br />
          <a href="?light=3" class="btn btn-success">3</a>
          <br />
          <a href="?light=4" class="led btn btn-danger">4</a>
          <br />
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
        </div>
      </section>
    </div>
  </body>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
  <script src="moment.min.js"></script>
  <script src="javascript.js"></script>
</html>  