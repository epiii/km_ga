<?php
require_once "lib/func.php";
require_once "lib/kmeans/Space.php";
require_once "lib/kmeans/Point.php";
require_once "lib/kmeans/Cluster.php";
// ----
// require_once 'lib/ga/individual.php';  //supporting individual 
// require_once 'lib/ga/population.php';  //supporting population 
// require_once 'lib/ga/fitnesscalc.php';  //supporting fitnesscalc 
// require_once 'lib/ga/algorithm.php';  //supporting fitnesscalc 
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
		$out['success']=true;
		$out['data']=$tb;
	}else{ // ga
		$no=0;
		$popNum=10;
		$maxFitness=0.1;
		// ---
		$popArr=array();
		$centArr=array();
		// ---
		
		// 1. random centroid 
		$cent    =getRandCent($clustNum,$attrNum,$attrRange); // decimal (40,50,32,12)
		$centDec =getCentDec($cent);	// decimal (4.0, 5.0, 3.2, 1.2)
		$centBin =getCentBin($cent);	// binary ("1001001","110010","1001001","1001001")
		
		// 2. calculate distance 
		$dt      =getDataArr($dataSrc);		// dataset (array) : 150 rows 
		$dist    =getDistance($dt,$centDec);// distance : data <-> centroid
		
		// 3. assign data to cluster
		$distMin =getMinDistance($dist); 	// selected cluster : 150 rows : (index,value)
		
		// 4.1 SSE
		$selCent =getSelectedCent($distMin, $centDec);
		$sse     =getSSE($dt,$selCent); 	// dataArray, centroidDecimal, 
		
		// 4.2 MSE
		$mse     =getMSE($sse);
		
		// 5. fitness
		$fitness =getFitness($mse);

		// 6. GA 
			// 6.1 create population
			$individuArr =getNewPopulation($popNum,$clustNum,$attrNum,$attrRange);

			// 6.2 selection : tournament 
/*			$tournamentSize=5;
			for ($i=0; $i <$tournamentSize ; $i++) { 
				$individuDec=getRandCent($clustNum,$attrNum,$attrRange);
			}
*/
			// 6.3 crossover
			$maxBit  =5; 	// 1100111 	=> 7 bit
							// 0123456  => 6 index	
							// 012345|6 => 5th is MAXimum (cut-point index) 
			$parent1 =$centBin;
			$parent2 =$individuArr[0];
			// process xOver
			$newIndividu =getCrossOver($maxBit,$parent1,$parent2);
				// hasil binary mode
				$binChild1 = $newIndividu[0];
				$binChild2 = $newIndividu[1];
				// hasil decimal mode
				$decChild1	= getBin2Dec($binChild1);
				$decChild2	= getBin2Dec($binChild2);

			// pr($decChild1);
			// 6.4 mutation

		$out['success']=true;
		$out['data']=array(
			// k-means
			'cent'    =>$cent,
			'centDec' =>$centDec,
			'centBin' =>$centBin,
			'data'    =>$dt,
			'dist'    =>$dist,
			'distMin' =>$distMin,
			// Fitness
			'sse'     =>$sse,
			'mse'     =>$mse,
			'fitness' =>$fitness,
			// xOver : parent
			'parent1' =>$parent1,
			'parent2' =>$parent2,
			// xOver : child
			'binChild1'  =>$binChild1,
			'binChild2'  =>$binChild2,
			'decChild1'  =>$decChild1,
			'decChild2'  =>$decChild2,
		);
	}echo json_encode($out);
}
