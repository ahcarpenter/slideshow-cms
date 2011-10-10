<?php
/* 
* FILENAME: albums_model.php
* DESCRIPTION: Provides a handful of functions for generating information from an XMl file.
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Albums_model extends Model {

	var $albums_path;
	var $albums_path_url;
	var $xmlPathIndex;
	var $xml;

	function Albums_model(){
	
			parent::Model();
			$this->albums_path = realpath(APPPATH . '../'); //may need an extra ../ to get to the gallery located at the root
			$this->xml_path = realpath(APPPATH . '../xml'); //may need to raise out one more level to get to root
	}
	
	/*
	*	do_upload()
	*		Used here to upload a new album. Creates new directory on server, following the album1, album2,...naming scheme,
	*			then adds new album node to xml file -- setting tn to uploaded image, adds new img node to album, then resizes uploaded image to 320x240 -- overwriting previous file
	*/
	function do_upload($path) {
	
		$path = $path['path'];
		$xml = simplexml_load_file($path); //xml is simpleXML object	
		$this->load->library('ftp');
		$config['hostname'] = 'ftp.mongiardo.com';
		$config['username'] = 'mongia';
		$config['password'] = 'TempDB1';
		$config['port']     = 21;
		$config['debug']    = TRUE;
		$this->ftp->connect($config);
		$dirPath = $this->albums_path . '/gallery/';
		$files = scandir($dirPath); //sets $files to an array of the filenames and folders located at gallery_path
		$files = array_diff($files, array('.','..')); //subtracts unwanted files and folders

		foreach ($files as $file){
		}
		
		$albumNum = substr($file, -1); //returns album num
		$albumNum = $albumNum + 1;
		$file = 'album' . $albumNum;
		$ftpPath = '/www/mongiardo/drew/gallery1/' . $file . '/'; //path needs to be changed  according to vince's path -- possibly /usr/www/htdocs/gallery/
		$this->ftp->mkdir($ftpPath, DIR_WRITE_MODE); 
		$attrPath = 'gallery/' . $file . '/';	
		$album = $xml->addChild('album');
		$album->addAttribute('title', 'add title');
		$album->addAttribute('description', 'add description');
		$album->addAttribute('lgpath', $attrPath);
		$album->addAttribute('tnpath', $attrPath); 
		$arrayPath = '/' . $attrPath;
			
		$config = array( 
					'allowed_types' => 'jpeg|jpg|gif|png', 					
					'upload_path' => $this->albums_path . $arrayPath,
					'max_size' => 2000 //2000 kB = 2MB
				  );
			
		$this->load->library('upload', $config);
		$this->upload->do_upload();
		$image_data = $this->upload->data(); //fetches uploaded images data -- returns an array containing info of upload proce
		$newImg = $album->addChild('img');
		$tnpath = $attrPath . $image_data['file_name'];
		$album->addAttribute('tn', $tnpath);
		$newImg->addAttribute('src', $image_data['file_name']);
		$newImg->addAttribute('title', $image_data['file_name']);
		$newImg->addAttribute('caption', 'Add caption.');
		$output= $xml->asXML(); //converts simplexml object to string
		$file = fopen ($path,"w"); 
		fwrite($file, $output); 
		fclose ($file);
		
		$config = array(
					'source_image' => $image_data['full_path'],
					'new_image' => $this->albums_path . $arrayPath,
					'maintain_ration' => true,
					'width' => 320,
					'height' => 240
				  );	
		
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();		
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
								'path' => $this->albums_path . '/' .  $file,
								'thumb_url' => $this->albums_path_url . 'thumbs/' . $file
							);
		}
		
		return $file_list;
	}

	/*
	*	getXMLPath()
	*		queries the file_list array to find the path of the chosen XML file, then returns that path as an array where array['path'] is the path.
	*
	*/
	function getXMLPath() {
		
		$files = scandir($this->xml_path); //sets $files to an array of the filenames and folders located at gallery_path
		$files = array_diff($files, array('.','..')); //subtracts unwanted files and folders
		$file_list = array();
		
		foreach ($files as $file){
			$file_list [] = array (
								'path' => $this->xml_path . '/' .  $file,
							);
		}
		
		$index = $this->session->userdata('file_index');
		$path = $file_list[$index];
		return $path;
	}

	/*
	*	printAlbumsThumbURLS()
	*		returns an array of information for each image node -- see array definition for information contained therein
	*/
	function printAlbumsThumbURLS($path){;
		
		$path = $path['path'];
		$this->xml = simplexml_load_file($path); //xml is simpleXML object
		$rootNode = $this->xml;
		$image_list = array();
		$index = 0;
		
		foreach($rootNode->children() as $secondTierNode){
			$image_list [] = array(
								'thumb_url' => $secondTierNode['tn'],
								'title' => $secondTierNode['title'],
								'titleText' => 'title',
								'descText' => 'description',
								'description' => $secondTierNode['description'],
								'index' => $index,
							);
			$index = $index + 1;
		 }
		 
		 return $image_list;
	}
	
	/*
	*	updateXML()
	*		outputs new xml information to xml file
	*/
	function updateXML(){ //should occur after the original xml file has been loaded and modified
		$files = scandir($this->xml_path); //sets $files to an array of the filenames and folders located at gallery_path
		$files = array_diff($files, array('.','..')); //subtracts unwanted files and folders
		$file_list = array();
		
		foreach ($files as $file){
			$file_list [] = array (
								'path' => $this->xml_path . '/' .  $file,
							);
		}
		
		$index = $_SESSION['file_index'];
		$path = $file_list[$index];
		$xml = simplexml_load_file($path); //xml is simpleXML object
		$output= $xml->asXML(); //converts simplexml object to string
		$file = fopen ($path,"w"); 
		fwrite($file, $output); 
		fclose ($file);
	}
}
?>