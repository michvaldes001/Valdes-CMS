<?php 


//Display photos.  Link photos to thumbnails.  Automatically generate thumbnails if new photos are uploaded. 
class PHOTO_RENDER 
	{

	private function photo_display($photo_directory) 
		{

		if ($diropen = opendir($photo_directory))
			{
		
		
			while (($files = readdir($diropen)) !== false)
				{

				$photo_path = ($photo_directory . "/" . $files);
				$text_file = substr($files, 0, -3) . "txt";
				if (!file_exists($photo_directory . "/thumbnail/" . $files))
					{
					echo "<h1>Thumbnails updating...</h2>";
					$photo_source_image = imagecreatefromjpeg($photo_path);
					$width = imagesx($photo_source_image);
					$height = imagesy($photo_source_image);
					$photo_virtual_image = imagecreatetruecolor(300, 200);
					imagecopyresampled($photo_virtual_image, $photo_source_image, 0, 0, 0, 0, 300, 200, $width, $height);			
					imagejpeg($photo_virtual_image, ($photo_directory. "/thumbnail/" . $files));
					unset($photo_source_image, $photo_virtual_image);
					flush();
					echo "<meta http-equiv='refresh' content='1'>";
					}

				if (!file_exists($photo_directory . "/descriptions/" . $text_file))
					{
					$text_file = ($photo_directory . "/descriptions/" . $text_file); 
					fopen($text_file, 'w');
					fclose($text_file, 'w');
					flush();
					}

				if ($files !== "thumbnail" && $files !== ".." && $files !== "." && $files !== "descriptions")
					{
					$description_text = file_get_contents($photo_directory . "/descriptions/" . $text_file);
					echo "<a href = '" . $photo_path . "'><img src = '" . $photo_directory . "/thumbnail/" . $files . "' alt = '" . $description_text ."'></a>";
					}

			 
				}		

			}	
		closedir($photo_directory);
		}



	private function thumbnail_cleanup($photo_directory) 
		{

		if ($diropen = opendir($photo_directory . "/thumbnails"))
			{
			while (($files = readdir($diropen)) !== false)	
				{
				$photo_path = ($photo_directory . "/thumbnails/" . $files);
				if (!file_exists($photo_directory . "/" . $files) && ($files !== "thumbnail" || $files !== "." || $files !== ".." || $files !== "descriptions"))
					{
					unlink($photo_path);
					}
				}	

			}

		$closedir($photo_directory);
		}

	public function gallery_directory()
		{
		$this -> photo_display('photos/gallery');
		$this -> thumbnail_cleanup('photos/gallery');
		}	


	}



?>
