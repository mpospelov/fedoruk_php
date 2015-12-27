<?php
	require('GistagrammClass.php');
    error_reporting(0);

	$link = mysql_connect("127.0.0.1", "root", "") or die("Could not connect : " . mysql_error());
	//$mysqli = new mysqli( "127.0.0.1" , "root" , "12345678", "fedor");
    mysql_select_db("fedoruk_php_lab") or die("Could not select database");

	$query = "select Elements.Id as id , Node1.x as x1 , Node1.y as y1 , Node2.x as x2 , Node2.y as y2 , Node3.x as x3 , Node3.y as y3
	from elements Elements , nodes Node1 , nodes Node2 , nodes Node3
	Where Elements.n1 = Node1.id and Elements.n2 = Node2.id and Elements.n3 = Node3.id";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());

	$areas =  array();

    while ($line = mysql_fetch_array($result , MYSQL_NUM)) {
     	$d1 = distance($line[1] , $line[2] , $line[3] , $line[4]);
     	$d2 = distance($line[3] , $line[4] , $line[5] , $line[6]);
     	$d3 = distance($line[1] , $line[2] , $line[5] , $line[6]);
     	$p = ($d1 + $d2 + $d3) / 2;
     	$areas[$line[0]] = sqrt($p * ($p - $d1) * ($p - $d2) * ($p - $d3));
    }

    $gistagramm = new Gistagramm($areas);
    $gistagramm->createGistagramm(800 , 400);
    imageJpeg($gistagramm->image);

    mysql_free_result($result);
    mysql_close($link);
?>
