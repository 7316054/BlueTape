<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
$config['google-clientid'] = '466653343196-620lga9usl4lsmr99o9k1kljrd94ecfj.apps.googleusercontent.com';
$config['google-clientsecret'] = 'a8yaddxuxBLO9JR8to9vkxyT';
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['email-config'] = Array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.googlemail.com',
    'smtp_port' => 465,
    'smtp_user' => 'rootbluetape@gmail.com', //masukan email google 
    'smtp_pass' => 'Rootbluetape123', //password email  // di config bikin 2 AUTH 1 YANG SALAH
    'mailtype' => 'html',
    'charset' => 'iso-8859-1'
);