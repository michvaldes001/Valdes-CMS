<?php

class FILE_OPS
	{
	
	//File editor.
	public function editor($get_file, $message) 
		{
		echo $message;

		$working_file = file_get_contents($get_file);

		echo '<br><form name = "editor_form" method = "POST">';
		echo '<textarea maxlength="20000" id = "editor" name = "editor" rows = "50" cols = "100">' . $working_file . '</textarea><br><br>';
		echo '<input type = "submit" name = "edit_file" id = "edit_file">&nbsp';
		echo '<button type = "submit" name = "cancel" id = "cancel">Cancel</button>';
		echo '</form>';

		if (isset($_POST['edit_file']))
			{
			file_put_contents($get_file, $_POST['editor']);
			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();
			}

		if (isset($_POST['cancel']))
			{
			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();
		 	}

		}
	
	//Delete files.
	public function delete_file($get_file)
	
		{
		echo '<h3>Deleting file: ' . $get_file . '</h3>';

		if (is_dir($get_file))
			{
			rmdir($get_file);
			}

		else 
			{
			unlink($get_file);
			}
		}

	//Rename files.
	public function rename_file($get_file)
		{
		echo '<br>';
		echo '<h3>Renaming file:' . $get_file . '</h3>';
		echo '<form name = "rename" id = "rename" method = "POST">';
		echo '<input type = "text" name = "rename_file" id = "rename_file"><br><br>';
		echo '<input type = "submit" name = "rename_file_submit" id = "rename_file_submit"></form><br>';
	
		if (isset($_POST['rename_file_submit']))
			{
			rename($get_file, (getcwd() . '/' . $_POST['rename_file']));
			echo '<h3>Renaming to: ' . getcwd() . '/' . $_POST['rename_file'] . '</h3>';
			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();
			}
	
		}

	//Upload files. 
	public function upload_file($get_file)
		{ 
		echo '<h3>Uploading to directory: ' . getcwd() . '<h3>';
		echo '<form name = "upload_file" method = "POST" enctype="multipart/form-data"><br><br>';
		echo '<input type = "file" name = "file_upload"><br><br>';
		echo '<input type = "submit" name = "upload_submit"></form>';


		if (isset($_POST['upload_submit']))
			{
			$upload_file_name = $_FILES['file_upload']['name'];
			$upload_file_temp_name = $_FILES['file_upload']['tmp_name'];
			move_uploaded_file($upload_file_temp_name, (getcwd() . '/' . $upload_file_name));
			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();		
			}
	
	}

	//Create new file.
	public function new_file($get_file)
		{
		echo '<br>';
		echo '<h3>Creating file in: ' . getcwd() . '</h3>';
		echo '<form name = "create" id = "create" method = "POST">';
		echo '<input type = "radio" name = "file_create" value = "file">Create File &nbsp&nbsp';
		echo '<input type = "radio" name = "file_create" value = "folder">Create Folder &nbsp&nbsp';
		echo '<input type = "text" name = "create_file" id = "create_file"><br><br>';
		echo '<input type = "submit" name = "create_file_submit" id = "create_file_submit"></form><br>';

		if (isset($_POST['create_file_submit']))
			{
			if ($_POST['file_create'] == 'file')
				{
				$new_file_name = $_POST['create_file'];
				$new_file_handle = fopen($new_file_name, 'w');
				fclose($new_file_handle);
				echo '<h3>Creating file: ' . getcwd() . '/' . $_POST['create_file'] . '</h3>';
				}

			elseif ($_POST['file_create'] == 'folder')
				{
				mkdir(getcwd() . '/' . $_POST['create_file'], 0755);
				}

			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();
			}
	
		}

	//Copy or move files.
	public function copy_move_file($get_file)
		{
		$file_name_only = array_pop(explode('/', $get_file));
		echo '<h3>File to be copied or moved: ' . $get_file . '</h3>'; 
		echo '<form name = "file_copy_move" method = "POST">';
		echo '<input type = "radio" name = "file_copy_move" value = "copy">Copy to&nbsp&nbsp';
		echo '<input type = "radio" name = "file_copy_move" value = "move">Move to &nbsp&nbsp';
		echo '<input type = "submit" name = "copy_move_file">';
	

		$file_list = scandir(getcwd());

       		echo '<ul>';
 
		foreach($file_list as $files)
			{

	       	 	if($files != '.')
				{

				if (is_dir($files))
					{
					echo '<li><a href = "' . SITE_URL . '/site_admin/site_admin.php?dir_change=' . getcwd() . '/'. $files . '"><img src = "file_manager_images/folder.png">&nbsp' . $files. '</a></li>'; 			
					}

	 
				}
			}
		echo '</ul>';
		
			if (isset($_POST['copy_move_file']))
			{
			if ($_POST['file_copy_move'] == 'copy')
				{
				copy($get_file, (getcwd() . '/' . $file_name_only));
				}

			elseif 	($_POST['file_copy_move'] == 'move')
				{
				copy($get_file, (getcwd() . '/' . $file_name_only));
				$this->delete_file($get_file);	
				}
			$_SESSION['editor_activate'] = 0;
			echo DIRECTORY_OPS::refresh();	
			}
	
		}

	}

?>
