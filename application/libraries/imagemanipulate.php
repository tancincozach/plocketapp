<?php

class imageManipulate{



public $filelocation , $filename ,$newCropInfo;
protected $image, $width, $height ,$folderImage,$folderImageFront;
private   $fontInfo;

	public function getCoordinates($coordinate)
	{
		$this->newCropInfo = $coordinate;
	}

	private function image_get ( $ext, $name )
	{
     switch ( $ext )
     {
		case '.jpg' :
		return ( imagecreatefromjpeg( $name ) );
		break;
        case '.gif' :
		return ( imagecreatefromgif( $name ) );
		break;
        case '.png' :
        return ( imagecreatefrompng ( $name ) );
        break;
     }
   }

	protected function createImage( $imageType)
	{
	 if( $this->filelocation=='' &&  !file_exists($this->filelocation))
	 {
        throw new Exception('Image not found.');
     }
	 switch($imageType)
	 {
        case 'image/png':
            $this->image = $this->image_get('.png',$this->filelocation);break;
        case 'image/gif':
        	$this->image = $this->image_get('.gif',$this->filelocation);break;
        case 'image/jpeg':
        case 'image/pjpeg':
          	$this->image = $this->image_get('.jpg',$this->filelocation);break;
            break;
        default:
           $this->image  = false;
      }
	}	

	private function getImageInfo()
	{
		$imageInfo = array();
		$imgLoc    = $this->filelocation;	
		if( $this->filelocation=='' &&  !file_exists($imgLoc))
		{
			throw new Exception('Image not found.');
		}
			$imgDetail = getimagesize($imgLoc); 			
			$imgType     = $imgDetail['mime'];

			$image_info = pathinfo($imgLoc);			
			$imageInfo['image_array'] = $imgDetail;
			$imageInfo['image_type'] = $imgDetail['mime'];
			$imageInfo['image_extension'] = strtolower($image_info["extension"]); //image extension
			$imageInfo['image_name_only'] = strtolower($image_info["filename"]);//file name only, no extension
		return $imageInfo;
	}


    private function save_image($source, $destination, $image_type, $quality)
	{
        switch(strtolower($image_type))
		{//determine mime type
            case 'image/png': 
                imagepng($source, $destination); return true; //save png file
                break;
            case 'image/gif': 
                imagegif($source, $destination); return true; //save gif file
                break;          
            case 'image/jpeg': case 'image/pjpeg': 
                imagejpeg($source, $destination, $quality); return true; //save jpeg file
                break;
            default: return false;
        }
     }
	 	 
	private function compare_values($value, $min, $max)
	{
		
		if ($value < $min) {
			return $min;
		}
		
		if ($value > $max) {
			return $max;
		}
		
		return $value;
	}

	private function rgbToHsl($r, $g, $b)
	{  
		  $newR = ($r / 255);  
		  $newG = ($g / 255);  
		  $newB = ($b / 255);  
		
		  $rgbMin = min($newR, $newG, $newB);  
		  $rgbMax = max($newR, $newG, $newB);  
		  $chroma = $rgbMax - $rgbMin;  
		  $v = $rgbMax;  
		  if ($chroma == 0)   
		  {  
			$h = 0;  
			$s = 0;  
		  }   
		  else   
		  {  
			$s = $chroma / $rgbMax;  
			$chromaR = ((($rgbMax - $newR)/6) + ($chroma/2))/$chroma;  
			$chromaG = ((($rgbMax - $newG)/6) + ($chroma/2))/$chroma;  
			$chromaB = ((($rgbMax - $newB)/6) + ($chroma/2))/$chroma;  
			if ($newR == $rgbMax) $h = $chromaB - $chromaG;  
			else if ($newG == $rgbMax) $h = ( 1 / 3 ) + $chromaR - $chromaB;  
			else if ($newB == $rgbMax) $h = ( 2 / 3 ) + $chromaG - $chromaR;  
			if ($h < 0) $h++;  
			if ($h > 1) $h--;  
		  }  
		  return array($h, $s, $v);  
	}

	private function hslToRgb($h, $s, $v)
	{  
	  if($s == 0)   
	  {  
		$r = $g = $b = $v * 255;  
	  }   
	  else   
	  {  
		$newH = $h * 6;  
		$i = floor( $newH );  
		$var_1 = $v * ( 1 - $s );  
		$var_2 = $v * ( 1 - $s * ( $newH - $i ) );  
		$var_3 = $v * ( 1 - $s * (1 - ( $newH - $i ) ) );  
		if ($i == 0) { $newR = $v ; $newG = $var_3 ; $newB = $var_1 ; }  
		else if ($i == 1) { $newR = $var_2 ; $newG = $v ; $newB = $var_1 ; }  
		else if ($i == 2) { $newR = $var_1 ; $newG = $v ; $newB = $var_3 ; }  
		else if ($i == 3) { $newR = $var_1 ; $newG = $var_2 ; $newB = $v ; }  
		else if ($i == 4) { $newR = $var_3 ; $newG = $var_1 ; $newB = $v ; }  
		else { $newR = $v ; $newG = $var_1 ; $newB = $var_2 ; }  
		$r = $newR * 255;  
		$g = $newG * 255;  
		$b = $newB * 255;  
	  }   
	  return array($r, $g, $b);  
	} 

	public function intitiateCropping()
	{
	 $thumb_square_size      = 200;
	 $max_image_size         = 500;
	 $thumb_prefix           = "thumb_"; 
	 $destination_folder     = CROPPED_DIR;
	 $jpeg_quality           = 90; 

	 if( $this->filelocation=='' &&  !file_exists($this->filelocation))
	 {
	  throw new Exception('Image not found.');
	 }

	if(count($this->newCropInfo)==0)
	{
	 throw new Exception('Select to crop.');
	}
		$image_name = $this->filelocation;
		$image_detail = getimagesize($this->filelocation);    
		$current_width  = $image_detail[0];
		$current_height = $image_detail[1];
		$type           = $image_detail[2];
		$attribute      = $image_detail[3];
		$image_type     = $image_detail['mime'];
		$this->createImage($image_type);
	 if($this->image)
	 {
	  $image_info = pathinfo($image_name);
	  $image_extension = strtolower($image_info["extension"]); //image extension
	  $image_name_only = strtolower($image_info["filename"]);//file name only, no extension
	  $new_file_name = $image_name_only. '.' . strtolower($image_extension);
	  $image_save_folder  = $destination_folder . $new_file_name;
	  $this->resizeImage($this->image, $image_save_folder, $image_type, $max_image_size, $current_width, $current_height, $jpeg_quality);imagedestroy($this->image);
	 }
	}
	  
private function resizeImage($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality){
   
	$targ_width_img = $targ_height_img = 500;

    if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize


   
    $new_canvas     = imagecreatetruecolor($targ_width_img, $targ_height_img );     

    if(imagecopyresampled($new_canvas,
                            $source,
                            0,
                            0,
                            (int)$this->newCropInfo['x'],
                            (int)$this->newCropInfo['y'], 
                             $targ_width_img,   
                             $targ_height_img,                                                     
                            (int)$this->newCropInfo['w'],
                             (int)$this->newCropInfo['h']
         )){


          $this->save_image($new_canvas, $destination, $image_type, $quality); //save resized image     


    }

    return true;
}




   private function createFolderImage(){
		
			$fileArray  = explode('.',$this->filename);
			
			$this->folderImage 		= FILTERED_DIR.$fileArray[0].'/';
			$this->folderImageFront = CUSTOM_DIR.$fileArray[0].'/';
			if(!is_dir($this->folderImage)){
		
				if(!file_exists($this->folderImageFront)){			
						mkdir($this->folderImage,0755);					
				}else{
							$handle = opendir($this->folderImageFront);
						
						if(!$handle) throw new Exception("Folder is not accessible..");
						
						
							while (false !== ($file = readdir($handle))) {

									if ($file != "." && $file != "..") {
										
										if(file_exists($this->folderImageFront.$file)){
												unlink($this->folderImageFront.$file);
										}
										
															
									}
																	
								}
				
				}
			}
	}
	
	private function selectFilters($filter){
	
		if(!$filter) throw new Exception('No Effects Seletected.');
					
					$filteredImg = "";
					$filteredImages = array();
					$croppedImg = CROP_DIR.$this->filename;
					$imgDetail = getimagesize($croppedImg); 
					$imgType     = $imgDetail['mime'];

					$image_info = pathinfo($croppedImg);
					$image_extension = strtolower($image_info["extension"]); //image extension
					$image_name_only = strtolower($image_info["filename"]);//file name only, no extension
					
					$this->createImage($imgType);		

				if($this->image){
							
							$this->width = imagesx($this->image);  
							$this->height = imagesy($this->image);
							switch( $filter ){
									
										case 'greyscale':	

												$filteredImg = $this->folderImageFront.'greyscale.' . $image_extension ;
												imagefilter($this->image,IMG_FILTER_GRAYSCALE);

										break;
										case 'invert':
												$filteredImg = $this->folderImageFront.'invert.' . $image_extension ;
												imagefilter($this->image,IMG_FILTER_NEGATE);

										break;
										case 'sepia':
													$filteredImg = $this->folderImageFront.'sepia.' . $image_extension ;
												for($_x = 0; $_x < $this->width; $_x++){
													for($_y = 0; $_y < $this->height; $_y++){
														$rgb = imagecolorat($this->image, $_x, $_y);
														$r = ($rgb>>16)&0xFF;
														$g = ($rgb>>8)&0xFF;
														$b = $rgb&0xFF;

														$y = $r*0.299 + $g*0.587 + $b*0.114;
														$i = 0.15*0xFF;
														$q = -0.001*0xFF;

														$r = $y + 0.956*$i + 0.621*$q;
														$g = $y - 0.272*$i - 0.647*$q;
														$b = $y - 1.105*$i + 1.702*$q;

														if($r<0||$r>0xFF){$r=($r<0)?0:0xFF;}
														if($g<0||$g>0xFF){$g=($g<0)?0:0xFF;}
														if($b<0||$b>0xFF){$b=($b<0)?0:0xFF;}

														$color = imagecolorallocate($this->image, $r, $g, $b);
														imagesetpixel($this->image, $_x, $_y, $color);
													}
												}
													
											
										break;
										case 'posterize':													
													$filteredImg = $this->folderImageFront.'posterize.' . $image_extension ;
													imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR,1); 
													imagefilter ($this->image, IMG_FILTER_BRIGHTNESS,-10);
														
													 
													
												
										break;
										case 'saturate':

														$filteredImg = $this->folderImageFront.'saturate.' . $image_extension ;
														
													for($x = 0; $x < $this->width; $x++)   
													   {  
															for($y = 0; $y < $this->height; $y++)   
															{  
																 $rgb = imagecolorat($this->image, $x, $y);  
																 $r = ($rgb >> 16) & 0xFF;  
																 $g = ($rgb >> 8) & 0xFF;  
																 $b = $rgb & 0xFF;  
																 $alpha = ($rgb & 0xFF000000) >> 24;    
																  list($h, $s, $v) = $this->rgbToHsl($r, $g, $b);  
																  $s = $s * 1.5;  
																  if($s > 1) $s=1;  
																  list($r, $g, $b) = $this->hslToRgb($h, $s, $v);   
																  imagesetpixel($this->image, $x, $y, imagecolorallocatealpha($this->image, $r, $g, $b, $alpha));  
															}  
													   }  
												
										break;
										case 'contrast':
												
												$filteredImg = $this->folderImageFront.'contrast.' . $image_extension ;

												imagefilter($this->image, IMG_FILTER_CONTRAST, $this->compare_values(-80, -100, 100));
												
										break;
										case 'opacity':
												$new_image = ImageCreateTruecolor($this->width, $this->height);
													   $bg = ImageColorAllocateAlpha($new_image, 255, 255, 255, 127);
												ImageFill($new_image, 0, 0 , $bg);
												ImageCopyMerge($new_image,$this->image, 0, 0, 0, 0, $this->width, $this->height, 40);																								
												$filteredImg = $this->folderImageFront.'opacity.' . $image_extension ;
												
												
										break;
										case 'polaroidish':												
												$filteredImg = $this->folderImageFront.'polaroid.' . $image_extension ;
												imagefilter ($this->image, IMG_FILTER_BRIGHTNESS, $this->compare_values(-100, -100, 100) / 6);
												
										break;
										case 'bias':
												$filteredImg = $this->folderImageFront.'bias.' . $image_extension ;												
												imagefilter ($this->image, IMG_FILTER_BRIGHTNESS,40);
												imagefilter($this->image, IMG_FILTER_CONTRAST, $this->compare_values(-80, -100, 100));
												
										break;
										default:
										
											$filteredImg = $this->folderImageFront.'normal.' . $image_extension ;		
											
										break;
									}
																		
							$this->save_image($this->image,$filteredImg, $imgType, 90);
								imagedestroy($this->image);	
								
								return $filteredImg;
				}
			
	}
	
	private function normalize_color($color) {
		
		if (is_string($color)) {
			
			$color = trim($color, '#');
			
			if (strlen($color) == 6) {
				list($r, $g, $b) = array(
					$color[0].$color[1],
					$color[2].$color[3],
					$color[4].$color[5]
				);
			} elseif (strlen($color) == 3) {
				list($r, $g, $b) = array(
					$color[0].$color[0],
					$color[1].$color[1],
					$color[2].$color[2]
				);
			} else {
				return false;
			}
			return array(
				'r' => hexdec($r),
				'g' => hexdec($g),
				'b' => hexdec($b),
				'a' => 0
			);
			
		} elseif (is_array($color) && (count($color) == 3 || count($color) == 4)) {
			
			if (isset($color['r'], $color['g'], $color['b'])) {
				return array(
					'r' => $this->compare_values($color['r'], 0, 255),
					'g' => $this->compare_values($color['g'], 0, 255),
					'b' => $this->compare_values($color['b'], 0, 255),
					'a' => $this->compare_values(isset($color['a']) ? $color['a'] : 0, 0, 127)
				);
			} elseif (isset($color[0], $color[1], $color[2])) {
				return array(
					'r' => $this->compare_values($color[0], 0, 255),
					'g' => $this->compare_values($color[1], 0, 255),
					'b' => $this->compare_values($color[2], 0, 255),
					'a' => $this->compare_values(isset($color[3]) ? $color[3] : 0, 0, 127)
				);
			}
			
		}
		return false;
	}	

	public function createCircleImage($image_info = array()){
				

		 $this->image = $image_info['image'];

		 $this->width  = imagesx($this->image);  

 		 $this->height = imagesy($this->image);


		$image = imagecreatetruecolor( $this->width,  $this->height);
		
		$image_background  = imagecolorallocate($image, 255, 0, 255);
		
		imagefill($image, 0, 0, $image_background);


		$transparent = imagecolorallocate($image, 0, 0, 0); 

		$i = ($this->width <=  $this->height ? $this->width :$this->height ); 
		
		imagefilledellipse ($image, ($this->width/2), ( $this->height/2), $i, $i, $transparent); 



		imagecolortransparent($image, $transparent);

		imagecopymerge($this->image, $image, 0, 0, 0, 0, $this->width,$this->height, 100);


		imagecolortransparent($this->image, $image_background);

		if(isset($image_info['new_width']) && isset( $image_info['new_height']))
		{
			if($image_info['new_width']!=$this->width &&  $image_info['new_height']!=$this->height)
			{
					$new_canvas     = imagecreatetruecolor($image_info['new_width'], $image_info['new_height']);  
					imagecopyresampled($new_canvas, $this->image,0,0,0,0, $image_info['new_width'],$image_info['new_height'],$this->width,$this->height);
			}
		}
			
 	
        $this->save_image($this->image, $image_info['dest'], $image_info['image_type'], $image_info['quality']); 			

}
	public function ImageLoad($postedData,$newFileName){

		$imgInfoDetail = $this->getImageInfo();
		$this->createImage($imgInfoDetail['image_type']);	
		$this->width 	= (int)$postedData['imgWidth']; 
		$this->height   = (int)$postedData['imgHeight']; 	

		$origWidth = imagesx($this->image);
		$origHeight = imagesy($this->image);

		if($this->width!=$origWidth &&  $this->height!=$origHeight){
			$small_dimensions     = imagecreatetruecolor($this->width, $this->height);  
			imagecopyresampled($small_dimensions, $this->image,0,0,0,0, $this->width,$this->height,$origWidth,$origHeight);
			$this->save_image($small_dimensions, $newFileName, $imgInfoDetail['image_type'], 90); 				
		}else{
			$normal_dimensions     = imagecreatetruecolor($this->width, $this->height );     
			imagealphablending($normal_dimensions , false);
			imagesavealpha($normal_dimensions, true);
			imagecopyresampled($normal_dimensions, $this->image,0,0,0,0, $this->width,$this->height,$origWidth,$origHeight);
			$this->save_image($normal_dimensions, $newFileName, $imgInfoDetail['image_type'], 90); 
		}
	}
	/* Temporary function for rendering circle image on the add text page */	
	public function TemporaryCircle($postedData)
	{		

		if(isset($postedData['origFileName']))
		{
			$origImgFileExpArray = explode('.',$postedData['origFileName']);
			$origImgFile = $origImgFileExpArray[0];
			$this->renderImageForSocial($postedData['imgSourceText']['newsource'],SOCIAL_DIR.$origImgFile.'.png','assets/img/template_post.jpg');
			$this->createCircleImage(array('image'=>$postedData['image'] ,'dest'=>$postedData['file'],'image_type'=>'image/png','quality'=>90));			
		}		
	}

	public function AddTextImage( $postedData = array() , $fontArray = array()){		

	


		if(count($postedData)==0) 	throw new Exception('Error : Could not process the image');

		
		
		$imgInfoDetail = $this->getImageInfo();
		if(count($fontArray)==0)
		{
			$this->save_image($this->image,$this->filelocation, $imgInfoDetail['image_type'], 90);  return true;  
		}

  		

		$this->createImage($imgInfoDetail['image_type']);	
		$this->width 	= imagesx($this->image);  
		$this->height   = imagesy($this->image);		
		$rgba    	    = $this->normalize_color($fontArray['color']);

		$pixel 			= (int) isset($fontArray['size']) ? $fontArray['size']:12;

		$point          =  $pixel * .75;



		$this->fontInfo =	array(
								'font_file' =>$fontArray['font'],
								'size'      =>$point,
								'style'     =>isset($fontArray['style']) ? $fontArray['style']:'normal',
								'color'     =>$rgba,
								'x'			=>$postedData['x'],
								'y'			=>$postedData['y'],
								'text'		=>$postedData['text'],
								'angle'		=> (int)isset($fontArray['angle']) ? $fontArray['angle']:0
							);
		

	
				$color = imagecolorallocatealpha($this->image, $this->fontInfo['color']['r'], $this->fontInfo['color']['g'], $this->fontInfo['color']['b'], $this->fontInfo['color']['a']);


				$box = @ImageTTFBBox($this->fontInfo['size'],0,$this->fontInfo['font_file'],$this->fontInfo['text']) ;



				if (!$box) {
					throw new Exception('Unable to load font: '.$this->fontInfo['font_file']);
				}
				
				
				$box_width = abs($box[4] - $box[6]);
				$box_height = abs($box[5] - $box[1]);

				imagettftext($this->image,
								   $this->fontInfo['size'] ,
								   $this->fontInfo['angle'] ,
								   $this->fontInfo['x'] ,
								   $this->fontInfo['y'] + $box_height,
								   $color, 
								   $this->fontInfo['font_file'],
					  			   $this->fontInfo['text']
				  			   );
			$postedData['image']=$this->image;
			$postedData['file'] =$this->filelocation;

			$this->save_image($this->image,$this->filelocation, $imgInfoDetail['image_type'], 90); 
			//$this->TemporaryCircle($postedData);
	}
				
	 public function renderFilter(){
			 if($this->filename){
				
							$this->createFolderImage();
							
							$filteredEffects = array('normal','posterize','saturate','contrast' ,'sepia','polaroidish','invert','greyscale','bias','opacity') ;
						
							$filteredImages = array();
								foreach($filteredEffects as $filteredValue){											
										$filteredImages[$filteredValue] = $this->selectFilters($filteredValue);
								}
								

							return $filteredImages;								
					
				}		
	 }

	private function get_rgb ( $image )
		{
		    $x = 0;
		    $colors = array ();

		    for ( $color = 10; $color <= 250; $color++ )
		    {
		        if ( imagecolorexact ( $img, $color, $color, $color ) == -1 )
		        {
		            $colors[] = array ( 'red' => $color, 'green' => $color, 'blue' => $color );

		            if ( $x == 1 )
		            {
		                imagedestroy ( $img );
		                return ( $colors );
		            }

		            $x++;
		        }
		    }

		    return ( $colors );
		}

	 public function  renderImageForSocial( $source,$output,$template){


				$background_img = $template;
				$bk_img 		= $this->image_get ( substr ( $background_img, strrpos ( $background_img, '.' ) ), $background_img );
				$bgX 			= imagesx($bk_img);
				$bgY 			= imagesy($bk_img);
				$bgFrame 		= imagecreatetruecolor($bgX,$bgY);
				$output_image   = $output;

				imagecopyresampled($bgFrame, $bk_img, 0, 0, 0, 0, $bgX, $bgY, $bgX, $bgY);
				
				
				
				
				
				$original_image = $source;

				$ext = substr ( $original_image, strrpos ( $original_image, '.' ) );			

				

				$new_500x500 = $this->image_get ($ext, $original_image );


				
				$circleX =160;
				$circleY =160; 
				$new = imagecreatetruecolor($circleX, $circleY);


				imagecopyresampled($new, $new_500x500, 0, 0, 0, 0, $circleX, $circleY, imagesx($new_500x500), imagesy($new_500x500));
				
		
				
				
			/* ********************* */
			
			/*this creates the cutout layer (2 colors, both will become transparent)*/
			$width  = imagesx($new);
			$height = imagesy($new);
			
			
			$old = imagecreate ( $width, $height );
			imageantialias( $old, true );
			imagecolorallocate ( $old, 255,0, 255 );
			$bg = imagecolorallocate ( $old, 0, 0, 0 );
			imagefilledellipse ( $old, floor ( $width / 2 ), floor ( $height / 2 ), $width, $height, $bg );
			imagecolortransparent ( $old, $bg );
			imagecopy ( $new, $old, 0, 0, 0, 0, $width, $height );
			$this->save_image($new, $output_image, 'image/png',90);

			imagedestroy ( $old );
			imagedestroy ( $new );
			
			
			

			/* this layers both images together, making a nice ellipse/round transparent image cutout*/

			$old = imagecreatetruecolor ( $width, $height );
			$new = $this->image_get ( '.png', $output_image );
			$tbg = imagecolorallocate ( $old,255,0,255 );
			imagecopy ( $old, $new, 0, 0, 0, 0, $width, $height );
			imagecolortransparent ( $old, $tbg );
			imagetruecolortopalette($old, true, 255);
			$this->save_image($new, $output_image, 'image/png',90);
			imagecopy ( $new, $old, 0, 0, 0, 0, $width, $height );
			imagecopyresampled($bgFrame, $old , 137, 310, 0, 0, 160, 160, 160, 160);				
			$this->save_image($bgFrame, $output_image, 'image/png',90);

	 }
	 
	public function addStickers($postedData)
	{
	 if(empty($postedData) && $postedData['total_image']==0)
	 {
		throw new Exception('Empty.');
	 }
	 if($postedData['background']=='')
	 {
	 	throw new Exception('Cannot process stickers without background image.');
	 }
	
	 $background    = $this->image_get (substr ( $postedData['background'], strrpos ( $postedData['background'], '.' ) ), $postedData['background']);
	 $bgwidth       = imagesx($background);
	 $bgheight      = imagesy($background);
	 $Output 	= imagecreatetruecolor($bgwidth,$bgheight);
	 imagecopyresampled($Output, $background, 0, 0, 0, 0, $bgwidth, $bgheight, $bgwidth, $bgheight);
	
	 if((int)$postedData['total_images']==0)
	 {
	   header('Content-type: image/png');				
		$targetfile = $postedData['dir'].$postedData['filename'].'.png';
		$nonCircleImage =  $postedData['dir'].'/'.$postedData['filename'].'-normal.png';
		$this->save_image($Output, $nonCircleImage, 'image/png',90);
		$this->createCircleImage(array('image'=>$Output ,'dest'=>$targetfile,'image_type'=>'image/png','quality'=>90));		
		$this->renderImageForSocial($targetfile,'assets/uploads/social-output/'. $postedData['filename'].'.png','assets/img/template_post.png');
      return true;		
	 }
	
	 for($i=0;$i < $postedData['total_images'];$i++)
	 {				
		$img = $this->image_get (substr ( $postedData['src'][$i], strrpos ($postedData['src'][$i], '.' ) ),$postedData['src'][$i]);					
		$orig_width   = imagesx($img);
		$orig_height = imagesy($img);
		$thumb   = imagecreatetruecolor($postedData['width'][$i],$postedData['height'][$i]);
		$trans_thumb  = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
		imagefill($thumb, 0, 0, $trans_thumb);				
		imagecopyresampled($thumb, $img,0,0,0,0, $postedData['width'][$i],$postedData['height'][$i],$orig_width,$orig_height);
		imagecopy($Output, $thumb,$postedData['x'][$i],$postedData['y'][$i], 0, 0,$postedData['width'][$i],$postedData['height'][$i]);	
	 }	
		header('Content-type: image/png');				
		$targetfile = $postedData['dir'].$postedData['filename'].'.png';
		$nonCircleImage =  $postedData['dir'].'/'.$postedData['filename'].'-normal.png';
		$this->save_image($Output, $nonCircleImage, 'image/png',90);
		$this->createCircleImage(array('image'=>$Output ,'dest'=>$targetfile,'image_type'=>'image/png','quality'=>90));		
		$this->renderImageForSocial($targetfile,'assets/uploads/social-output/'. $postedData['filename'].'.png','assets/img/template_post.png');	
   }
}
