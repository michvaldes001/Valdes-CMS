<?php

//Set current date.
$current_year = date("Y");
$current_month = date("F M");
$url_year = $_GET['year_id'];
$current_month = substr($current_month, 0, strrpos($current_month, ' '));

if (!isset($url_year)){

header('Location: ?year_id=' . $current_year . '&month_id=' . $current_month);

}

?>
