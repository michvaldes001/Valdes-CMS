


<?php
//Sort and display posts by date.
class POST_RENDER
	{

	public function display_posts()
		{

		foreach (glob("posts/".$_GET['year_id']."/".$_GET['month_id']."/*.txt") as $filename) 
			{
			$filename_display = str_replace('/','&nbsp',$filename);
			$filename_display = substr($filename_display, 0, -4);
    			$filename_display = substr($filename_display, 10);
    			echo "<h2>" . $filename_display . "</h2>";
			echo "<p>";
			echo file_get_contents($filename);
			echo "</p>";
			}
		}



	public function list_posts()
		{
    		$folder_files = scandir("posts/" . $_GET['year_id']);
    		echo '<h3>' . $_GET['year_id'] . '</h3>';
    		if(!is_numeric($_GET['year_id']))
			{
			echo '<h3>Past Posts</h3>';
			}
	    	echo '<ul>';
    		foreach($folder_files as $folder_file)
			{
        		if($folder_file != '.' && $folder_file != '..')
				{
	    			if (is_numeric($folder_file))
					{
			            	echo '<li><a href = "?year_id=' .$folder_file .'">'. $folder_file . '</a>';
			            	echo '</li>';
					}
				else 
					{
			            	echo '<li><a href = "?year_id=' . $_GET["year_id"] . '&month_id=' .$folder_file .'">'. $folder_file . '</a>';
			            	echo '</li>';
					}
			        }
			
			}
    		echo '<li><a href = "?year_id=">Older Posts</a></li>';
    		echo '</ul>';
		}

	}


?>
