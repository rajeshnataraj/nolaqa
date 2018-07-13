<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-showques
	Description:
		Shows Question in the view format according to the question selected in - library-diagmastery-availableques.php page. 
	History:
*/


$id = (isset($method['id'])) ? $method['id'] : 0;
$id = explode("~",$id);

/*--- Variable deceleration-----*/
$testtype='';
$filename ='';

$qids = explode(",",$id[1]);
$length = sizeof($qids);

for($i=0;$i<$length;$i++)
{
	if($id[0]==$qids[$i])
	{
		$n=$i;
		$p=$i;
		$n++;
		$p--;
		$nex = isset($qids[$n]) ? $qids[$n] : '0';
		$pre = isset($qids[$p]) ? $qids[$p] : '0';
	}
}

$qryfile=$ObjDB->QueryObject("SELECT fld_question_type_id AS testtype, fld_file_name AS filename 
							FROM itc_question_details 
							WHERE fld_id='".$id[0]."'");
if($qryfile->num_rows>0){
	$rowfile = $qryfile->fetch_assoc();
	extract($rowfile);
}
?>
<section data-type='#library-diagmastery' id='library-diagmastery-showques'>
	<script language="javascript">
		function fn_load(id){
            $("#ques").load("library/diagmastery/library-diagmastery-showques.php #ques > *",{'id':id});
        }
		
		function fn_removesection(id)
		{
			if(id==0)
			{
				removesections("#library-diagmastery-diagques");
			}
			else if(id==1)
			{
				removesections("#library-diagmastery-availableques");
			}
			else if(id==2)
			{
				removesections("#library-diagmastery-review");
			}
		}
	</script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Question Details</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
        	<div class='twelve columns formBase' id="ques">
                <div class='row'>
		        	<div class='eleven columns centered insideForm' style="min-height:300px;">
                    	<div class='row rowspacer'>
                        	<div class='four columns'>
                            	<input class="darkButton <?php if($id[0]==$qids[0]) {?>dim<?php }?>" type="button" id="pre" name="pre" value="Previous" onclick='fn_load("<?php echo $pre."~".$id[1]."~".$id[2];?>");' /> 
                            </div>
                            
                        	<div class='four columns' style="font-weight:bolder; font-size:24px; text-align:center">
                                <?php echo $ObjDB->SelectSingleValue("SELECT b.fld_question_type 
																	FROM itc_question_details AS a 
																	LEFT JOIN itc_question_type AS b ON 
																		a.fld_question_type_id = b.fld_id 
																		WHERE a.fld_id='".$id[0]."'"); ?>
                            </div>
                            
                            <div class='four columns'>
                                <input class="darkButton four columns right <?php if($id[0]==$qids[$length-1]) {?>dim<?php }?>" type="button" id="nex" name="nex" value="Next" onclick='fn_load("<?php echo $nex."~".$id[1]."~".$id[2];?>");'/>
                            </div>
                        </div>
                        
                        <div id="loadImg"><img src="<?php echo __HOSTADDR__; ?>img/AjaxLoader.gif" /></div>
                        
                        <iframe src="library/diagmastery/library-diagmastery-iframe.php?id=<?php echo $id[0]; ?>" width="100%" height="10px" style="border:#F00;" id="ifr_question" onload="$('#loadImg').remove();autoResize('ifr_question');" ></iframe>
                        <?php if($testtype!=1){?>
                        <div class='row rowspacer'>
                              <div class='twelve columns'>
                            	<span class="wizardReportDesc">File Name:</span>
                                <div class="wizardReportData"><?php echo $filename; ?>
                                <input type="button" id="btnlibrary-questions-rempreview" value="Preview" class="mainBtn darkButton" style="margin-left:10px;" name="<?php echo $id[0]?>" align="right"/>
                                </div>
                              </div>
                      	 </div>
                        
                        <?php } ?>
                        <div class='row rowspacer'>
                            <p class='btn secondary four columns' style="float:left; margin-left:280px;">
                                <a onclick="fn_removesection(<?php echo $id[2];?>)">Close</a>
                            </p>
                        </div>
            		</div>
            	</div>
            </div>
    	</div>
    </div>
</section>
<?php
	@include("footer.php");