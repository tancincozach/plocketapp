<?php
class Font{
	
	private $fontDetails    = array();
	private $fontDir  =  'assets/css/font/';
	private $fonts  = array();
	public  $fontLoc;


	public function loadFonts($fontInfo = array()){
		if(count($fontInfo)==0){

			throw  new Exception('Cannot Locate Font');

		}

		$this->fontDetails = $fontInfo;

		$this->getFontFace();
	}

	protected function getFontByFontStyle( ){

	if(!$this->fontDetails['style']) throw new Exception("Error : missing font style.");

		$directory  = $this->fontDir.$this->fontDetails['font_dir'].'/';
		

		switch($this->fontDetails['style']) {

			case "bold":

							if(in_array($this->fontDetails['font_file'].'-'.'Bold.ttf',$this->fonts)){

								$this->fontLoc = $directory.$this->fontDetails['font_file'].'-'.'Bold.ttf';

							}

			break;
			case "italic":

							if(in_array($this->fontDetails['font_file'].'-'.'Italic.ttf',$this->fonts)){

								$this->fontLoc = $directory.$this->fontDetails['font_file'].'-'.'Italic.ttf';

							}
			break;
			default:


							if(in_array($this->fontDetails['font_file'].'-'.'Regular.ttf',$this->fonts)){

								$this->fontLoc = $directory.$this->fontDetails['font_file'].'-'.'Regular.ttf';

							}


							if(in_array($this->fontDetails['font_file'].'.ttf',$this->fonts)){

								$this->fontLoc = $directory.$this->fontDetails['font_file'].'.ttf';

							}									
						

			break;
		}

	}

	protected function getFontFace(){

		$fontList = array();
	

		if(!isset($this->fontDetails['font_dir'])){

			throw  new Exception("Error :  Font is Empty");

		}
		

		if(!file_exists($this->fontDir.$this->fontDetails['font_dir'])){

			throw  new Exception("Error : cannot find font ".$this->fontDetails['font_dir']);


		}	

		if(!$handle = opendir($this->fontDir.$this->fontDetails['font_dir'])){

			 throw new Exception("Error : Font File Directory is not accessible..");

		}
			

		while (false !== ($file = readdir($handle))) {

			if ($file != "." && $file != "..") {
				
					$this->fonts[] = trim($file);
									
			}
											
		}

		$this->getFontByFontStyle();

	}	

}
?>