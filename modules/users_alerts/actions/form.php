<?php

$obj = array();

if(isset($_GET['id']))
{
	$obj = db_find('app_users_alerts',filter_var($_GET['id'],FILTER_SANITIZE_STRING));
}
else
{
	$obj = db_show_columns('app_users_alerts');
}
