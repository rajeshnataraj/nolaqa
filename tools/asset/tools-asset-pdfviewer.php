<?php
@include("sessioncheck.php");
$filename = isset($method['filename']) ? $method['filename'] : '';
?>
<section data-type='#reports' id='tools-asset-pdfviewer'>
	<div class='container'>
    	<div class='row formBase'>
            <div class='eleven columns centered insideForm'>
            	<input type="hidden" id="hidpdfile" name="hidpdfile" value="<?php echo _CONTENTURL_; ?>asset/<?php echo $filename;?>" />
                <div id="loadImg" style="height:0px;"><img src="<?php echo __HOSTADDR__; ?>img/ajax-loader.gif"/></div>
            	<iframe src="../../reports/pdfviewer/generic/web/viewer.php?hidpdf=<?php echo _CONTENTURL_; ?>asset/<?php echo $filename;?>" width="100%" style="min-height:900px;visibility:hidden" id="ifr_pdf" onload="autoResize('ifr_pdf');"></iframe>		
            
			<script language="javascript" type="text/javascript" >
           $('#ifr_pdf').ready(function () {
				$('#loadImg').css('display', 'none');
			});
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
