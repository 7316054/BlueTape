<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library("unit_test");
		$this->load->database();
		$this->load->model('JadwalDosen_model');
		$this->load->library('BlueTape');
		
	}

	public function index(){
		$this->requestByDosen('Samuel');
		print_r($this->unit->result());
	}

	/**
	 * Method untuk memeriksa method requestBy pada model jadwal_dosen
	 * @var adalaha nama dari dosen
	 * Expected result merupakan Array dari hasil query
	**/
	public function requestByDosen($var){
		
	    $test = $this->JadwalDosen_model->requestsBy($var);
		//print_r($test);
		$expected_result = $this->expectedResDosen($var);
		$test_name = 'Memeriksa method requestBy dari JadwalDosen_model';

		$this->unit->run($test, $expected_result, $test_name);

		
	}   

	public function runTest(){
		$userInfo = $this->Auth_model->getUserInfo();
		$arr = $this->Transkrip_model->requestsBy('7316068@student.unpar.ac.id');
	    for($i=0; $i<sizeof($arr);$i++){ 
					$row=$arr[$i]; 
					print_r($row->requestByEmail." "); 
					$this->checkNPMandEMAIL($row->requestByEmail);
		};

	}

	/**
	 * Method untuk mendapatkan expected result dari test requestByDosen
	 * @var adalaha nama dari dosen
	 * Expected result merupakan Array dari hasil query
	**/
	public function expectedResDosen($var){
		// cara 1
		$query1 = $this->db->query("SELECT * FROM jadwal_dosen WHERE user = '$var' ORDER BY durasi DESC");
		//cara 2
		$this->db->where('user', $var);
		$this->db->from('jadwal_dosen');
		$this->db->order_by('durasi', 'DESC');
		$query2 = $this->db->get();

		//ubah $query menjadi  $query1 atau  $query2 untuk memilih cara query yang digunakan
		$res = $query1->result();
		//print_r($res);
		return $res;
	}
}
	

