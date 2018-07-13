<?php
/*
 * Created by - Mohan M(PHP Programmer)
 * view page for material details
 * DB:itc_mis_materials_master
 */
@include("sessioncheck.php");

$materialid = isset($method['id']) ? $method['id'] : '0';
$qry_materiallist_det=$ObjDB->QueryObject("SELECT fld_materials AS materialname, fld_mat_desc AS materialdesc, fld_thumbimg_url AS thumbimgpath, fld_upload_path AS uploadimg
                                             FROM itc_mis_materials_master WHERE fld_id='".$materialid."' AND fld_delstatus='0'"); // get the materil's details using materialid
if($qry_materiallist_det->num_rows>0)
{
        $materialsdetails = $qry_materiallist_det->fetch_assoc();		
        extract($materialsdetails);	
}
?>
<section data-type='#library-missionmaterials' id='library-missionmaterials-viewmaterials'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $materialname;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
         <div class='row formBase rowspacer'>
             <div class='eleven columns centered insideForm'>
                 <div class="row">
                     <div class="row rowspacer">
                        <div class="wizardReportData"><?php echo $materialname.":";?></div>
                     </div>
                     <div class="row rowspacer">
                        <div class="wizardReportDesc"><?php echo nl2br($materialdesc);?></div>
                     </div>
                     <div class="row rowspacer">
                        <div class="wizardReportData" style="align:center;"><img src="<?php if($thumbimgpath != '') { echo $thumbimgpath; } else { echo __CNTMATERIALICONPATH__.$uploadimg; } ?>" ALT="<?php echo $materialname; ?>" align="center" style="width: 10em;" ></div>
                     </div>
                 </div>
                  <div class='row rowspacer' id="unitbtn">
                    <div class='row'>
                        <div class='four columns btn primary push_two noYes' style="margin-left:35%;">
                            <a onclick="fn_cancel('library-missionmaterials-actions')" tabindex="4">Close</a>
                        </div>
                     </div>
                  </div>
             </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");