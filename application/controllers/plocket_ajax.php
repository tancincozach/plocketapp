<?php
ini_set('memory_limit', "256M");
session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Plocket_Ajax extends CI_Controller {

public  $parameters ,$filtered_dir_loc =array(),$session_img,$session_id;
private $filteredImg = array();

	public function __construct()
	{
	  parent::__construct(); 
	  if (!$this->input->is_ajax_request())
	  {		 		
	   header('HTTP/1.1 403 Forbidden');
	   echo "FORBIDDEN ACCESS";
	   exit();
	  }	 
	}

   public function findFile($directory,$extensions = array())
   {
   	$files =array();
	function glob_recursive($directory, &$directories = array())
		{
		 foreach(glob($directory, GLOB_ONLYDIR | GLOB_NOSORT) as $folder)
		 {
		   $directories[] = $folder;
		   glob_recursive("{$folder}/*", $directories);
		 }	        
	    }
		   glob_recursive($directory, $directories);
		   $files = array ();
		foreach($directories as $directory) 
		{
		  foreach($extensions as $ext)
		  {
		    foreach(glob("{$directory}/*.{$ext}") as $file)
			{
			  $files[] = $file;
			}
	      }
	    }  
	    return array('directories'=>$directories,'files'=>$files);
   }

   public function findImageFileAndDelete($directory,$keyword,$extensions = array())
   {
	function glob_recursive($directory, &$directories = array())
	{
	 foreach(glob($directory, GLOB_ONLYDIR | GLOB_NOSORT) as $folder)
	 {
	   $directories[] = $folder;
	   glob_recursive("{$folder}/*", $directories);
	 }	        
    }
	   glob_recursive($directory, $directories);
	   $files = array ();
	foreach($directories as $directory) 
	{
	  foreach($extensions as $ext)
	  {
	    foreach(glob("{$directory}/*.{$ext}") as $file)
		{
	     if(strpos($file,$keyword)!== false)
		 {
		  unlink($file);
		 }
		}
      }
	  if(strpos($directory,$keyword)!== false)
	  {
	    rmdir($directory);
	  }
    }
  }   
  
  public function startOver()
  {
   try
   {
     $this->findImageFileAndDelete(substr(UPLOAD, 0, -1), $this->session->userdata('hashkey'),array('jpg','png','gif','pdf'));
	 $this->session->sess_destroy();
	 echo json_encode(array('success'=>1));
	 
   }
   catch(Exception $e)
   {
   $this->session->set_userdata(array('global_error'=>$e->getMessage()));	
   	echo json_encode(array('error'=>$e->getMessage()));
   }
  
  }
  
  public function upload()
  {	
	 try
	 {
	  $ext = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);	  
	  $allowed = array('png', 'jpg', 'gif');
	  $hash =  session_id();
	  $filename = $hash.'.'.strtolower($ext);
	  
	  //echo $filename;exit();
	  if(!in_array(strtolower($ext), $allowed))
	  {
	   throw new Exception('Invalid File');
	  }
	  
	  if(!move_uploaded_file($_FILES['upl']['tmp_name'], UPLOAD.$filename)){
	   throw new Exception('File cannot Be upload');exit;		
	  }	
	  
	  $this->session->set_userdata(array('hashkey'=>$hash,'img'=>$filename));
	  $result = array('status'=>'success');	  
	 }
	 catch(Exception $e)
	 {
	 $this->session->set_userdata(array('global_error'=>$e->getMessage()));	
	   $result = array('status'=>'error','message'=>$e->getMessage());
	 }
	 echo json_encode($result);
   }

   public function cropImage()
	{
	
	 $message = array();
	 try
	 {
	  $this->imagemanipulate->filelocation  = UPLOAD.$this->session->userdata('img');
	  $this->imagemanipulate->filename     = $this->session->userdata('img');						
	  $this->imagemanipulate->getCoordinates($this->input->post('coordinates'));
	  $this->imagemanipulate->intitiateCropping();
	  $this->session->set_userdata(array('crop-img'=>$this->session->userdata('img')));
	  $message['success'] = 'You have successfully Cropped : '.$this->session->userdata('img');
	 }
	 catch(Exception $e)
	 {
	  $this->session->set_userdata(array('global_error'=>$e->getMessage()));	
	  $message['error'] = $e->getMessage();
	 }
	  echo json_encode($message);	
  }
	
  public function applyFilter()
  {	
	 try
	 {
	  $img = $this->input->post('selected_image_filter');
	  
	   
	  if(!isset($img))
	  {
	   throw new Exception('There are no images to be filtered');
	  }
		
	  if(strpos($img,'/')!==false)
	  {
	    $loc = explode("/",$img);
		if(count($loc) > 0)
		{
		  $imageName = $loc[count($loc)-1];
		  $directory = FILTERED_DIR.$loc[3].'/';	
		  $this->session->set_userdata(array("filtered_dir"=>$directory)); 		  
		  if(!$handle = opendir($directory))
		  {
		   throw new Exception('cannot open directory.');
		  }
			
		  while(false !== ($file = readdir($handle))) 
		  {
		   if($file != "." && $file != "..") 
		   {
			if(trim($file)!=trim($imageName))
			{
			  unlink($directory.$file);					
			}
			else
			{																
			  $this->session->set_userdata(array('filtered_image'=>$file));		  
		    }
		   }
		  }			 
		  closedir($handle);$message['success'] = 1;
		}
	  }		
    }
	catch(Exception $e)
	{	  
	 $message['error'] = 0;
	}
	 echo json_encode($message);
 }
 
 public function loadFonts()
 {
  try
  {
    $fonts = $this->findFile('assets/css/font',array('json'));


    $newfonts = array();    
    if(isset($fonts['files']) && count($fonts['files'])==0)
    {
      throw new Exception('cannot load fonts because of empty result.');
    }
    foreach($fonts['files'] as $fontpath)
    {

     $font  = $fontpath;

     $fontArray = (strpos($font,'/' )!== false ? explode('/',$font):'');

   	 array_pop($fontArray);     

     $fontDir = implode('/',$fontArray);

     if(file_exists($fontpath))
     {
		$data = file_get_contents($fontpath);
		$fontDetail = json_decode($data,true);

 		$newfonts[] =  array('fontname'=> $fontDetail['name']);  

     }       
    }  
  	 echo json_encode(array('fonts'=>$newfonts));
  }
  catch(Exception $e)
  {
    echo json_encode(array('error'=>$e->getMessage()));
  }   
 }

 public function addtext()
 {
	$this->load->library('font');
  	try{
		$height = $this->input->post('imgheight');
		$width = $this->input->post('imgwidth');		
		$font_value  = $this->input->post('font');	
		$session_id   = $this->session->userdata('hashkey');	
		
		if($height==0 && $width==0 )
		{
		 throw new Exception('Image Cannot Be Processed...');
		}
		if($font_value!='')
		{
		  $font = (strpos($font_value,' ' )!== false ? str_replace(' ', '', $font_value):$font_value);
		  
	      $this->font->loadFonts(array('font_dir'=>strtolower($font),'font_file'=> ucwords($font),'style'=>$this->input->post('style') ? $this->input->post('style'):'regular'));
		
		  $this->parameters = array( 'x'=>$this->input->post('x_post'),'size'=>(int)$this->input->post('size'), 'font'=>$this->font->fontLoc,'y'=>$this->input->post('y_post'),'color'=>$this->input->post('color'),'text'=>$this->input->post('text'),'imgHeight'=>$height,'imgWidth'=>$width);
		}
		else
		{
		 $this->parameters = array('imgHeight'=>$height,'imgWidth'=>$width);
		}

		
		$this->session->set_userdata('filtered_image_with_text',$this->session->userdata('filtered_dir').$session_id.'-text.jpg');
		$this->imagemanipulate->filelocation  = FILTERED_DIR.$session_id.'/'.$this->session->userdata('filtered_image');					
		$this->imagemanipulate->filename      = $this->session->userdata('filtered_image');						
		$this->imagemanipulate->ImageLoad( $this->parameters , $this->session->userdata('filtered_image_with_text')); 
		$this->filtered_dir_loc =  array('source'=>$this->imagemanipulate->filelocation,'newsource'=> $this->session->userdata('filtered_image_with_text'));
				
		$this->imagemanipulate->filelocation  = $this->filtered_dir_loc['newsource'];						
		$this->imagemanipulate->filename      = $this->session->userdata('filtered_image');
		$this->parameters['imgSourceText'] =  $this->filtered_dir_loc;
		$this->parameters['origFileName'] = $this->session->userdata('img');
		
		if(!isset($this->parameters['font']))
		{
		 $this->imagemanipulate->AddTextImage($this->parameters,array());
		}
		else
		{
		 $this->imagemanipulate->AddTextImage($this->parameters,array('font'=>$this->parameters['font'],'size'=>$this->parameters['size'],'text'=>$this->parameters['text'],'color'=>$this->parameters['color'])); 	
		}
		$this->session->set_userdata('image_with_text',str_replace('../','',$this->imagemanipulate->filelocation));		
		echo json_encode(array('msg'=>'Adding of text is successful'));
			
	}
	catch(Exception $e)
	{
	 echo json_encode(array('error'=>$e->getMessage()));
	}
 }

 public function sticker()
 {
	$postValues = $this->input->post();

	try
	{
	  if(empty($postValues))
	  {
 	   throw new Exception('Cannot process this request because of empty value');
	  }
	   $postValues['background']    = $this->session->userdata('filtered_image_with_text');
	   $postValues['filename']      = $this->session->userdata('hashkey');
	   $postValues['dir']           = STICKER_DIR;
	   $this->imagemanipulate->addStickers($postValues);
	   $this->session->set_userdata('image_with_sticker',$postValues['dir'].$this->session->userdata('hashkey').'.png');
	   return true;
	}
	catch(Exception $e)
	{
	 echo json_encode(array('error'=>$e->getMessage()));
	}	
 }

 public function attachpdf()
 {

 	try
 	{
 	   $img_with_text = $this->session->userdata('image_with_sticker');
 	   $this->parameters['name'] = $this->input->post('name');
 	   $this->parameters['comment'] = $this->input->post('comment');
	   $this->parameters['email']  =  $this->input->post('email');
   	   $this->parameters['srcpdf']	= "assets/uploads/image-pdf/".$this->session->userdata('hashkey').".pdf";	

 	   if(!file_exists($img_with_text))
	   {
        throw new Exception("Unable to convert image to pdf", 1);  
	   }

		$this->load->library(array('pdf_html','email'));

		$this->pdf_html->AliasNbPages();
		$this->pdf_html->SetAutoPageBreak(true, 15);
		$this->pdf_html->AddPage();
		$this->pdf_html->Image('assets/img/PLOCKET_PRINT.png',50,10,-300);
		$this->pdf_html->Image($img_with_text,59,60,8,8,'PNG');
		$this->pdf_html->Image($img_with_text,137,60,8,8);
		$this->pdf_html->Image($img_with_text,58,75.5,10,10);
		$this->pdf_html->Image($img_with_text,136,75.5,10,10);
		$this->pdf_html->Image($img_with_text,57,92,12,12);
		$this->pdf_html->Image($img_with_text,135,92,12,12);
		$this->pdf_html->Image($img_with_text,56.5,111,13,13);
		$this->pdf_html->Image($img_with_text,134.5,111,13,13);
		$this->pdf_html->Image($img_with_text,56,131,15,15);
		$this->pdf_html->Image($img_with_text,134,131,15,15);
		$this->pdf_html->Image($img_with_text,55,155,16,16);
		$this->pdf_html->Image($img_with_text,133,155,16,16);
		$this->pdf_html->Image($img_with_text,53.5,181,19,19);
		$this->pdf_html->Image($img_with_text,131.5,181,19,19);
		$this->pdf_html->Image($img_with_text,52,206.5,22,22);
		$this->pdf_html->Image($img_with_text,130,206.5,22,22);
		$this->pdf_html->Image($img_with_text,50.5,235,25,25);
		$this->pdf_html->Image($img_with_text,128.5,235,25,25);
		$this->pdf_html->Output($this->parameters['srcpdf'],'F');

		$this->sendmail();
		$this->load->database();
	    $this->load->model('plocket_ajax_model','plocket');
 	    $this->plocket->storeuser($this->parameters);	
	  
 	}
 	catch(Exception $e)
 	{
     echo json_encode(array('error'=>$e->getMessage()));
 	}
  }
  public function sendmail(){
  	try{
  			

			if($this->parameters['name'] =='' && $this->parameters['email']=='')
			{
			   throw new Exception("Cannot send mail without necessary informations.");			
			}
			if(!$this->email
			   ->initialize(array('mailtype'=>'html','crlf' => "\r\n",'newline' => "\r\n"))
			   ->subject("Plocketplug PDF for printing")
			   ->from("plocketplug.com <sales@salesone.org>")
			   ->to($this->parameters['email'])
			   ->attach($this->parameters['srcpdf'])
			   ->message("<b>Hi {$this->parameters['name']},</b><br/><br/>
						<b> Here is your PDF for printing. Please find under the attached file.</b><br/>


						<p>&nbsp;</p>
						<i>More Power</i>,<br/>
						<b span style='color:#333'>Salesone International LLC | www.plocketplug.com </b><br/><br/>
						<span style='color:#aaa'>
						16 Fitch
						Norwalk, CT 06855
						<br/>
						Toll Free: (866)-507-2537
						<br/>
						Phone (For International Customers): 1-203-356-9077
						<br/>
						Fax: (203)-356-9249
						<br/>
						Hours: Monday-Friday from 9am-6pm (EST)
						</span>")
			    ->send())
			{
			  throw new Exception("Cannot send email");		
			}
			return true;
  	}
  	catch(Exception $e)
  	{
  		echo json_encode(array('error'=>$e->getMessage()));
  	}
  }
  public function postFacebook()
  {
  	$this->load->library('facebook');
  	if($this->facebook->authenticateFB())
  	 {
 		
              $result = $this->facebook->postImage(SOCIAL_DIR,$this->session->userdata('hashkey').'.png',array('msg'=>$this->input->post('message'),'album'=>$this->input->post('album')));

              	echo json_encode($result);
     }
  }
}
