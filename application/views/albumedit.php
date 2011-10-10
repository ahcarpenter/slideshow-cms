<!-- 
FILENAME: albumedit.php
DESCRIPTION: Provides a view to edit pictures within an album.
DATE: 4/29/2010
WRITTEN BY: Drew Carpenter
 -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Slideshow Management System - Album Gallery</title>
		<style type="text/css">
			#file_select, #file_list, #upload{border: 1px solid #ccc; margin: 10px auto; width: 570px; padding: 10px;}
			.file{font-family: Arial; font-size: 14px; color: white;text-align: left;background-color: gray; margin-top: 10px;}
			#upload {font-family: Arial; font-size: 14px;text-align: left; float: right;}
			#blank_gallery, #centered{font-family: Arial; font-size: 36px; font-weight: bold; position: absolute; right: 1em;top: 1em;}
			.thumb {float: left; width: 320px; height: 370px; padding: 10px; margin: 10px; background-color: #ddd;}
			.thumb:hover {outline: 1px solid #999;}
			img {border: 0;}
			#gallery:after {content: "."; visibility: hidden; display: block; clear: both; height: 0; font-size: 0;}
			#logo {float: left; margin-bottom: 10px;}
			body{background-image: url('Applications/XAMPP/xamppfiles/htdocs/vince/cms/background.png');} 
			#arrow {float:left; text-shadow: inherit; position: relative;}
			#header{text-align: center;}
		</style>
	</head>

<body>
	<div id="logo" class="">		
		<img src="jnh_logo.jpg" alt="Unable to load logo"/>
		</div>
		<div id="centered" class="">
			Current Album
		</div>
	<div id="gallery">
		<?php if (isset($image_list) && count($image_list)):
			foreach($image_list as $image):	?>
			<div class="thumb">
				<a href="<?php echo $image['thumb_url']; ?>">
					<img src="<?php echo $image['thumb_url']; ?>" />
				</a>
			<div id="photo upload" class="file">
			<!--
			<div id="arrow" class="">
				<img src="Picture.png" alt="Unable to load logo"/>
			</div>
			-->

		<?php
				$array = array('title' => $image['title'], 'caption' => $image['caption'], 'src' => $image['src'], 'link' => $image['link']);
				
				echo form_open('albumEdit'); //where form data will be sent
				//echo "Description: " . $image['description']; 
				echo "Title: " . $array['title'];
				echo form_hidden('imgSRC', $image);
				//echo form_upload('userfile'); //name userfile expected by default
	
				echo form_submit('imgTitle', 'Edit Title');
				
				//echo $SUBMIT;
				echo form_close();
				echo form_open('albumEdit'); //where form data will be sent
				//echo "Description: " . $image['description']; 
				echo "Caption: " . $array['caption'];
				
				echo form_hidden('imgSRC', $image);
				//echo form_upload('userfile'); //name userfile expected by default
				echo form_submit('imgCaption', 'Edit Caption');
				
				echo form_open('albumEdit'); //where form data will be sent
				//echo "Description: " . $image['description']; 
				echo "<br>Link: " . $array['link'];
				
				echo form_hidden('imgSRC', $image);
				//echo form_upload('userfile'); //name userfile expected by default
				echo form_submit('imgLink', 'Edit Link');
				//echo $SUBMIT;
				echo form_close();
				echo form_open('albumEdit');
				echo form_hidden('dSRC', $image['src']);
				echo form_submit('delete', 'Delete Slide');
				echo form_close();
				
				echo form_open('albumEdit');
				echo form_hidden('src', $image['src']);
				echo form_submit('setAsThumb', 'Set As Album Thumb');
				echo form_close();
			?>

			
		</div>		
			</div>
		<?php endforeach; else: ?>
			<div id="blank_gallery">Please Upload an Image</div>
		<?php endif; ?>
	</div>
		
		<div id="upload" class="">
				<div id="header" class="">
					<b>Edit Album Details</b><br>
				</div>
			
					<?php
				$path = $path['path'];
				$xml = simplexml_load_file($path); //xml is simpleXML object
				foreach($xml->album as $album)
   				{
     		 		if ($album['title'] == $albumIndex)
      				{			
      					break;
   					}
   				}
				echo form_open('albumEdit'); //where form data will be sent
				echo "Title: ";
				$data = array(
              		'name'        => 'attr',
              		'value'       => $album['title'],
              		'maxlength'   => '50',
              		'size'        => '50',
              		'style'       => 'width:50%',
            	);

				echo form_input($data);
				echo form_submit('title', 'Edit Title');
				echo form_close();
				echo form_open('albumEdit'); //where form data will be sent
				echo "Description: ";
			
				$data = array(
              		'name'        => 'desc',
              		//'id'          => 'username',
              		'value'       => $album['description'],
              		'maxlength'   => '250',
              		'size'        => '50',
              		'style'       => 'width:50%',
            	);

				echo form_input($data);
				echo form_submit('description', 'Edit Description');
				echo form_close();
				
				echo '<br><br>';
				echo "Upload New Slide";
				echo form_open_multipart('albumedit'); //for file upload
				echo form_upload('userfile'); //name userfile expected by default
				echo form_submit('upload', 'Upload');
				echo form_close();
			?>
			</div>	
	
		</div>
	</body>
</html>