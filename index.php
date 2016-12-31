<?php
  include 'lib/func.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>KM-GA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/process.js"></script>
  </head>


  <body>
    <div class="container">
      <?php 
        include 'menu.php';
        include 'popUp.php';
      ?>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <center><h1>KM-GA</h1></center>
      <h4>Dataset :
          <?php 
            $p="data/iris.csv";
            echo getDataTotal($p); 
          ?>
        <button onclick="datasetFC();" class="btn btn-success bs-docs-activate-animated-progressbar">view</button>  
      </h4>
      <div id="datasetTBL" style="overflow-y:auto;height:300px; display:none;" class="overflow">
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