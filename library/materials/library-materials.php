<?php 
/*
 * created by - Vijayalakshmi PHP programmer
 * creating (CRUD APPLN for Materialsl
 */
@include("sessioncheck.php");
$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';

$sqry='';
/* filtering for tag and material name in search text box **/
if($sid!=0){
	$sid = explode(',',$sid);
	for($i=0;$i<sizeof($sid);$i++){
		$ids = explode('_',$sid[$i]);
		if($ids[1]=='material'){
			$sqry.= " and U.fld_id =".$ids[0];
		}
		else{
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
			                                WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' 
											AND fld_tag_type='27'");
			$sqry.= " AND (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" U.fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" U.fld_id=".$fld_item_id." or ";
				}
				$j++;
			} // while ends
		} // nested if ends 
	}// for ends
}// if ends
?>

<section data-type='2home' id='library-materials'>
	<!--Script for Tag Well-->
	<script type="text/javascript" charset="utf-8">	
		$.getScript('library/materials/library-materials.js');
               			
        $(function(){				
            var t4 = new $.TextboxList('#form_tags_materials', {
                startEditableBit: false,
                inBetweenEditableBits: false,
                plugins: {
                    autocomplete: {
                        onlyFromValues:true,
                        queryRemote: true,
                        remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=27&material=1" },
                        placeholder: ''
                    }
                },
                bitsOptions:{editable:{addKeys: [188]}}													
            });	
            
            
            
            t4.addEvent('bitBoxAdd',function(bit) {
              
                fn_loadmaterial();
            });
            
            t4.addEvent('bitRemove',function(bit) {
                fn_loadmaterial();
            });					
                
        });	
	
         function fn_loadmaterial(){
           
            var sid = $('#form_tags_materials').val();
            $("#materialslist").load("library/materials/library-materials.php #materialslist > *",{'sid':sid});
            removesections('#library-materials');
        }
		
    </script>
  
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Materials</p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
        <!--Tag For Searching/Selecting-->
        <div class='row'>
            <div class='twelve columns'>
            	<!--<p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">To filter this list, search by Tag Name and Material Name.</p>-->
                <p class="<?php if($sessmasterprfid == '10'){ echo "filterLightTitle"; }else { echo "filterDarkTitle"; } ?>">Search by Material name, Tag name, or browse through the available Materials below.</p>
                <div class="tag_well">
                	<input type="text" name="form_tags_materials" value="" id="form_tags_materials" />
              	</div>
            </div>
        </div>
        
        <!--Dispaly New/Saved Materials in Grid view-->
       <div class='row buttons rowspacer' id="materialslist">
     
      <a class='skip btn mainBtn' href='#library-materials' id='btnlibrary-materials-newmaterial'>
      <div class='icon-synergy-add-dark'></div>
      <div class='onBtn'>New<br />Material</div>
      </a>
      <?php 
			
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)
			{
                           	$qry = "SELECT U.fld_id AS materialid, U.fld_materials AS materialname, fn_shortname (U.fld_materials, 1) AS shortname, U.fld_thumbimg_url AS thumbimgicon, fld_upload_path AS uploadmaterialicon
                                            FROM itc_materials_master AS U WHERE U.fld_delstatus = '0'  AND U.fld_created_by = '".$uid."' ".$sqry." GROUP BY U.fld_id";
			}
                        else {
                            
                                $qry = "SELECT U.fld_id AS materialid, U.fld_materials AS materialname, fn_shortname (U.fld_materials, 1) AS shortname, U.fld_thumbimg_url AS thumbimgicon, fld_upload_path AS uploadmaterialicon
                                            FROM itc_materials_master AS U, itc_license_track AS V WHERE U.fld_delstatus = '0' AND U.fld_created_by = '".$uid."' AND V.fld_school_id='".$schoolid."' AND V.fld_user_id=0 ".$sqry." GROUP BY U.fld_id";
                        }
			
			$qry_listallmaterials = $ObjDB->QueryObject($qry);
			
			if($qry_listallmaterials->num_rows>0)
			{
				while($res=$qry_listallmaterials->fetch_assoc()){
					extract($res);
			?>
      <a class='skip btn mainBtn' href='#library-materials' id='btnlibrary-materials-actions' name='<?php echo $materialid.",".$materialname;?>'>
      <div class="icon-synergy-units">
          <img class="thumbimg" src="thumb.php?src=<?php if($thumbimgicon != '') { echo $thumbimgicon; } else { echo __CNTMATERIALICONPATH__.$uploadmaterialicon; } ?>&w=40&h=40&q=100" />
      </div>
      <div class='onBtn tooltip' title="<?php echo $materialname;?>"><?php echo $shortname; ?></div>
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