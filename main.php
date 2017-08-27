<?PHP

require_once("settings.php");  // defines $settings-Array

echo "*** Church Generator ***";


$outputPov = file_get_contents("template.pov");

for($j = 0; $j < 6; $j++) {
	for($i = 0; $i < 6; $i++) {
		$zufall = mt_rand(0,360);
		$settings["rotate"] = "< 0, $zufall, 0>";
		$settings["translate"] = "<-180+ $i * (60),0, -180 +$j * 60>";
		$outputPov .= generate($settings);
	}
}


file_put_contents("output.pov",$outputPov);

// ------------------------------------------
	
	
function generate(&$set) {
	generateColors($set);
	
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS);
	
	$halle = "// Halle".PHP_EOL;
	$halle .= generateHalle($set);
	
	$turm = "// Turm".PHP_EOL;
	$turm .= generateTurm($set);
			
	$result .= "// Beginn einer kompletten Kirche".PHP_EOL;
	$result .= PHP_EOL."union {".PHP_EOL.$halle.PHP_EOL.$turm.PHP_EOL;
	$result .= " rotate $rotate translate $translate} // Ende einer kompletten Kirche".PHP_EOL;
	$result .= "// ##########################################".PHP_EOL . PHP_EOL;
	return $result;
}

function generateColors(&$set) {
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	
	// Main Walls
	$zufall = mt_rand(0,3);
	if ($zufall == 0) {
		$set["texture1"] = "texture{ pigment {color <0.8,0.8,0.8>}}";
	} elseif ($zufall == 1) {
		$set["texture1"] = "texture {pigment { bozo color_map { [0.0 color <0.3,0.6,0.8>]  [1.0 color <0.9,0.9,0.9>] }  }  }";			
	} elseif ($zufall == 2) {
		$set["texture1"] = "texture {pigment { bozo color_map { [0.0 color <0.8,0.8,0.4>]  [1.0 color <1,1,1>] }  }  }";			
	} elseif ($zufall == 3) {
		$set["texture1"] = "texture { T_Stone44 scale 70 }";			
	}

	// Roof
	$zufall = mt_rand(0,3);
	if ($zufall == 0) {
		$texture5 = "texture{ pigment {color Red}}";
	} elseif ($zufall == 1) {
		//$texture5 = "texture{  normal{bumps 0.3 scale 3} finish{reflection{0.2 metallic}} pigment {color <0.7,0.4,0.4>}}";
		$texture5 = "texture{ T_Wood33 scale 20}";
	} elseif ($zufall == 2) {
		$texture5 = "texture { pigment { gradient y color_map { [0.0 color <0.7,0.2,0.1>]  [1.0 color <0.5,0.1,0.1>] }  } }";			
	} elseif ($zufall == 3) {
		$texture5 = "texture{ T_Brass_1A scale 5 normal{bumps 0.1}}";			
	}
	
	// Glass
	$zufall = mt_rand(0,1);
	if ($zufall == 0) {
		$texture3 = "texture{  normal{bumps 0.3 scale 1} finish{reflection{0.7 }} pigment {color <0.2,0.9,0.2>}}";
	} elseif($zufall == 1) {
		$texture3 = "texture{  normal{bumps 0.3 scale 1} finish{reflection{0.7 }} pigment {color <0.2,0.2,0.9>}}";
	}
	
	/*
	$settings["texture1"] = "texture{ pigment {color <0.8,0.8,0.8>}}";  // Main walls
	$settings["texture2"] = "texture{ pigment {color White}}";			// Window frames
	$settings["texture3"] = "texture{ pigment {color Green}}";			// Glass colour
	$settings["texture4"] = "texture{ pigment {color <0.5,0.5,0.5>}}";	// drain
	//$settings["texture5"] = "texture{ pigment {color Red}}";			// Roof Colour
	$settings["texture5"] = "texture{ Cherry_Wood }";			// Roof Colour
	$settings["texture6"] = "texture{ pigment {color <0.8,0.1,0>}}";	// Door Colour
	$settings["texture7"] = "texture{ pigment { color <0.8,0.8,0.8> }  normal { bumps 0.1 } finish { phong albedo 0.9 phong_size 60 }}";
	$settings["texture8"] = "texture { normal { bumps 0.5 } pigment {color <0.5,0.5,0.5> }}";
	*/
}

function generateHalle(&$set) {
	$result = "";
	$add1 = "union{".PHP_EOL; // for creating a union of differences
	$sub1 = "";
	$add2 = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	$hallenlaenge = mt_rand($hallenlaenge_min, $hallenlaenge_min + $hallenlaenge_span);
	$hallenbreite = mt_rand($hallenbreite_min, $hallenbreite_min + $hallenbreite_span);
	$hallenbreite2 = $hallenbreite * 0.5;

	$traufhoehe = mt_rand($traufhoehe_min, $traufhoehe_min + $traufhoehe_span);
	$dachhoehe = mt_rand($dachhoehe_min, $dachhoehe_min + $dachhoehe_span);
	$firsthoehe = $traufhoehe + $dachhoehe;

	// Erzeugung aller Elemente der Haupthalle, so dass die zur xy und yz Ebene Symmetrisch ist!
	$add1 .= generateHaupthalle($set,true);
	
	// Querschiff
	$zufall = mt_rand(0,1);
	if(($zufall == 1) AND ($hallenlaenge > ($hallenbreite+2))) {
		$span = $hallenlaenge - $hallenbreite;
		$zval = mt_rand(0,$span);
		$tmp = "// Querschiff ".PHP_EOL;
		$tmp .= "union {".PHP_EOL;
		$tmp .= generateHaupthalle($set,false);
		$tmp .= "translate<0,0,$hallenlaenge / 2>".PHP_EOL;
		$tmp .= "scale 0.9".PHP_EOL;			
		$tmp .= "rotate<0,90,0>".PHP_EOL;
		$tmp .= "translate<0,0,-($zval + $hallenbreite / 2)>".PHP_EOL;
		$tmp .= "} // Querschiff-Ende".PHP_EOL;
		$add1 .= $tmp;
	}
	
	
	// Türmchen
	$zufall = mt_rand(0,1);
	if($zufall == 1) {
		$add1 .= generateHintertuermchen($set);
	}

	$add1 .= "} // End of union".PHP_EOL;
	
	
	if($sub1 == "") {
		$result = $add1.PHP_EOL.$add2;
	} else {
		$result = "difference {". PHP_EOL .$add1. PHP_EOL .$sub1. PHP_EOL ."}".PHP_EOL.$add2;
	}
	$result .= PHP_EOL . "// --- End of generateHalle() ---".PHP_EOL;
	return $result;
}


function generateHaupthalle(&$set, $apsisPossible) {
	$result = "// Haupthalle".PHP_EOL;
	$add1 = "union{".PHP_EOL; // for creating a union of differences
	$sub1 = "";
	$add2 = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	$hallenbreite2 = $hallenbreite * 0.5;
	$firsthoehe = $traufhoehe + $dachhoehe;

	// Haupthalle mit Dach
	$add1 .= "box { <-$hallenbreite2, 0 , 0>, < $hallenbreite2, $traufhoehe , -$hallenlaenge> $texture1 }".PHP_EOL;
	$add1 .= "object{ MDRoof scale < $hallenbreite2 , $dachhoehe , $hallenlaenge> translate <0, $traufhoehe, -$hallenlaenge>  $texture5}".PHP_EOL;

	// Giebel
	$zufall = 0;
	if($apsisPossible) {
		$zufall = mt_rand(0,3);
	} else {
		$zufall = mt_rand(0,1);
	}
	if($zufall == 0) {
		$add1 .= generateGiebel1($set,true, true);
	} elseif($zufall == 1) {
		$add1 .= generateGiebel2($set, true, true);			
	} elseif($zufall == 2) {
		$add1 .= generateGiebel1($set,true, false);
		$add1 .= generateApsis($set);
	} elseif($zufall == 3) {
		$add1 .= generateGiebel2($set,true, false);
		$add1 .= generateApsis($set);
	}
	
	// Strebepfeiler
	$add1 .= generateStrebepfeiler($set);
	
	// Dachrinne
	$add1 .= "// Dachrinne Halle".PHP_EOL;
	$add1 .= "cylinder{ <$hallenbreite2,$traufhoehe,0> , <$hallenbreite2,$traufhoehe,-$hallenlaenge>,0.5  $texture4  }".PHP_EOL;
	$add1 .= "cylinder{ <-$hallenbreite2,$traufhoehe,0> , <-$hallenbreite2,$traufhoehe,-$hallenlaenge>,0.5  $texture4  }".PHP_EOL;
	
	// MiniErker
	$zufall = mt_rand(0,10);
	if($zufall <= 4) { 
		$add1 .= generateErker($set);
	}		

	$add1 .= "} // End of union".PHP_EOL;
	
	// Hallenfenster
	$fensterarray = generateHallenfenster($set); // ["sub1"]=>... , ["add2"]=>...
	$sub1 .= $fensterarray["sub1"];
	$add2 .= $fensterarray["add2"];
	
	if($sub1 == "") {
		$result = $add1.PHP_EOL.$add2;
	} else {
		$result = "difference {". PHP_EOL .$add1. PHP_EOL .$sub1. PHP_EOL ."}".PHP_EOL.$add2;
	}
	$result .= PHP_EOL . "// --- End of generateHalle() ---".PHP_EOL;
	return $result;
}

function generateHintertuermchen(&$set) {
	$result = "// Hintertuermchen".PHP_EOL;
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	$firsthoehe = $traufhoehe + $dachhoehe;
	
	$result .= "cylinder{<0,0,-$hallenlaenge>,<0,$firsthoehe + 2,-$hallenlaenge> , 1.5 $texture1} ".PHP_EOL;
	$result .= "cone{<0,$firsthoehe + 2,-$hallenlaenge>, 1.8 <0,$firsthoehe + 8,-$hallenlaenge> , 0 $texture5} ".PHP_EOL;
	
	return $result;
}

function generateApsis(&$set) {
	$result = "// Apsis".PHP_EOL;
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	$firsthoehe = $traufhoehe + $dachhoehe;
	$segmentLen = $hallenbreite/11;
	
	$result .= "cylinder{<0,0,-$hallenlaenge>,<0,$traufhoehe,-$hallenlaenge>, ($hallenbreite / 2) $texture1 }".PHP_EOL;
	$result .= "cone{<0,$traufhoehe,-$hallenlaenge>,($hallenbreite / 2),<0,$firsthoehe,-$hallenlaenge>,0  $texture5 }".PHP_EOL;
	
	$result .= "//Dachrinne der Apsis".PHP_EOL;
	$result .="intersection {".PHP_EOL;
	$result .= "torus{($hallenbreite/2) , (0.5) }".PHP_EOL;
	$result .= "box{<-$hallenbreite,-3,0>,<$hallenbreite,3,-($hallenbreite + 1)>}";
	$result .= "translate<0,$traufhoehe,-$hallenlaenge> $texture4";
	$result .= "}";
	
	return $result;
}

// $front: boolean, front-giebel
// $back:  boolean, back-giebel
function generateGiebel1(&$set, $front, $back) {
	$result = "// Giebeltreppen".PHP_EOL;
	
	$add1 = "";
	$sub1 = "";
	$add2 = "";
	
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	$firsthoehe = $traufhoehe + $dachhoehe;
	$segmentLen = $hallenbreite/11;
	
	
	for($i = 0; $i < 5; $i++) {
		$x1 = (  $i   * $segmentLen) - ($hallenbreite/2);
		$x2 = $x1 + $segmentLen;
		$yval = $traufhoehe + ($segmentLen * ($i+1)) * ($dachhoehe / ($hallenbreite / 2));
		
		if($front) {
			$add1 .= "box{<$x1,0,0>,<$x2,$yval,0.5> $texture1}".PHP_EOL;
			$add1 .= "box{<-$x1,0,0>,<-$x2,$yval,0.5> $texture1}".PHP_EOL;
		}
		if($back) {
			$add1 .= "box{<$x1,0,-$hallenlaenge>,<$x2,$yval,-$hallenlaenge-0.5> $texture1}".PHP_EOL;
			$add1 .= "box{<-$x1,0,-$hallenlaenge>,<-$x2,$yval,-$hallenlaenge-0.5> $texture1}".PHP_EOL;
		}
	}
	$xm1 = (  5   * $segmentLen) - ($hallenbreite/2);
	$xm2 = $xm1 + $segmentLen;
	
	if($front) {
		$add1 .= "box{<$xm1,0,0>,<$xm2,$firsthoehe,0.5> $texture1}".PHP_EOL;
		// Rosettenfenster
		$sub1 .= "cylinder{<0,($firsthoehe / 2) ,2 >, <0,($firsthoehe / 2) ,-2 >, 3 $texture3}".PHP_EOL;
		$add2 .= "cylinder{<0,($firsthoehe / 2) ,0.1 >, <0,($firsthoehe / 2) ,-0.1 >, 3 $texture3}".PHP_EOL;
		
	}
	if($back) {
		$add1 .= "box{<$xm1,0,-$hallenlaenge>,<$xm2,$firsthoehe,-$hallenlaenge-0.5> $texture1}".PHP_EOL;
		// Rosettenfenster
		$sub1 .= "cylinder{<0,($firsthoehe / 2) ,-$hallenlaenge + 2 >, <0,($firsthoehe / 2) ,-$hallenlaenge - 2 >, 3 $texture3}".PHP_EOL;
		$add2 .= "cylinder{<0,($firsthoehe / 2) ,-$hallenlaenge + 0.1 >, <0,($firsthoehe / 2) ,-$hallenlaenge -0.1 >, 3 $texture3}".PHP_EOL;
	}
	
	$add1 = "union{".PHP_EOL.$add1.PHP_EOL."}";
			
	if($sub1 == "") {
		$result .= $add1.PHP_EOL.$add2;
	} else {
		$result .= "difference {". PHP_EOL .$add1. PHP_EOL .$sub1. PHP_EOL ."}".PHP_EOL.$add2;
	}
	$result .= PHP_EOL . "// --- End of generateGiebel1() ---".PHP_EOL;
	
	
	return $result;
}

// $front: boolean, front-giebel
// $back:  boolean, back-giebel
function generateGiebel2(&$set, $front, $back) {
	$result = "// Giebelabschluss".PHP_EOL;
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	$firsthoehe = $traufhoehe + $dachhoehe;
	$verhaeltnis = 2 * $traufhoehe / $firsthoehe;
				
	if($front) {
		$result .= "difference {".PHP_EOL;
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 6,  <-1,0>, <1,0>, <1,$verhaeltnis>, <0,2>, <-1,$verhaeltnis>, <-1,0> rotate -90*x scale<$hallenbreite / 2, $firsthoehe/2, 1> scale<1.05,1.05,1> translate<0,0,0> $texture1 }".PHP_EOL;
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 6,  <-1,0>, <1,0>, <1,$verhaeltnis>, <0,2>, <-1,$verhaeltnis>, <-1,0> rotate -90*x scale<$hallenbreite / 2, $firsthoehe/2, 1> scale<1,1,1> translate<0,0,0.6> $texture1 }".PHP_EOL;
		$result .= "}".PHP_EOL;
	}
	if($back) {
		$result .= "difference {".PHP_EOL;
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 6,  <-1,0>, <1,0>, <1,$verhaeltnis>, <0,2>, <-1,$verhaeltnis>, <-1,0> rotate -90*x scale<$hallenbreite / 2, $firsthoehe/2, 1> scale<1.05,1.05,1> translate<0,0,-$hallenlaenge> $texture1 }".PHP_EOL;
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 6,  <-1,0>, <1,0>, <1,$verhaeltnis>, <0,2>, <-1,$verhaeltnis>, <-1,0> rotate -90*x scale<$hallenbreite / 2, $firsthoehe/2, 1> scale<1,1,1> translate<0,0,-$hallenlaenge - 0.6> $texture1 }".PHP_EOL;
		$result .= "}".PHP_EOL;
	}
	return $result;
}

function generateErker(&$set) {
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	
	$anzSegmente = floor($hallenlaenge / 8); // mindestens 8m Erkerabstand
	$segmentLen = $hallenlaenge / $anzSegmente;
	
	$erkerZ = array();
	for($i = 0; $i < $anzSegmente; $i++) {
		$erkerZ[] = (-1) * (($segmentLen/2) + $i*$segmentLen);
	}
	$erkerY = $traufhoehe + $dachhoehe/2; 
	$erkerX = $hallenbreite/4;
	
	foreach($erkerZ as $zval) {
		$result .= "box { <-0.5, -0.5 , -0.5>, < 0.5,0.5,0.5> $texture4 ".
		"scale < 4,4,2> translate< -1,-1,0> rotate < 0,0,-10>  translate < $erkerX , $erkerY, $zval >}".PHP_EOL;
		$result .= "box { <-0.5, -0.5 , -0.5>, < 0.5,0.5,0.5> $texture4 ".
		"scale < 4,4,2> translate< 1,-1,0> rotate < 0,0,10>  translate < (-1) * $erkerX , $erkerY, $zval >}".PHP_EOL;
	}
	return $result;
}

function generateStrebepfeiler(&$set) { 
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	// Hallensegmente
	$anzSegmente = floor($hallenlaenge / 8); // mindestens 8m Segmentabstand
	$segmentLen = $hallenlaenge / $anzSegmente;
	$hallenbreite2 = $hallenbreite/2;
	
	$segZ = array();
	for($i = 0; $i <= $anzSegmente; $i++) {
		$segZ[] = (-1) * ( $i*$segmentLen);
	}
	foreach($segZ as $zval) {
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 5,  <-1,0>, <0,0>, <0,2>, <-1,1.8>, <-1,0> $texture1 rotate -90*x scale<1, $traufhoehe/2, 1> translate<-$hallenbreite2, 0, $zval>  }".PHP_EOL;
		$result .= "prism {linear_sweep linear_spline -0.5, 0.5, 5,  <1,0>, <0,0>, <0,2>, <1,1.8>, <1,0> $texture1 rotate -90*x  scale<1, $traufhoehe/2, 1> translate<$hallenbreite2, 0, $zval>  }".PHP_EOL;
	}
	
	return $result;
}
function generateHallenfenster(&$set) { 
	$result = array();    // ["sub1"]=>... , ["add2"]=>...
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	// Hallensegmente
	$anzSegmente = floor($hallenlaenge / 8); // mindestens 8m Segmentabstand
	$segmentLen = $hallenlaenge / $anzSegmente;
	
	$fensterZ = array();
	for($i = 0; $i < $anzSegmente; $i++) {
		$fensterZ[] = (-1) * (($segmentLen/2) + $i*$segmentLen);
	}
	$fensterY = $traufhoehe/2; 
	$fensterX = $hallenbreite/2;
	$fensterhoehe = $traufhoehe * 0.7;
			
	// Windows to subtract		
	$wintosubtract = "union { // alle fensterloecher in der hallenbox".PHP_EOL;
	foreach($fensterZ as $zval) {
		$wintosubtract .= "object{ MDPortal $texture3 ".
		"rotate<0,90,0> scale<0.2,0.5 * $fensterhoehe,1> translate<$fensterX,$fensterY,$zval>}".PHP_EOL;
		$wintosubtract .= "object{ MDPortal $texture3 ".
		"rotate<0,90,0> scale<0.2,0.5 * $fensterhoehe,1> translate<-$fensterX,$fensterY,$zval>}".PHP_EOL;
	}
	$wintosubtract .= "}".PHP_EOL;
	
	$wintoadd = "";
	foreach($fensterZ as $zval) {
		$wintoadd .= "difference {  // der Fensterrahmen".PHP_EOL;
		$wintoadd .= "object{ MDPortal $texture2 ".
		"rotate<0,90,0> scale<0.2,0.5 * $fensterhoehe,1> translate<$fensterX,$fensterY,$zval>}".PHP_EOL;
		$wintoadd .= "object{ MDPortal $texture2 ".
		"rotate<0,90,0> scale<0.3,0.5 * $fensterhoehe * 0.9,0.9> translate<$fensterX,$fensterY,$zval>}".PHP_EOL;
		$wintoadd .= "}".PHP_EOL;
		
		$wintoadd .= "difference {  // der Fensterrahmen".PHP_EOL;
		$wintoadd .= "object{ MDPortal $texture2 ".
		"rotate<0,90,0> scale<0.2,2,1> translate<-$fensterX,$fensterY,$zval>}".PHP_EOL;
		$wintoadd .= "object{ MDPortal $texture2 ".
		"rotate<0,90,0> scale<0.3,0.5 * $fensterhoehe * 0.9,0.9> translate<-$fensterX,$fensterY,$zval>}".PHP_EOL;
		$wintoadd .= "}".PHP_EOL;
	}
	
	$result["sub1"] = $wintosubtract . PHP_EOL;
	$result["add2"] = $wintoadd . PHP_EOL;
		
	return $result;
}
	

	

function generateTurm(&$set) {
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	$zufall = mt_rand(1,10);
	if($zufall <= 8) { // ein normaler Turm
		$result = "union{".PHP_EOL;
		$result .= generateTurmStd($set);
		$turmbreite2 = $turmbreite * 0.5;
		// Turm translate
		$turmtranslatex = mt_rand(-$turmtranslatex_span,$turmtranslatex_span);
		$turmtranslatez = mt_rand(1-$turmbreite2,$turmtranslatez_span);
		$result .= "translate<$turmtranslatex,0,$turmtranslatez - $turmbreite2>".PHP_EOL;
		$result .= "}".PHP_EOL;
	} elseif($zufall <= 10) { // zwei Symmetrische Tuerme
		$seed = mt_rand();
		
		$result .= "union{".PHP_EOL;
		mt_srand($seed);
		$result .= generateTurmStd($set);
		$turmbreite2 = $turmbreite * 0.5;
		// Turm translate
		$result .= "translate<$hallenbreite / 2,0,0-$turmbreite2>".PHP_EOL;
		$result .= "}".PHP_EOL;
	
		$result .= "union{".PHP_EOL;
		mt_srand($seed); // gleiche Turmart erneut generieren!
		$result .= generateTurmStd($set);
		$turmbreite2 = $turmbreite * 0.5;
		// Turm translate
		$result .= "translate<-$hallenbreite / 2,0,0-$turmbreite2>".PHP_EOL;
		$result .= "}".PHP_EOL;
		
	}
	
	return $result;
}

function generateTurmStd(&$set) {
	$result = "union{ // Der Turm".PHP_EOL;
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	$turmdachhoehe = mt_rand($turmdachhoehe_min, $turmdachhoehe_min + $turmdachhoehe_span);
	$turmhoehe = mt_rand($turmhoehe_min, $turmhoehe_min + $turmhoehe_span);
	if($turmhoehe < $firsthoehe) {
		$turmhoehe = $firsthoehe;  // dach höher als turm ist unlogisch
	}

	$turmbreite = mt_rand($turmbreite_min, $turmbreite_min + $turmbreite_span);
	$turmbreite2 = $turmbreite * 0.5;

	// Portal
	$result .= generatePortal($set) . PHP_EOL;
	
	// Turmsegmente
	$anzSegmente = floor($turmhoehe / 6); // mindestens 6m Turmsegmenthoehe
	$segmentLen = $turmhoehe / $anzSegmente;
	
	$zufall = mt_rand(0,10);
	if($zufall <= 10) {
		$result .= generateTurmsegmente($set);
	}
	
	if($anzSegmente >= 4) {
		$fensterarray = generateTurmfenster($set); // ["sub1"]=>... , ["add2"]=>...
		$result .= "difference { ".PHP_EOL;
		$result .= "  box {< $turmbreite2, 0, 0>, < $turmbreite2 * (-1), $turmhoehe, $turmbreite> $texture1 }".PHP_EOL;
		$result .= "  ".$fensterarray["sub1"];
		$result .= "}".PHP_EOL;
		
		$result .= "// jetzt die Fensterrahmen. ".PHP_EOL;
		$result .= $fensterarray["add2"].PHP_EOL;			
	} else {
		$result .= "  box {< $turmbreite2, 0, 0>, < $turmbreite2 * (-1), $turmhoehe, $turmbreite> $texture1 }".PHP_EOL;			
	}

	// Turmuhr
	if($anzSegmente >= 3) {
		$result .= "object{MDClock scale<0,1.3,0.3> translate<0,($anzSegmente-0.5) * $segmentLen, $turmbreite>}".PHP_EOL;
	}
	
	
	// Dachpyramiden
	$tmp = mt_rand(0,35); // dachtyp  (gewichteter Zufall)
	if($tmp <= 5) {
		$roofType = 1;
	} elseif ($tmp <= 8) {
		$roofType = 2;
	} elseif ($tmp <= 10) {
		$roofType = 3;
	} elseif ($tmp <= 15) {
		$roofType = 4;
	} elseif ($tmp <= 20) {
		$roofType = 5;
	} elseif ($tmp <= 25) {
		$roofType = 6;
	} elseif ($tmp <= 35) {
		$roofType = 7;
	}	
		
	if ($roofType == 1) { // Einfache Pyramide
		$result .=  "object{ MDPyramid scale <$turmbreite2 * 1.15, $turmdachhoehe , $turmbreite2 * 1.15> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
	} elseif ($roofType == 2) { // Zwei einfache Pyramiden
		$result .=  "object{ MDPyramid scale <$turmbreite2 * 1.15, 0.5* $turmdachhoehe , $turmbreite2 * 1.15> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 *0.7, $turmdachhoehe , $turmbreite2 * 0.7> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
	} elseif ($roofType == 3) { // Drei einfache Pyramiden
		$result .=  "object{ MDPyramid scale <$turmbreite2 * 1.15, 0.3* $turmdachhoehe , $turmbreite2 * 1.15> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 *0.7, 0.6* $turmdachhoehe , $turmbreite2 * 0.7> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 *0.5, $turmdachhoehe , $turmbreite2 * 0.5> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
	} elseif ($roofType == 4) { // Zwiebeldach
		$result .=  "object{ MDPyramid scale <$turmbreite2, $turmbreite2 , $turmbreite2> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		// wenn turmdachhoehe nicht groß genug ist, dann wird sie angepasst!
		if($turmdachhoehe < $turmbreite) {
			$turmdachhoehe = 3 * $turmbreite2;
		}
		$result .=  "object{ MDPyramid scale <$turmbreite2 * 0.6, $turmdachhoehe , $turmbreite2 * 0.6> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		
		$result .= "intersection {".PHP_EOL;
		$result .= "    cylinder{<-$turmbreite2,0,0>, <$turmbreite2,0,0>, $turmbreite2 $texture5}".PHP_EOL;
		$result .= "    cylinder{<0,0,-$turmbreite2>, <0,0,$turmbreite2>, $turmbreite2 $texture5}".PHP_EOL;
		$result .= "    translate <0,$turmhoehe + $turmbreite2,$turmbreite2>".PHP_EOL;
		$result .= "}".PHP_EOL;

		//$result .=  "sphere{<0,$turmhoehe+ $turmbreite2,$turmbreite2>, $turmbreite2 $texture5 }".PHP_EOL;
	} elseif($roofType == 5) { // eine Pyramide mit vier kleinen Pyramiden
		$result .=  "object{ MDPyramid scale <$turmbreite2*0.8, $turmdachhoehe , $turmbreite2*0.8> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		// and 4 smaller edge-pyramids
		$result .=  "object{ MDPyramid scale <$turmbreite2 / 3, $turmdachhoehe * 0.5 , $turmbreite2 / 3 > translate <0 + ($turmbreite / 3), $turmhoehe, $turmbreite2 + ($turmbreite / 3)> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 / 3, $turmdachhoehe * 0.5 , $turmbreite2 / 3 > translate <0 + ($turmbreite / 3), $turmhoehe, $turmbreite2 - ($turmbreite / 3)> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 / 3, $turmdachhoehe * 0.5 , $turmbreite2 / 3 > translate <0 - ($turmbreite / 3), $turmhoehe, $turmbreite2 + ($turmbreite / 3)> $texture5 }".PHP_EOL;
		$result .=  "object{ MDPyramid scale <$turmbreite2 / 3, $turmdachhoehe * 0.5 , $turmbreite2 / 3 > translate <0 - ($turmbreite / 3), $turmhoehe, $turmbreite2 - ($turmbreite / 3)> $texture5 }".PHP_EOL;
	} elseif ($roofType == 6) { // Prag-Style
		$result .=  "object{ MDPrague scale <$turmbreite2, $turmdachhoehe * 2 , $turmbreite2> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		
		$result .= "sphere { <$turmbreite / 4, $turmhoehe + $turmdachhoehe , $turmbreite2 >, 0.5". " $texture7 }".PHP_EOL;

		$result .= "sphere{<- $turmbreite / 4, $turmhoehe + $turmdachhoehe , $turmbreite2 >, 0.5 ". " $texture7 }".PHP_EOL;
	} elseif ($roofType == 7) { // Südtirol-Style
		$result .= "intersection {".PHP_EOL;
		$result .=  "object{ MDPyramid8 scale <$turmbreite2, $turmdachhoehe , $turmbreite2> translate <0, $turmhoehe, $turmbreite2> $texture5 }".PHP_EOL;
		$result .=  "box{ <$turmbreite2,$turmhoehe,0>, <-$turmbreite2, $turmhoehe+ $turmdachhoehe , $turmbreite>  $texture1 }".PHP_EOL;
		$result .= "}".PHP_EOL;
		$result .= "object{MDRoof translate z*(-0.5) scale <1.05,1,1.05> scale<$turmbreite2,$turmdachhoehe/3,$turmbreite> translate <0,$turmhoehe, $turmbreite2> $texture1}".PHP_EOL;
		$result .= "object{MDRoof translate z*(-0.5) rotate y*90 scale <1.05,1,1.05> scale<$turmbreite,$turmdachhoehe/3,$turmbreite2> translate <0,$turmhoehe, $turmbreite2> $texture1}".PHP_EOL;
		$result .= "// Dachrinne Suedtiroler Erker".PHP_EOL;
		$result .= "cylinder{ <$turmbreite2,$turmhoehe,0> , <0,$turmhoehe+$turmdachhoehe/3,0>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <-$turmbreite2,$turmhoehe,0> , <0,$turmhoehe+$turmdachhoehe/3,0>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <$turmbreite2,$turmhoehe,$turmbreite> , <0,$turmhoehe+$turmdachhoehe/3,$turmbreite>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <-$turmbreite2,$turmhoehe,$turmbreite> , <0,$turmhoehe+$turmdachhoehe/3,$turmbreite>,0.5  $texture4  }".PHP_EOL;

		$result .= "cylinder{ <$turmbreite2,$turmhoehe,0> , <$turmbreite2,$turmhoehe+$turmdachhoehe/3,$turmbreite2>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <$turmbreite2,$turmhoehe,$turmbreite> , <$turmbreite2,$turmhoehe+$turmdachhoehe/3,$turmbreite2>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <-$turmbreite2,$turmhoehe,0> , <-$turmbreite2,$turmhoehe+$turmdachhoehe/3,$turmbreite2>,0.5  $texture4  }".PHP_EOL;
		$result .= "cylinder{ <-$turmbreite2,$turmhoehe,$turmbreite> , <-$turmbreite2,$turmhoehe+$turmdachhoehe/3,$turmbreite2>,0.5  $texture4  }".PHP_EOL;
		
	}
	
	// Turmecksteine
	$result .= "// Turmecksteine".PHP_EOL;
	$result .= "box{ <0,0,0> , <1.5, $turmhoehe , 1.5>  $texture8 translate <-($turmbreite2 + 0.1 )  ,0, -0.1> }".PHP_EOL;
	$result .= "box{ <0,0,0> , <1.5, $turmhoehe , 1.5>  $texture8 translate <($turmbreite2 + 0.1 -1.5) ,0, -0.1> }".PHP_EOL;
	$result .= "box{ <0,0,0> , <1.5, $turmhoehe , 1.5>  $texture8 translate <-($turmbreite2 + 0.1 ) ,0, $turmbreite-1.5+0.1> }".PHP_EOL;
	$result .= "box{ <0,0,0> , <1.5, $turmhoehe , 1.5>  $texture8 translate <($turmbreite2 + 0.1 -1.5) ,0, $turmbreite-1.5+0.1> }".PHP_EOL . PHP_EOL;
	
	// Dachrinne
	$result .= "// Dachrinne Turm".PHP_EOL;
	$result .= "cylinder{ <$turmbreite2,$turmhoehe,0> , <$turmbreite2,$turmhoehe,$turmbreite>,0.5  $texture4  }".PHP_EOL;
	$result .= "cylinder{ <-$turmbreite2,$turmhoehe,0> , <-$turmbreite2,$turmhoehe,$turmbreite>,0.5  $texture4  }".PHP_EOL;
	$result .= "cylinder{ <-$turmbreite2,$turmhoehe,0> , <$turmbreite2,$turmhoehe,0>,0.5  $texture4  }".PHP_EOL;
	$result .= "cylinder{ <-$turmbreite2,$turmhoehe,$turmbreite> , <$turmbreite2,$turmhoehe,$turmbreite>,0.5  $texture4  }".PHP_EOL;
	
	// ggf. kugel auf dem Turmdach
	$sphereOnTop = (mt_rand(0,10) < 5);
	if($sphereOnTop AND $roofType <= 5) {
		$result .=  "sphere { <0, $turmhoehe + $turmdachhoehe , $turmbreite2 >, 0.5  $texture7 }".PHP_EOL;
	}
	
	
	$result .= "} // Ende des Turms".PHP_EOL;
	
	return $result;
}

function generateTurmsegmente(&$set) {
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	// Turmsegmente
	$anzSegmente = floor($turmhoehe / 6); // mindestens 6m Turmsegmenthoehe
	$segmentLen = $turmhoehe / $anzSegmente;
	$turmbreite2 = $turmbreite/2;
	
	for($i = 2; $i < $anzSegmente; $i++) {  // untere beide Segmente fuer das Portal
		$result .="box{< -$turmbreite2 , -0.2 , -0.3>, < $turmbreite2 , 0.2, 0.3> ";
		$result .="translate < 0, $segmentLen * $i , 0> $texture1 }".PHP_EOL;
		$result .="box{< -$turmbreite2 , -0.2 , -0.3>, < $turmbreite2 , 0.2, 0.3> ";
		$result .="translate < 0, $segmentLen * $i , $turmbreite> $texture1 }".PHP_EOL;

		$result .="box{< -0.3 , -0.2 , 0>, < 0.3 , 0.2, $turmbreite> ";
		$result .="translate < $turmbreite2, $segmentLen * $i , 0> $texture1 }".PHP_EOL;
		$result .="box{< -0.3 , -0.2 , 0>, < 0.3 , 0.2, $turmbreite> ";
		$result .="translate < -$turmbreite2, $segmentLen * $i , 0> $texture1 }".PHP_EOL;
		
	}
	
	return $result;
}

function generateTurmfenster(&$set) { 
	$result = array();    // ["sub1"]=>... , ["add2"]=>...
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars

	// Turmsegmente
	$anzSegmente = floor($turmhoehe / 6); // mindestens 6m Turmsegmenthoehe
	$segmentLen = $turmhoehe / $anzSegmente;
	$turmbreite2 = $turmbreite/2;
	
	// da die unteren beiden Segmente vom Portal und das oberste von der Uhr
	// belegt sind, bleiben nur die "mittleren":
	
	// Windows to subtract
	$turmfenster_yi = array();
	for($i = 2; $i < ($anzSegmente-1); $i++) {
		$turmfenster_yi[] = $segmentLen/2 + $i * $segmentLen;
	}
	$turmfenster_r = $turmbreite/6; // radius aussen
	$turmfenster_r2 = $turmfenster_r * 0.8; // radius innen
	
	$wintosubtract = "union { // alle fensterloecher in der turmbox".PHP_EOL;
	foreach($turmfenster_yi as $turmfenster_y) {
		$wintosubtract .= "  cylinder{<0,0,0> , <0,0,1>, $turmfenster_r $texture3 translate<0, $turmfenster_y , $turmbreite - 0.8 >}".PHP_EOL; // 0.8: 80cm versenken in den turm, 20cm überstand
	}
	$wintosubtract .= "}".PHP_EOL;
	
	$wintoadd = "";
	foreach($turmfenster_yi as $turmfenster_y) {
		$wintoadd .= "difference {  // der Fensterrahmen".PHP_EOL;
		$wintoadd .= "  cylinder{<0,0,0> , <0,0,1>, $turmfenster_r $texture2 translate<0, $turmfenster_y , $turmbreite - 0.8 >}".PHP_EOL;
		$wintoadd .= "  cylinder{<0,0, -0.0001> , <0,0,1>, $turmfenster_r2 $texture2 scale  <1,1,1.0001>  translate<0, $turmfenster_y , $turmbreite - 0.8 >  }".PHP_EOL;
		$wintoadd .= "}".PHP_EOL;
	}
	
	$result["sub1"] = $wintosubtract . PHP_EOL;
	$result["add2"] = $wintoadd . PHP_EOL;
		
	return $result;
}


function generatePortal(&$set) {
	$result = "";
	extract($set,EXTR_OVERWRITE | EXTR_REFS); // extract $settings to local vars
	
	$result .= "difference {".PHP_EOL;
	
	// Portal außen
	$result .= "union {".PHP_EOL;
	$result .= "box {<-1, 0 , 0>, <1,1,1>} ".PHP_EOL;
	$result .= "cylinder{<0, 1 , 0>, <0,1,1> , 1} ".PHP_EOL;
	$result .= "scale <$turmbreite /4, $turmbreite / 2 , 1 > ";
	$result .= "translate <0, 0 , $turmbreite> ";
	$result .= " $texture1 ".PHP_EOL;
	$result .= "}".PHP_EOL;
	
	// Portal innen
	$result .= "union {".PHP_EOL;
	$result .= "box {<-1, 0 , 0>, <1,1,1>} ".PHP_EOL;
	$result .= "cylinder{<0, 1 , 0>, <0,1,1> , 1} ".PHP_EOL;
	$result .= "scale < 0.8 , 0.9 , 1.01> ".PHP_EOL;  // !!!!
	$result .= "scale <$turmbreite /4, $turmbreite / 2 , 1 > ";
	$result .= "translate <0, 0 , $turmbreite> ";
	$result .= " $texture1 ".PHP_EOL;
	$result .= "}".PHP_EOL;
	$result .= "} // Ende Portal".PHP_EOL;

	// Tür
	$result .= "union {".PHP_EOL;
	$result .= "box {<-1, 0 , 0>, <1,1,1>} ".PHP_EOL;
	$result .= "cylinder{<0, 1 , 0>, <0,1,1> , 1} ".PHP_EOL;
	$result .= "scale < 0.8 , 0.9 , 0.01> ".PHP_EOL;  // !!!!
	$result .= "scale <$turmbreite /4, $turmbreite / 2 , 1 > ";
	$result .= "translate <0, 0 , $turmbreite> ";
	$result .= " $texture6 ".PHP_EOL;
	$result .= "}".PHP_EOL;

	
	return $result;
}
	
?>


