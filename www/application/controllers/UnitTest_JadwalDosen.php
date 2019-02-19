<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class UnitTest_JadwalDosen extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
        }

        /**\
         * Methd untuk menjalankan testcase
         */
        public function index(){
            $this->cekGetAllJadwal();
            $this->cekJadwalByJamMulai(7,1,'samuel');
            print_r($this->unit->result());
        }

        /**
         * Method untuk mengecek apakah method getAllJadwal() pada sama dengan 
         */
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
?>