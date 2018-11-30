<?php 
	//Set running time
	ini_set('max_execution_time', 3000);
	//Mengubah file excel menjadi array
	$theData = array();
	$filename = "DataTrain_Tugas3_AI.csv";
	if (($handle = fopen($filename, "r")) !== FALSE) {
		$key = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$count = count($data);
			for ($i=0; $i < $count; $i++) {
				$theData[$key][$i] = $data[$i];
			}
			$key++;
		}
		fclose($handle);
	}
	
	//K-Fold
	$n = 8;
	$k = 5;
	$accuracy = array();
	for($i = 1;$i <= $n;$i++){//batas akhir n
		$testIndexLast = ((800 / $n) * ($i));
		$testIndexStart = $testIndexLast - ((800 / $n) - 1);
		$hit = 0;
		$data = 800 / $n;
		for($j = $testIndexStart;$j <= $testIndexLast;$j++){//batas akhir testIndexLast
			//echo 'Start = '.$testIndexStart.'<br>';
			//echo 'Target = ' . $theData[$j][6].'<br>';
			$result = array();
			$b = 0;
			for($l = 1;$l <= 800;$l++){//batas akhir 800, awal 1
				if($l < $testIndexStart || $l > $testIndexLast){
					$sum = 0;
					for($a = 1;$a <= 5;$a++){
						$sum = $sum + abs($theData[$j][$a] - $theData[$l][$a]);
					}
					$b++;
					$result[$b][0] = $sum;
					$result[$b][1] = $theData[$l][6];
				}
			}
			//if(!empty($result)){
			$d = $b;
			do {
				$swapped = false;
				for ($c = 1; $c < $d; $c++) {
					// swap when out of order
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
			//echo $theData[$j][1] . '<br>';
			//echo 'Terbanyak = ' .$maxs[0].'<br>';
			if($maxs[0] == $theData[$j][6]){$hit++;}
			//}
		}
		$accuracy[$i] = $hit / $data;
	}
	$a = array_filter($accuracy);
	$average = array_sum($accuracy)/count($accuracy);
	echo 'Accuracy = ' . $average * 100 . '%';
?>