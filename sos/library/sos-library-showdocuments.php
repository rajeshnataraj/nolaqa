<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$ids=explode(",",$id);


$phaseid=$ids[0];
$unitid=$ids[1];
?>
<section data-type='#sos-library' id='sos-library-showdocuments'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Documents</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
<?php
$qrymenuname = $ObjDB->QueryObject("SELECT fld_id AS docid,fld_document_name AS docname,fld_document_icon as docicon FROM 
                                                                itc_sosdocument_master WHERE fld_unit_id='".$unitid."' AND fld_phase_id='".$phaseid."' AND fld_delstatus='0' ORDER BY docname ASC");

if($qrymenuname->num_rows > 0){ 
while($rowmenuname = $qrymenuname->fetch_assoc())
{
    extract($rowmenuname);
        ?>
    <a class='skip btn mainBtn' href='#sos-library-previewdocuments' id='btnsos-library-documentspreview' name='<?php echo $docid;?>'>
        <div class="icon-synergy-units"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$docicon; ?>&w=40&h=40&q=100" /> </div>
        <div class='onBtn tooltip' title="<?php echo $videoname;?>"><?php echo $docname; ?></div>
    </a>
        <?php
}
}
else{
            ?>
       <div class='row'>
            <div class='twelve columns'>
            	<p style="font-size:35px">There are no Document for this Phase</p>
                <p class="darkSubTitle">&nbsp;</p>
        </div>
    </div>
    <?php
}
            ?>
        </div>
    </div>
</section>
<?php
      
	@include("footer.php");
