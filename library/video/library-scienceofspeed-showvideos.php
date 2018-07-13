<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$ids=explode(",",$id);


$phaseid=$ids[0];
$unitid=$ids[1];
?>
<section data-type='2home' id='library-scienceofspeed-showvideos'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Videos</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
<?php
$qrymenuname = $ObjDB->QueryObject("SELECT fld_id AS videoid,fld_video_name AS videoname,fld_video_icon as videoicon FROM 
                                                                itc_sosvideo_master WHERE fld_unit_id='".$unitid."' AND fld_phase_id='' AND fld_delstatus='0' ORDER BY videoname ASC");

while($rowmenuname = $qrymenuname->fetch_assoc())
{
    extract($rowmenuname);
        ?>
    <a class='skip btn mainBtn' href='#library-scienceofspeed-showvideos' id='btnlibrary-scienceofspeed-showvideos' name='<?php echo $videoid;?>'>
        <div class="icon-synergy-units"> <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$videoicon; ?>&w=40&h=40&q=100" /> </div>
        <div class='onBtn tooltip' title="<?php echo $videoname;?>"><?php echo $videoname; ?></div>
    </a>
        <?php
}
            ?>
        </div>
    </div>
</section>
<?php
      
	@include("footer.php");
