<?PHP
	$settings = array();
	
	/*
	*********** Color settings ************
	*/
	/*
	$settings["color1"] = "<0.8,0.8,0.8>";  // Main walls
	$settings["color2"] = "White";			// Window frames
	$settings["color3"] = "Green";			// Glass colour
	$settings["color4"] = "<0.5,0.5,0.5>";	// drain
	$settings["colorRoof"] = "Red";			// Roof Colour
	$settings["colorWood"] = "<0.8,0.1,0>";	// Door Colour
	*/
	
	$settings["texture1"] = "texture{ pigment {color <0.8,0.8,0.8>}}";  // Main walls
	$settings["texture2"] = "texture{ pigment {color White}}";			// Window frames
	$settings["texture3"] = "texture{ pigment {color Green}}";			// Glass colour
	$settings["texture4"] = "texture{ pigment {color <0.5,0.5,0.5>}}";	// drain
	//$settings["texture5"] = "texture{ pigment {color Red}}";			// Roof Colour
	$settings["texture5"] = "texture{ Cherry_Wood }";			// Roof Colour
	$settings["texture6"] = "texture{ pigment {color <0.8,0.1,0>}}";	// Door Colour
	$settings["texture7"] = "texture{ pigment { color <0.8,0.8,0.8> }  normal { bumps 0.1 } finish { phong albedo 0.9 phong_size 60 }}";
	$settings["texture8"] = "texture { normal { bumps 0.5 } pigment {color <0.5,0.5,0.5> }}";
	/*
	*********** Geometry settings ***********
	*/
	$settings["hallenlaenge_span"] = 25;
	$settings["hallenlaenge_min"] = 10;
	$settings["hallenlaenge"] = 0; // replaymode
	$settings["hallenbreite_span"] = 15;
	$settings["hallenbreite_min"] = 10;
	$settings["hallenbreite"] = 0; // replaymode
	$settings["traufhoehe_span"] = 7;
	$settings["traufhoehe_min"] = 7;
	$settings["traufhoehe"] = 0; // replaymode
	$settings["dachhoehe_span"] = 15;
	$settings["dachhoehe_min"] = 5;
	$settings["dachhoehe"] = 0; // replaymode
	
	$settings["turmdachhoehe_span"] = 25;
	$settings["turmdachhoehe_min"] = 12;
	$settings["turmdachhoehe"] = 0; // replaymode
	$settings["turmhoehe_span"] = 20;
	$settings["turmhoehe_min"] = 15;
	$settings["turmhoehe"] = 0; // replaymode
	$settings["turmbreite_span"] = 5;
	$settings["turmbreite_min"] = 6;
	$settings["turmbreite"] = 0; // replaymode
	
	$settings["turmtranslatex_span"] = 5;
	$settings["turmtranslatez_span"] = 4;
	$settings["turmtranslatex"] = 0; // replaymode
	$settings["turmtranslatez"] = 0; // replaymode

	$settings["turmfensteranz_max"] = 3;
	$settings["turmfensteranz"] = 0;  // replaymode
	$settings["turmfensteranz_left"] = 0;  // replaymode
	$settings["turmfensteranz_right"] = 0;  // replaymode

	$settings["roofType"] = 0; // replaymode
	$settings["sphereOnTop"] = true; // replaymode
	
	// $settings[""] = 0;

	
	$settings["rotate"] = "< 0, 50, 0>";
	$settings["translate"] = "<0, 0, 0>";

	/*
	*********** Non changeable settings ***************
	*/
	$settings["firsthoehe"] = 0;

?>