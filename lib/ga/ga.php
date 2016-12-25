<?php
require_once('individual.php');  //supporting individual 
require_once('population.php');  //supporting population 
require_once('fitnesscalc.php');  //supporting fitnesscalc 
require_once('algorithm.php');  //supporting fitnesscalc 
require_once('func.php');  //supporting fitnesscalc 

//Lets see what was choosen
$solution_phrase= isset($_REQUEST['solution'] )? $_REQUEST['solution'] : "Hello World!";
function sendMsg2($id, $json_msg) {
	echo "id: $id" . PHP_EOL;
	echo "event: update" . PHP_EOL; // PHP_EOL : new line
	echo "data: $json_msg" . PHP_EOL;
	echo PHP_EOL;
	ob_flush();
	flush();
}

function sendMsg($time,$data){
	echo json_encode(array('time'=>$time,'data'=>$data));
}
	
algorithm::$uniformRate=0.50;
algorithm::$mutationRate=0.05;
algorithm::$poolSize=15; /* crossover how many to select in each pool to breed from */
$initial_population_size=75;	//how many random individuals 
algorithm::$max_generation_stagnant=200;  //maximum number of unchanged generations terminate loop
algorithm::$elitism=true;  //keep fittest individual  for next gen
$lowest_time_s=100.00; //keeps track of lowest time in seconds
$generationCount = 0;
$generation_stagnant=0; 
$most_fit=0;
$most_fit_last=400;

$response = array();  //holdse the JSON object to be returned
$response['done']=false; //assume not done 

// Set a candidate solution static class
fitnesscalc::setSolution($solution_phrase);
// Create an initial population
$time1 = microtime(true);
// pr($time1);
$myPop = new population($initial_population_size, true);
// Evolve our population until we reach an optimum solution

// pr($myPop->getFittest()->getFitness());
while ($myPop->getFittest()->getFitness() > fitnesscalc::getMaxFitness()){
	$response['stagnant']=0;
	$generationCount++;
	$most_fit=$myPop->getFittest()->getFitness();          
	$myPop = algorithm::evolvePopulation($myPop); //create a new generation

	if ($most_fit < $most_fit_last){
		// echo " *** MOST FIT ".$most_fit." Most fit last".$most_fit_last;
		$response['generation'] =$generationCount;
		$response['stagnant']=$generation_stagnant;
		$response['best_fittest_value']=$most_fit;
		$response['best_individual']= "".$myPop->getFittest();
		$most_fit_last=$most_fit;
		$generation_stagnant=0; //reset stagnant generation counter

		$time2 = microtime(true);
		$response['elapsed'] = round($time2-$time1,2)."s";
		$response['message'] = "PHP Server Working...";
		$serverTime = microtime();
		sendMsg($serverTime,$response);
		// exit();
		// pr($response);			
		// sendMsg($serverTime,json_encode($response) );
   	}else 
   		$generation_stagnant++; //no improvement increment may want to end early
 
	if($generation_stagnant > algorithm::$max_generation_stagnant){
		$response['stagnant']=$generation_stagnant;
		$response['message'] = "STOPPING NOW TOO MANY (".algorithm::$max_generation_stagnant.") stagnant generations. Showing Best Effort <br>";
		break;
	}
}//end of while loop
	
//we're done
$time2 = microtime(true);
$response['best_fittest_value']=$most_fit;
$response['best_individual']= "".$myPop->getFittest();
$response['elapsed'] = round($time2-$time1,2)."s";
$response['message'].="Done!, completed Genetic Algorithm for this solution";
$response['done']=true;
$serverTime = microtime();			
sendMsg($serverTime,$response);
// sendMsg($serverTime,json_encode($response) );
exit;
?>
