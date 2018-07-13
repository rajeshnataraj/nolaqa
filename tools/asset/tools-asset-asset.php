<?php 
    @include("sessioncheck.php");
?>

<script type='text/javascript'>
	$.getScript("tools/asset/tools-asset-newasset.js");
</script>
<section data-type='2home' id='tools-asset-asset'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Assets</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>        	
        <div class='row buttons' id="asset">
        	
            <a class='skip btn mainBtn' href='#tools-asset' id='btntools-asset-newasset'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br />Asset</div>
            </a>            
           <?php 
					$qry = $ObjDB->QueryObject("SELECT fld_id as assetid, fld_asset_name as assetname, 
					                                   fn_shortname(fld_asset_name,1) as shortasstname, fld_file_name as filename, 
					                                   fld_file_type as filetype, fld_share, fld_created_by FROM itc_asset_master 
											   WHERE  fld_created_by='".$uid."' AND fld_delstatus='0'");
					if($qry->num_rows>0){
						while($res=$qry->fetch_assoc()){
							extract($res);
						?>
                 <a class='skip btn mainBtn' href='#tools-asset' id='btntools-asset-actions' name="<?php echo $assetid;?>">
                    <div class='icon-synergy-products'></div>
                    <div class='onBtn tooltip' title="<?php echo $assetname; ?>"><?php echo $shortasstname; ?></div>
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
