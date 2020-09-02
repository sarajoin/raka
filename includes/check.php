<?php
  $error_list = array();
     
//check gd lib
  if (!extension_loaded('gd') or !function_exists('gd_info')) 
  {
    $error_list[] = "PHP GD library is NOT installed on your web server";
  }
  
//check mbstring  
  if (!extension_loaded('mbstring')) { 
  	$error_list[] = "PHP mbstring extension is NOT installed on your web server";
  }
  
//check mbstring
  if (!extension_loaded('xmlwriter')) {
  	$error_list[] = "PHP XMLWriter extension is NOT installed on your web server";
  } 
  
//check curl  
  if(!extension_loaded("curl"))
  {
  	$error_list[] = "PHP CURL extension is NOT installed on your web server";
  }
  
//check zip
  if(!extension_loaded("zip"))
  {
  	$error_list[] = "PHP ZIP extension is NOT installed on your web server";
  } 
  
//check zip
  if(!extension_loaded("xml"))
  {
  	$error_list[] = "PHP XML extension is NOT installed on your web server";
  }  
  
//check folder
  $check_folders = array('config','backups','log','tmp','uploads','uploads/attachments','uploads/users','cache');
  
  foreach($check_folders as $v)
  {
    if(is_dir($v))
    {
      if(!is_writable($v))
      {
        $error_list[] = sprintf('Error: folder "%s" is not writable!',dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $v);
      }
    }
    else
    {
      $error_list[] = sprintf('Error: folder "%s" does not exist',dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $v);
    }
  }

//dispaly errors if exist  
  if(count($error_list))
  {
    echo '<p>Please fix following errors.</p>';
    foreach($error_list as $v)
    {
      echo '<div>' . $v . '</div>';
    }
        
    exit();
  }