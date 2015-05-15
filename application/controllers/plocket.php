<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plocket extends CI_Controller {
	
		protected $data = array();
		
	public function __construct()
	{
	 parent::__construct(); 
	}

	public function index()
	{
		$this->data['title'] = '- Upload Image';
		$this->data['content'] = 'pages/upload';
		$this->load->view('index',$this->data);
	}
		
	public function crop()
	{		
	 try{
		if($this->session->userdata('img')=='')
		{
		 throw new Exception('Upload an image First...');									
		} 				
		$this->data['title']   = '- Crop';	
		$this->data['img']     = $this->session->userdata('img');
		$this->data['content'] = 'pages/crop';		
		$this->load->view('index',$this->data);					
	 }
	 catch(Exception $e)
	 {
		$this->session->set_userdata(array('global_error'=>$e->getMessage()));				
		redirect(base_url());		
	 }						
			
	}
				
	public function customize()
	{

	  try{
		if($this->session->userdata('crop-img')=='')
		{
		 throw new Exception('Crop an image First...');									
		}
		
		$this->data['title']   = '- Customize';			
		$this->data['crop-img']     = $this->session->userdata('crop-img');
		$this->data['content'] = 'pages/customize';			
		
		/*Inititating File for rendering filered images */
		$this->imagemanipulate->filelocation = CROP_DIR.$this->session->userdata('img');
		$this->imagemanipulate->filename     = $this->session->userdata('img');		
		
		/*Passing rendered images to an array  */			
		$this->data['filter'] = $this->imagemanipulate->renderFilter();
		
		$this->load->view('index',$this->data);	
	  }
	  catch(Exception $e)
	  {
	   $e->getMessage();
	   $this->session->set_userdata(array('global_error'=>$e->getMessage()));				
	   redirect(base_url());		
	   
	  }				 
	}

	public function addtext()
	{
	  try{
		if($this->session->userdata('filtered_image')=='')
		{
		 throw new Exception('Edit an image First...');									
		} 
		 $this->data['title']   = '- Add Text';		
		 $this->data['filtered_dir']     = $this->session->userdata('filtered_dir');
		 $this->data['filtered_img']     = $this->session->userdata('filtered_image');
		 $this->data['content'] = 'pages/addtext';			
		 $this->load->view('index',$this->data);	
	  }
	  catch(Exception $e)
	  { 
		 $this->session->set_userdata(array('global_error'=>$e->getMessage()));				
		 redirect(base_url());		
	  }
	}
	
	public function getAllStickers()
   {
	$extensions = array('jpg','png','gif');
    $stickers = array();
	$directory = STICKERS_DIR;
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
	if(count($directories) > 0)
	{
	foreach($directories as $directory) 
		{
		  foreach($extensions as $ext)
		  {
		    foreach(glob("{$directory}/*.{$ext}") as $file)
			{
				$stickers[] = $file;
			}
	      }	  
	    }	
	}
	
	return $stickers;
  }
	public function addsticker()
	{	
		
				
	  try
	   {
	   	   if($this->session->userdata('filtered_image_with_text')=='')
	   	   {
	   	   		 throw new Exception('Edit an image First...');	
	   	   }
		   
	   	     $this->data['title']   = '- Add Sticker';		
		     $this->data['filtered_image_with_text']     = $this->session->userdata('filtered_image_with_text');
			 $this->data['stickers'] = $this->getAllStickers();			 
		     $this->data['content'] = 'pages/addsticker';	
		     $this->load->view('index',$this->data);		
		     
	   }
	    catch(Exception $e)
	   {

		 $this->session->set_userdata(array('global_error'=>$e->getMessage()));				
		 redirect(base_url());	
	   }
	}
	
   public function printplocket()
   {
     try
	 {
	   if($this->session->userdata('image_with_sticker')=='')
	   {
		 throw new Exception('Unable to Print..');
	   }
	     $this->data['title']   = '- Print Plocket';	
	     $this->data['img']     = $this->session->userdata('hashkey');
		 $this->data['content'] = 'pages/print';			
		 $this->load->view('index',$this->data);	
	 }
	 catch(Exception $e)
	 {
	     $this->session->set_userdata(array('global_error'=>$e->getMessage()));				
		 redirect(base_url());		
	 }
   }  
	public function finalstep()
	{
	  try{
		if($this->session->userdata('image_with_sticker')=='')
		{
		 throw new Exception('Edit an image First...');									
		} 		 
		 $this->data['title']   = '- Save Image';		
		 $this->data['filtered_dir']     = STICKER_DIR;
		 $this->data['image_with_sticker']  = $this->session->userdata('image_with_sticker');
		 $this->data['hashid']  =$this->session->userdata('hashkey');
		 $this->data['content'] = 'pages/final';			
		 $this->load->view('index',$this->data);	
	  }
	  catch(Exception $e)
	  {
		 $this->session->set_userdata(array('global_error'=>$e->getMessage()));				
		 redirect(base_url());		
	  }
   }

   
}
