<?php
// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "http://cdip.ucsd.edu/data_access/synopsis.cdip");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$CDIP_string = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

$buoy_array = explode("\n", $CDIP_string);

unset($buoy_array[0], $buoy_array[1], $buoy_array[2], $buoy_array[63], $buoy_array[64]);

$buoy_array = array_values($buoy_array);

function create_buoy_array(){
	global $buoy_array;
	class buoyObject {
		public function __construct($stationId, $stationName, $dayOfMonth, $readTime, $peakPeriod, $swellHeight, $swellDirection, $waterTemp){
			$this->stationId = $stationId;
			$this->stationName = $stationName;
			$this->dayOfMonth = $dayOfMonth;
			$this->readTime = $readTime;
			$this->peakPeriod = $peakPeriod;
			$this->swellHeight = $swellHeight;
			$this->swellDirection = $swellDirection;
			$this->waterTemp = $waterTemp;
		}
	}

	for ($i=0; $i < count($buoy_array); $i++) {
		$stationId = "";
		$stationName = "";
		$dayOfMonth = "";
		$readTime = "";
		$peakPeriod = "";
		$swellHeight = "";
		$swellDirection = "";
		$waterTemp = "";
		
		for ($j=0; $j <= 2; $j++) { 
		 	$stationId .= $buoy_array[$i][$j];
		}

		for ($j=4; $j <= 29; $j++) { 
		 	$stationName .= $buoy_array[$i][$j]; 
		}

		for($j = 30; $j<=31; $j++){
		 	$dayOfMonth .= $buoy_array[$i][$j];
		}

		for ($j=33; $j <= 36; $j++) { 
			$readTime .= $buoy_array[$i][$j];
		}
		for($j = 51; $j<=52; $j++){
			$peakPeriod .= $buoy_array[$i][$j];
		}

		for($j = 47; $j <= 49; $j++){
	        $swellHeight.=$buoy_array[$i][$j];
	    }
	    $swellHeight = ($swellHeight*0.39370)/12;
	    $swellHeight = round($swellHeight, 1);	

		 for($j = 54; $j <=56; $j++){
		 	$swellDirection .= $buoy_array[$i][$j];
		 }

		 for($j = 64; $j <= 67; $j++){
		 	$waterTemp .= $buoy_array[$i][$j];
		 }

		 $stationId = intval($stationId);
		 $stationName = trim($stationName);

		$buoy_array[$i] = new buoyObject($stationId, $stationName, $dayOfMonth, $readTime, $peakPeriod, $swellHeight, $swellDirection, $waterTemp);
	}
}

create_buoy_array();

function remove_unnecessary_readings(){
	global $buoy_array;
	for ($i=0; $i <= 8; $i++) { 
	unset($buoy_array[$i]);
	}

	unset($buoy_array[12], $buoy_array[16], $buoy_array[17], $buoy_array[22], $buoy_array[23], $buoy_array[26]);

	for($i=37; $i <= 57; $i++){
		unset($buoy_array[$i]);
	}

	$buoy_array = array_values($buoy_array);
}

remove_unnecessary_readings();

function send_buoy_info(){
	global $buoy_array;
	require('../mysql_connect.php');
	$query = "SELECT COUNT(*) FROM `29`";
	$count_results = mysqli_query($conn, $query);
	print_r($count_results);
	// for ($i=0; $i < count($buoy_array); $i++) { 
	// 	$query = "INSERT INTO `{$buoy_array[$i]->stationId}`(`id`, `station_num`, `station_name`, `day_of_month`, `read_time`, `peak_period`, `swell_height`, `swell_direction`, `water_temp`) VALUES (null, {$buoy_array[$i]->stationId},'{$buoy_array[$i]->stationName}','{$buoy_array[$i]->dayOfMonth}','{$buoy_array[$i]->readTime}','{$buoy_array[$i]->peakPeriod}','{$buoy_array[$i]->swellHeight}','{$buoy_array[$i]->swellDirection}','{$buoy_array[$i]->waterTemp}')";
	// 	$results = mysqli_query($conn, $query);
	// 	if (mysqli_affected_rows($conn) > 0) {
	// 	   print('success');
	// 	}else {
	// 	    print('query not working');
	// 	}
	// }
}

send_buoy_info();




