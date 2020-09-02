<?php echo ajax_modal_template_header(TEXT_HEADING_COPY) ?>

<?php echo form_tag('form-copy-to', url_for('ext/with_selected/copy','action=copy_selected&reports_id=' . filter_var($_GET['reports_id'],FILTER_SANITIZE_STRING)),array('class'=>'form-horizontal')) ?>

<?php echo input_hidden_tag('redirect_to',$app_redirect_to) ?>

<?php

if(!isset($app_selected_items[$_GET['reports_id']])) $app_selected_items[$_GET['reports_id']] = array();

if(count($app_selected_items[$_GET['reports_id']])==0)
{
  echo '
    <div class="modal-body">    
      <div>' . TEXT_PLEASE_SELECT_ITEMS . '</div>
    </div>    
  ' . ajax_modal_template_footer('hide-save-button');
}
else
{
?>

<div class="modal-body ajax-modal-width-790" >
  <div id="modal-body-content">    
    <p><?php echo TEXT_COPY_CONFIRMATION ?></p>

<?php
  $entity_info = db_find('app_entities',$reports_info['entities_id']);
  if($entity_info['parent_id']>0)
  {              
    $choices = [];
    $selected = '';
    if(isset($_GET['path']))
    {	    	
    	$path_parsed = items::parse_path(filter_var($_GET['path'],FILTER_SANITIZE_STRING));  
        	
    	$choices[$path_parsed['parent_entity_item_id']]  = items::get_heading_field($path_parsed['parent_entity_id'],$path_parsed['parent_entity_item_id']);
    	$selected = $path_parsed['parent_entity_item_id'];
    }
                        
    echo '
      <div class="form-group">
				<label class="col-md-3 control-label" for="settings_copy_comments">' . TEXT_COPY_TO . '</label>
					<div class="col-md-9">
  					' . select_entities_tag('copy_to',$choices,$selected,['entities_id'=>filter_var($entity_info['parent_id'],FILTER_SANITIZE_STRING),'class'=>'form-control required','data-placeholder'=>TEXT_ENTER_VALUE]) . '      			
    			</div>
			</div>
    ';
  }
  
  require(component_path('ext/with_selected/copy_options'));
    
?>  
  </div>
</div> 
<?php echo ajax_modal_template_footer(TEXT_BUTTON_COPY) ?>

<?php } ?>
</form>  

<script>
  $(function(){

    $('#form-copy-to').validate({ignore:'',              
        submitHandler: function(form)
        {           
        	$('button[type=submit]',form).css('display','none')
          $('#modal-body-content').css('visibility','hidden').css('height','1px');             
          $('#modal-body-content').after('<div class="ajax-loading"></div>');      
                    
          $('#modal-body-content').load($(form).attr('action'),$(form).serializeArray(),function(){
            $('.ajax-loading').css('display','none');          
            $('#modal-body-content').css('visibility','visible').css('height','auto');
          })
                    
          return false;                  
        }
    })   
      
  })
</script>