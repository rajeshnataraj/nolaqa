<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-availableques
	Description:
		Shows Question, Add icon in the table format according to the type & question selected in - library-diagmastery-diagques.php page. 
	
	Actions Performed:
		Add - Adds the question to selected row & Redirects to diagnosticmastery questions questions form - library-diagmastery-diagques.php 
		Question - Redirects to preview the question form - library-diagmastery-showques.php.
		
	History:
	

*/

$id = (isset($method['id'])) ? $method['id'] : 0;
$id = explode("~",$id);

//$id[0] -> default id(1 to 6)
//$id[1] -> type(diag - 1/mas1 - 2/mas2 - 3)
//$id[2] -> question id's
//$id[3] -> diagmastery id

?>
<section data-type='#library-diagmastery' id='library-diagmastery-availableques'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Add a Question to Your Test</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row' id="taglist">
            <div class='span10 offset1'>                    
                <table class='table table-hover table-striped table-bordered' id='mytable'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th style='width:10%;'>Add</th>
                            <th class='centerText'>Available Questions</th>                                                                 
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					$qry = $ObjDB->QueryObject("SELECT a.fld_question AS question, a.fld_id AS quesid 
												FROM itc_question_details AS a 
												LEFT JOIN `itc_diag_question_mapping` AS b 
												ON a.fld_lesson_id=b.fld_lesson_id 
												WHERE a.fld_question_type_id='".$id[1]."' AND a.fld_delstatus='0' 
													AND a.fld_access='1' AND a.fld_id NOT IN(".$id[2].") 
													AND b.fld_id='".$id[3]."'");
                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                        ?>
                        <tr id="tr_ques_<?php echo $quesid; ?>" name="<?php echo $quesid; ?>">
                            <td id="ques_<?php echo $quesid;?>" onclick="fn_rowclick(this.id)" name="<?php echo $quesid; ?>"> 
                            	<span class="icon-synergy-add-small"></span>
                            </td>
                            <td style="padding-left:30px;" onclick="fn_showques(2,<?php echo $quesid;?>,1);">
                                <div id="ques_<?php echo $quesid;?>"><?php echo strip_tags($question);?></div>
                            </td>                                                                 
                        </tr>
                        <?php
                        }
                    }
                    else
                    {
                        ?>
                        <tr><td colspan="2">No Questions Found.</td></tr>
                        <?php
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='six columns'></div>
            <div class='twelve columns'>
                <div class='row' id="submit" style="display:none;">
                     <input type="button" class="darkButton" value="Submit" style="width:130px; height:40px; float:right" onClick="fn_addques(<?php echo $id[1];?>);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");