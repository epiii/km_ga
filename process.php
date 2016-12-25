<?php
require_once "lib/func.php";
require_once "lib/kmeans/Space.php";
require_once "lib/kmeans/Point.php";
require_once "lib/kmeans/Cluster.php";
// ----
require_once 'lib/ga/individual.php';  //supporting individual 
require_once 'lib/ga/population.php';  //supporting population 
require_once 'lib/ga/fitnesscalc.php';  //supporting fitnesscalc 
require_once 'lib/ga/algorithm.php';  //supporting fitnesscalc 
// --
$dataSrc="data/iris.csv";

$out=array();
$out['success']=true;

$clustNum =3; // number of cluster
$attrRange=[ // range of attribute divide by 10 
	[43,79], 	// sl 4-7
	[20,44], 	// sw 2-4
	[10,69], 	// pl 1-6
	[1,25], 	// pw 1-2
];
$attrNum  =count($attrRange); // number  of attribute 

if (!isset($_POST['mode'])) { // invalid request
	$out['success']=false;
	$out['data']=null;
}else{ // valid request 
	if($_POST['mode']=='kmeans'){ // k-means process
		$points = getDataArr($dataSrc);
		$space  = new KMeans\Space($attrNum);

		// add points to space
		foreach ($points as $coordinates){
			$space->addPoint($coordinates);
		}

	// result ----------
		// print cluster 

		//  solve($nbClusters, $seed = self::SEED_DEFAULT, $iterationCallback = null){
		$clusters = $space->solve($clustNum); 
		// $clusters = $space->solve($clustNum,3); 
		$tb='<h4>Centroid Result</h4>';
		$tb.='<table class="table bordered table-striped">';
			$tb.='<tr style="background-color:black; color:white;">';
				$tb.='<th class="text-center">cluster</th>';
				$tb.='<th class="text-center">centroid of ('.implode(',', getDataHeader($dataSrc)).')</th>';
				$tb.='<th class="text-center">total member</th>';
			$tb.='</tr>';
		$nox=1;
		// pr($clusters);
		foreach ($clusters as $k => $v) {
		// foreach ($clusters as $v) {
			$clr=$k==0?'warning':($k%2==0?'info':'success');
			$tb.='<tr class="'.$clr.'">';
				$tb.='<td class="text-center">'.$nox.'</td>';
				$tb.='<td class="text-center">('.dec($v[0]).','.dec($v[1]).','.dec($v[2]).','.dec($v[3]).')</td>';
				$tb.='<td class="text-center">'.count($v).'</td>';
			$tb.='</tr>';
			$nox++;
		}
		$tb.='</table>';
		
		// print data
		$tb.='<h4>Assigned Data Result</h4>';
		$tb.='<div style="overflow-y: auto;height: 300px;" >';
			$tb.='<table class="table bordered">';
				$tb.='<tr style="background-color:black; color:white;">';
					$tb.='<th class="text-center">no.</th>';
					$tb.='<th class="text-center">Centroid Attibute /<br> Data Attribute</th>';
					$tb.='<th class="text-center">Prediction Cluster /<br> Real Cluster</th>';
					$tb.='<th class="text-center">MSE</th>';
				$tb.='</tr>';
		$nox=1;

		$dt =getDataArr($dataSrc);
		foreach ($clusters as $i => $v) { // 
			$clr=$i==0?'warning':($i%2==0?'info':'success');
			foreach ($v as $ii => $vv) {
				$tb.='<tr class="'.$clr.'">';
					$tb.='<td class="text-center">'.$nox.'</td>';
					$tb.='<td class="text-center"> 
							'.dec($v[0]).','.dec($v[1]).','.dec($v[2]).','.dec($v[2]).'/<br> 
						</td>';
					$tb.='<td class="text-center">'.($i+1).'</td>';
				$tb.='</tr>';
				$nox++;
			}
		}$tb.='</table></div>';

		// foreach ($clusters as $i => $cluster)
		// printf("Cluster %s [%d,%d]: %d points\n", $i, $cluster[0], $cluster[1], count($cluster));
		$cent =getInitCentroid($clustNum,$attrNum,$attrRange);
		
		$out['success']=true;
		$out['data']=$tb;
		$out['mse']=getMSE($dt,$attrNum,$cent);
		// pr($out['mse']);
	}else{ // ga
		$no=0;
		$popNum=50;
		// ---
		$popArr=array();
		$centArr=array();
		// ---

		// pr($cent);
		// for($i=0;$i<$clustNum;$i++){ //cluster
		// 	for($j=0;$j<$attrNum;$j++){ // atribut
		// 		$popArr[$i][$j]=rand($attrRange[$i][0],$attrRange[$i][1])/10;
		// 	}
		// }
		
		// getRandomPopulation
		$cent =getInitCentroid($clustNum,$attrNum,$attrRange); // random centroid (4,2,6,1)
		$dt   =getDataArr($dataSrc);	// data 1-150 
		$dist =getDistance($dt,$cent);	// distance data and centroid
		$mse  =getMSE($dt,$attrNum,$cent); // means square error
		// pr($mse);
		$out['success']=true;
	}
	echo json_encode($out);
}
