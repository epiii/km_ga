<?php
  include 'lib/func.php';
?>
<!DOCTYPE html>
<html lang="en">

  <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>KM-GA</title>
      <link rel="stylesheet" href="css/bootstrap.css">
      <script src="js/jquery-1.8.3.min.js"></script>
      <script src="js/process.js"></script>
      <script src="js/ie_eventsource.js"></script>
  </head>

  <body>
    <div class="container">
        <center><h1>KM-GA</h1></center>
        <h4>Dataset :  
            <?php 
              $p="data/iris.csv";
              echo getDataTotal($p); 
            ?>
        </h4>
        <div style="overflow-y: auto;height: 300px;" class="overflow">
          <?php 
            tableView($p);
          ?>
        </div>
        
        <h4 class="text-center">Calculation</h4>
        <div class="row">
        <!-- --   -->
          <div class="col-md-6">
            <center>
              <button  onclick="runKM();" class="btn btn-lg btn-success">Run K-Means</button>
              <div id="kmDV">
                <label>... press button ...</label>
                <!-- <label style="background-color:lightBlue; xcolor:white;margin:2px;padding:2px;">press button</label> -->
                <!-- output kmeans (pure) -->
              </div>
            </center>
          </div>

          <div class="col-md-6">
            <center>
              <button onclick="runGA();" class="btn btn-lg  btn-success">Run K-Means by GA</button>
              <div id="kmgaDV">....
                  <label>... press button ...</label>
                <!-- output kmeans - GA  -->
              </div>
            </center>
          </div>
          <!-- -- -->
        </div>
    </div>

    <p>&nbsp;</p>
  </body>

</html>