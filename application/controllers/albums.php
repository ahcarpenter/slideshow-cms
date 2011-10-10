<?php
/* 
* FILENAME: albums.php
* DESCRIPTION: Albums controller
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Albums extends Controller{
	
	function index() {
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->load->model('Albums_model'); //loads model
		
		$config = array(
               array(
                     'field'   => 'path', 
                     'label'   => 'Path', 
                     'rules'   => 'callback_path_check'
                  ),
            );

		$this->form_validation->set_rules($config);
		
		if (($this->form_validation->run() == FALSE))
		{
				$data['file_list'] = $this->Albums_model->get_XML_files(); //returns an array of xml file names
				$this->session->set_userdata('count', 0);
				$this->load->view('splash', $data);
		}
		
		else
		{
			if($this->session->userdata('count') ==  1){
				$data['file_list'] = $this->Albums_model->get_XML_files(); //returns an array of xml file names
				$path = $this ->Albums_model ->getXMLPath();
				$data['image_list'] = $this->Albums_model->printAlbumsThumbURLS($path);
				$this->load->view('albums', $data);
			}
			else{
				$this->session->set_userdata('file_index', $_POST['path']);
				$this->session->set_userdata('count', 1);				
				$data['file_list'] = $this->Albums_model->get_XML_files(); //returns an array of xml file names
				$path = $this ->Albums_model ->getXMLPath();
				$data['image_list'] = $this->Albums_model->printAlbumsThumbURLS($path);
				$this->load->view('albums', $data);
			}
		}
		
		if($this->input->post('upload')){
			
			$path = $this->Albums_model->getXMLPath();
			$this->Albums_model->do_upload($path); 
		}
	}
	
	function path_check($str)
	{
		$file_array = $this->Albums_model->get_XML_files();
		$size = sizeof($file_array);
		$size = $size -1;
		if (($str > $size) || ($str < 0))
		{
			$this->form_validation->set_message('path_check', 'The %s field requires a valid file number.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
