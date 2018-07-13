<?php
@include("sessioncheck.php");
$menuid= isset($method['id']) ? $method['id'] : '';
?>
<section data-type='#users' id='users-individuals'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">Individuals</p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    <div class='row buttons'>
      <?php
	  	$qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id AS menuid, a.fld_menu_name AS menuname, a.fld_class AS class, a.fld_href AS href, 
											a.fld_hrefid AS id, a.fld_divclass AS divclass 
										FROM itc_main_menu AS a 
										RIGHT JOIN itc_menu_privileges AS b ON b.fld_profile_id=".$sessprofileid." AND a.fld_id=b.fld_menu_id AND b.fld_access='1' 
										WHERE a.fld_main_menu_id='".$menuid."' and a.fld_delstatus='0' and b.fld_delstatus='0' 
										ORDER BY a.fld_position ASC");
				
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
		?>
    </div>
  </div>
</section>
<?php
	@include("footer.php");
