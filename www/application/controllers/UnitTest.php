<?php
    use SebastianBergmann\CodeCoverage\CodeCoverage; 
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class unittest extends CI_Controller{

        const ENABLE_COVERAGE = true; // Requires xdebug
        private $coverage;
      /**
       * Constructor untuk unit test
       */
       public function __construct(){
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
            $this->cekYearMonthToSemesterCodeSimplified();
            $this->requestByDosen('Samuel');
            $this->getName('GABRIEL PANJI LAZUARDI');
            $this->dbDateTimeToReadableDate();
            $this->getEmail();
            $this->cekGetNpm();
            $this->cekGetAllJadwal();
            $this->cekJadwalByJamMulai(7,0,'anugrahjaya23@gmail.com');
            $this->report();
			$this->requestBy('7316053@student.unpar.ac.id')
            print_r($this->unit->result());
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

            //testcase semester false
             $test4=$this->bluetape->semesterCodeToString(185);
             $expected_result3=FALSE;
             $test_name4="Memeriksa hasil dari translasi code semester ke string";
             $this->unit->run($test3,$expected_result3,__FUNCTION__,$test_name3);
       }

       public function testBlueTapeGetNPM(){
            $this->unit->run(
                $this->bluetape->getNPM('7316054@student.unpar.ac.id'),
                '2016730054',
                __FUNCTION__,
                'angkatan 2016'
            );
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
        if ($statistics['Fail'] > 0) {
            exit(1);
        }        
    }
  
    public function testBlueTapeLibraryGetNPM() {
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
        //testcase1 < 2017
        $npm='2016730053';
        $exceptedRes='7316053@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sebelum 2017");

        //testcase2 >= 2017
        $npm1='6181801025';
        $exceptedRes1='6181801025@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getEmail($npm1),$exceptedRes1,__FUNCTION__,"NPM angkatan sesudah 2017");
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


        //sam
        //Model -addJadwal
        public function cekAddjadwal(){
            $jenis='Praktek';
            $data=array("user"=>"gemini2911f665@gmail.com", "hari"=>"0", "jam_mulai"=>"7","durasi"=>"1","jenis_jadwal"=>"konsultasi","label_jadwal"=>"");
            $query=$this->db->query("SELECT * from jadwal_dosen");
            $res=$query->result();
            $jumlahAwal=sizeof($res);
        

            $this->JadwalDosen_model->addJadwal($data);

            $query2=$this->db->query("SELECT * from jadwal_dosen");
            $res2=$query2->result();
            $jumlahAkhir=sizeof($res2);
    public function cekJadwalByJamMulai($jamMulai,$hari,$user){
        $result=$this->JadwalDosen_model->cekJadwalByJamMulai($jamMulai,$hari,$user);
        $size=sizeof($result);
        $expetecRes=1;
        $this->unit->run($size,$expetecRes,__FUNCTION__,'Jadwal Dosen pada hari dan jam yang sama hanya boleh ada 1');
        //echo $this->unit->report();
    }

    public function requestBy($email){
        $result=$this->JadwalDosen_model->requestsBy($email);

        if ($email !== NULL) {
            $this->db->where('requestByEmail', $email);
        }
        if ($start !== NULL && $rows !== NULL) {
            $this->db->limit($rows, $start);
        }
        $this->db->from('transkrip');//jadwal_dosen
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        $exceptedRes=$query->result();

        print_r($exceptedRes);

        $this->unit->run($result,$exceptedRes,__FUNCTION__,'seluruh request dari email '+$email);
    

               
        }

        //Libraries-BlueTape
        public function cekGetNpm(){
            //test case 1
            $result= $this->bluetape->getNPM('7316054@student.unpar.ac.id');
            $expected='2016730054';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");
            //test case2 
            $result= $this->bluetape->getNPM('6181801025@student.unpar.ac.id');
            $expected='6181801025';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");
            //test case3
            $result= $this->bluetape->getNPM('6181801025@goole.com');
            $expected='null';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");
        }
   

        //libraries-yearMonthToSemesterCodeSimplifeid
        public function cekYearMonthToSemesterCodeSimplified(){

            //test case 1
            $year=2019;
            $month=12;
                $result= $this->bluetape->yearMonthToSemesterCodeSimplified($year,$month);
            $expected='191';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester");


            //test case 2
            $year2=2080;
            $month2=3;
                $result= $this->bluetape->yearMonthToSemesterCodeSimplified($year2,$month2);
            $expected='801';
                $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester");

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
	
        public function testBlueTapeLibraryGetNPM() {
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

        public function getEmail(){
            //testcase1 <= 2017
            $npm='2016730053';
            $exceptedRes='7316053@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sebelum 2017");

            //testcase2 > 2017
            $npm1='6181801025';
            $exceptedRes1='6181801025@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getEmail($npm1),$exceptedRes1,__FUNCTION__,"NPM angkatan sesudah 2017");
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
