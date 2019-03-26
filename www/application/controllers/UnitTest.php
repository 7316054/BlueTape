<?php
    // use SebastianBergmann\CodeCoverage\CodeCoverage;
    defined('BASEPATH') OR exit('No direct script access allowed');
    class unittest extends CI_Controller{
    //   const ENABLE_COVERAGE = true; // Requires xdebug
    //   private $coverage;
      /**
       * Constructor untuk unit test
       */
       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            //$this->unit->use_strict(TRUE); 
            // if (self::ENABLE_COVERAGE) { 
            //     $this->coverage = new SebastianBergmann\CodeCoverage\CodeCoverage; 
            //     $this->coverage->filter()->addDirectoryToWhitelist('application/controllers'); 
            //     $this->coverage->filter()->removeDirectoryFromWhitelist('application/controllers/tests'); 
            //     $this->coverage->filter()->addDirectoryToWhitelist('application/libraries'); 
            //     $this->coverage->filter()->addDirectoryToWhitelist('application/models'); 
            //     $this->coverage->filter()->addDirectoryToWhitelist('application/views'); 
            //     $this->coverage->start('UnitTests'); 
            // }
            $this->load->library('BlueTape');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
       }
       /**
        * Method untuk menjalankan Test case
        */
       public function index(){
         //$this->testBlueTapeGetNPM();
         $this->cekJadwalByUsername('Dipo');
         $this->cekYearMonthToSemesterCode();
         $this->cekSemesterCodeToString();
         $this->cekUpdateJadwal();
         //$this->report();
         print_r($this->unit->result());
       }
    //    private function report() {
    //         if (self::ENABLE_COVERAGE) {
    //             $this->coverage->stop();        
    //             $writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
    //             $writer->process($this->coverage, '../reports/code-coverage');
    //         }
    //         // Generate Test Report HTML
    //         file_put_contents('../reports/test_report.html', $this->unit->report());
    //         // Output result to screen
    //         $statistics = [
    //             'Pass' => 0,
    //             'Fail' => 0
    //         ];
    //         $results = $this->unit->result();
    //         foreach ($results as $result) {
    //             echo "=== " . $result['Test Name'] . " ===\n";
    //             foreach ($result as $key => $value) {
    //                 echo "$key: $value\n";
    //             }
    //             echo "\n";
    //             if ($result['Result'] === 'Passed') {
    //                 $statistics['Pass']++;
    //             } else {
    //                 $statistics['Fail']++;
    //             }
    //         }
    //         echo "==========\n";
    //         foreach ($statistics as $key => $value) {
    //             echo "$value test(s) $key\n";
    //         }
    //         if ($statistics['Fail'] > 0) {
    //             exit(1);
    //         }        
    //     }
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
      
       public function cekUpdateJadwal(){
         $data=array("user"=>"Dipo1","hari"=>"4","jam_mulai"=>"13","durasi"=>"5","jenis"=>"Kelas","label"=>"Update");
         $id=4;
         //$this->JadwalDosen_model->updateJadwal($id,$data);
         $query=$this->db->query("SELECT user,hari,jam_mulai,durasi,jenis,label
                                  FROM jadwal_dosen
                                  WHERE id='$id'");
         $expected_result=$query->result();
         
           $this->unit->run($data,(array)$expected_result[0],__FUNCTION__,"Memeriksa apakah data yang di insert benar");
         //   print_r((array)$expected_result[0]);
         //   print_r($data);
       }

      //  public function testBlueTapeGetNPM(){
      //    $this->unit->run(
      //       $this->bluetape->getNPM('7316054@student.unpar.ac.id'),
      //       '2016730054',
      //       __FUNCTION__,
      //       'angkatan 2016'
      //   );
      //  }

       

    

    }
?>
  