<?php
    $this->load->library('unit_testing');

    $test = 1 + 1;

    $expected_result = 2;

    $test_name = 'Adds one plus one';

    $this->unit->run($test, $expected_result, $test_name);
?>