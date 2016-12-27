<?php

function dec($x){
	return round($x,2);
}

function pr($par){
	echo "<pre>";
		print_r($par);
	echo"</pre>";
	exit();
}

function tableView($path){
	$tb='<table class="table table-bordered table-hovered">';
	// header
	$tb.= '<thead><tr style="background-color:black;color:white;" ><th class="text-right">No.</th>';
	foreach (getDataHeader($path) as $i => $v) {
		$tb.='<th class="text-center">'.$v.'</th>';
	}$tb.='</tr></thead>';
	// body
	$tb.='<tbody>';
	$nox=1;
	foreach (getDataArr($path) as $i => $v) {
		$tb.='<tr class="text-center">';
		$tb.='<td class="text-right">'.$nox.'.</td>';
		foreach ($v as $ii => $vv) {
			$tb.='<td>'.$vv.'</td>';
		}$tb.='</tr>';
		$nox++;
	}$tb.='</tbody>';
	// ---
	$tb.='</table>';
	echo $tb;
}

function getDataTotal($path){
	return count(getDataArr($path));
}

function getDataHeader($path){
	$row = 0;
	$dataArr=array();
	if (($handle = fopen($path, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    $colNum = count($data);
		    for ($i=0; $i<$colNum-1; $i++) {
		    	if(!is_numeric($data[$i])) {
		    		$dataArr[$i]=$data[$i];
		    	}
		    }$row++;
		}fclose($handle);
	}return $dataArr;
}

function getDataArr($path){
	$row = 0;
	$dataArr=array();
	if (($handle = fopen($path, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    $colNum = count($data);
		    for ($i=0; $i<$colNum-1; $i++) {
		    	if(is_numeric($data[$i])) {
		    		$dataArr[$row][$i]=$data[$i];
		    	}
		    }$row++;
		}fclose($handle);
	}return $dataArr;
}

function getDataArr2($path){
	$row = 0;
	$dataArr=array();
	if (($handle = fopen($path, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    $colNum = count($data);
		    for ($i=0; $i<$colNum-3; $i++) {
		    	if(is_numeric($data[$i])) {
		    		$dataArr[$row][$i]=$data[$i];
		    	}
		    }$row++;
		}fclose($handle);
	}return $dataArr;
}

function tableNumRow($path){
	$numRow;
	if (($handle = fopen($path, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    $numRow = count($data);
	    }
    }return $numRow; 
}

$distArr=array();
function getDistance($dtArr,$centArr){
	foreach ($dtArr as $i => $v) { // data : row (1-150)
		foreach ($v as $ii => $vv) { // data : col (1-4) : sl, sw, pl, pw
			foreach ($centArr as $k => $val) {// centroid : row (1-3) 
				$distx=0;
				foreach ($val as $kk => $vval) { // centroid : col (1-4) 
					$distx+=pow(($vv-$vval),2); 
				}$distx=dec(sqrt($distx));
				$distArr[$i][$k]=$distx;
			}
		}
	}return $distArr;
}

function getMinDistance($dtArr) { // output 150 rows
	$minArr=array();
	foreach ($dtArr as $i => $v) {
		// $minArr[$i]['index']=array_keys($v,min($v));
		$minArr[$i]['index']=array_search(min($v),$v);
		$minArr[$i]['value']=min($v);
	}return $minArr;
}

// function getMinCluster($dtArr) {
// 	$min=array();
// 	foreach ($dtArr as $i => $v) {
// 		$min[]=min($v);
// 	}return $min;
// }

function getRandCent($clustNum,$attrNum,$attrRange){
	$retArr=array();
	for($i=0;$i<$clustNum;$i++){ //cluster
		for($j=0;$j<$attrNum;$j++){ // atribut
			$retArr[$i][$j]=rand($attrRange[$j][0],$attrRange[$j][1]); // misal : 46 --> 4.6
		}
	}return $retArr;
}

function getCentDec($centArr){
	$retArr=array();
	foreach ($centArr as $i => $v)
		foreach ($v as $ii => $vv) $retArr[$i][$ii]=$vv/10;
	
	return $retArr;
}

function getCentBin($centArr){
	$retArr=array();
	foreach ($centArr as $i => $v){ //centroid row 3
		foreach ($v as $ii => $vv) { // centroid col 4
			$decb= sprintf('%07d', decbin($vv));
			$retArr[$i][$ii]=$decb;
		}
	}return $retArr;
}

function getMSE2($data,$attrNum,$centroid){
	$distArr=array();
	foreach ($data as $i => $v) { // data > @record || 150
		$distx=0;
		foreach ($v as $ii => $vv) { // data > record > column || 4
			foreach ($centroid as $k => $val) { // centroid > @cluster || 3
				foreach ($val as $kk => $vval) { // centroid > @cluster || 3
					$distx+=pow(($vv-$vval),2);
				}
			}
		}
		// $distx=sqrt($distx);
		$distArr[]=$distx/$attrNum;
	}return $distArr;
}

$sseArr=array();
function getSSE($dtArr,$selCent){ // output : 150 rows
	foreach ($dtArr as $i => $v) { // data : row (1-150)
		foreach ($v as $ii => $vv) { // data : col (1-4) : sl, sw, pl, pw
			foreach ($selCent as $k => $val) {// centroid : row (1-3) 
				$distx=0;
				foreach ($val as $kk => $vval) { // centroid : col (1-4) 
					$distx+=pow(($vv-$vval),2); 
				}
			}
		}$sseArr[$i]=$distx;
	}return $sseArr;
}

function getSelectedCent($distMin, $centDec){
	$centArr=array();
	foreach ($distMin as $i => $v) {
		foreach ($centDec as $ii => $vv) {
			if($ii==$v['index'])
				$centArr[]=$vv;
		}
	}return $centArr;
}

function getMSE($sse){
	$sseTot=0;
	foreach ($sse as $i => $v) {
		$sseTot+=$v;
	}return dec($sseTot/count($sse));
}

function getFitness($mse){
	return dec(1/$mse);
	// return dec(1/$mse)*10;
}

function getCrossOver($maxBit,$ind1,$ind2){
	$newInd1 = array();
	$newInd2 = array();
	foreach ($ind1 as $i => $v) { // ind1 : row : 3 
		$cut = rand(1,($maxBit));
		// pr($cut);
		foreach ($v as $ii => $vv) { // ind1 : col : 4 string 
			// parent 1
											// src     "1011|001" 
			$str1 = substr($vv, 0,$cut);  	// returns "1011___"
			$str2 = substr($vv, $cut);  	// returns "____001"
			// parent 2
														// src     "1000|110" 
			$str11 = substr($ind2[$i][$ii], 0,$cut);  	// returns "1000___"
			$str22 = substr($ind2[$i][$ii], $cut);		// returns "____110"  	

			// new individu 1
			$newStr1= $str1.$str22;
			// new individu 2
			$newStr2= $str11.$str2;
			
			// save 2 individu 	

			$newInd1[$i][$ii]=$newStr1;	// c1: "1011|110" 
			$newInd2[$i][$ii]=$newStr2; // c2: "1000|001"
			// echo $cut.' p1: '.$vv.' p2:'.$ind2[$i][$ii].' c1:'.$newStr1.' c2: '.$newStr2;
			// exit();
		}
	}return array($newInd1,$newInd2);
}

function getCutString($string,$cut){
	$str1 = substr($string, 0,$cut); 
	$str2 = substr($string, $cut);  
	return array($str1,$str2);
}

function getNewPopulation($popNum,$clustNum,$attrNum,$attrRange){
	$individuArr=array();
	for ($i=0;$i<$popNum; $i++) { // 10 individu
		$individuDec     =getRandCent($clustNum,$attrNum,$attrRange);
		$individuBin     =getCentBin($individuDec);
		$individuArr[$i] =$individuBin;
	} return $individuArr;
} 

function getBin2Dec($centArr){
	$ret=array();
	foreach ($centArr as $i => $v) {
		foreach ($v as $ii => $vv) {
			$ret[$i][$ii]=bindec($vv)/10;
		}
	}return $ret;
}

