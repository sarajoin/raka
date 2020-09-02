
<?php require(component_path('entities/navigation')) ?>

<h3 class="page-title"><?php echo  TEXT_FORMS_FIELDS_DISPLAY_RULES ?></h3>

<p><?php echo TEXT_FORMS_FIELDS_DISPLAY_RULES_INFO ?></p>

<?php echo button_tag(TEXT_BUTTON_ADD_NEW_RULE,url_for('forms_fields_rules/rules_form','entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING)),true) ?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
<thead>
  <tr>
    
    <th><?php echo TEXT_ACTION?></th>
    <th>#</th>    
    <th width="100%"><?php echo TEXT_RULE_FOR_FIELD ?></th>    
    <th><?php echo TEXT_VALUES ?></th>
    <th><?php echo TEXT_DISPLAY_FIELDS ?></th>    
    <th><?php echo TEXT_HIDE_FIELDS ?></th>    
  </tr>
</thead>
<tbody>
<?php
$form_fields_query = db_query("select r.*, f.name, f.type, f.id as fields_id, f.configuration from app_forms_fields_rules r, app_fields f where r.fields_id=f.id and r.entities_id='" . _get::int('entities_id'). "'");

if(db_num_rows($form_fields_query)==0) echo '<tr><td colspan="9">' . TEXT_NO_RECORDS_FOUND. '</td></tr>'; 

while($v = db_fetch_array($form_fields_query)):
?>
<tr>  
  <td style="white-space: nowrap;"><?php echo button_icon_delete(url_for('forms_fields_rules/rules_delete','id=' . filter_var($v['id'],FILTER_SANITIZE_STRING) . '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING))) . ' ' . button_icon_edit(url_for('forms_fields_rules/rules_form','id=' . filter_var($v['id'],FILTER_SANITIZE_STRING). '&entities_id=' . filter_var($_GET['entities_id'],FILTER_SANITIZE_STRING))) ?></td>
  <td><?php echo htmlentities($v['id']) ?></td>
  <td><?php echo fields_types::get_option(filter_var($v['type'],FILTER_SANITIZE_STRING),'name',filter_var($v['name'],FILTER_SANITIZE_STRING)) ?></td>  
  <td>

<?php  
	if(strlen($v['choices']))
	{		
		if(in_array($v['type'],['fieldtype_boolean_checkbox','fieldtype_boolean']))
		{
			foreach(explode(',',$v['choices']) as $id)
			{
				switch($id)
				{
					case 1: 
						echo TEXT_BOOLEAN_TRUE;
					break;
					
					case 2:
						echo TEXT_BOOLEAN_FALSE;
						break;
				}
			}
		}
		elseif($v['type']=='fieldtype_user_accessgroups')
		{
			foreach(explode(',',$v['choices']) as $id)
			{
				echo access_groups::get_name_by_id($id) . '<br>';
			}
		}
		else
		{	
			$cfg = new fields_types_cfg($v['configuration']);
			
			if($cfg->get('use_global_list')>0)
			{
				$choices_query = db_query("select * from app_global_lists_choices where lists_id = '" . db_input(filter_var($cfg->get('use_global_list'),FILTER_SANITIZE_STRING)). "' and id in (" . filter_var($v['choices'],FILTER_SANITIZE_STRING) . ") order by sort_order, name");
			}
			else 
			{
				$choices_query = db_query("select * from app_fields_choices where fields_id = '" . db_input(filter_var($v['fields_id'],FILTER_SANITIZE_STRING)). "' and id in (" . filter_var($v['choices'],FILTER_SANITIZE_STRING) . ") order by sort_order, name");
			}
			
			while($choices = db_fetch_array($choices_query))
			{
				echo htmlentities($choices['name']) . '<br>';
			}
		}
	}
?>

  </td>
  <td>

<?php 
	if(strlen($v['visible_fields']))
	{
		$fields_query = db_query("select * from app_fields where id in (" . filter_var($v['visible_fields'],FILTER_SANITIZE_STRING) . ")");
		while($fields = db_fetch_array($fields_query))
		{
			echo filter_var($fields['name'],FILTER_SANITIZE_STRING) . '<br>';
		}
	} 
?>

	</td>
  <td>

<?php 
	if(strlen($v['hidden_fields']))
	{
		$fields_query = db_query("select * from app_fields  where id in (" . filter_var($v['hidden_fields'],FILTER_SANITIZE_STRING) . ")");
		while($fields = db_fetch_array($fields_query))
		{
			echo filter_var($fields['name'],FILTER_SANITIZE_STRING) . '<br>';
		}
	} 
?>
  
  </td>
     
</tr>  
<?php endwhile ?>
</tbody>
</table>
</div>
