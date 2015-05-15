<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plocket_Ajax_Model extends CI_Model {

  public function storeuser($data)
  {
  	if($data)
  	{
  	  $this->db->insert('logs', $data);
  	}
  }
}