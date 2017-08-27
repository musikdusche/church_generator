#include "colors.inc"                                                           
#include "stones.inc"                                                           
#include "textures.inc"                                                         
#include "shapes.inc"                                                           
#include "glass.inc"                                                            
#include "metals.inc"                                                           
#include "woods.inc"                                                            
#declare slowRender = false;
#declare areaLightDimension = 5;
#declare useFocalBlur = false;                                                        
#declare myBrilliance = 0.2;                                                      
#declare myDiffuse = 1;                                                           
#declare myReflection = 0.09;                                                      
#declare myAmbientFactor = 0.2;


global_settings { assumed_gamma 1.8 }                                             

#if (slowRender)
  #include "rad_def.inc"
  global_settings {
    radiosity {
      Rad_Settings(Radiosity_Normal,on,on)
      brightness 1.6
      count 1000
      nearest_count 20
      minimum_reuse 0.015
      pretrace_end 0.001

    }
  }
  #default {finish{ambient 0}}
  #declare areaLightDimension = 8 ;                                                 
  #declare myAmbientFactor = 0.0;
#else
  #declare areaLightDimension = 1;
#end
    
    
    
//#declare myclock = clock;
#declare myclock = 0.1;
                                                                                  
camera {                                                                          
   location  <200 , 185,200> rotate <0,myclock * 360,0>
   look_at   <0, -40, 0>                                                            
   #if (useFocalBlur)                                                                
      focal_point < 10, 0, 7>                                                       
      aperture 0.2         // hoher Wert = viel Unschaerfe                           
      blur_samples 10      // more samples higher quality image                     
      variance  1/10000   // je kleiner je smoother                                
   #end                                                                              
}                                                                                 
    
/*                                                                                    
camera {                                                                          
   location  <60, 85,140> rotate <0,360*clock,0>
   look_at   <-20, 25, 110>                                                            
   #if (useFocalBlur)                                                                
      focal_point < 10, 0, 7>                                                       
      aperture 0.2         // hoher Wert = viel Unschaerfe                           
      blur_samples 10      // more samples higher quality image                     
      variance  1/10000   // je kleiner je smoother                                
   #end                                                                              
}                                                                                 
*/                                                                                  
                                                                                  
light_source {                                                                    
   <95, 170, -35>                                                                     
   color red 1 green 1 blue 1                                               
   area_light <9, 0, 0>, <0, 0, 9>, areaLightDimension, areaLightDimension        
}                                                                                 
                                                                                  
                                                                                  
// floor                                                                          
//plane { <0, 1, 0>, 0  texture{pigment{checker color Gray40 color Gray80 }         
//        finish {ambient 0.0  diffuse 0.8 } scale 5}}                                      
//plane { <0, 1, 0>, 0  texture{normal{bumps 0.1 } pigment{checker color <0.1,0.3,0.1> color <0.1,0.5,0.1> scale 10 }         
//        finish {ambient 0.0 reflection{0.2}  diffuse 0.8 } scale 5}}                                      
plane { <0, 1, 0>, 0  texture{normal{bumps 0.15 scale 3 } pigment{checker color <0.1,0.3,0.1> color <0.1,0.5,0.1> scale 10 }         
        finish {ambient 0.0 reflection{0.00} specular albedo 0.1 roughness 0.5   diffuse 0.5  } scale 5}}                                      
		
		
		
		
// sky                                                                            
plane { <0, 1, 0>, 1000  pigment{color red 1 green 1 blue 1 }                      
       finish {ambient 0  diffuse 0.9 }}                                          
                                                                                  
// Koordinatensystem                                                              
cylinder{<0,0.1,0> <5,0.1,0> 0.1 pigment{checker color Red color White}}          
cylinder{<0,0.1,0> <0,5.1,0> 0.1 pigment{checker color Green color White}}        
cylinder{<0,0.1,0> <0,0.1,5> 0.1 pigment{checker color Blue color White}}         

                    
#declare MDPyramid = intersection {
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0>}
   plane { <-1, 0,  0>, 0  rotate <  0, 0, -45>  translate < -1, 0,  0>}
   plane { < 0, 0,  1>, 0  rotate <-45, 0,   0> translate <  0, 0,  1>}
   plane { < 0, 0, -1>, 0  rotate < 45, 0,   0> translate <  0, 0,  -1>}
   plane { <0, -1, 0>, 0 }                
   translate <0 ,0, 0>
   
   bounded_by {box {<-1,0,-1>, <1,1,1>}}
}                             

#declare MDPyramid8 = intersection {
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*0}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*45}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*90}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*135}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*180}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*225}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*270}
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0> rotate y*315}

   plane { <0, -1, 0>, 0 }                
   translate <0 ,0, 0>
   rotate y*22.5
   scale sqrt(2)/1.0823922 * x  // so the edges are at 1,1 
   scale sqrt(2)/1.0823922 * z 
   bounded_by {box {<-1.5,0,-1.5>, <1.5,1,1.5>}}
}                             
          
#declare MDPrague = intersection {
   plane { < 1, 0,  0>, 0  rotate <  0, 0,  45> translate <  1, 0,  0>}
   plane { <-1, 0,  0>, 0  rotate <  0, 0, -45>  translate < -1, 0,  0>}
   plane { < 0, 0,  1>, 0  rotate <-(90-degrees(atan2(0.5,1))), 0,   0> translate <  0, 0,  1>}
   plane { < 0, 0, -1>, 0  rotate < (90-degrees(atan2(0.5,1))), 0,   0> translate <  0, 0,  -1>}
   plane { <0, -1, 0>, 0 }                
   translate <0 ,0, 0>
   
   bounded_by {box {<-1,0,-1>, <1,1,1>}}
}                             
                    
#declare MDRoof = prism {linear_sweep linear_spline 0, 1, 4,  <-1,0>, <1,0>, <0,1>, <-1,0> rotate <-90,0,0> translate<0, 0, 1>  }

// z-Achse ist das Zylinderzentrum
#declare MDPortal = union{box{<-1,-1,-1>,<1,0,1>} cylinder{<0,0,-1>,<0,0,1>,1}}

// z-Achse ist das Zylinderzentrum
#declare MDClock = union{cylinder{<0,0,-1>,<0,0,1>,1 pigment{color White}} 
                    sphere{<0,0.7,1>,0.15 pigment{color Black} finish { phong albedo 0.9 phong_size 40 } } 
                    sphere{<0,-0.7,1>,0.15 pigment{color Black} finish { phong albedo 0.9 phong_size 40 } }
                    sphere{<0.7,0,1>,0.15 pigment{color Black} finish { phong albedo 0.9 phong_size 40 } }
                    sphere{<-0.7,0,1>,0.15 pigment{color Black} finish { phong albedo 0.9 phong_size 40 } } }
                    
// ####################################################
// ####################################################
// Ab hier gehts los
// ####################################################
// ####################################################

//object{ MDRoof pigment {Yellow} scale <10, 10 , 10> translate <0, 0, 20> }

