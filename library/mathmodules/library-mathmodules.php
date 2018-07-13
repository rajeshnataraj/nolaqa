<?php 
/*------
	Page - library-Mathmodules
	Description:
		List the mathmodules according to the tag well filter.
	
	Actions Performed:	
		Tag well - Shows the mathmodules in fullscreen
		
	History:	
		
------*/

	@include("sessioncheck.php");
	
	/*------
		sid = mathmoduleid for custom tag	
	------*/
	
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid); // split the id's 
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]); //split the id and conditional name
			if($id[1]=='mathmodule'){				
				$sqry.= " AND a.fld_id =".$id[0];
			}
			else{
				//get mathmodules for the custom tag	
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='23'");
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

<section data-type='2home' id='library-mathmodules'>
	<!--Script for Tag Well-->
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/mathmodules/library-mathmodules.js');
			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_mathmodules', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=23&mathmodule=1" },
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
            var sid = $('#form_tags_mathmodules').val();
            $("#modulelist").load("library/mathmodules/library-mathmodules.php?sid="+sid+" #modulelist > *");
            removesections('#library-mathmodules');
        }
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Math Modules</p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>Tag Name and <?php } ?>Module Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">Search by Module name, Tag name, or browse through the Module titles below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_mathmodules" value="" id="form_tags_mathmodules" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="modulelist">
        	<?php 
			if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
                <a class='skip btn mainBtn' href='#library-mathmodules' id='btnlibrary-mathmodules-newmathmodule' name='0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New Math<br />Module</div>
                </a>
            <?php 
			}
			?>
            <a class='skip btn mainBtnLarge' style="width:210px;" href='#library-mathmodules' onclick="window.open('http://robo-review.pitsco.com');">
            	<div class='icon-synergy-add-dark'></div>
                <div class='onBtn'><img src="img/robo_review-link.png" width="210" style="height: 98px;margin-left: -2px;margin-top: -52px;" /></div>
            </a>
            <?php
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)   //For Pitsco & Content Admin
			{ 
				   $qrymathmodulename = $ObjDB->QueryObject("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS mathmodulename,
				   													fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.fld_version),1) AS shortname, 
																	a.fld_id AS mathmoduleid, a.fld_module_id AS moduleid 
															FROM itc_mathmodule_master  a 
															LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
															WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry." ORDER BY a.fld_mathmodule_name ASC");
			}
			else{				
				
				if($sessmasterprfid==6)   //For District Admin
				{ 
					$qrymathmodulename = $ObjDB->QueryObject("SELECT c.fld_module_id as mathmoduleid, 
										 CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS mathmodulename,
				   						 fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.fld_version),1) AS shortname 
									FROM itc_mathmodule_master AS a 
										 LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
										 LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id
										 LEFT JOIN itc_license_track AS d ON c.fld_license_id=d.fld_license_id 
									WHERE a.fld_delstatus='0' AND d.fld_district_id='".$sendistid."' AND d.fld_school_id='0' AND d.fld_delstatus='0'                                         AND c.fld_active='1' AND c.fld_type='2' AND d.fld_start_date<='".date('Y-m-d')."' AND  b.fld_delstatus = '0' AND 
										 d.fld_end_date>='".date('Y-m-d')."' ".$sqry." 
										 GROUP BY c.fld_module_id ORDER BY a.fld_mathmodule_name ASC");
				}
				else  //For Remaining users
				{ 
					
					$qrymathmodulename = $ObjDB->QueryObject("SELECT c.fld_module_id as mathmoduleid, 
					                     CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS mathmodulename,
				   						 fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.fld_version),1) AS shortname  
									FROM itc_mathmodule_master AS a 
										 LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
										 LEFT JOIN itc_license_mod_mapping AS c ON a.fld_id=c.fld_module_id
										 LEFT JOIN itc_license_track AS d ON c.fld_license_id=d.fld_license_id 
								    WHERE a.fld_delstatus='0' AND d.fld_school_id='".$schoolid."' AND d.fld_user_id='".$indid."' AND d.fld_delstatus='0'                                         AND c.fld_active='1' AND c.fld_type='2' AND d.fld_start_date<='".date('Y-m-d')."' AND  b.fld_delstatus = '0' AND 
										 d.fld_end_date>='".date('Y-m-d')."' ".$sqry." GROUP BY c.fld_module_id ORDER BY a.fld_mathmodule_name ASC");
				}
			}
			while($res=$qrymathmodulename->fetch_assoc()){
				extract($res);

                $contentManager = new contentManager($mathmoduleid, 'mathmod');
                if($contentManager->disabled) $btn="btnOff";
                else $btn="mainBtn";
				?>
				<a class='skip btn mainBtn' onclick="checkContent(<?=$mathmoduleid;?>, 'mathmod')"  href='#library-mathmodules' id='btnlibrary-mathmodules-actions' name="<?php echo $mathmoduleid.",".$mathmodulename;?>">
					<div class='icon-synergy-modules'></div>
					<div class='onBtn tooltip' title="<?php echo $mathmodulename; ?>"><?php echo $shortname; ?></div>
				</a>
				<?php
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");