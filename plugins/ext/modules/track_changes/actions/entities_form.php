<?php

$obj = array();

if(isset($_GET['id']))
{
	$obj = db_find('app_ext_track_changes_entities',filter_var($_GET['id'],FILTER_SANITIZE_STRING));
}
else
{
	$obj = db_show_columns('app_ext_track_changes_entities');
}