<?php
/* 
* FILENAME: splash_model.php
* DESCRIPTION: Provides a handful of functions for generating information from an XMl file.
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Splash_model extends Model {
	
	var $gallery_path;
	var $gallery_path_url;
	
	function Splash_model(){
	
			parent::Model();
			$this->xml_path = realpath(APPPATH . '../xml'); //change to xml file path
			$this->xml_path_url = base_url() . 'xml/'; //change to to xml file path
	}
	
	/*
	*	get_XML_files()
	*		returns an array of the file names in the xml folder - minus extraneous types
	*
	*/

	function get_XML_files() {
	
		$files = scandir($this->xml_path); //sets $files to an array of the filenames and folders located at gallery_path
		$files = array_diff($files, array('.','..')); //subtracts unwanted files and folders
		$file_list = array();
		
		foreach ($files as $file){
			$file_list [] = array (
								'path' => $this->xml_path . '/' .  $file
							);
		}		
		
		return $file_list;
	}
}
?>