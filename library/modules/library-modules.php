<?php 
	@include("sessioncheck.php");
	
	/*
		Created By - Muthukumar. D
		Page - library-modules
		Description:
			Show the Tags textbox, New Module and Saved Module(Module name) buttons.
	
		Actions Performed:
			Tag - Used to filter the details
			New Module - Redirects to Module details form - library-modules-newmodule.php
			Save Module - Redirects to Module actions form - library-modules-actions.php
		
		History:
	
	
	*/
	
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	$date=date("Y-m-d");
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]);
			if($id[1]=='module'){				
				$sqry.= " AND a.fld_id =".$id[0];
			}
			else{	
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
				                               WHERE fld_tag_id='".$sid[$i]."' 
											   AND fld_access='1' AND fld_tag_type='3'");
				$sqry = "AND (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" a.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" a.fld_id=".$fld_item_id." or";
					}
					$j++;
				}
			}
		}		
	}
?>

<section data-type='2home' id='library-modules'>
	<!--Script for Tag Well-->
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/modules/library-modules.js');
			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_modules', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=3&module=1" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitAdd',function(bit) {
                fn_loadmodule();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadmodule();
            });					
                
        });	
		
        function fn_loadmodule(){
            var sid = $('#form_tags_modules').val();
            $("#modulelist").load("library/modules/library-modules.php?sid="+sid+" #modulelist > *");
            removesections('#library-modules');
        }
    </script>
  
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Modules</p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>Tag Name and <?php } ?>Module Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">Search by Module name, Tag name, or browse through the Module titles below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_modules" value="" id="form_tags_modules" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="modulelist">
        	<?php 
			if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
                <a class='skip btn mainBtn' href='#library-modules' id='btnlibrary-modules-newmodule' name='0,0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New<br />Module</div>
                </a>
            <?php 
			}
			?>
            <a class='skip btn mainBtnLarge' style="width:210px;" href='http://robo-review.pitsco.com' id='btnlibrary-modules-newmodule' onclick="window.open('http://robo-review.pitsco.com');" name='0,0'>
            	<div class='icon-synergy-add-dark'></div>
                <div class='onBtn'><img src="img/robo_review-link.png" width="210" style="height: 98px;margin-left: -2px;margin-top: -52px;" /></div>
                   
            </a>
            <?php
			if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
						$qry ="SELECT   a.fld_id AS moduleid, CONCAT(a.fld_module_name, ' ', b.fld_version) AS modulename, 
										 fn_shortname (CONCAT(a.fld_module_name, ' ', fld_version), 1) AS shortname 
							  FROM itc_module_master AS a 
							  LEFT JOIN itc_module_version_track AS b  ON fld_mod_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_module_type<>'7' ".$sqry." 
							  ORDER BY a.fld_module_name ASC ";
			}
			else{				
				if($sessmasterprfid==6){ //For District Admin
					$qry = "SELECT b.fld_module_id AS moduleid, CONCAT(a.fld_module_name, ' ', d.fld_version ) AS modulename, 
					                fn_shortname (CONCAT(a.fld_module_name, ' ', d.fld_version), 1) AS shortname 
							FROM  itc_module_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b  ON a.fld_id = b.fld_module_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
							LEFT JOIN   itc_module_version_track  AS d ON a.fld_id=d.fld_mod_id
							WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' 
							AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<='".$date."' AND  d.fld_delstatus = '0'
							AND c.fld_end_date>='".$date."' AND a.fld_module_type<>'7' AND b.fld_type<>'2' ".$sqry." 
							GROUP BY b.fld_module_id 
							ORDER BY a.fld_module_name ASC";
				}
				else{ //For Remaining users
					$qry = "SELECT b.fld_module_id AS moduleid, CONCAT( a.fld_module_name, ' ', fld_version) AS modulename, 
                                   fn_shortname (CONCAT(a.fld_module_name, ' ', fld_version), 1) AS shortname 
                            FROM itc_module_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id = b.fld_module_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                            LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = a.fld_id 
                            WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' 
							AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' AND  d.fld_delstatus = '0'
							AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' AND a.fld_module_type<>'7' AND b.fld_type<>'2' ".$sqry." 
							GROUP BY b.fld_module_id 
							ORDER BY a.fld_module_name ASC";
				}
			}
			
			$qry_for_get_all_modules = $ObjDB->QueryObject($qry);
			while($res=$qry_for_get_all_modules->fetch_assoc()){
				extract($res);

                $contentManager = new contentManager($moduleid, 'mod');
                if($contentManager->disabled) $btn="btnOff";
                else $btn="mainBtn";
				?>
				<a class='skip btn <?=$btn;?>' onclick="checkContent(<?=$moduleid;?>, 'mod')" href='#library-modules' id='btnlibrary-modules-actions' name="<?php echo $moduleid.",".$modulename;?>">
					<div class='icon-synergy-modules'></div>
					<div  class='onBtn tooltip' title="<?php echo $modulename; ?>"><?php echo $shortname; ?></div>
				</a>
				<?php
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");