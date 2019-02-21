
	


<?php
use SebastianBergmann\CodeCoverage\CodeCoverage;
defined('BASEPATH') OR exit('No direct script access allowed');
class UnitTest extends CI_Controller {
    const ENABLE_COVERAGE = true; // Requires xdebug
    private $coverage;
    public function __construct() {
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
     * Run all tests
     */
    public function index() {
        //$this->testBlueTapeLibraryGetNPM();
        //$this->testBlueTapeLibraryGetNPM_2017();
        $this->getEmail();

        $this->cekGetAllJadwal();
        $this->cekJadwalByJamMulai(7,1,'samuel');

        $this->report();
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
        //testcase1 <= 2017
        $npm='2016730053';
        $exceptedRes='7316053@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getEmail($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sebelum 2017");

        //testcase2 > 2017
        $npm='6181801025';
        $exceptedRes='6181801025@student.unpar.ac.id';
        $this->unit->run($this->bluetape->getNPM($npm),$exceptedRes,__FUNCTION__,"NPM angkatan sesudah 2017");
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

