<?php
	@include("sessioncheck.php");
	$repositoryid = isset($method['id']) ? $method['id'] : '0';
	$qry_lessondetails = $ObjDB->QueryObject("SELECT fld_id AS repositoryid, fld_repository_name AS repositoryname, fld_file_name AS filename, fld_file_type AS filetypename FROM itc_repository_master WHERE fld_id = '".$repositoryid."' and fld_delstatus='0'");
	
	while($res_lessondetails = $qry_lessondetails->fetch_assoc()){
		extract($res_lessondetails);
	}
	$noview=array('xlsx','xls','txt','doc','docx');
?>
<script type='text/javascript'>
	$.getScript("tools/repository/tools-repository-newrepository.js");
</script>
<section data-type='2home' id='tools-repository-viewrepository'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">View Repository</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        <div class='row'>        
        	<div class="twelve columns formBase">
            	<div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<div class='row'>
                            <div class='eight columns'>
                            	<span class="wizardReportDesc">Repository name:</span>
                                <div class="wizardReportData"><?php echo $repositoryname; ?></div>
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
                                    <div class="wizardReportData"><?php echo $filename; ?></div><br>
                                    <?php //synbtn-demote
                                        if(!in_array(strtolower($filetypename),$noview)) {      ?>
                                            <input type="button" id="btntools-repository-preview" value="Preview" class="mainBtn darkButton" name="<?php echo $filename.",".$filetypename;?>" align="right" />  
                                        <?php } ?>    &nbsp;&nbsp;                   
                                            <input type="button" id="btntools-repository-download" value="Download"  class="darkButton" onclick="fn_downloaddoc();" align="right" />
                                </div>
                                <div class='four columns'></div>
                            </div>
                        <?php }?>
                        <input type="hidden" id="repositoryfilename" name="repositoryfilename" value="<?php echo $filename;?>" />
                         <input type="hidden" id="repositoryfileformat" name="repositoryfileformat" value="<?php echo $filetypename;?>" />
                    </div>
             	</div>
            </div>
      	</div>
    </div>    
</section>
<?php
	@include("footer.php");