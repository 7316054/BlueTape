<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class UnitTest_BlueTape_Library extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('bluetape');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
        }

        /**
         * Methd untuk menjalankan testcase
         */
        public function index(){
            $this->getEmail();
            print_r($this->unit->result());
        }

        public function getEmail(){
            //testcase1 <= 2017
            $npm='2016730053';
            $exceptedRes='7316053@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sebelum 2017");

            //testcase2 > 2017
            $npm='6181801025';
            $exceptedRes='6181801025@student.unpar.ac.id';
            $this->unit->run($this->bluetape->getNPM($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sesudah 2017");
        }
    }
?>