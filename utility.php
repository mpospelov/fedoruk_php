<?php
	function findMaxKey(array $array){
		$maxKey = 0;
		foreach ($array as $key => $value) {
			if($maxKey < $key)
				$maxKey = $key;
		}
		return $maxKey;
	}

	function findMaxValue(array $array) {
		$maxValue = 0;
		foreach ($array as $value) {
			if($maxValue < $value)
				$maxValue = $value;
		}
		return $maxValue;
	}

	function distance($x1 , $y1 , $x2 , $y2){
		return sqrt(($x2 - $x1) * ($x2 - $x1) + ($y2 - $y1) * ($y2 - $y1));
	}

	function getColor($image , $value , $maxValue){
		$middle = $maxValue / 2;
		if($middle > $value)
			return imagecolorallocate($image, 0 ,255 * ($value)/$middle , 255 * ($middle - $value)/$middle);
		return imagecolorallocate($image, 255 * ($value - $middle) / $middle ,255 * ($maxValue - $value)/$middle , 0);
	}
?>