<?php 

$entity_info = db_find('app_entities',$current_reports_info['entities_id']);

//check if parent reports was not set
if($entity_info['parent_id']>0 and $current_reports_info['parent_id']==0)
{
  reports::auto_create_parent_reports($current_reports_info['id']);
}

$reports_list[] = $current_reports_info['id'];
$reports_list = reports::get_parent_reports($current_reports_info['id'],$reports_list);

if($current_reports_info['reports_type']=='entity_menu')
{
  $entity_cfg = entities::get_cfg($current_reports_info['entities_id']);
  $page_title = (strlen($entity_cfg['menu_title'])>0 ? $entity_cfg['menu_title'] : $entities['name']);
}
else
{
  $page_title = $current_reports_info['name'];
}
?>

<h3 class="page-title"><?php echo TEXT_HEADING_FILTERS_FOR_REPORT  . ' ' . link_to($page_title,url_for('ext/common_reports/reports')) ?></h3>

<?php
foreach($reports_list as $reports_id)
{

$report_info = db_find('app_reports',$reports_id);
$entity_info = db_find('app_entities',$report_info['entities_id']);

$parent_reports_param = '';
if($current_reports_info['id']!=$reports_id)
{
  $parent_reports_param = '&parent_reports_id=' . $reports_id;    
}
?>

<div class="panel panel-default">
  <div class="panel-heading"><?php echo TEXT_FILTERS_FOR_ENTITY . ': <b>' . filter_var($entity_info['name'],FILTER_SANITIZE_STRING) . '</b>' ?></div>
    <div class="panel-body"> 

    <?php echo button_tag(TEXT_BUTTON_ADD_NEW_REPORT_FILTER,url_for('ext/common_reports/filters_form','reports_id=' . filter_var($current_reports_info['id'],FILTER_SANITIZE_STRING) . $parent_reports_param)) ?>
    
    <div class="table-scrollable">
    <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th><?php echo TEXT_ACTION ?></th>        
        <th width="100%"><?php echo TEXT_FIELD ?></th>
        <th><?php echo TEXT_FILTERS_CONDITION ?></th>
        <th><?php echo TEXT_VALUES ?></th>
                
      </tr>
    </thead>
    <tbody>
    <?php if(db_count('app_reports_filters',$reports_id,'reports_id')==0) echo '<tr><td colspan="5">' . TEXT_NO_RECORDS_FOUND. '</td></tr>'; ?>
    <?php  
      $filters_query = db_query("select rf.*, f.name, f.type from app_reports_filters rf left join app_fields f on rf.fields_id=f.id where rf.reports_id='" . db_input($reports_id) . "' order by rf.id");
      while($v = db_fetch_array($filters_query)):
    ?>
      <tr>
        <td style="white-space: nowrap;"><?php echo button_icon_delete(url_for('ext/common_reports/filters_delete','id=' . htmlentities(filter_var($v['id'],FILTER_SANITIZE_STRING)) . '&reports_id=' . $current_reports_info['id'] . $parent_reports_param)) . ' ' . button_icon_edit(url_for('ext/common_reports/filters_form','id=' . htmlentities(filter_var($v['id'],FILTER_SANITIZE_STRING)) . '&reports_id=' . $current_reports_info['id'] . $parent_reports_param))  ?></td>    
        <td><?php echo fields_types::get_option(filter_var($v['type'],FILTER_SANITIZE_STRING),'name',filter_var($v['name'],FILTER_SANITIZE_STRING)) ?></td>
        <td><?php echo reports::get_condition_name_by_key(filter_var($v['filters_condition'],FILTER_SANITIZE_STRING)) ?></td>
        <td class="nowrap"><?php echo reports::render_filters_values(filter_var($v['fields_id'],FILTER_SANITIZE_STRING),filter_var($v['filters_values'],FILTER_SANITIZE_STRING),'<br>',filter_var($v['filters_condition'],FILTER_SANITIZE_STRING)) ?></td>            
      </tr>
    <?php endwhile?>  
    </tbody>
    </table>
    </div>
    
  </div>
</div>  
  
<?php } ?>  

<?php echo '<a class="btn btn-default" href="' . url_for('ext/common_reports/reports') . '">' . TEXT_BUTTON_BACK. '</a>'; ?>  


