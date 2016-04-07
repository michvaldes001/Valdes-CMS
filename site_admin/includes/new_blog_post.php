<?php

//Create new blog post.
class NEW_BLOG_POST
	{
	private function set_year_month_date()
		{
		$set_year = date('Y');
		$set_month = date('F');
		$set_day = date('d');
		return array($set_year, $set_month, $set_day);
		}

	private function verify_directories()
		{
		$date = $this -> set_year_month_date();
		chdir(ROOT_DIRECTORY . '/' . posts);
		if (!file_exists($date[0]))
			{
			mkdir(getcwd() . '/' . $date[0] , 0755);
			chdir($date[0]);
			}
		
		else	
			{	
			chdir($date[0]);
			}

		if (!file_exists($date[1]))
			{	
			mkdir(getcwd() . '/' . $date[1] , 0755);
			chdir($date[1]);
			}
		else
			{	
			chdir($date[1]);
			}		

		if (!file_exists($date[2] . '.txt'))
			{
			$new_post_handle = fopen($date[2] . '.txt' , 'w');
			fclose($new_post_handle);
			}

		}
	public function new_post_generate()
		{
		$this -> verify_directories();
		$date = $this -> set_year_month_date();
		echo FILE_OPS::editor($date[2] . '.txt', ('<h3> Creating/Editing post for: ' . $date[0] . '-' . $date[1] . '-' . $date[2] . '</h3>'));
		}
		


	}

?>
