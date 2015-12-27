<?php
require('utility.php');

class Gistagramm{
	var $widthColumn;
	var $array;
	var $align;
	var $image;
	function Gistagramm(array $array){
		$this->array = $array;
		$this->widthColumn = 0.6;
		$this->align = 10;
	}

	function setWidth($width){
		if($width > 1 || $width < 0)
			$width = 0.5;
		else
			$this->widthColumn = $width;
	}

	function createGistagramm($width , $heigth){
    	$image = @imagecreate($width, $heigth);
    	$maxKey = findMaxKey($this->array);
		$maxValue = findMaxValue($this->array);

    	ImageColorAllocate($image , 255 , 255 , 255);
   	 	ImageLine ($image, $this->align, $heigth - $this->align, $width - $this->align, $heigth - $this->align, 1);
		ImageLine ($image, $this->align, $this->align, $this->align, $heigth - $this->align, 1);

		$widthRect = round(($width - 2 * $this->align) / ($maxKey + 1)) * $this->widthColumn;
		foreach ($this->array as $key => $value) {
			$color = getColor($image , $value , $maxValue);
        	$heigthRect = round($value * ($heigth - 2 * $this->align) / $maxValue);
        	$pos = round(($key / ($maxKey + 1)) * ($width - 2 * $this->align)) + $this->align;
        	ImageFilledRectangle ($image, $pos - $widthRect / 2 , $heigth -  $heigthRect, $pos + $widthRect / 2 , $heigth - $this->align, $color);

        	ImageString ($image, 0, $pos - $widthRect / 2, $heigth - $heigthRect - 10, $value , 1);
        	ImageString ($image, 0, $pos - $widthRect / 2, $heigth - $this->align, $key , 1);
    	}

		$this->image = $image;
	}
};
?>
