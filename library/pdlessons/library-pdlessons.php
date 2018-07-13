<?php 
/*------
	Page - library-pd
	Description:
		List the pd according to the tag well filter.
	
	Actions Performed:	
		Tag well - Shows the pd in fullscreen
	
	History:	
		no update
------*/

@include("sessioncheck.php");
	
	/*------
		sid = pdlesson id for custom tag	
	------*/
      
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]);
			if($id[1]=='pd'){				
				$sqry.= " and b.fld_id =".$id[0];
			}
			else{	
				$itemqry = $ObjDB->QueryObject("select fld_item_id from itc_main_tag_mapping where fld_tag_id='".$sid[$i]."' and fld_access='1' and fld_tag_type='30'");
				$sqry = "and (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" b.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" b.fld_id=".$fld_item_id." or";
					}
					$j++;
				} // while ends
			} // nested if ends 
		} // for ends		
	} // if ends
?>

<script type="text/javascript" charset="utf-8">		
	$.getScript("library/pdlessons/library-pdlessons-newpd.js");	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_pd', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=30&pdlessons=1" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadpd();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadpd();
		});						
	});	

	function fn_loadpd(){
		var sid = $('#form_tags_pd').val();
		$("#pdlist").load("library/pdlessons/library-pdlessons.php #pdlist > *",{"sid":sid});
		removesections('#library-pdlessons');
	}
</script>

<section data-type='2home' id='library-pdlessons'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">PD lessons</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <!--start of new filter-->
        <div class='row'>
            <div class='twelve columns'>
                <!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterDarkTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid==2 || $sessmasterprfid==3){?>Tag Name, <?php }?>pdlesson Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterDarkTitle"; }else { echo "filterDarkTitle"; } ?>">To see the list of PD Lessons available, search by Lesson name, Tag name, or browse through the options below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_pd" value="" id="form_tags_pd" />
                </div>
            </div>
        </div>
        <!--end of new filter-->
        <div class='row buttons rowspacer' id="pdlist">
        	<?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?><!--  pitscoadmin or Content Admin  -->
            <a class='skip btn mainBtn' href='#library-pdlessons' id='btnlibrary-pdlessons-newpd'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br />pdlesson</div>
            </a>            
            <?php }
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)  //Admin level users
			{			
                             $qry = $ObjDB->QueryObject("SELECT b.fld_id AS pdid, b.fld_pd_icon AS pdicon, CONCAT(b.fld_pd_name,' ',a.fld_version) AS 
                                                                    pdname, fn_shortname(CONCAT(b.fld_pd_name,' ',a.fld_version),1) AS shortname,
                                                                    a.fld_zip_name AS zipname FROM itc_pd_master AS b
                                                                    LEFT JOIN itc_pd_version_track AS a ON b.fld_id=a.fld_pd_id 
                                                                    WHERE a.fld_delstatus = '0' AND a.fld_zip_type='1' AND b.fld_delstatus='0' ".$sqry." 
                                                                    GROUP BY b.fld_id ORDER BY b.fld_pd_name ASC");
			}
			else 
                         {    // other level users
                            
                                if($sessmasterprfid == 5 || $sessmasterprfid == 6 || $sessmasterprfid == 7 || $sessmasterprfid == 8 ){ //For other level Admin
					 $qry = $ObjDB->QueryObject("SELECT a.fld_id AS pdid, CONCAT(a.fld_pd_name,' ',d.fld_version) AS pdname,
                                                                                    fn_shortname(CONCAT(a.fld_pd_name,' ',d.fld_version),1) AS shortname, a.fld_pd_icon AS pdicon,
                                                                                    d.fld_zip_name AS zipname FROM itc_pd_master AS a
                                                                                            LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id = b.fld_pd_id 
                                                                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                                                                                            LEFT JOIN itc_pd_version_track  AS d ON a.fld_id=d.fld_pd_id
                                                                                           
                                                                                    WHERE a.fld_delstatus='0' AND c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' 	
                                                                                    AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' 
                                                                                    AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND  d.fld_delstatus = '0' ".$sqry."  
                                                                                    GROUP BY b.fld_pd_id 
                                                                                    ORDER BY a.fld_pd_name ASC");
                                 
				}
				else{ //For Remaining users
                                    $qry = $ObjDB->QueryObject("SELECT a.fld_id AS pdid, CONCAT(a.fld_pd_name,' ',d.fld_version) AS pdname,
                                                                                    fn_shortname(CONCAT(a.fld_pd_name,' ',d.fld_version),1) AS shortname, a.fld_pd_icon AS pdicon,
                                                                                    d.fld_zip_name AS zipname FROM itc_pd_master AS a
                                                                                            LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id = b.fld_pd_id 
                                                                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                                                                                            LEFT JOIN itc_pd_version_track  AS d ON a.fld_id=d.fld_pd_id
                                                                                           
                                                                                    WHERE a.fld_delstatus='0' AND c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' 	
                                                                                    AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' 
                                                                                    AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND  d.fld_delstatus = '0' ".$sqry."  
                                                                                    GROUP BY b.fld_pd_id 
                                                                                    ORDER BY a.fld_pd_name ASC");
                                 
                                 }
                        }
                         if($qry->num_rows>0){
				while($res=$qry->fetch_assoc()){
					extract($res);
                            ?>  <!-- To display the PD -->
					<a class='skip btn mainBtn' href='#Library-pdlessons' name="<?php echo $pdid.",lesson,".$zipname; ?>" id='btnlibrary-pdlessons-actions'>
                                            <div class='icon-synergy-lessons'><?php if($pdicon!='' and $pdicon!='no-image.png') {?><img class="thumbimg" style="background-color:#fafafa;" src="<?= ITC_URL ?>/thumb.php?src=<?=CONTENT_URL."/pdlessonicon/".$pdicon?>" /><?php }?></div>
						<div class='tooltip onBtn' title="<?php echo $pdname;?>"><?php echo $shortname; ?></div>
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