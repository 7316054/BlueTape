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
            $this->load->model('Transkrip_model');
            $this->load->model('PerubahanKuliah_model');
            $this->load->database();
       }
       /**
        * Method untuk menjalankan Test case
        */
       public function index(){
            $this->cekJadwalByUsername('Dipo');
            $this->cekYearMonthToSemesterCode();
            $this->cekSemesterCodeToString();
            $this->cekYearMonthToSemesterCodeSimplified();
            $this->getName('anugrahjaya23@gmail.com');
            $this->dbDateTimeToReadableDate();
            $this->getEmail();
            $this->cekGetNpm();
            $this->cekGetAllJadwal();
            $this->cekJadwalByJamMulai(7,0,'anugrahjaya23@gmail.com');
			$this->checkRequestTypesForbidden();
            $this->cekAddjadwal();
			$this->checkKolomKeHari();
            $this->checkHariKeKolom();
            $this->cekGetNamaHari();
            $this->cekGetNamaBulan();
            $this->cekUpdateJadwal();
            $this->cekGetNamaHari();
            $this->cekGetNamaBulan();
            $this->deleteByUsername('anugrahjaya23@gmail.com');
            $this->cekDeleteJadwal(1);
            $this->cekRequestByIdTranskrip(1,null,null);
            $this->cekRequestByIdTranskrip(1,3,0);
            $this->cekRequestBy('7316053@student.unpar.ac.id',null,null);
            $this->cekRequestBy('7316053@student.unpar.ac.id',1,3);
            $this->cekRequestByPerubahanKuliah('rootbluetape@gmail.com',null,null);
            $this->cekRequestByPerubahanKuliah('rootbluetape@gmail.com',1,0);
            $this->cekSend_email('rootbluetape@gmail.com','Mengetes pengiriman pesan','Tugas proyek informatika',false);
             $this->report();
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
         * PATH : Models/transkrip_model.php
         */
        public function cekRequestBy($email,$rows,$start){
            $temp=$this->Transkrip_model->requestsBy($email,$rows,$start);
            $query=$this->db->query("SELECT *
                 FROM transkrip
                 WHERE requestByEmail='$email' Order By requestDateTime DESC
                 ");
            $obj=$query->result();
            $expected_result=array();
            $result=array();

            for($i=0;$i<sizeof($temp);$i++){
                if (is_object($temp[$i])) {
                    $result[$i]= get_object_vars($temp[$i]);
                }
                if (is_object($obj[$i])) {
                    $expected_result[$i]= get_object_vars($obj[$i]);
                }
                $i++;
            }

            $this->unit->run($result,$expected_result,__FUNCTION__,'Test ini berfungsi untuk memeriksa apakah method requestBy pada transkrip model sudah masuk ke dalam dataBase atau belum');
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
            
            $test_name="Test ini berfungsi untuk memeriksa apakah Jadwal sesuai dengan Username nya.";
            $this->unit->run($test,$expected_result,__FUNCTION__,$test_name);
       }

       /**
        * Method ini memeriksa apakah method yearMonthToSemester
        * berfungsi dengan semestinya.
        * PATH : libraries/bluetape.php
        */
       public function cekYearMonthToSemesterCode(){
            //testcase untuk semester genap
            $tahun1=2019;
            $bulan1=2;

            $test1=$this->bluetape->yearMonthToSemesterCode($tahun1,$bulan1);
            $expected_result1=192;
            $test_name1="Test ini berfungsi untuk memeriksa code semester sudah sesuai dengan tahun dan bulan semester genap";
            $this->unit->run((int)$test1,$expected_result1,__FUNCTION__,$test_name1);
            //testcase untuk semester padat
            $tahun2=2018;
            $bulan2=6;

            $test2=$this->bluetape->yearMonthToSemesterCode($tahun2,$bulan2);
            $expected_result2=184;
            $test_name2="Test ini berfungsi untuk memeriksa code semester sudah sesuai dengan tahun dan bulan semester padat";
            $this->unit->run((int)$test2,$expected_result2,__FUNCTION__,$test_name2);
            //testcase untuk semester ganjil
            $tahun3=2018;
            $bulan3=11;

            $test3=$this->bluetape->yearMonthToSemesterCode($tahun3,$bulan3);
            $expected_result3=181;
            $test_name3="Test ini berfungsi untuk memeriksa code semester sudah sesuai dengan tahun dan bulan semester ganjil";
            $this->unit->run((int)$test3,$expected_result3,__FUNCTION__,$test_name3);
       }
       /**
        * PATH : libraries/bluetape.php
        */
       public function cekSemesterCodeToString(){
            //testcase semester genap
            $test=$this->bluetape->semesterCodeToString(192);
            $expected_result="Genap 2018/2019";
            $test_name="Test ini berfungsi untuk memeriksa hasil dari translasi code semester genap ke string";
            $this->unit->run($test,$expected_result,__FUNCTION__,$test_name);

            //testcase semester ganjil
            $test2=$this->bluetape->semesterCodeToString(181);
            $expected_result2="Ganjil 2018/2019";
            $test_name2="Test ini berfungsi untuk memeriksa hasil dari translasi code semester ganjil ke string";
            $this->unit->run($test2,$expected_result2,__FUNCTION__,$test_name2);

            //testcase semester padat
            $test3=$this->bluetape->semesterCodeToString(184);
            $expected_result3="Padat 2017/2018";
            $test_name3="Test ini berfungsi untuk memeriksa hasil dari translasi code semester padat ke string";
            $this->unit->run($test3,$expected_result3,__FUNCTION__,$test_name3);

            //testcase semester false
             $test4=$this->bluetape->semesterCodeToString(185);
             $expected_result3=FALSE;
             $test_name4="Test ini berfungsi untuk memeriksa hasil dari translasi code semester ke string untuk kasus yang return false";
             $this->unit->run($test4,$expected_result3,__FUNCTION__,$test_name3);
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
            $test_name = 'Test ini berfungsi untuk memeriksa method requestBy dari JadwalDosen_model';

            $this->unit->run($test, $expected_result, $test_name);
        }   
       

          /**
         * PATH : libraries/bluetape.php
         */
	   public function getEmail(){
            //testcase1 < 2017
            $npm='2016730053';
            $exceptedRes='7316053@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"Test ini berfungsi untuk memeriksa melakukan cek terhadap email dari NPM yang angkatannya sebelum 2017");

            //testcase2 >= 2017
            $npm1='6181801025';
            $exceptedRes1='6181801025@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getEmail($npm1),$exceptedRes1,__FUNCTION__,"NTest ini berfungsi untuk memeriksa melakukan cek terhadap email dari NPM yang angkatannya sesudah 2017");
        }
        /**
         * Method untuk memeriksa method getName pada libraries/BlueTape
         * @var adalaha nama dari user BlueTape
         * Expected result merupakan  hasil query
         * PATH : libraries/bluetape.php
        **/
        public function getName($var){
		//Test case 1
            $test1 = $this->bluetape->getName($var);
            
            $expected_result = $this->expectedResGetName($var);
            $test_name = 'Test ini berfungsi untuk memeriksa method getName dari BlueTape untuk yang return nya bernilai suatu value';

            $this->unit->run($test1, $expected_result, $test_name);
		
		//Test case 2
		 $test2 = $this->bluetape->getName('7316054@student');
            
            $expected_result2 = NULL;
            $test_name2 = 'Test ini berfungsi untuk memeriksa method getName dari BlueTape untuk kasus contoh yang nilai nya NULL';

            $this->unit->run($test2, $expected_result2, $test_name2);
        }

        /**
         * Method untuk memeriksa method dbDateTimeToReadableDate pada libraries/BlueTape
         * Expected result merupakan  hasil konversi DateTime dari database ke dalam string yang dapat dibaca
         * PATH : libraries/bluetape.php
        **/
        public function dbDateTimeToReadableDate(){
            
            $this->db->select('requestDateTime');
            $this->db->from('transkrip');
            $query = $this->db->get();
            $dateTime = $query->row();
            setlocale(LC_TIME, 'ind');
            $expected_result = strftime('%A, %#d %B %Y',(new DateTime($dateTime->requestDateTime))->getTimestamp());
            $test = $this->bluetape->dbDateTimeToReadableDate($dateTime->requestDateTime);
            $test_name = 'Test ini berfungsi untuk memeriksa method dbDateTimeToReadableDate dari BlueTape';

            $this->unit->run($test, $expected_result, $test_name);
        }

		/**
		* Memeriksa method request type forbidden dari Transkrip_model
		* path model/Transkrip_model
		*
		**/
		public function checkRequestTypesForbidden(){
			
			//Test case 1
			
			$request = $this->Transkrip_model->requestsBy('7316091@student.unpar.ac.id');
			$test1 = $this->Transkrip_model->requestTypesForbidden($request);
			$expected_result1 = array( 'LHS');
			$test_name1 = 'Test ini berfungsi untuk memeriksa method requestTypeForbidden dari Transkrip_model yang keluarannya request type dari permintaan transkrip';
             $this->unit->run($test1, $expected_result1, $test_name1);
             
			 //Test Case2
			 $request = $this->Transkrip_model->requestsBy('7316054@student.unpar.ac.id');
			$test2 = $this->Transkrip_model->requestTypesForbidden($request);
			$expected_result2 ='Anda tidak bisa meminta cetak karena ada permintaan lain yang belum selesai.';
			$test_name2 = 'Test ini berfungsi untuk memeriksa method requestTypeForbidden dari Transkrip_model dimana belum ada transkrip yang di jawab(answer)';
            $this->unit->run($test2, $expected_result2, $test_name2);
          
             //Test case 3
             $date = getdate();
            $currentYear = $date['year'];
            $currentMonth = $date['mon'];
             $currentSemester = $this->bluetape->yearMonthToSemesterCodeSimplified($currentYear, $currentMonth);      
             $request = $this->Transkrip_model->requestsBy('7316053@student.unpar.ac.id');
			$test3 = $this->Transkrip_model->requestTypesForbidden($request);
			$expected_result3 ='Anda tidak bisa meminta cetak karena seluruh jenis transkrip sudah pernah dikabulkan di semester ini (' . $this->bluetape->semesterCodeToString($currentSemester) . ').';
            $test_name3 = 'Test ini berfungsi untuk memeriksa method requestTypeForbidden dari Transkrip_model dimana seluruh jenis transkrip sudah terkabul';
            $this->unit->run($test3, $expected_result3, $test_name3);
		}


        
        //Model -addJadwal
        public function cekAddjadwal(){
            $data=array("user"=>"gemini2911f665@gmail.com", "hari"=>"0", "jam_mulai"=>"7","durasi"=>"1","jenis_jadwal"=>"konsultasi","label_jadwal"=>"aa");
            $this->JadwalDosen_model->addJadwal($data);
            $query=$this->db->query("SELECT *from jadwal_dosen where user='gemini2911f665@gmail.com' And hari=0  
            And jam_mulai=7 And durasi=1 And jenis='konsultasi'");
            $row=$query->result();
            $obj=$row[sizeof($row)-1];
            if (is_object($obj)) {
                $res = get_object_vars($obj);
            }
            $data2=array("user"=>$res['user'], "hari"=>$res['hari'], "jam_mulai"=>$res['jam_mulai'],"durasi"=>$res['durasi'],"jenis_jadwal"=>$res['jenis'],"label_jadwal"=>"aa");
            $this->unit->run($data,$data2,__FUNCTION__,"Test ini berfungsi untuk mengecek apakah inputan data sudah masuk atau tidak");
        }
        
        
         /**
          * PATH : models/JadwalDosen_model.php
          */
    public function cekJadwalByJamMulai($jamMulai,$hari,$user){
        $result=$this->JadwalDosen_model->cekJadwalByJamMulai($jamMulai,$hari,$user);
        $size=sizeof($result);
        $expetecRes=1;
        
        $this->unit->run($size,$expetecRes,__FUNCTION__,'Test ini berfungsi untuk memeriksa Jadwal Dosen pada hari dan jam yang sama hanya boleh ada 1');
        //echo $this->unit->report();
    }
    /**
     * PATH : models/JadwalDosen_model.php 
     */
    public function requestBy($email,$rows,$start){
        $result=$this->JadwalDosen_model->requestsBy($email,$rows,$start);

        if ($user !== NULL) {
            $this->db->where('user', $user);
        }
        if ($start !== NULL && $rows !== NULL) {
            $this->db->limit($rows, $start);
        }
        $this->db->from('jadwal_dosen');
        $this->db->order_by('durasi', 'DESC');
        $query = $this->db->get();
        $exceptedRes=$query->result();


        $this->unit->run($result,$exceptedRes,__FUNCTION__,'Test ini berfungsi untuk memeriksa seluruh request dari email '+$email);        
        }

        //PATH : Libraries/BlueTape.php
        public function cekGetNpm(){
            //test case 1
            $result= $this->bluetape->getNPM('7316054@student.unpar.ac.id');
            $expected='2016730054';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak untuk kasus dimana mahasiswa angkatan di bawah 2017");
            //test case2 
            $result= $this->bluetape->getNPM('6181801025@student.unpar.ac.id');
            $expected='6181801025';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak untuk kasus dimana mahasiswa angkatan di atas 2017");
            //test case3
            $result= $this->bluetape->getNPM('6181801025@goole.com');
            $expected=NULL;
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak untuk kasis yang tidak valid");
        }
   

        //PATH : libraries/bluetape.php
        public function cekYearMonthToSemesterCodeSimplified(){

            //test case 1
            $year=2019;
            $month=12;
                $result= $this->bluetape->yearMonthToSemesterCodeSimplified($year,$month);
            $expected='191';
            $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester untuk kasus semester ganjil");


            //test case 2
            $year2=2019;
            $month2=3;
                $result2= $this->bluetape->yearMonthToSemesterCodeSimplified($year2,$month2);
            $expected2='192';
                $this->unit->run($result2,$expected2,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester untuk kasus semester genap");

        }

        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function cekGetAllJadwal(){
            $result=$this->JadwalDosen_model->getAllJadwal();
            $expetecRes=$this->getAllJadwal();
            
            $this->unit->run((array)$result[0],(array)$expetecRes[0],__FUNCTION__,'Test ini berfungsi untuk memeriksa semua jadwal apakah sama dengan yang ada di database atau tidak');
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
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function cekUpdateJadwal(){
            $data=array("user"=>"Dipo1","hari"=>"4","jam_mulai"=>"13","durasi"=>"5","jenis"=>"Kelas","label"=>"Update");
            $id=4;
            $this->JadwalDosen_model->updateJadwal($id,$data);
            $query=$this->db->query("SELECT user,hari,jam_mulai,durasi,jenis,label
                                     FROM jadwal_dosen
                                     WHERE id='$id'");
            $expected_result=$query->result();
            $this->unit->run($data,(array)$expected_result[0],__FUNCTION__,"Test ini berfungsi untuk memeriksa apakah data yang di insert ke tabel jadwal_dosen sudah benar");
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function cekGetNamaHari(){
            $test=$this->JadwalDosen_model->getNamaHari();
            $expected_result=array("Senin","Selasa","Rabu","Kamis","Jumat");
            $this->unit->run($test,$expected_result,__FUNCTION__,"Test ini berfungsi untuk memeriksa apakah nama hari-hari nya benar");
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function cekGetNamaBulan(){
           $test=$this->JadwalDosen_model->getNamaBulan();
           $expected_result=array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
           $this->unit->run($test,$expected_result,__FUNCTION__,"Test ini berfungsi untuk memeriksa apakah nama bulan-bulan nya benar");
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function deleteByUsername($username){
            $query=$this->db->query('SELECT user from jadwal_dosen');
            $res=$query->result();
        
            $exceptedRes=sizeof($res);
            $this->JadwalDosen_model->deleteByUsername($username);

            $query=$this->db->query('SELECT user from jadwal_dosen');
            $res=$query->result();
            $result=sizeof($res)+2;
            
            $this->unit->run($result,$exceptedRes,__FUNCTION__,'Test ini berfungsi untuk memeriksa apakah username yang dihapus sudah tidak ada di database lagi');
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function checkKolomKeHari(){
            $namaHari = 'Senin';

		 $test = $this->JadwalDosen_model->kolomKeHari($namaHari);
		 $exceptedRes = FALSE;

            $test_name = 'Test ini berfungsi untuk memerikas method kolom ke hari dari JadwalDosen_model apakah valid atau tidak';

            $this->unit->run($test, $exceptedRes, $test_name);
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
	 public function checkHariKeKolom(){
		$coloumn = 3;
		
		$test = $this->JadwalDosen_model->hariKeKolom($coloumn);
		$expected_result = 'E';
        
		$test_name = 'Test ini berfungsi untuk memeriksa method hari ke kolom dari JadwalDosen_model apakah valid atau tidak';

            $this->unit->run($test, $expected_result, $test_name);
            
        }
        /**
         * PATH : models/jadwaldosen_model.php
         */
        public function cekDeleteJadwal($id){
            $this->JadwalDosen_model->deleteJadwal($id);
            $query=$this->db->query("SELECT *from jadwal_dosen where id=$id");
            $row=$query->result();
            $obj=null;
            if(sizeof($row)==0){
                $obj=null;
            }
            else{
                $obj=$row[sizeof($row)-1];
            }
            
            $this->unit->run($obj,null,__FUNCTION__,"Test ini berfungsi untuk mengecek apakah data sudah terdelete atau tidak");
        }

	    //PATH : Model/transkrip_model.php
        public function cekRequestByIdTranskrip($id,$rows,$start){
            $res=$this->Transkrip_model->requestById($id,$rows,$start);
            $exceptedRes=(object)[
                "id" => 1,
                "requestByEmail" => "7316053@student.unpar.ac.id",
                "requestDateTime" => "2019-03-26 15:28:58",
                "requestType" => "DPS_ID",
                "requestUsage" => "kuliah",
                "answer" => "printed",
                "answeredByEmail" => "rootbluetape@gmail.com",
                "answeredDateTime" => "2019-04-02 10:06:09",
                "answeredMessage" => "oke"
            ];

            $this->unit->run($res->requestByEmail,$exceptedRes->requestByEmail,__FUNCTION__,"Test ini berfungsi untuk memeriksa requestby berdasarkan id");
        }

        /** 
         * Path: Models/PerubahanKuliah_model.php
        */
        public function cekRequestByPerubahanKuliah($email,$row,$start){
            $temp=$this->PerubahanKuliah_model->requestsBy($email,$row,$start);

            $query=$this->db->query("SELECT *
                 FROM PerubahanKuliah
                 WHERE requestByEmail='$email'
                 Order by requestDateTime DESC
                 ");
            $obj=$query->result();
            $expected_result=array();
            $result=array();

            for($i=0;$i<sizeof($temp);$i++){
                if (is_object($temp[$i])) {
                    $result[$i]= get_object_vars($temp[$i]);
                }
                if (is_object($obj[$i])) {
                    $expected_result[$i]= get_object_vars($obj[$i]);
                }
                $i++;
            }
            $this->unit->run($result,$expected_result,__FUNCTION__,'Test ini berfungsi untuk memeriksa siapa yang melakukan request');

         
        }

        	//--------------EXPECTED RESULTS-----------------------------------------------------------------------------------------------------------------------------------


        /**
         * Method untuk mendapatkan expected result dari test getName
         * @var adalaha nama dari user BlueTape
         * Expected result merupakan hasil query
        **/
        public function expectedResGetName($var){
            $this->db->where('email',$var);
            $this->db->from('Bluetape_Userinfo');

            $query = $this->db->get();

            $row = $query->row();

            return $row->name;
        }

        public function cekSend_email($email,$subject,$message,$debug){
            $this->Email_model->send_email($email,$subject,$message,$debug);

        }


    }




        