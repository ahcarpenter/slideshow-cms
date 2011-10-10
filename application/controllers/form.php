<?php

class Form extends Controller {
	
	function index()
	{
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');
		$config = array(
               array(
                     'field'   => 'username', 
                     'label'   => 'Username', 
                     'rules'   => 'callback_username_check'
                  ),
            );

		$this->form_validation->set_rules($config);
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('myform');
		}
		else
		{
			$this->load->view('albums');
		}
	}
	
	function username_check($str)
	{
		if ($str == 'test')
		{
			$this->form_validation->set_message('username_check', 'The %s field can not be the word "test');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
?>