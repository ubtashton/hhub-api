<?php

use chriskacerguis\RestServer\RestController;

class Api_controller extends RestController {

	//constructor
	function __construct()
	{
		parent::__construct();
		//get the passed apikey if it exists
		if($this->input->get_request_header("X-API-KEY")){
			//create a global for apiData
			global $apiData;
			$apiData=new stdClass;

			$this->db->select('external_id, owner, expires');
			$this->db->where('apikey', $this->input->get_request_header("X-API-KEY"));
			$apikey=$this->db->get('gc_apikeys')->result()[0];
			$this->db->select('id, userid, uid, first_name, last_name');
			$this->db->where('id', $apikey->owner);
			$user=$this->db->get('users')->result()[0];
			$apiData->apikey=$apikey;
			$apiData->user=$user;
		}


	}
	


}
