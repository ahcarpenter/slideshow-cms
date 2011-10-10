<?php
/* 
* FILENAME: albumedit.php
* DESCRIPTION: Albumedits controller
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Albumedit extends Controller{
	
	function index() {
		
		$this->load->model('Albumedit_model'); //loads model
		$this->load->library('session');
		
		if($this->session->userdata('count') != 0) {
			$this->session->set_userdata('albumIndex', $_POST['index']);
			$this->session->set_userdata('count', 0);
		}
		//echo $this->session->userdata('count');
		
		if($this->input->post('upload')){ //if the upload button has been clicked 
			
			$path = $this ->Albumedit_model ->getXMLPath();
			$this->Albumedit_model->do_upload($path); 
		}
		
		if($this->input->post('setAsThumb')){ //if the upload button has been clicked 
			
			$path = $this ->Albumedit_model ->getXMLPath();
			$albumIndex = $this->session->userdata('albumIndex');
			$attribute = 'tn';
			$newAttributeText = $_POST['src'];
			$this->Albumedit_model->updateAlbumAttributes($albumIndex, $attribute, $newAttributeText);
		}
		
		if($this->input->post('delete')){ //if the upload button has been clicked 
			
			$src = $_POST['dSRC'];
			$path = $this ->Albumedit_model ->getXMLPath();
			$path = $path['path'];
			$this->Albumedit_model->deleteIMG($path, $src); //uploads image to /image folder, then resizes image to thumbnail size and places in /images/thumbs/
		}
		
		if($this->input->post('imgTitle')){ //if the upload button has been clicked
			
			$imgSRC = $_POST['imgSRC'];
			$this->session->set_userdata('src', $imgSRC);

			echo form_open('albumedit');
			echo "New Title: ";
			$data = array(
              		'name'        => 'title1',
              		'value'       => $imgSRC['title'],
              		'maxlength'   => '50',
              		'size'        => '50',
              		'style'       => 'width:50%',
            		);	
            echo form_input($data);
            echo form_submit('newTitle', 'Update');
		}
		
		if($this->input->post('newTitle')){
			
			$path = $this ->Albumedit_model ->getXMLPath();
			$path = $path['path'];
			$imgSRC = $this->session->userdata('src');
			$imgSRC = $imgSRC['src'];
			$newAttributeText = $_POST['title1'];
			$attribute = 'title';
			$this->Albumedit_model->updateIMGAttributes($imgSRC, $attribute, $newAttributeText, $path);
		}
	
		if($this->input->post('imgCaption')){ //if the upload button has been clicked
			
			$imgSRC = $_POST['imgSRC'];
			$this->session->set_userdata('src', $imgSRC);
			echo form_open('albumedit');
			echo "New Caption: ";
			$data = array(
              		'name'        => 'caption',
              		'value'       => $imgSRC['caption'],
              		'maxlength'   => '250',
              		'size'        => '50',
              		'style'       => 'width:50%',
            		);
            echo form_input($data);
            echo form_submit('newCaption', 'Update');
			}
			
		if($this->input->post('newCaption')){
			
			$path = $this ->Albumedit_model ->getXMLPath();
			$path = $path['path'];
			$imgSRC = $this->session->userdata('src');
			$imgSRC = $imgSRC['src'];
			$newAttributeText = $_POST['caption'];
			$attribute = 'caption';
			$this->Albumedit_model->updateIMGAttributes($imgSRC, $attribute, $newAttributeText, $path);
		}
			
		if($this->input->post('imgLink')){ //if the upload button has been clicked
			
			$imgSRC = $_POST['imgSRC'];
			$this->session->set_userdata('src', $imgSRC);
			echo form_open('albumedit');
			echo "New Link: ";
			$data = array(
              		'name'        => 'link',
              		'value'       => $imgSRC['link'],
              		'maxlength'   => '250',
              		'size'        => '50',
              		'style'       => 'width:50%',
            		);
            echo form_input($data);
            echo form_submit('newLink', 'Update');
		}
			
		if($this->input->post('newLink')){
			
			$path = $this ->Albumedit_model ->getXMLPath();
			$path = $path['path'];
			$imgSRC = $this->session->userdata('src');
			$imgSRC = $imgSRC['src'];
			$newAttributeText = $_POST['link'];
			$attribute = 'link';
			$this->Albumedit_model->updateIMGAttributes($imgSRC, $attribute, $newAttributeText, $path); 
		}
			
		if($this->input->post('title')){ 
			
			$albumIndex = $this->session->userdata('albumIndex');
			$attribute = 'title';
			$newAttributeText = $_POST['attr'];
			$this->session->set_userdata('albumIndex', $newAttributeText);
			$this->Albumedit_model->updateAlbumAttributes($albumIndex, $attribute, $newAttributeText);		
		}
		
		if($this->input->post('description')){
			
			$albumIndex =  $this->session->userdata('albumIndex'); 
			$attribute = 'description';
			$newAttributeText = $_POST['desc'];
			$this->Albumedit_model->updateAlbumAttributes($albumIndex, $attribute, $newAttributeText);
		}
		
		$data['file_list'] = $this->Albumedit_model->get_XML_files(); //returns an array of xml file names
		$path = $this->Albumedit_model ->getXMLPath();
		$data['path'] = $path;
		$data['image_list'] = $this->Albumedit_model->printAlbumsThumbURLS($path);
		$data['albumIndex'] =$this->session->userdata('albumIndex');
		$this->load->view('albumedit', $data);
	}
}
