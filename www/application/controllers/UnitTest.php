

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
       }

       

    //    public function testDb(){


    //    }

    }


