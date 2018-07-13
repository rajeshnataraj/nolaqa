<?php 
	@include("sessioncheck.php");
	
	$id = isset($_POST['id']) ? $_POST['id'] : '0';
	$id = explode(",",$id);
	
	$qryfile=$ObjDB->QueryObject("SELECT fld_question_type_id AS testtype, fld_file_name AS filename 
								FROM itc_question_details 
								WHERE fld_id='".$id[0]."'");
	if($qryfile->num_rows > 0){
		$rowfile = $qryfile->fetch_assoc();
		extract($rowfile);
	}
	
?>
<section data-type='2home' id='library-questions-directreview'>
    <div class='container'>
        <div class='row'>
        	<div class="span10">
              <p class="darkTitle">View Question</p>
              <p class="darkSubTitle">&nbsp;View the question details below.</p>              
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns'>       
                <a class='skip btn mainBtn' href='#btnlibrary-questions' id='btnlibrary-questions-steps' name="<?php echo $id[0];?>">
                    <div class='icon-synergy-edit'></div>
                    <div class='onBtn'>Edit<br />Question</div>
                </a>
                <a class='skip btn main' href='#btnlibrary-questions' onclick="fn_delete(<?php echo $id[0];?>)">
                    <div class='icon-synergy-trash'></div>
                    <div class='onBtn'>Delete<br />Question</div>
                </a>
            </div>
        </div>
        
        <div class='row rowspacer'>
        	<div class='twelve columns formBase'>
                <div class='row'>
		        	<div class='eleven columns centered insideForm' style="min-height:300px;">
	            		<div id="loadImg">
                        	<img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif" />
                       	</div>
                        <iframe src="library/questions/library-questions-reviewiframe.php?id=<?php echo $id[0]; ?>" width="100%" height="10px" style="border:#F00;overflow:hidden" id="ifr_question" onload="$('#loadImg').remove();autoResize('ifr_question',1);"  ></iframe>
                            
                            
                        <?php if($testtype!=1 and $filename != ''){?>
                            <div class='row rowspacer'>
                                  <div class='twelve columns'>
                                    <span class="wizardReportDesc">File Name:</span>
                                    <div class="wizardReportData"><?php echo $filename; ?>
                                    <input type="button" id="btnlibrary-questions-rempreview" value="Preview" class="mainBtn darkButton" style="margin-left:10px;" name="<?php echo $id[0]?>" align="right"/>
                                    </div>
                                  </div>
                             </div>
                        <?php } ?>
               		</div>
              	</div>
         	</div>
        </div>
  </div>
</section>
<?php
	@include("footer.php");