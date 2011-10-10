<?php
/* 
* FILENAME: albumedit_model.php
* DESCRIPTION: Provides a handful of functions for generating information from an XMl file.
* DATE: 4/29/2010
* WRITTEN BY: Drew Carpenter
*/

class Albumedit_model extends Model {
	
	var $albums_path;
	var $albums_path_url;
	var $xmlPathIndex;
	var $xml;
	
	function Albumedit_model(){
	
			parent::Model();
			$this->albums_path = realpath(APPPATH . '../'); //may need an extra ../ to get to the gallery at root
			$this->xml_path = realpath(APPPATH . '../xml'); //may need an extra ../ to get to the gallery at root
	}
	
	/*
	*	do_upload()
	*		Used here to upload a new image to an existing album.
	*		adds new img node to album in XML file, then resizes uploaded image to 320x240 -- overwriting previous file
	*/
	function do_upload($path) {

		$path = $path['path'];
		$xml = simplexml_load_file($path); //xml is simpleXML object
		
		foreach($xml->album as $album){
     		if ($album['title'] == $this->session->userdata('albumIndex')){	
      			$albumPath = $album['lgpath'];
    			break;
      		}
   		}
	
		$config = array(
					'allowed_types' => 'jpeg|jpg|gif|png', 					
					'upload_path' => $this->albums_path . "/" . $albumPath,
					'max_size' => 2000 //2000 kB = 2MB
				  );
			
		$this->load->library('upload', $config);
		$this->upload->do_upload();
		$image_data = $this->upload->data(); //fetches uploaded images data -- returns an array containing info of upload process
		$newImg = $album->addChild('img');
		$newImg->addAttribute('src', $image_data['file_name']);
		$newImg->addAttribute('title', $image_data['file_name']);
		$newImg->addAttribute('caption', 'Add caption.');
		$output= $xml->asXML(); //converts simplexml object to string
		$file = fopen ($path,"w"); 
		fwrite($file, $output); 
		fclose ($file);
				
		$config = array(
					'source_image' => $image_data['full_path'],
					'new_image' => $this->albums_path .  "/" . $albumPath,
					'maintain_ration' => true,
					'width' => 320,
					'height' => 240
				  );	
				  		
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
	}
	
	/*
	*	deleteIMG()
	*		removes specified img node from xml file, and then from the server
	*/
	function deleteIMG($path, $imgSRC) {

		$xml = simplexml_load_file($path); //xml is simpleXML object
		
		foreach($xml->album as $album){
     		if ($album['title'] == $this->session->userdata('albumIndex')){	
      			$albumPath = $album['lgpath'];
    			break;
      		}
   		}
   		
   		foreach($album->img as $img){
     		if ($img['src'] == $imgSRC){	
      			$dom=dom_import_simplexml($img);
        		$dom->parentNode->removeChild($dom);
      			$output= $xml->asXML(); //converts simplexml object to string
				$file = fopen ($path,"w"); 
				fwrite($file, $output); 
				fclose ($file);
    			break;
      		}
   		}
   		
		$this->load->library('ftp');
		$config['hostname'] = 'ftp.mongiardo.com';
		$config['username'] = 'mongia';
		$config['password'] = 'TempDB1';
		$config['port'] = 21;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
		$file = '/www/mongiardo/drew/' . $albumPath . $imgSRC;  //path needs to be changed  according to vince's path -- possibly /usr/www/htdocs/
		$this->ftp->delete_file($file);
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
		$xml = simplexml_load_file($path); //xml is simpleXML object
		$image_list = array();
		$index = 0;
		
		foreach($xml->album as $album){
     		if ($album['title'] == $this->session->userdata('albumIndex')){	
    			break;
      		}
   		}
   		
		foreach($album->children() as $secondTierNode){
			$image_list [] = array(
				'thumb_url' => $album['tnpath'] . $secondTierNode['src'],
				'title' => $secondTierNode['title'],
				'titleText' => 'title',
				'descText' => 'description',
				'description' => $secondTierNode['description'],
				'caption' => $secondTierNode['caption'],
				'index' => $index,
				'src' => $secondTierNode['src'],
				'link' => $secondTierNode['link'],
			);
			$index = $index + 1;
		 }

		 return $image_list;
	}

	/*
	*	updateAlbumAttributes()
	*		updates appropriate album attributes then updates the xml file
	*/
	function updateAlbumAttributes($albumIndex, $attribute, $newAttributeText){
		
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
		$path = $path['path'];
		$xml = simplexml_load_file($path); //xml is simpleXML object
		
		foreach($xml->album as $album){
     		 if ($album['title'] == $albumIndex){	
      			if($attribute == 'title'){
      				$album['title'] = $newAttributeText;
        		 	break;
      			}
      			
      			if($attribute == 'description'){
      				$album['description'] = $newAttributeText;
        		 	break;
      			}
      			
      			if($attribute == 'tn'){
      				$album['tn'] = $album['tnpath'] . $newAttributeText; 
      			}
      		}
   		}
	
		$output= $xml->asXML(); //converts simplexml object to string
		$file = fopen ($path,"w"); 
		fwrite($file, $output); 
		fclose ($file);
	}

	/*
	*	updateIMGAttributes()
	*		updates appropriate img attributes then updates the xml file
	*/
	function updateIMGAttributes($imgSRC, $attribute, $newAttributeText, $path){
		
		$xml = simplexml_load_file($path); //xml is simpleXML object
		
		foreach($xml->album as $album){
     		if ($album['title'] == $this->session->userdata('albumIndex')){	
      			$albumPath = $album['lgpath'];
    			break;
      		}
   		}
   		
   		foreach($album->img as $img){
     		if ($img['src'] == $imgSRC){	
				$img[$attribute] = $newAttributeText;
      			$output= $xml->asXML(); //converts simplexml object to string
				$file = fopen ($path,"w"); 
				fwrite($file, $output); 
				fclose ($file);
    			break;
      		}
   		}
	}
}
?>