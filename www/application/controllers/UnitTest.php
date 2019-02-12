<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class unittest extends CI_Controller{

       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('BlueTape');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
       }

       private function division($a,$b){
            return $a/$b;
       }

       public function index(){
         $this->test();
         $this->testBlueTapeGetNPM();
         $this->cekJadwalByUsername();
       }

       public function cekJadwalByUsername(){
          $query=$this->db->query("SELECT *
                                   FROM jadwal_dosen
                                   WHERE user='Dipo'");
          $expected_result=$query->result();
          // foreach($query->result() as $row){
          //   $expected_result=$row->user;
          // }
          // $query2=$this->JadwalDosen_model->getJadwalByUsername('Dipo');
          $test=$this->JadwalDosen_model->getJadwalByUsername('Dipo');
          // foreach($query2 as $row){
          //   $test=$row->user;
          // }
          $test_name="Memeriksa apakah Jadwal sesuai dengan Username nya.";
          echo $this->unit->run($test,$expected_result,__FUNCTION__,$test_name);
       }

       public function test(){
         $test=$this->division(6,2);
         $expected_result=3;
         $test_name="Divison 6: 3";
         echo $this->unit->run($test, $expected_result, $test_name);  
       }

       public function testBlueTapeGetNPM(){
        echo $this->unit->run(
            $this->bluetape->getNPM('7316054@student.unpar.ac.id'),
            '2016730054',
            __FUNCTION__,
            'angkatan 2016'
        );
       }

       

    //    public function testDb(){


    //    }

    }
?>
  