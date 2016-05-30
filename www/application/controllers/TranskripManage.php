<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TranskripManage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('bluetape');
        $this->load->model('Transkrip_model');
        $this->load->database();
    }

    public function index() {
        $this->load->config('transkrip');
        $requests = $this->Transkrip_model->requestsBy(NULL);
        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'MENUNGGU';
                $request->labelClass = 'warning';
            } else if ($request->answer === 'printed') {
                $request->status = 'TERCETAK';
                $request->labelClass = 'success';
            } else if ($request->answer === 'rejected') {
                $request->status = 'DITOLAK';
                $request->labelClass = 'alert';
            }
            $request->requestByName = $this->bluetape->getName($request->requestByEmail, '(belum tersedia)');
            $request->requestByNPM = $this->bluetape->getNPM($request->requestByEmail);
            $request->requestDateString = $this->bluetape->dbDateTimeToReadableDate($request->requestDateTime);
            $request->answeredDateString = $this->bluetape->dbDateTimeToReadableDate($request->answeredDateTime);
        }
        unset($request);

        $userInfo = $this->Auth_model->getUserInfo();
        $this->load->view('TranskripManage/main', array(
            'answeredByEmail' => $userInfo['email'],
            'currentModule' => get_class(),
            'requests' => $requests,
            'transkripURLs' => $this->config->item('url')
        ));
    }

    public function answer() {
        date_default_timezone_set("Asia/Jakarta");
        try {
            $userInfo = $this->Auth_model->getUserInfo();
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('Transkrip', array(
                'answer' => $this->input->post('answer'),
                'answeredByEmail' => $userInfo['email'],
                'answeredDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
                'answeredMessage' => $this->input->post('answeredMessage')
            ));
            $this->session->set_flashdata('info', 'Permintaan cetak transkrip sudah dijawab.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /TranskripManage');
    }

}