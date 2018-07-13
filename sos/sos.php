<?php
@include("sessioncheck.php");

$menuid= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$iplcount=0;
$modulecount=0;

?>
<section data-type='2home' id='sos'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="lightTitle">Science of Speed</p>
                <p class="lightSubTitle">Choose from one of the options below.</p>
            </div>
        </div>
        <div class='row buttons'>
			<?php
                        
            $qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id ,a.fld_menu_name, a.fld_class, a.fld_href, a.fld_hrefid, a.fld_divclass FROM itc_main_menu AS a RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id WHERE b.fld_profile_id=".$sessprofileid." AND b.fld_access='1' and a.fld_main_menu_id='".$menuid."' and a.fld_delstatus=0 and b.fld_delstatus=0 order by a.fld_position ASC");
            
            while($rowmenuname=$qrymenuname->fetch_object())
            {
				
				$menuname=$rowmenuname->fld_menu_name;
				$menuid=$rowmenuname->fld_id;
				$class=$rowmenuname->fld_class;
				$href=$rowmenuname->fld_href;
				$id=$rowmenuname->fld_hrefid;
				$divclass=$rowmenuname->fld_divclass;
				
                                if($menuid == 65)
					$sosunitcount = $ObjDB->SelectSingleValueInt("SELECT 
                                                                                    count(a.fld_unit_id)
                                                                                FROM
                                                                                    itc_license_sosunit_mapping AS a
                                                                                        LEFT JOIN
                                                                                    itc_license_track AS b ON a.fld_license_id = b.fld_license_id
                                                                                WHERE
                                                                                    b.fld_school_id = '".$schoolid."'
                                                                                        AND b.fld_district_id = '".$districtid."'
                                                                                        AND b.fld_user_id = '".$indid."'
                                                                                        AND b.fld_delstatus = '0'
                                                                                        AND a.fld_access = '1'");
                                if($sosunitcount!=0 or $menuid == 66 or $menuid == 67){
                                
				?>
                                        <a class='<?php echo $class;?>' href='<?php echo $href; ?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                                                <div class='<?php echo $divclass;?>'></div>
                                                <div class='onBtn'><?php echo ucfirst($menuname);?></div>
                                        </a>
                                <?php
            }
            }
                       
            ?>
            
            <a class='skip btn main' href='http://www.science-of-speed.com' onClick="window.open('http://www.science-of-speed.com','_blank');" target="_blank" >
                                            <div class='icon-synergy-help-a'></div>
                                        <div class='onBtn'>Science of speed</div>
            </a>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
