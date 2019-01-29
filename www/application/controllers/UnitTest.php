<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library("unit_test");

	}

	public function start_test(){
		
		$test = 1 + 1;

		$expected_result = 2;

		$test_name = 'Adds one plus one';

		$this->unit->run($test, $expected_result, $test_name);

		echo $this->unit->report();
	}

	
}
	

