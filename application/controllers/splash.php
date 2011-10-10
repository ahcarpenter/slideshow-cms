<?php
/* 
* FILENAME: splash.php
* DESCRIPTION: Splash's controller
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Splash extends Controller{
	
	function index() {
		$this->load->library('session');
		$this->load->model('Splash_model'); //loads model
		$data['file_list'] = $this->Splash_model->get_XML_files(); //returns an array of xml file names
		$this->session->unset_userdata('file_index');		
		$this->load->view('splash', $data);
		
	}
}
