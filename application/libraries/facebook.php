<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Autoload the required files
require_once( APPPATH . 'libraries/facebook-php-sdk-v4/autoload.php' );
// include required files form Facebook SDK

// added in v4.0.5

require_once( 'facebook-php-sdk-v4/src/Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/HttpClients/FacebookCurl.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/HttpClients/FacebookCurlHttpClient.php' );

// added in v4.0.0
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookSession.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookRequest.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookResponse.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookSDKException.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookRequestException.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookOtherException.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookAuthorizationException.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/GraphObject.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/GraphSessionInfo.php' );
require_once( 'facebook-php-sdk-v4/src/Facebook/FacebookJavaScriptLoginHelper.php' );






// added in v4.0.5
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;

// added in v4.0.0
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
use Facebook\FacebookJavaScriptLoginHelper;



class Facebook{

  public $sessionfb ,$img;
  protected $ci ,$helper,$permission;
  public function __construct()
  {
		$this->ci =& get_instance();
		$this->permissions = $this->ci->config->item('permissions', 'facebook');



		/* Create the login helper and replace REDIRECT_URI with your URL
		 Use the same domain you set for the apps 'App Domains' */		 

		FacebookSession::setDefaultApplication( $this->ci->config->item('api_id', 'facebook'), $this->ci->config->item('app_secret', 'facebook') );

		 $this->helper = new FacebookRedirectLoginHelper( $this->ci->config->item('redirect_url', 'facebook') );


		if ( $this->ci->session->userdata('fb_token') ) {
			$this->sessionfb = new FacebookSession( $this->ci->session->userdata('fb_token') );

			//Validate the access_token to make sure it's still valid

			try 
			{
			  if ( ! $this->sessionfb->validate() ) {
			   $this->sessionfb = null;
			   }
			}
			catch ( Exception $e ) 
			{
				// Catch any exceptions
				$this->sessionfb = null;
			}
		}
		else
	    {
				// No session exists
				try {
				   $this->sessionfb = $this->helper->getSessionFromRedirect();


				} 
				catch( FacebookRequestException $ex ) 
				{
				// When Facebook returns an error
				} 
				catch( Exception $ex ) 
				{
				// When validation fails or other local issues
		        }
		}		

		if ( $this->sessionfb )
		{
		  $this->ci->session->set_userdata( 'fb_token', $this->sessionfb->getToken() );
		  $this->sessionfb = new FacebookSession( $this->sessionfb->getToken() );
		}

		$this->helper->getLoginUrl( $this->permissions );

}
 	

	public function authenticateFB(){

	  
	      $this->helper = new FacebookJavaScriptLoginHelper();
	   	
			try{
					$this->sessionfb = $this->helper->getSession();
               	$permission = (new FacebookRequest($this->sessionfb, 'GET', '/me/permissions'))->execute()->getGraphObject(GraphUser::className());

	      } catch(FacebookRequestException $ex){
	          // When Facebook returns an error
	      } catch(\Exception $ex) {
	          // When validation fails or other local issues
	      }
	 
	      if ($this->sessionfb) {
			// Logged in.
	        return true;
	      }else{
	       return false;
	      }

	}

	public function postImage($imgDir,$img,$postedData= array()){


	  if($this->sessionfb)
	  {


		 $this->helper = new FacebookRedirectLoginHelper( $this->ci->config->item('redirect_url', 'facebook') );
		 
		$this->helper->getLoginUrl( $this->permissions );

	  	$message =array();

		 try {

		 	$this->ci->load->helper('path');


			$this->img  =   class_exists('CurlFile', false) ? new CURLFile($img, 'image/png') : '@'.set_realpath($imgDir, TRUE).$img;
	        

	
	          $response = (new FacebookRequest(
	            $this->sessionfb, 'POST', '/me/photos', array(
	         	 'source' =>$this->img ,
	         	 'album'=>isset($postedData['album']) ? $postedData['album']:"",
	              'message' => isset($postedData['msg']) ? $postedData['msg']:""
	            )
	          ))->execute()->getGraphObject();

	          		$message['message'] ="Facebook Post Successful!.";
	        } catch(FacebookRequestException $e) {
	          
					$message['error'] = $e->getMessage();


	        } 
	         catch (FacebookSDKException $e ) {
	                  
	                  $message['error'] = $e->getMessage();
	    
	          }
			catch(\Exception $ex) {
	                $message['error'] = $e->getMessage();
	          }	

	          return $message;
			}
	}

}


?>