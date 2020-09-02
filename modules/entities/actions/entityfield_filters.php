<?php

$fields_info_query = db_query("select * from app_fields where id='" . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING). "'");
if(!$fields_info = db_fetch_array($fields_info_query))
{
	redirect_to('entities/fields','entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING));
}	

$cfg = new fields_types_cfg($fields_info['configuration']);

$entity_id = $cfg->get('entity_id');

$reports_type = 'entityfield' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING); 
$reports_info_query = db_query("select * from app_reports where entities_id='" . db_input($entity_id). "' and reports_type='{$reports_type}'");
if(!$reports_info = db_fetch_array($reports_info_query))
{
  $sql_data = array('name'=>'',
                    'entities_id'=>$entity_id,
                    'reports_type'=>$reports_type,                                              
                    'in_menu'=>0,
                    'in_dashboard'=>0,
                    'created_by'=>0,
                    );
  db_perform('app_reports',$sql_data);
  
  redirect_to('entities/entityfield_filters','entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING). '&fields_id=' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING));
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
    $sql_data = array('reports_id'=>filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING),
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
        
    redirect_to('entities/entityfield_filters','reports_id=' . filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING). '&fields_id=' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING));      
  break;
  case 'delete':
      if(isset($_GET['id']))
      {      

        db_query("delete from app_reports_filters where id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");
                            
        $alerts->add(TEXT_WARN_DELETE_FILTER_SUCCESS,'success');
     
                
        redirect_to('entities/entityfield_filters','reports_id=' . filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING). '&fields_id=' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING));  
      }
    break;   
}
