	<?php 
	use SebastianBergmann\CodeCoverage\CodeCoverage; 
	defined('BASEPATH') OR exit('No direct script access allowed'); 
	class CodeCover extends CI_Controller { 
	const ENABLE_COVERAGE = true; // Requires xdebug 
	private $coverage; 

	public function __construct() { 
		parent::__construct(); 
		$this->load->library('unit_test'); 
		$this->unit->use_strict(TRUE); 
		if (self::ENABLE_COVERAGE) { 
			$this->coverage = new SebastianBergmann\CodeCoverage\CodeCoverage; 
			$this->coverage->filter()->addDirectoryToWhitelist('application/controllers'); 
			$this->coverage->filter()->removeDirectoryFromWhitelist('application/controllers/tests'); 
			$this->coverage->filter()->addDirectoryToWhitelist('application/libraries'); 
			$this->coverage->filter()->addDirectoryToWhitelist('application/models'); 
			$this->coverage->filter()->addDirectoryToWhitelist('application/views'); 
			$this->coverage->start('UnitTests'); 
		} 
		$this->load->library('BlueTape'); 
		$this->load->library("unit_test");
		$this->load->model('JadwalDosen_model');
		$this->load->database();
		
	} 

	private function report() { 
		if (self::ENABLE_COVERAGE) { 
		$this->coverage->stop(); 
		$writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade; 
		$writer->process($this->coverage, '../reports/code-coverage'); 
		} 
		// Generate Test Report HTML 
		file_put_contents('../reports/test_report.html', $this->unit->report()); 
		// Output result to screen 
		$statistics = [ 
		'Pass' => 0, 
		'Fail' => 0 
		]; 
		$results = $this->unit->result(); 
		foreach ($results as $result) { 
		echo "=== " . $result['Test Name'] . " ===\n"; 
		foreach ($result as $key => $value) { 
		echo "$key: $value\n"; 
		} 
		echo "\n"; 
		if ($result['Result'] === 'Passed') { 
			$statistics['Pass']++; 
		} else { 
			$statistics['Fail']++; 
		} 
		} 
		echo "==========\n"; 
		foreach ($statistics as $key => $value) { 
		echo "$value test(s) $key\n"; 
		} 
		if ($statistics['Fail'] > 0) { 
			exit(1); 
		} 
	} 

	/** 
	* Run all tests 
	*/ 
	public function index(){
		$this->requestByDosen('Samuel');
		$this->getName('GABRIEL PANJI LAZUARDI');
		$this->dbDateTimeToReadableDate();
		$this->unit->result();
		$this->report();
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

		setlocale(LC_TIME, 'ind');
		$expected_result = strftime('%A, %B, %Y',(new DateTime($dateTime->requestDateTime))->getTimestamp());
		$test = $this->bluetape->dbDateTimeToReadableDate($dateTime->requestDateTime);
		$test_name = 'Memeriksa method dbDateTimeToReadableDate dari BlueTape';

		$this->unit->run($test, $expected_result, $test_name);

	}




	//---------------EXPECTED RESULTS----------------------------------------------//
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