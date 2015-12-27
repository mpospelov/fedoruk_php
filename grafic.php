<?php

Header("Content-Type: image/png");

function draw_axises($im_width,$im_height)
{
  global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY,$minX,$minY,$red;
  global $string_x_start, $string_x_end, $string_y_start, $string_y_end;
  global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY,$startX,$startY,$minX,$minY;

   $maxX = $im_width  - 25.0;
   $maxY = $im_height - 20.0;
   $minY = $maxY - $minY - 20.0;

   imageline($im, 25.0, $minY + 20.0, $maxX, $minY + 20.0,   $black);
   imageline($im, $minX + 25.0, 20.0, $minX + 25.0, $maxY, $black);

   $xArrow[0]=$maxX-6; $xArrow[1]=$minY+20-2;
   $xArrow[2]=$maxX;   $xArrow[3]=$minY+20;
   $xArrow[4]=$maxX-6; $xArrow[5]=$minY+20+2;
   imagefilledpolygon($im, $xArrow, 3, $black);

   $yArrow[0]=$minX+25-2; $yArrow[1]=20+6;
   $yArrow[2]=$minX+25;   $yArrow[3]=20;
   $yArrow[4]=$minX+25+2; $yArrow[5]=20+6;
   imagefilledpolygon($im, $yArrow, 3, $black);

   imagestring($im, 2, 25.0,  $minY + 20.0,  $string_x_start, $black);
   imagestring($im, 2, $maxX, $minY + 20.0, $string_x_end,   $black);
   imagestring($im, 2, $minX + 15.0, 20.0,  $string_y_end, $black);
}

function draw_grid($xStep,$yStep,$xCoef,$yCoef) {
  global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY,$minX,$minY,$red;
  global $string_x_start, $string_x_end, $string_y_start, $string_y_end;
  global $im,$black,$l_grey,$x0,$y0,$maxX,$maxY,$startX,$startY,$minX,$minY;

   $maxX = $im_width  - 25.0;
   $maxY = $im_height - 20.0;

   $xSteps=($maxX-$x0)/$xStep-1;

   $x0 = 25;
   $y0 = 20;

   $ySteps=($maxY-$y0)/$yStep-1;

   for($i=1;$i<$xSteps+1;$i++){
      imageline($im, $x0+$xStep*$i, $y0, $x0+$xStep*$i,$maxY-1, $l_grey);
      imagestring($im, 1, ($x0+$xStep*$i)-1, $maxY+2, $startX+$i*$xCoef, $black);
   }

   for($i=1;$i<$ySteps+1;$i++){
      imageline($im, $x0+1, $maxY-$yStep*$i, $maxX,
      $maxY-$yStep*$i, $l_grey);
      imagestring($im, 1, 0, ($maxY-$yStep*$i)-3, $startY+$i*$yCoef, $black);
   }
   imageline($im, 1, 1, 100, 100, $l_grey);
}

function draw_function($y, $step_x, $N_points, $color) {
  global $im;
  $minY = abs(min($y));
  $maxY = abs(max($y));
  $y_len = abs($maxY - $minY);

  for($i = 1; $i < $N_points; $i++) {
    if(max($y) < -0.01) {
      imageline($im, 25+($i-1)*$step_x, 400-20 - (($maxY)/($minY))*360 - (($y_len)/($minY))*360 - (($y[$i-1])/($minY))*360,
        25+($i)*$step_x, 400-20 - (($maxY)/($minY))*360 - (($y_len)/($minY))*360 - (($y[$i])/($minY))*360, $color);
      continue;
    } else {
      if(min($y) > 0.01) {
        imageline($im, 25 + ($i-1)*$step_x, 400-20-(($y[$i-1]-$minY)/($y_len+$minY))*360 - (($minY)/($minY+$y_len))*360,
          25+($i)*$step_x, 400-20-(($y[$i]-$minY)/($y_len+$minY))*360 - (($minY)/($minY+$y_len))*360,$color);
        continue;
      } else {
        imageline($im, 25+($i-1)*$step_x, 400-20-(($y[$i-1]+$minY)/$y_len)*360,
                        25+($i)*$step_x, 400-20-(($y[$i]+$minY)/$y_len)*360,$color);
      }
    }
  }
}

$im = @ImageCreate(500, 400);
$white = ImageColorAllocate ($im, 255, 255, 255);
$black = ImageColorAllocate ($im, 0, 0, 0);
$red = ImageColorAllocate ($im, 255, 0, 0);
$green = ImageColorAllocate ($im, 0, 255, 0);
$blue = ImageColorAllocate ($im, 0, 0, 255);
$yellow = ImageColorAllocate ($im, 255, 255, 0);
$magenta = ImageColorAllocate ($im, 255, 0, 255);
$cyan = ImageColorAllocate ($im, 0, 255, 255);
$l_grey = ImageColorAllocate ($im, 221, 221, 221);

$func_str = "return " . $_POST["Function"] . " ;";
$func = create_function('$x', $func_str);

$xstart = (float)$_POST["XStart"];
$xstop  = (float)$_POST["XStop"];

$x1 = array($xstart);
$y1 = array($func($xstart));
$position = $xstart+0.01;

while($position <= $xstop) {
  array_push($x1, $position);
  array_push($y1, $func($position));
  $position = $position + 0.01;
}
$s1 = count($x1);

$x_len = abs(max($x1) - min($x1));
$y_len = abs(max($y1) - min($y1));

$x_count = count($x1);
$y_count = count($y1);

$x_step = round((500-(25+25))/($x_count-1),2);
$y_step = round((400-(20+20))/($y_count-1),2);

$x_count_left = 0;
for($i=0; $x1[$i] < 0; $i++){
  $x_count_left = $i + 1;
}

$y_count_down = 0;
for($i = 0; $y1[$i] < 0; $i++){
  $y_count_down = $i + 1;
}

$minX = $x_step * $x_count_left;
$minY = $y_step * $y_count_down;

if(min($y1) < -0.01) {
  $minY = round(abs(min($y1)/$y_len)*360.0,2);
} else {
  $minY = 0;
}

$string_x_start = round(min($x1),2);
$string_x_end   = round(max($x1),2);
$string_y_start = round(min($y1),2);
$string_y_end   = round(max($y1),2);

draw_axises(500, 400);

draw_function($y1, $x_step, $x_count, $red);

ImagePNG($im);
imagedestroy($im);
?>
