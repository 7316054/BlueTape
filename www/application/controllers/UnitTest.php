<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library("unit_test");
		$this->load->database();
		//$this->load->model('Transkrip_model');
		$this->load->library('BlueTape');
		
	}

	public function index(){
		$this->checkNPMandEMAIL();
	}

	public function checkNPMandEMAIL(){
		$userInfo = $this->Auth_model->getUserInfo();
	   $test = $this->bluetape->getNPM($userInfo['email'],NULL);
	
		$arr= array('7316068@student.unpar.ac.id','2016730086','GABRIEL PANJI LAZUARDI');
		$expected_result = '2016730068';
		$test_name = 'Mengecek apakah NPM sama Email Sama pemohon sesuai';

		$this->unit->run($test, $expected_result, $test_name);

		echo $this->unit->report();
	}   
}
	

