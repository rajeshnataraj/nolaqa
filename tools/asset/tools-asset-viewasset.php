<?php
	@include("sessioncheck.php");
	$assetid = isset($method['id']) ? $method['id'] : '0';
	$qry_lessondetails = $ObjDB->QueryObject("SELECT fld_id AS assetid, fld_asset_name AS assetname, fld_file_name AS filename, fld_file_type AS filetypename FROM itc_asset_master WHERE fld_id = '".$assetid."' and fld_delstatus='0'");
	
	while($res_lessondetails = $qry_lessondetails->fetch_assoc()){
		extract($res_lessondetails);
	}
	$noview=array('xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3','doc','docx');
?>
<script type='text/javascript'>
	$.getScript("tools/asset/tools-asset-newasset.js");
</script>
<section data-type='2home' id='tools-asset-viewasset'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">View Assets</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        <div class='row'>        
        	<div class="twelve columns formBase">
            	<div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<div class='row'>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Asset name:</span>
                                <div class="wizardReportData"><?php echo $assetname; ?></div>
                          	</div>
                            
                      	</div>
                        <div class='row rowspacer'>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">file type:</span>
                                <div class="wizardReportData"><?php echo $filetypename; ?></div>
                          	</div>
                      	</div>
                        <?php if($filetypename!=''){?>
                            <div class="row rowspacer">
                                <div class='eight columns'>
                                    <span class="wizardReportDesc">file:</span>
                                    <div class="wizardReportData"><?php echo $filename; ?></div>
									<?php //synbtn-demote
                                    if(!in_array(strtolower($filetypename),$noview)) {
                                    ?>
                                    <input type="button" id="btntools-asset-preview" value="Preview" class="mainBtn darkButton" name="<?php echo $filename.",".$filetypename;?>" align="right" />  
                                    <?php }
					 				?>                       
                                    <input type="button" id="btntools-asset-download" value="Download"  class="darkButton" onclick="fn_downloaddoc();" align="right" />
                                    
                                </div>
                                <div class='four columns'></div>
                            </div>
                        <?php }?>
                        <input type="hidden" id="assetfilename" name="assetfilename" value="<?php echo $filename;?>" />
                    </div>
             	</div>
          	</div>
      	</div>
    </div>    
</section>
<?php
	@include("footer.php");