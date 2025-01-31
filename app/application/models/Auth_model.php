<?php

use Jumbojett\OpenIDConnectClient;

class Auth_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		//load config model
		$this->load->model('Config_model', 'c');
	}


}
