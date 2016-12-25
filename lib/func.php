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

function getDistance($data,$centroid){
	$distArr=array();
	foreach ($data as $i => $v) { // data > @record || 150
		$distx=0;
		foreach ($v as $ii => $vv) { // data > record > column || 4
			foreach ($centroid as $k => $val) { // centroid > @cluster || 3
				foreach ($val as $kk => $vval) { // centroid > @cluster || 3
					$distx+=pow(($vv-$vval),2);
				}
			}
		}$distx=sqrt($distx);
		$distArr[]=$distx;
	}return $distArr;
}

function getInitCentroid($clustNum,$attrNum,$attrRange){
	$popArr=array();
	for($i=0;$i<$clustNum;$i++){ //cluster
		for($j=0;$j<$attrNum;$j++){ // atribut
			$popArr[$i][$j]=rand($attrRange[$i][0],$attrRange[$i][1])/10;
		}
	}return $popArr;
}

function getMSE($data,$attrNum,$centroid){
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