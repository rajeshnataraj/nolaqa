<?php
/*------
	Page - users
	Description:
		Showing the menus related with users / accounts
	
	History:	
		
------*/
@include("sessioncheck.php");
$menuid= isset($method['id']) ? $method['id'] : '0';
?>
<section data-type='2home' id='users'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="dialogTitle">Users</p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    <div class='row buttons'>
		<?php
		if($sessprofileid == '6'){
	  		$qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id as menuid,a.fld_menu_name as menuname,a.fld_class as class,a.fld_href as href,
												a.fld_hrefid as id,a.fld_divclass as divclass 
											FROM itc_main_menu AS a 
											RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id  
											WHERE b.fld_profile_id='".$sessprofileid."' AND b.fld_access='1' 
												and (a.fld_main_menu_id='".$menuid."' or a.fld_main_menu_id='43') and a.fld_delstatus='0' 
											order by a.fld_position ASC");
		}
		else{
			$qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id as menuid,a.fld_menu_name as menuname,a.fld_class as class,a.fld_href as href,
												a.fld_hrefid as id,a.fld_divclass as divclass 
											FROM itc_main_menu AS a 
											RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id  
											WHERE b.fld_profile_id='".$sessprofileid."' AND b.fld_access='1' 
												and a.fld_main_menu_id='".$menuid."' and a.fld_delstatus='0' 
											order by a.fld_position ASC");
		}
		if($qrymenuname->num_rows>0) {				
                    while($rowmenuname=$qrymenuname->fetch_assoc())
                    {
                            extract($rowmenuname);
                    ?>
                    <a class='<?php echo $class;?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                    <div class='<?php echo $divclass;?>'></div>
                    <div class='onBtn'><?php echo ucfirst($menuname);?></div>
                    </a>
                    <?php
                    }
                }
		?>
      
    </div>
  </div>
</section>
<?php
	@include("footer.php");
