
<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="utf-16"/>
<link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
<link rel= "stylesheet" type= "text/css" href= "includes/site_admin.css">
</head>
<div id = "main_container">


<?php

class Main 
	{
	//Initiate session.  Default to HTTPS.  Set server root URL and directory.
	private function initiate_session() 
		{
		if($_SERVER["HTTPS"] != "on")
			{
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    			exit();
			}


		if (session_status() == PHP_SESSION_NONE) 
			{
			session_start();
			}

		define('ROOT_DIRECTORY', $_SERVER['DOCUMENT_ROOT']);
		define('SITE_URL',  "https://" . $_SERVER["HTTP_HOST"]);
		}



	//Include file and folder classes.
	private function get_includes()
		{
		require_once('includes/directory_ops.php');
		require_once('includes/file_ops.php');	
		require_once('includes/new_blog_post.php');		
		}


		
		



	//Log in and execute functions.
	public function authenticate()
		{
		$this -> initiate_session();
		$this -> get_includes();
		$directory_ops = new DIRECTORY_OPS();	
		$file_ops = new FILE_OPS();
		$new_blog_post = new NEW_BLOG_POST(); 
				
		
		$_SESSION['username_login'] = file_get_contents('includes/user.usr');
		$_SESSION['username_password'] = file_get_contents('includes/password.pwd');


		if ($_SESSION['logged_in'] != 1)
			{
			echo '<form id = "login" method = "POST"><br>';
			echo 'Username: <input type = "text" name = "username" id = "username"><br><br>';
			echo 'Password: <input type = "password" name = "password" id = "password"><br><br>';
			echo '<input type = "submit" name = "user_password_submit" id = "user_password_submit">';
			echo '</form>';
			}




		if (isset($_POST['user_password_submit']) || $_SESSION['logged_in'] == 1)
			{	
			if ($_SESSION['first_refresh'] != 1)
				{
				$_SESSION['first_refresh'] = 1;
				$directory_ops -> refresh();
				}
			}
		
		if ($_SESSION['logged_in'] != 1)
			{
			if ($_POST['username'] == $_SESSION['username_login'] && password_verify($_POST['password'], $_SESSION['username_password']))
				{
				$_SESSION['logged_in'] = 1;
				}

			else 
				{
				echo '<h3>Please Enter Username and Password</h3>';
				$_SESSION['logged_in'] = 0;
				session_destroy();
				$file_ops -> refresh();
				}
			}

		if ($_SESSION['logged_in'] == 1)
			{
		
				switch($_SESSION['editor_activate'])
				{

				case 1:
				$file_ops -> editor($_SESSION['working_selected_file'],('<h3>Editing File:' . $_SESSION['working_selected_file'] . '</h3>'));
				break;


				case 2:
				$directory_ops -> change_directory();
				$file_ops -> rename_file($_SESSION['working_selected_file']);
				break;
	

				case 3:
				$directory_ops -> change_directory();
				$file_ops -> upload_file();	
				break;
	

				case 4:
				$directory_ops -> change_directory();
				$file_ops -> new_file();
				break;


				case 5:
				$directory_ops -> change_directory();
				$file_ops -> copy_move_file($_SESSION['working_selected_file']);
				break;
		
				case 6:
				$directory_ops -> change_directory();
				$new_blog_post -> new_post_generate();
				break;
	
				default:
				$directory_ops -> change_directory();
				echo '<br>';
				echo '<h3>Current Directory: ' . getcwd() . '</h3>';
				echo '<br>';
				$directory_ops -> list_directory();
				break;
				}
			}

		}

	}

$initiate = new Main();
$initiate -> authenticate();

?>

</div>
</body>
</html>


