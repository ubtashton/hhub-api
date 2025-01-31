<?php


class Status extends Api_Controller {

	function __construct()
	{
		parent::__construct();
	}

	//in this controller there is likely only one method, index_get

	public function index_get()
	{
		global $apiData;
		$this->response([
			'status' => TRUE,
			'api_version' => API_VERSION,
			'message' => 'API is running',
			'apiData' => $apiData,
		], 200);
	}


	public function notfound()
	{
		$this->response([
			'status' => FALSE,
			'api_version' => API_VERSION,
			'message' => 'API endpoint not found',
		], 404);
	}


	
}
