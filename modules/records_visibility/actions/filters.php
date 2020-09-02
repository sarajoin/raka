<?php

$current_rules_info_query = db_query("select * from app_records_visibility_rules where id='" . db_input(filter_var($_GET['rules_id'],FILTER_SANITIZE_STRING)). "'");
if(!$current_rules_info = db_fetch_array($current_rules_info_query))
{
  $alerts->add(TEXT_RECORD_NOT_FOUND,'error');
  redirect_to('records_visibility/rules','entities_id=' . _get::int('entities_id'));
}


$current_reports_info_query = db_query("select * from app_reports where entities_id='" . $current_rules_info['entities_id'] . "' and reports_type='records_visibility" . db_input($current_rules_info['id']). "'");
if(!$current_reports_info = db_fetch_array($current_reports_info_query))
{
   //atuo create report 
      $sql_reports_data = array(
      								 'name'=>'',
                       'entities_id'=>$current_rules_info['entities_id'],
                       'reports_type'=>'records_visibility' . $current_rules_info['id'],                                              
                       'in_menu'=>0,
                       'in_dashboard'=>0,
                       'listing_order_fields'=>'',
                       'created_by'=>$app_logged_users_id,
                       );
                   
      db_perform('app_reports',$sql_reports_data);
      $reports_id = db_insert_id();
                  
      $current_reports_info = db_find('app_reports',$reports_id);
}

switch($app_module_action)
{
  case 'save':
    
    $values = '';
    
    if(isset($_POST['values']))
    {
      if(is_array($_POST['values']))
      {
        $values = implode(',',filter_var_array($_POST['values']));
      }
      else
      {
        $values = filter_var($_POST['values'],FILTER_SANITIZE_STRING);
      }
    }
    $sql_data = array('reports_id'=>(isset($_GET['parent_reports_id']) ? filter_var($_GET['parent_reports_id'],FILTER_SANITIZE_STRING):filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING)),
                      'fields_id'=>filter_var($_POST['fields_id'],FILTER_SANITIZE_STRING),
                      'filters_condition'=>filter_var($_POST['filters_condition'],FILTER_SANITIZE_STRING),                                              
                      'filters_values'=>$values,
                      );
        
    if(isset($_GET['id']))
    {        
      db_perform('app_reports_filters',$sql_data,'update',"id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");       
    }
    else
    {               
      db_perform('app_reports_filters',$sql_data);                  
    }
    
    redirect_to('records_visibility/filters','rules_id=' . $_GET['rules_id'] . '&entities_id=' . _get::int('entities_id'));
        
          
  break;
  case 'delete':
      if(isset($_GET['id']))
      {      
        db_query("delete from app_reports_filters where id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");
        
        redirect_to('records_visibility/filters','rules_id=' . $_GET['rules_id']. '&entities_id=' . _get::int('entities_id'));                  
      }
    break;   
}