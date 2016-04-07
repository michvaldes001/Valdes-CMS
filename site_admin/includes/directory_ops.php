<?php

class DIRECTORY_OPS
	{
	
	//List contents of directory and action buttons.
	public function list_directory()
		{
		echo '<h3>Actions: </h3>';
		echo '<form name = "file_actions" method = "POST">';
		echo '<input type = "radio" name = "file_action" value = "delete">Delete&nbsp&nbsp';
		echo '<input type = "radio" name = "file_action" value = "edit" checked = "checked">Edit &nbsp&nbsp';
		echo '<input type = "radio" name = "file_action" value = "rename">Rename &nbsp&nbsp';
		echo '<input type = "radio" name = "file_action" value = "new">New File &nbsp&nbsp';
		echo '<input type = "radio" name = "file_action" value = "copy_move">Copy or Move File &nbsp&nbsp';
		echo '<input type = "radio" name = "file_action" value = "upload">Upload &nbsp&nbsp';
		echo '<input type = "submit" name = "modify_file">&nbsp';
		echo '<button type = "submit" name = "new_post" id = "new_post" >New Post</button>&nbsp';
		echo '<button type = "submit" name = "logout" id = "logout" >Logout</button>';

		$file_list = scandir(getcwd());

       		echo '<ul style = "list-style-type: none;">';

		foreach($file_list as $files)
		{
	        	if($files != '.')
				{

	            		echo '<li><input type = "checkbox" name = "file_select[]" value = "' . getcwd() . '/' .$files . '">';

				if (is_dir($files))
					{
					echo '<a href = "' . SITE_URL . '/site_admin/site_admin.php?dir_change=' . getcwd() . '/'. $files . '"><img src = "file_manager_images/folder.png">&nbsp' . $files. '</a>'; 			
					}

				elseif (substr(strrchr($files,'.'),1) == "jpg" || substr(strrchr($files,'.'),1) == "JPG" || substr(strrchr($files,'.'),1) == "png" || substr(strrchr($files,'.'),1) == "PNG")
					{			
					echo '<a href = "'. str_replace("site_admin","",str_replace(ROOT_DIRECTORY, "", getcwd())) . '/' . $files . '"><img src = "file_manager_images/photo.png">&nbsp' . $files . '</a>';	
					}

				elseif (substr(strrchr($files,'.'),1) == "txt")
					{				
					echo '<a href = "'. str_replace("site_admin","",str_replace(ROOT_DIRECTORY, "", getcwd())) . '/' . $files . '"><img src = "file_manager_images/text.png">&nbsp' . $files . '</a>';	
					}

				else
					{
	            			echo '<img src = "file_manager_images/file.png">&nbsp' . $files;
					}
		

	            		echo '</li>';
	     
				}
			}

    		echo '</ul>';
		echo '</form>';

		if (isset($_POST['new_post']))
			{
			$_SESSION['editor_activate'] = 6;
			$this->refresh();
			}
	


		if (isset($_POST['logout']))
			{
			session_destroy();
			$this->refresh();
			}
	
	
		if (isset($_POST['modify_file']))
			{
			if ($_POST['file_action'] == 'upload')
				{
				$_SESSION['editor_activate'] = 3;
				echo '<h3>Entering upload screen...</h3>';
				$this->refresh();
				}

			if ($_POST['file_action'] == 'new')
				{
				$_SESSION['editor_activate'] = 4;
				echo '<h3>Entering file create screen...</h3>';
				$this->refresh();
				}


			foreach($_POST['file_select'] as $selected_list)
				{
 
				$check_count++;

				if ($_POST['file_action'] == 'edit' && $check_count == 1)
					{
					$_SESSION['working_selected_file'] = $selected_list;
					$_SESSION['editor_activate'] = 1;
					echo '<h3>Editing:' . $selected_list . '...</h3>';
					$this->refresh();
					}


				elseif ($_POST['file_action'] == 'edit' && $check_count > 1)
					{
					echo '<h3>Cannot edit more than one file at a time!  Only first checked file will be used.</h3>';
					}


				elseif ($_POST['file_action'] == 'delete')
					{
					echo FILE_OPS::delete_file($selected_list);
					$this->refresh(); 
					}


				elseif ($_POST['file_action'] == 'rename' && $check_count == 1)
					{
					$_SESSION['working_selected_file'] = $selected_list;
					$_SESSION['editor_activate'] = 2;
					echo '<h3>Entering rename screen...</h3>';
					$this->refresh();
					}

				elseif ($_POST['file_action'] == 'rename' && $check_count > 1)
					{
					echo '<h3>Cannot rename more than one file at a time!</h3>';
					$this->refresh();
					}

				elseif ($_POST['file_action'] == 'copy_move')
					{
					$_SESSION['working_selected_file'] = $selected_list;
					$_SESSION['editor_activate'] = 5;
					echo '<h3>Entering Copy Move Screen...</h3>';
					$this->refresh();
					}

				echo '<br>';
				}		
			}
		}

	//Change working directory.
	public function change_directory()
		{

		if (isset($_GET['dir_change']))
			{
			$directory_to_change = $_GET['dir_change'];
			chdir($directory_to_change);
			}

		else 
			{
			chdir(ROOT_DIRECTORY); 
			}	
		}

	//Refresh page.
	public function refresh()
		{
		echo '<META HTTP-EQUIV="Refresh" Content="1; URL="' . SITE_URL . '/site_admin/site_admin.php>';
		}

	}

?>
