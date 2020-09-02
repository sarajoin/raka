<?php

$entities_id = _get::int('entities_id');
$entities_info = db_find('app_entities',$entities_id);
$fields_id = _get::int('fields_id');

$reports_type = 'field' . $fields_id . '_entity_item_info_page';
$reports_info_query = db_query("select * from app_reports where entities_id='" . db_input(filter_var($entities_id,FILTER_SANITIZE_STRING)). "' and reports_type='{$reports_type}'");
if(!$reports_info = db_fetch_array($reports_info_query))
{
	$sql_data = array('name'=>'',
			'entities_id'=>$entities_id,
			'reports_type'=>$reports_type,
			'in_menu'=>0,
			'in_dashboard'=>0,
			'created_by'=>0,
	);
	db_perform('app_reports',$sql_data);
	$id = db_insert_id();
	$reports_info = db_find(app_reports,$id);
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

		redirect_to('entities/infopage_entityfield_filters','reports_id=' . filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING) . '&related_entities_id=' . filter_var($_GET['related_entities_id'],FILTER_SANITIZE_STRING) . '&fields_id=' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING));
		break;
	case 'delete':
		if(isset($_GET['id']))
		{

			db_query("delete from app_reports_filters where id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");

			$alerts->add(TEXT_WARN_DELETE_FILTER_SUCCESS,'success');
			 

			redirect_to('entities/infopage_entityfield_filters','reports_id=' . filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING) . '&related_entities_id=' . filter_var($_GET['related_entities_id'],FILTER_SANITIZE_STRING) . '&fields_id=' . filter_var($_GET['fields_id'],FILTER_SANITIZE_STRING));
		}
		break;
}
