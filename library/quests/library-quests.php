<?php 
	@include("sessioncheck.php");
	
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]);
			if($id[1]=='quest'){				
				$sqry.= " and a.fld_id =".$id[0];
			}
			else{	
				$itemqry = $ObjDB->QueryObject("select fld_item_id from itc_main_tag_mapping where fld_tag_id='".$sid[$i]."' and fld_access='1' and fld_tag_type='25'");
				$sqry = "and (";
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

<section data-type='2home' id='library-quests'>
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/quests/library-quests.js');
			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_quests', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=26&quest=1" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitAdd',function(bit) {
                fn_loadquest();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadquest();
            });					
                
        });	
		
        function fn_loadquest(){
            var sid = $('#form_tags_quests').val();
            $("#questlist").load("library/quests/library-quests.php?sid="+sid+" #questlist > *");
            removesections('#library-quests');
        }
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Quests</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>Tag Name, and <?php } ?>Quest Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">Search by Quest name, Tag name, or browse through the Quest titles below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_quests" value="" id="form_tags_quests" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="questlist">
        	<?php 
			if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
                <a class='skip btn mainBtn' href='#library-quests' id='btnlibrary-quests-newquest' name='0,0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New<br />Quest</div>
                </a>
            <?php 
			}
			if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
				$qry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS modulename, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname, a.fld_id AS moduleid FROM itc_module_master AS a WHERE a.fld_delstatus='0' AND a.fld_module_type='7' ".$sqry." ORDER BY a.fld_module_name ASC");
			}
			else{				
				if($indid!=0){ //For Individual users
					$qry = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, d.fld_module_name as modulename, fn_shortname(d.fld_module_name,1) AS shortname FROM itc_license_track AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_license_id=b.fld_license_id LEFT JOIN itc_module_master AS d ON b.fld_module_id=d.fld_id WHERE a.fld_user_id='".$indid."' AND a.fld_delstatus='0' AND b.fld_active='1'  AND a.fld_start_date<='".date("Y-m-d")."' AND a.fld_end_date>='".date("Y-m-d")."' AND b.fld_type='7' AND d.fld_module_type='7' GROUP BY b.fld_module_id");
				}
				if($sessmasterprfid==6){ //For District Admin
					$qry = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) as modulename, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<='".date("Y-m-d")."' AND c.fld_end_date>='".date("Y-m-d")."' AND a.fld_module_type='7' AND b.fld_type='7' ".$sqry." GROUP BY b.fld_module_id ORDER BY a.fld_module_name ASC");
				}
				else{ //For Remaining users
					$qry = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE
fld_mod_id=a.fld_id AND fld_delstatus='0')) as modulename, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<='".date("Y-m-d")."' AND c.fld_end_date>='".date("Y-m-d")."' AND a.fld_module_type='7' AND b.fld_type='7' ".$sqry." GROUP BY b.fld_module_id ORDER BY a.fld_module_name ASC");
				}
			}
			while($res=$qry->fetch_assoc()){
				extract($res);
				?>
				<a class='skip btn mainBtn' href='#library-quests' id='btnlibrary-quests-actions' name="<?php echo $moduleid.",".$modulename;?>">
					<div class='icon-synergy-modules'></div>
					<div class='onBtn tooltip' title="<?php echo $modulename; ?>"><?php echo $shortname; ?></div>
				</a>
				<?php
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");