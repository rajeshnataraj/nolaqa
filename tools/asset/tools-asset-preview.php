<?php
	@include("sessioncheck.php");	
	
	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
    $filename = $id[0];	
	$fileformat = $id[1];	

	$imagearray=array('jpg','jpeg','gif','png');
	$videoarrayarray=array('mp3','wav','wma','swf','avi','flv','mp4','mpg','wmv','divx','mpeg');
	if(in_array($fileformat,$imagearray)) $type=1;
	if(in_array($fileformat,$videoarrayarray)) $type=2;

?>
<section data-type='2home' id='tools-asset-preview'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Preview Asset </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>     
        <div class='row buttons'> 
        
        	<?php if($type=='2') { ?>  	
                <iframe src="<?php echo _CONTENTURL_;?>asset/<?php echo $filename;?>" width="99%" height="610px;" style="border:none;margin:0 auto;"></iframe>
            <?php } if($type=='1') {
				$data = getimagesize(__FULLCNTASSETPATH__.$filename);
			   $width = $data[0];
			   $height = $data[1];
			   if($width>600 or $height>900)
			   {
				   $src="thumb.php?src=".__CNTASSETPATH__.$filename."&w=900&h=600&q=100";
			   }
			   else
			   {
				   $src=__CNTASSETPATH__.$filename;
			   }
			?>
				<img id="imgload" src="<?php echo $src;?>" style="border:none;margin:0 auto;max-height:600;max-width:900px;visibility:hidden">
                <script type="text/javascript" language="javascript">
                   $('#imgload').attr('src', '<?php echo $src;?>').load(function() {  
					  $(this).css('visibility','visible')
					});
                   </script> 
           <?php } ?>         
        </div>
    </div>
</section>
<?php
	@include("footer.php");
