<?php 
	//Set running time
	ini_set('max_execution_time', 3000);
	//Mengubah file excel menjadi array
	$theDataTrain = array();
	$filename = "DataTrain_Tugas3_AI.csv";
	if (($handle = fopen($filename, "r")) !== FALSE) {
		$key = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$count = count($data);
			for ($i=0; $i < $count; $i++) {
				$theDataTrain[$key][$i] = $data[$i];
			}
			$key++;
		}
		fclose($handle);
	}
	$theDataTest = array();
	$filename = "DataTest_Tugas3_AI.csv";
	if (($handle = fopen($filename, "r")) !== FALSE) {
		$key = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$count = count($data);
			for ($i=0; $i < $count; $i++) {
				$theDataTest[$key][$i] = $data[$i];
			}
			$key++;
		}
		fclose($handle);
	}
	
	//The Algorithm
	$k = 5;
	$res = array();
	$h = 0;
	for($i = 1;$i <= 200;$i++){
		$result = array();
		$b = 0;
		for($j = 1;$j <= 800;$j++){
			$sum = 0;
			for($a = 1;$a <= 5;$a++){
				$sum = $sum + abs($theDataTest[$i][$a] - $theDataTrain[$j][$a]);
			}
			$b++;
			$result[$b][0] = $sum;
			$result[$b][1] = $theDataTrain[$j][6];
		}
		//Sorting
		$d = $b;
		do {
			$swapped = false;
			for ($c = 1; $c < $d; $c++) {
				if ($result[$c][0] > $result[$c + 1][0]) {
					$temp = $result[$c];
					$result[$c] = $result[$c + 1];
					$result[$c + 1] = $temp;
					$swapped = true;
				}
			}
			$d--;
		}while ($swapped);
		$count = array();
		for($e = 0;$e <= 3;$e++){$count[$e] = 0;}
		for($q = 1;$q <= $k;$q++){
			if($result[$q][1] == '0'){$count[0]++;}
			else if($result[$q][1] == '1'){$count[1]++;}
			else if($result[$q][1] == '2'){$count[2]++;}
			else if($result[$q][1] == '3'){$count[3]++;}
		}
		$maxs = array_keys($count, max($count));
		$res[$h] = $maxs[0];
		$h++;
	}
	
	//Prepare output array
	$out = array();
	for($q = 0;$q < 200;$q++){
		$out[$q][0] = $res[$q];
	}
	
	//Export to CSV
	$output = fopen("php://output",'w') or die("Can't open php://output");
	header("Content-Type:application/csv"); 
	header("Content-Disposition:attachment;filename=TebakanTugas3.csv");
	foreach($out as $outp) {
		fputcsv($output, $outp);
	}
	fclose($output) or die("Can't close php://output");
?>