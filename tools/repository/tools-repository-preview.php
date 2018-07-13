<?php
	@include("sessioncheck.php");	
	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
        $filename = $id[0];	
	$fileformat = $id[1];	

	$imagearray=array('jpg','jpeg','gif','png');
	$videoarrayarray=array('pdf','doc','docx','xlsx','xls');
	if(in_array($fileformat,$imagearray)) $type=1;
	if(in_array($fileformat,$videoarrayarray)) $type=2;

?>
<section data-type='2home' id='tools-asset-preview'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Preview Repository </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>     
        <div class='row buttons'> 
                    <?php if($type=='2') { ?> 
                        <div class='row formBase'>
                            <div class='eleven columns centered insideForm'>
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
                <?php } ?>         
        </div>
    </div>
</section>
<?php
	@include("footer.php");
