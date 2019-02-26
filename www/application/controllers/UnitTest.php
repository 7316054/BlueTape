
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class unittest extends CI_Controller{
      /**
       * Constructor untuk unit test
       */
       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('BlueTape');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
       }
       /**
        * Method untuk menjalankan Test case
        */
       public function index(){
         $this->testBlueTapeGetNPM();
         $this->cekJadwalByUsername('Dipo');
         $this->cekYearMonthToSemesterCode();
         $this->cekSemesterCodeToString();
		 $this->requestByDosen('Samuel');
		$this->getName('GABRIEL PANJI LAZUARDI');
        $this->dbDateTimeToReadableDate();
        $this->getEmail();
        $this->cekGetAllJadwal();
        $this->cekJadwalByJamMulai(7,0,'anugrahjaya23@gmail.com');
        $this->report();
         print_r($this->unit->result());
       }
       /**
        * Method yang di gunakan untuk melakukan testing terhadap
        * jadwal menggunakan username yang di lakukan cek pada databasenya
        * PATH : Model-Jadwal_model-getJadwalByUsername
        */
       public function cekJadwalByUsername($user){
          $query=$this->db->query("SELECT *
                                   FROM jadwal_dosen
                                   WHERE user='$user'");
          $expected_result=$query->result();
          
          $test=$this->JadwalDosen_model->getJadwalByUsername($user);
         
          $test_name="Memeriksa apakah Jadwal sesuai dengan Username nya.";
          $this->unit->run($test,$expected_result,__FUNCTION__,$test_name);
       }

       /**
        * Method ini memeriksa apakah method yearMonthToSemester
        * berfungsi dengan semestinya.
        */
       public function cekYearMonthToSemesterCode(){
         //testcase untuk semester genap
          $tahun1=2019;
          $bulan1=2;

          $test1=$this->bluetape->yearMonthToSemesterCode($tahun1,$bulan1);
          $expected_result1=192;
          $test_name1="Memeriksa code semester sudah sesuai dengan tahun dan bulan semester";
           $this->unit->run($test1,$expected_result1,__FUNCTION__,$test_name1);
          //testcase untuk semester padat
          $tahun2=2018;
          $bulan2=6;

          $test2=$this->bluetape->yearMonthToSemesterCode($tahun2,$bulan2);
          $expected_result2=184;
          $test_name2="Memeriksa code semester sudah sesuai dengan tahun dan bulan semester";
           $this->unit->run($test2,$expected_result2,__FUNCTION__,$test_name2);
          //testcase untuk semester ganjil
          $tahun3=2018;
          $bulan3=11;

          $test3=$this->bluetape->yearMonthToSemesterCode($tahun3,$bulan3);
          $expected_result3=181;
          $test_name3="Memeriksa code semester sudah sesuai dengan tahun dan bulan semester ";
           $this->unit->run($test3,$expected_result3,__FUNCTION__,$test_name3);
       }

       public function cekSemesterCodeToString(){
         //testcase semester genap
         $test=$this->bluetape->semesterCodeToString(192);
         $expected_result="Genap 2018/2019";
         $test_name="Memeriksa hasil dari translasi code semester ke string";
          $this->unit->run($test,$expected_result,__FUNCTION__,$test_name);

         //testcase semester ganjil
         $test2=$this->bluetape->semesterCodeToString(181);
         $expected_result2="Ganjil 2018/2019";
         $test_name2="Memeriksa hasil dari translasi code semester ke string";
          $this->unit->run($test2,$expected_result2,__FUNCTION__,$test_name2);

         //testcase semester padat
         $test3=$this->bluetape->semesterCodeToString(184);
         $expected_result3="Padat 2017/2018";
         $test_name3="Memeriksa hasil dari translasi code semester ke string";
          $this->unit->run($test3,$expected_result3,__FUNCTION__,$test_name3);
       }

       public function testBlueTapeGetNPM(){
         $this->unit->run(
            $this->bluetape->getNPM('7316054@student.unpar.ac.id'),
            '2016730054',
            __FUNCTION__,
            'angkatan 2016'
        );
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
		$dateTime = $query->row;

		setlocale(LC_TIME, 'ind');
		$expected_result = strftime('%A, %B, %Y',(new DateTime($dateTime->requestDateTime))->getTimestamp());
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

		$row = $query->row;

		return $row->name;
	}
	
	}

       

    /public function testBlueTapeLibraryGetNPM() {
        $this->unit->run(
            $this->bluetape->getNPM('7313013@student.unpar.ac.id'),
            '2013730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan < 2017'
        );
    }
    public function testBlueTapeLibraryGetNPM_2017() {
        $this->unit->run(
            $this->bluetape->getNPM('2017730013@student.unpar.ac.id'),
            '2017730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan >= 2017'
        );
    }

    //library
    public function getEmail(){
        //testcase1 <= 2017
        $npm='2016730053';
        $exceptedRes='7316053@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sebelum 2017");

        //testcase2 > 2017
        $npm1='6181801025';
        $exceptedRes1='6181801025@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getNPM($npm1),$exceptedRes1,__FUNCTION__,"NPM angkatan sesudah 2017");
    }

    public function cekGetAllJadwal(){
        $result=$this->JadwalDosen_model->getAllJadwal();
        $expetecRes=$this->getAllJadwal();

        $this->unit->run($result,$expetecRes,__FUNCTION__,'Jadwal sama');
        //echo $this->unit->report();
    }


    public function cekJadwalByJamMulai($jamMulai,$hari,$user){
        $result=$this->JadwalDosen_model->cekJadwalByJamMulai($jamMulai,$hari,$user);
        $size=sizeof($result);
        $expetecRes=1;
        $this->unit->run($size,$expetecRes,__FUNCTION__,'Jadwal Dosen pada hari dan jam yang sama hanya boleh ada 1');
        //echo $this->unit->report();
    }
    
    /**
     * User = email
     */
    public function getAllJadwal(){
        $query = $this->db->query('SELECT jadwal_dosen.*, bluetape_userinfo.name
            FROM jadwal_dosen
            INNER JOIN bluetape_userinfo ON jadwal_dosen.user=bluetape_userinfo.email');
        return $query->result();
    }

    }
