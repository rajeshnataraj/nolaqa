<?php
@include("sessioncheck.php");
$msgid = isset($method['msgid']) ? $method['msgid'] : '';
$ids = isset($method['ids']) ? $method['ids'] : '';


$filename=$ObjDB->SelectSingleValue("SELECT fld_file_name AS filname, fld_file_type AS filtype FROM itc_message_upload_mapping 
                                                 WHERE fld_messageid='".$msgid."' AND fld_id='".$ids."'");


?>
<section data-type='#reports' id='tools-messagefile-pdfviewer'>
    <div class='container'>
    	<div class='row formBase'>
            <div class='eleven columns centered insideForm'>
            	<input type="hidden" id="hidpdfile" name="hidpdfile" value="<?php echo _CONTENTURL_; ?>asset/<?php echo $filename;?>" />
                <div id="loadImg" style="height:0px;"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
            	<iframe src="../../reports/pdfviewer/generic/web/viewer.php?hidpdf=<?php echo _CONTENTURL_; ?>asset/<?php echo $filename;?>" width="100%" style="min-height:900px;visibility:hidden" id="ifr_pdf" onload="autoResize('ifr_pdf');$('#loadImg').remove();"></iframe>		
            
                <script language="javascript" type="text/javascript" >
                    $('#ifr_pdf').load(function () {
                            $('#loadImg').css('display', 'none');
                            $(this).css('visibility', 'visible');
                    });
                </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
