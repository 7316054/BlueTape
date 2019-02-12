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
            $this->testBlueTapeLibraryGetNPM();
            $this->testBlueTapeLibraryGetNPM_2017();
            $this->cek();
            $this->getHari();
            $this->getAllJadwal();
        }
        public function cek(){
            $myemail="7316053@student.unpat.ac.id";
            $models=$this->JadwalDosen_model->getJadwalByUsername('Samuel');
            $row=$models[1];
            //echo $row;
            print_r($row->user);
        }
        
        public function getAllJadwal(){
            $models=$this->JadwalDosen_model->getAllJadwal();
            for($i=0;$i<sizeof($models);$i++){
                $row=$models[0];
                print_r($row);
                print_r(" \n");
            }
        }

        public function getHari(){
            $day=$this->JadwalDosen_model->getNamaHari();
            print_r($day);
        }
        public function testBlueTapeLibraryGetNPM() {
            echo $this->unit->run(
                $this->bluetape->getNPM('7313013@student.unpar.ac.id'),
                '2013730013',
                __FUNCTION__,
                'Ensure e-mail to NPM conversion works, for angkatan < 2017'
            );
        }
        public function testBlueTapeLibraryGetNPM_2017() {
            echo $this->unit->run(
                $this->bluetape->getNPM('2017730013@student.unpar.ac.id'),
                '2017730013',
                __FUNCTION__,
                'Ensure e-mail to NPM conversion works, for angkatan >= 2017'
            );
        }

    }
?>
