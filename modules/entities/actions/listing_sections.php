<?php

$listing_types_id = _get::int('listing_types_id');

$listing_types_query = db_query("select * from app_listing_types where id='" . filter_var(_get::int('listing_types_id'),FILTER_SANITIZE_STRING) . "'");
if(!$listing_types = db_fetch_array($listing_types_query))
{
	redirect_to('entities/listing_types','entities_id=' . filter_var(_get::int('entities_id'),FILTER_SANITIZE_STRING));
}

switch($app_module_action)
{
	case 'save':
		$sql_data = array(
			'listing_types_id' => filter_var($listing_types['id'],FILTER_SANITIZE_STRING),
			'name'=>filter_var($_POST['name'],FILTER_SANITIZE_STRING),		
			'fields' => (isset($_POST['fields']) ? implode(',',filter_var_array($_POST['fields'])) : ''),
			'display_field_names' => (isset($_POST['display_field_names']) ? 1 : 0),
			'sort_order'=>filter_var($_POST['sort_order'],FILTER_SANITIZE_STRING),			
			'text_align' => filter_var($_POST['text_align'],FILTER_SANITIZE_STRING),						
			'display_as' => filter_var($_POST['display_as'],FILTER_SANITIZE_STRING),
			'width' => (isset($_POST['width']) ? filter_var($_POST['width'],FILTER_SANITIZE_STRING) : ''),			
		);

		if(isset($_GET['id']))
		{
			db_perform('app_listing_sections',$sql_data,'update',"id='" . db_input(filter_var($_GET['id'],FILTER_SANITIZE_STRING)) . "'");
		}
		else
		{			
			db_perform('app_listing_sections',$sql_data);			
		}

		redirect_to('entities/listing_sections', 'listing_types_id=' . filter_var($listing_types['id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING));
		break;
	case 'delete':
		if(isset($_GET['id']))
		{			
			db_delete_row('app_listing_sections', _get::int('id'));
			
			redirect_to('entities/listing_sections', 'listing_types_id=' . filter_var($listing_types['id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING));
		}
		break;
	case 'sort':
		$choices_sorted = filter_var($_POST['choices_sorted'],FILTER_SANITIZE_STRING);
	
		if(strlen($choices_sorted)>0)
		{
			$choices_sorted = json_decode(stripslashes($choices_sorted),true);
						
			$sort_order = 1;
			foreach($choices_sorted as $v)
			{
				db_query("update app_listing_sections set sort_order={$sort_order} where id={$v['id']}");
				$sort_order++;
			}
		}
		 
		redirect_to('entities/listing_sections', 'listing_types_id=' . filter_var($listing_types['id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING));
		break;		
}