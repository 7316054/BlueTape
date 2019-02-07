<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library("unit_test");

	}

	public function start_test(){
		$this->load->database();
		$this->load->model('Transkrip_model');
		$this->load->libraries('BlueTape')
		$test = $this->BlueTape->getNPM()
		//$this->Transkrip_model->requestsBy('7316068@student.unpar.ac.id',NULL,NULL);
		
		print_r($test);
     /**$this->Transkrip_model->requestBy('7316068@student.unpar.ac.id',NULL,NULL);**/

		$arr= array('7316068@student.unpar.ac.id','2016730086','GABRIEL PANJI LAZUARDI');
		$expected_result = $arr;//array( [0] => stdClass Object ( [id] => 1 [requestByEmail] => '7316068@student.unpar.ac.id' 
				//[requestDateTime] => 2019-01-29 14:22:17 [requestType] => 'DPS_EN' [requestUsage] => 'Untuk Unit Testing'
				//[answer] => 'printed' [answeredByEmail] => 'gabriel.lazuardi@tu.ftis.unpar' [answeredDateTime] => 2019-01-08 00:00:00 [answeredMessage] => 'Accepted unit test' ) );

		$test_name = 'Test to see if the request maker is inside the database';

		$this->unit->run($test, $expected_result, $test_name);

		echo $this->unit->report();
	}   


}
	

