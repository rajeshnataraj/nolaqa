<?php 
	@include("sessioncheck.php");
	
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	$date=date("Y-m-d");// H:i:s
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]);
			if($id[1]=='expedition'){				
				$sqry.= " AND a.fld_id =".$id[0];
			}
			else{	
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
				                               WHERE fld_tag_id='".$sid[$i]."' 
											   AND fld_access='1' AND fld_tag_type='28'");
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

<section data-type='2home' id='library-expedition'>
	<!--Script for Tag Well-->
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/expedition/library-expedition.js');
                $.getScript('library/expedition/expedition.js');
			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_expedition', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=28&expedition=1" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });																	
            
            t4.addEvent('bitBoxAdd',function(bit) {
                fn_loadmodule();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadmodule();
            });					
                
        });	
		
        function fn_loadmodule(){
            var sid = $('#form_tags_expedition').val();
            $("#expeditionlist").load("library/expedition/library-expedition.php?sid="+sid+" #expeditionlist > *");
        }
    </script>
  
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Expeditions</p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by <?php if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>Tag Name and <?php } ?>Expedition Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">Search by Expedition name, or browse through the available Expeditions below. </p>
                <div class="tag_well">
                	<input type="text" name="form_tags_expedition" value="" id="form_tags_expedition" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="expeditionlist">
        	<?php 
			if($sessmasterprfid == 2 || $sessmasterprfid == 3) { ?>
                <a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-newexpedition' name='0,0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New<br />Expedition</div>
                </a>
            <?php 
			}

			if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
						$qry ="SELECT a.fld_id AS expid, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS expname,fld_stem_exp as exptype1, 
										 fn_shortname (CONCAT(a.fld_exp_name, ' ', b.fld_version), 1) AS shortname 
							  FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' ".$sqry." 
							  ORDER BY a.fld_exp_name ASC ";
			}
			else{				
				if($sessmasterprfid==6){ //For District Admin
					$qry = "SELECT a.fld_id AS expid, CONCAT(a.fld_exp_name, ' ', d.fld_version ) AS expname,fld_stem_exp as exptype1, 
					                fn_shortname (CONCAT(a.fld_exp_name, ' ', d.fld_version), 1) AS shortname 
							FROM  itc_exp_master AS a 
							LEFT JOIN itc_license_exp_mapping AS b  ON a.fld_id = b.fld_exp_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
							LEFT JOIN itc_exp_version_track  AS d ON a.fld_id=d.fld_exp_id
							WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' 
							AND c.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_start_date<='".$date."' 
							AND c.fld_end_date>='".$date."' ".$sqry." 
							GROUP BY a.fld_id
							ORDER BY a.fld_exp_name ASC";
				}
				else{ //For Remaining users
					$qry = "SELECT a.fld_id AS expid, CONCAT( a.fld_exp_name, ' ', fld_version) AS expname,fld_stem_exp as exptype1, 
                                   fn_shortname (CONCAT(a.fld_exp_name, ' ', fld_version), 1) AS shortname 
                            FROM itc_exp_master AS a 
							LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_exp_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                            LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = a.fld_id 
                            WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_school_id='".$schoolid."' 
							AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_flag='1' 
							AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' ".$sqry." 
							GROUP BY a.fld_id
							ORDER BY a.fld_exp_name ASC";
				}
			}
			
			$qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
			while($res=$qry_for_get_all_expedition->fetch_assoc()){
				extract($res);
				?>
				<a class='skip btn mainBtn' href='#library-expedition' id='btnlibrary-expedition-actions' name="<?php echo $expid;?>">
                                    <?php if($exptype1==0){?>
                                        <div class='icon-expeditions'></div>
                                    <?php } ?>
                                    <?php if($exptype1==1){?>
                                        <div class='icon-STEM_expeditions'></div>
                                    <?php } ?>
                                    <?php if($exptype1==2){?>
                                        <div class='icon-MATH_expeditions'></div>
                                    <?php } ?>
					
					<div  class='onBtn tooltip' title="<?php echo $expname; ?>"><?php echo $shortname; ?></div>
				</a>
				<?php
            }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");