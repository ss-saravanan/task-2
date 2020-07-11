<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set("display_errors", "1");
error_reporting(E_ALL);

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('twilio');
	}

	public function index() {
		if( !$this->session->userdata('user') ) {
			if( isset($_POST['submit']) ) {
				$name = $this->input->post('name');
				$pwd = $this->input->post('password');
				if( $name == 'admin' && $pwd == 'admin@123' ) {
					$this->session->set_userdata('user', $name);
					$this->session->set_flashdata('msg', 'Logged In');
					redirect('get-data');
				} else {
					$this->session->set_flashdata('msg', 'Username or Password Mismatch');
					redirect('', 'refresh');
				}

			} else {
				$data['title'] = 'Login';
				$this->load->view('login', $data);
			}
		} else {
			redirect('get-data', 'refresh');
		}
	}

	public function get_data() {
		if( !$this->session->userdata('user') ) {	redirect('', 'refresh');	}
		$data['title'] = 'Task 1 - Google Sheet';

		$client = $this->fetch_data();
		$spreadsheetId = '1drnqn_5NHXAxJzbULbJ5rnoc_23FI0ae_30uubTGtE4';
		$range = 'Test!A2:D';

		$service = new Google_Service_Sheets($client);
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$data['values'] = $response->getValues();
		$this->load->view('google_data', $data);
	}

	function fetch_data() {
		if( !$this->session->userdata('user') ) {	redirect('', 'refresh');	}
		require 'vendor/autoload.php';

		$client = new Google_Client();
		$client->setApplicationName('Google Sheets API PHP Quickstart');
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$client->setAuthConfig('credentials.json');
		$client->setAccessType('offline');
		// $client->setPrompt('select_account consent');
		return $client;
	}

	public function update_data() {
		if( !$this->session->userdata('user') ) {	redirect('', 'refresh');	}
		$values = [[
					$this->input->post('Name'),
					$this->input->post('Mobile'),
					$this->input->post('Email'),
					$this->input->post('Age')
		],];
		$spreadsheetId = '1drnqn_5NHXAxJzbULbJ5rnoc_23FI0ae_30uubTGtE4';
		$range = 'Test!A'.$this->input->post('row').':D'.$this->input->post('row');
		require 'vendor/autoload.php';
		$body = new Google_Service_Sheets_ValueRange([ 'values' => $values ]);

		$params = [ 'valueInputOption' => 'RAW' ];
		$service = new Google_Service_Sheets($this->fetch_data());
		$result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

		echo json_encode(array('status' => true, 'data' => $result));
	}

	public function send_sms() {
		if( !$this->session->userdata('user') ) {	redirect('', 'refresh');	}
		$data['title'] = 'Task 2 - TWILIO SMS';
		$this->load->view('send_sms', $data);
	}

	public function create_sms() {
		$data = ['phone' => '+91'.$this->input->post('Phone'), 'text' => $this->input->post('Message')];

		// $sid = 'AC0a1483128770f47827c5c78a09aa6628';
		// $token = 'd56b83bce70fd8227491bfa284dce7cf';
		$from = "+16148082075";
		$send = $this->twilio->sms($from, $data['phone'],$data['text']);
		echo json_encode(array(
			'status' => ($send) ? true : false,
			'data' => $send
		));
	}

	public function send_otp() {
		$otp = mt_rand(100000, 999999);
		$data = ['phone' => '+91'.$this->input->post('Mobile'), 'text' => 'Your OTP is '.$otp];
		$from = "+16148082075";
		$send = $this->twilio->sms($from, $data['phone'],$data['text']);
		$data['otp'] = $otp;
		$data['data'] = $send;
		echo json_encode(array(
			'status' => ($send) ? true : false,
			'data' => $data
		));
	}

	public function logout() {
		if( $this->session->unset_userdata('user') ) {
			redirect('', 'refresh');
		} else {
			redirect('get-data', 'refresh');
		}
	}



}
