<!-- 
FILENAME: albums.php
DESCRIPTION: Provides a view to select an XML file for editing.
DATE: 4/29/2010
WRITTEN BY: Drew Carpenter
 -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Jimmy Nash Homes - Slideshow Management System</title>
		<style type="text/css">
			#file_select, #file_list{
				border: 1px solid #ccc; margin: 10px auto; width: 570px; padding: 10px;
			}
			.file {
				clear: both;
				width: 540px;
				padding: 10px;
				margin-right: auto;
				margin-left: auto;
				margin-bottom: 10px;
				margin-top: 10px;
				color: white;
				font-family: Arial; font-size: 14px; 
				text-align: left;background-color: gray;
			}
			.upload {
				font-family: Arial; font-size: 14px;text-align: center;
			}
			#blank_gallery, #centered {
				font-family: Arial; font-size: 36px; font-weight: bold; position: absolute; right: 1em;top: 1em;	
			}
			.thumb {
				float: left; width: 150px; height: 100px; padding: 10px; margin: 10px; background-color: #ddd;
			}
			.thumb:hover {
				outline: 1px solid #999;
			}
			img {
				border: 0;
			}
			#xml_file_list:after {
				content: "."; visibility: hidden; display: block; clear: both; height: 0; font-size: 0;
			}
			#logo {
				float: left; margin-bottom: 10px;
			}
			#xml_logo {
				float: left;
			}
		</style>
	</head>

	<body>
		<div id="logo" class="">		
			<img src="jnh_logo.jpg" alt="Unable to load logo"/>
		</div>
		
		<div id="centered" class="">
			XML File Select
		</div>
				
		<div id="xml_file_list" class="file_box"> 
			<?php $index = 0; if (isset($file_list) && count($file_list)):
				foreach($file_list as $file): ?>
				
				<div id="file_list" class = "file">
					<?php echo '[File #' . $index . ']	' . $file['path']; $index = $index + 1;//write to xml by adding each file as child?>
				</div>
				
			<?php endforeach; else: ?>
				<div id="empty_directory" class="">Missing XML Files</div>
			<?php endif; ?>
		</div>

		<div id="file_select" class="upload">	
			<?php
				echo validation_errors();
				echo form_open('albums'); //where form data will be sent
				echo "Enter the File # to edit: "; 
				$pathValue = set_value('path');
				echo form_input('path', $pathValue);
				echo form_submit('SUBMIT', 'Edit');
				echo form_close();
			?>
		</div>		
	</body>
</html>