<?php
@include("sessioncheck.php");

$unitid = isset($method['id']) ? $method['id'] : '0';	
?>
<section data-type='#sos-library' id='sos-library-showphases'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Phases</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
<?php
$qrymenuname = $ObjDB->QueryObject("SELECT fld_id AS phaseid,fld_phase_name AS phasename,fld_phase_icon as phaseicon FROM itc_sosphase_master WHERE fld_unit_id='".$unitid."' AND fld_delstatus='0'");
if($qrymenuname->num_rows > 0){ 
while($rowmenuname = $qrymenuname->fetch_assoc())
{
    extract($rowmenuname);
        ?>
    <a class='skip btn mainBtn' href='#sos-library-showvideosdocuments' id='btnsos-library-showvideosdocuments' name='<?php echo $phaseid.",".$unitid;?>'>
        <div class="icon-synergy-units"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$phaseicon; ?>&w=40&h=40&q=100" /> </div>
        <div class='onBtn tooltip' title="<?php echo $phasename;?>"><?php echo $phasename; ?></div>
    </a>
        <?php
}
}
else{
            ?>
       <div class='row'>
            <div class='twelve columns'>
            	<p style="font-size:35px">There are no Phases for this Unit</p>
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
