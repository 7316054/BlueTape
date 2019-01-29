<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    class UnitTest extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->library("unit_test");
        }
        public function beginTest(){
            $test =1+1;
            $expectedResult=2;
            $testName="Add one plus one";
            $this->unit->run($test,$expectedResult,$testName);
            echo $this->unit->report();
        }
    }

    