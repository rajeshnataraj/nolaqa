<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$ids=explode(",",$id);

$phaseid=$ids[0];
$unitid=$ids[1];

$phasename = $ObjDB->SelectSingleValue("SELECT fld_phase_name AS phasename FROM itc_sosphase_master WHERE fld_id='".$phaseid."' AND fld_delstatus='0'");

$qrymenuname = $ObjDB->QueryObject("SELECT fld_id AS docid,fld_document_name AS docname,fld_document_icon as docicon FROM 
                                                                itc_sosdocument_master WHERE fld_unit_id='".$unitid."' AND fld_phase_id='".$phaseid."' AND fld_delstatus='0' ORDER BY docname ASC");

$qrymenunamevideo = $ObjDB->QueryObject("SELECT fld_id AS videoid,fld_video_name AS videoname,fld_video_icon as videoicon FROM 
                                                                itc_sosvideo_master WHERE fld_unit_id='".$unitid."' AND fld_phase_id='".$phaseid."' AND fld_delstatus='0' ORDER BY videoname ASC");

 
?>
<section data-type='#sos-library' id='sos-library-showvideosdocuments'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $phasename; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
        
       <?php
             if($qrymenunamevideo->num_rows > 0){
        ?>
        <a class='skip btn mainBtn' href='#sos-library-showvideos' id='btnsos-library-showvideos' name='<?php echo $phaseid.",".$unitid;?>'>
        <div class="icon-synergy-lessons"></div>
        <div class='onBtn'>Videos</div>
       </a>
        
        <?php
             }
             
             if($qrymenuname->num_rows > 0){
        ?>
        <a class='skip btn mainBtn' href='#sos-library-showdocuments' id='btnsos-library-showdocuments' name='<?php echo $phaseid.",".$unitid;?>'>
        <div class="icon-synergy-lessons"></div>
        <div class='onBtn'>Documents</div>
       </a>
            <?php
               }
               ?>
       
        </div>
    </div>
</section>
<?php
      
	@include("footer.php");
