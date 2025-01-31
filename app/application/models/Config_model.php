<?php

class Config_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}


	function item($key){
		$this->db->where('config_item', $key);
        $cq = $this->db->get('config');
        if ($cq->num_rows() == 0) {
            //no item found.
            return null;
        } else {
            return $cq->result()[0]->config_value;
        }
	}

	function generateRandomString($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateUniqueRandomString($length, $table, $column)
    {
        //get an initial random string.
        $string = $this->generateRandomString($length);
        $this->db->where($column, $string);
        $q = $this->db->get($table);
        if ($q->num_rows() > 0) {
            //this already exists, try again
            return $this->generateUniqueRandomString($length, $table, $column);
        } else {
            return $string;
        }

    }

	



}
