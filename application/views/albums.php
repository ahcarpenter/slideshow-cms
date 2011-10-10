<!-- 
FILENAME: albums.php
DESCRIPTION: Provides a view to select an album for editing.
DATE: 4/29/2010
WRITTEN BY: Drew Carpenter
 -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>Slideshow Management System - Album Gallery</title>
		<style type="text/css">
				#file_select, #file_list, #upload{
					border: 1px solid #ccc; margin: 10px auto; width: 570px; padding: 10px;
				}
				.file{
					font-family: Arial; font-size: 14px; color: white; 
					text-align: left;background-color: gray; margin-top: 10px;
				}
				#upload {
					font-family: Arial; font-size: 14px;text-align: center;
				}
				#blank_gallery, #centered{
					font-family: Arial; font-size: 36px; font-weight: bold; position: absolute; right: 1em;top: 1em;	
				}
				.thumb {
					float: left; width: 320px; height: 300px; padding: 10px; margin: 10px; background-color: #ddd;
				}
				.thumb:hover {
					outline: 1px solid #999;
				}
				img {
					border: 0;
				}
				#gallery:after {
					content: "."; visibility: hidden; display: block; clear: both; height: 0; font-size: 0;
				}
				#logo {
					float: left; margin-bottom: 10px;
				}
		</style>
	</head>

	<body>
	<div id="logo" class="">		
		<img src="jnh_logo.jpg" alt="Unable to load logo"/>
		</div>
		<div id="centered" class="">
			Current Albums
		</div>
	<div id="gallery">
		<?php if (isset($image_list) && count($image_list)):
			foreach($image_list as $image):	?>
			<div class="thumb">
				<a href="<?php echo $image['thumb_url']; ?>">
					<img src="<?php echo $image['thumb_url']; ?>" />
				</a>
			
			<div id="photo upload" class="file">
			<?php
				echo form_open('albumEdit'); //where form data will be sent
				echo "Title: " . $image['title'] . "<br>";
				echo "Description: " . $image['description'] . "<br>";
				$data = array(
              		'name'        => 'index',
               		'value'       => $image['title'],
              		'maxlength'   => '50',
              		'size'        => '50',
              		'style'       => 'width:50%',
            	);

				echo form_hidden('index', $image['title']);
				echo form_submit('SUBMIT', 'Review/Edit Album');
				echo form_close();
			?>
			
		</div>		
			</div>
		<?php endforeach; else: ?>
			<div id="blank_gallery">Please Upload an Image</div>
		<?php endif; ?>
	</div>
		
		<div id="upload" class="">
			<div id="arrow" class="">
				Create a new album by uploading its thumbnail.
				<img src="upload.png" alt="Unable to load logo"/>
			

			</div>
			
			<?php 
				echo form_open_multipart('albums'); //for file upload
				echo form_upload('userfile'); //name userfile expected by default
				echo form_submit('upload', 'Upload');
				echo form_close();
			?>
		</div>
	</body>
</html>