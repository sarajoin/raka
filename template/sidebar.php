<div class="page-sidebar-wrapper noprint">
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu">
				<li class="sidebar-toggler-wrapper">
          
<?php
  if(is_file(DIR_FS_UPLOADS  . '/' . CFG_APP_LOGO))
  {
    if(is_image(DIR_FS_UPLOADS  . '/' . CFG_APP_LOGO))
    {
        echo '######1';
      $html = '<img src="uploads/' . CFG_APP_LOGO .  '" border="0" title="' . CFG_APP_NAME . '">';
      
      if(strlen(CFG_APP_LOGO_URL)>0)
      { echo '######2';
        $html = '<div class="logo"><a href="' . CFG_APP_LOGO_URL . '" target="_new">' . $html . '</a></div>';
      }
      else
      {  echo '######3';
        $html = '<div class="logo"><a href="' . url_for('dashboard/') . '">' . $html . '</a></div>';
      }
      
      echo $html;
    }
  }
  
?>          
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<div class="clearfix">
					</div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					
				</li>
        <li>
        
        	<?php         	
	        	if(is_ext_installed())
	        	{
	        		//echo global_search::render('search-form-sidebar');
	        	}
        	?>
        </li>
				
        <?php
          $sidebarMenu = build_main_menu(); 
          echo renderSidebarMenu($sidebarMenu) 
        ?>
                            
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
</div>

