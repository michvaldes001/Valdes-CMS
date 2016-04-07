<html>
<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="utf-16"/>
<link href='https://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>

<link href='includes/layout.css' rel='stylesheet' type='text/css'>


</head>



<body>

<div id = "main_container">


<?php

//Check server for needed modules.
class CHECK_MODULES
	{
	private function check_ssl()
		{
		echo '<br><h3>Checking SSL...</h3>';
		if (empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off')
			{
			echo '<p>SSL not enabled.</p>';
			return 1;
			}

			else 
			{
			echo '<p>Found!</p>';
			if($_SERVER["HTTPS"] != "on")
				{
				header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
				}
			}
		}

	private function check_php_mcrypt()
		{
		echo '<h3>Checking mcrypt...</h3>';
		if (!function_exists("mcrypt_encrypt"))
			{
			echo '<p>Mcrypt is not enabled or installed.</p>';
			return 1;
			}

			else 
			{
			echo '<p>Found!</p>';
			}

		}

	private function check_php_gd()
		{
		echo '<h3>Checking GD...</h3>';
		if (!extension_loaded('gd') && !function_exists('gd_info'))
			{
			echo '<p>GD is not enabled or installed.</p>';
			return 1;
			}

			else 
			{
			echo '<p>Found!</p>';
			}
		}

	public function initiate_module_checks()
			{
			echo '<br><h2>Checking necessary server modules...</h2>';
			$issue_counter = 0;
			$issue_counter = $issue_counter + $this -> check_ssl();
			$issue_counter = $issue_counter + $this -> check_php_mcrypt();
			$issue_counter = $issue_counter + $this -> check_php_gd();
			if ($issue_counter == 0)

				{
				echo '<h3>Server has all necessary modules.  Proceeding with installation.</h3>';
				echo '<p>It is recommended that you log into the site admin page, add a first post and images to your gallery.<p>';
				$generate_password_object = new USER_PASSWORD_GENERATE();
				$generate_password_object -> generate_user_password();
	
				}
			
			else
				{
				echo '<h3>You have ' . $issue_counter . ' missing modules.  Installation cannot continue.</h3>'; 
				}

			}	
	}


//Create username and password.
class USER_PASSWORD_GENERATE 
	{

	public function generate_user_password()
		{
		echo '<form id = "user_password_create" name = "user_password_create" method = "POST">';
		echo 'Enter new user name. <input type = "text" name = "username_create" id = "username_create"><br><br>';
		echo 'Enter new password. <input type = "password" name = "password_create" id = "password_create"><br><br>';
		echo '<input type = "submit" name = "user_password_create" id = "user_password_create">';
		echo '</form>';	
		if (isset($_POST['user_password_create']))
			{

			$this->generate_password($_POST['username_create'], $_POST['password_create']);
			}
		}

	private function generate_password($user_name, $password)
		{
		$salt_generate = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
		$user_file = fopen('site_admin/includes/user.usr', 'w');
		fwrite($user_file, $user_name);
		fclose($user_file);
		$password_hash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt_generate));
		$password_file = fopen('site_admin/includes/password.pwd', 'w');
		fwrite($password_file, $password_hash);
		fclose($password_file);
		$this->check_generated_files($password, $password_hash);
		
		}

	private function check_generated_files($password, $password_hash)
		{
			$file_error = 0;
			if (password_verify($password, $password_hash))
				{
  		 		echo '<h3>Password Created.</h3>';
				}

			else
				{
				$file_error = 1;
				}

		$password_hash_file = file_get_contents('site_admin/includes/password.pwd');
			
			if (password_verify($password, $password_hash_file))
				{
  		 		echo '<h3>Password File Verified.</h3>';	
				}
			
			else
				{
				$file_error = 1;
				}

			if ($file_error == 1)
				{
				echo '<h3>Error creating user or password.  Cannot continue.</h3>';
				}		
			else
				{
				$this -> redirect_cleanup();
				}
		}
			
	private function redirect_cleanup()
		{
		unlink('index.php');
		rename('index.inst', 'index.php');
		echo '<h3>Redirecting to site admin page</h3>';
		header('refresh:3; url=site_admin/site_admin.php'); 
		}	
		
			

	}

		
				
$initiate_installation = new CHECK_MODULES();
$initiate_installation -> initiate_module_checks();


?>
</div>
</div>
</html>
