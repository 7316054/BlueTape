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
		$this->getName('GABRIEL PANJI LAZUARDI');
		$this->dbDateTimeToReadableDate();
		
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

	/**
	 * Method untuk memeriksa method getName pada libraries/BlueTape
	 * @var adalaha nama dari user BlueTape
	 * Expected result merupakan  hasil query
	**/
	public function getName($var){
		$test = $this->bluetape->getName($var);
		
		$expected_result = $this->expectedResGetName($var);
		$test_name = 'Memeriksa method getName dari BlueTape';

		$this->unit->run($test, $expected_result, $test_name);
	}

	/**
	 * Method untuk memeriksa method dbDateTimeToReadableDate pada libraries/BlueTape
	 * Expected result merupakan  hasil konversi DateTime dari database ke dalam string yang dapat dibaca
	**/
	public function dbDateTimeToReadableDate(){
		
		$this->db->select('requestDateTime');
		$this->db->from('transkrip');
		$query = $this->db->get();
		$dateTime = $query->row();
		$fixed = $dateTime->requestDateTime;

		setlocale(LC_TIME, 'ind');
		$expected_result = strftime('%A, %B, %Y',(new DateTime($fixed))->getTimestamp());
		$test = $this->bluetape->dbDateTimeToReadableDate($dateTime->requestDateTime);
		$test_name = 'Memeriksa method dbDateTimeToReadableDate dari BlueTape';

		$this->unit->run($test, $expected_result, $test_name);

	}









	//--------------EXPECTED RESULTS-----------------------------------------------------------------------------------------------------------------------------------

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

	/**
	 * Method untuk mendapatkan expected result dari test getName
	 * @var adalaha nama dari user BlueTape
	 * Expected result merupakan hasil query
	**/
	public function expectedResGetName($var){
		$this->db->where('name',$var);
		$this->db->from('bluetape_userinfo');

		$query = $this->db->get();

		$row = $query->row();

		return $row->name;
	}
}
	

