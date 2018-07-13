<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-diagques
	Description:
		Shows Add Question, Question, Up, Down & Remove icons in the table format according to the step selected in - library-diagmastery-steps.php page. 
	
	Actions Performed:
		Add Question, Question - Redirects to available questions form - library-diagmastery-availableques.php.
		Up - Moves the Question in the upward direction.
		Down - Moves the Question in the downward direction.
		Remove - Deletes the Question.
	History:
*/

$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
$id = explode(",",$id);

//$id[0] -> diagmasid
//$id[1] -> type(diag - 2/mas1 - 3/mas2 - 4)

if($id[1]==2) //Loads Diagnostic Details
{
	$qry = $ObjDB->QueryObject("SELECT fld_diag_ques1a AS diagmasques_1, fld_diag_ques1b AS diagmasques_2, 
								fld_diag_ques2a AS diagmasques_3, fld_diag_ques2b AS diagmasques_4, 
								fld_diag_ques3a AS diagmasques_5, fld_diag_ques3b AS diagmasques_6 
							FROM itc_diag_question_mapping 
							WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
	if($qry->num_rows>0){
		while($row = $qry->fetch_assoc())
		{
			extract($row);
		}
	}
	$msg = "Diagnostic Test Questions";
	$cls = "diagques";
	$removecls1 = "mas1ques";
	$removecls2 = "mas2ques";
	$type=1;
}
if($id[1]==3) //Loads Mastery1 Details
{
	$qry = $ObjDB->QueryObject("SELECT fld_mast1_ques1a AS diagmasques_1, fld_mast1_ques1b AS diagmasques_2, 
								fld_mast1_ques2a AS diagmasques_3, fld_mast1_ques2b AS diagmasques_4, 
								fld_mast1_ques3a AS diagmasques_5, fld_mast1_ques3b AS diagmasques_6 
							FROM itc_diag_question_mapping WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
	if($qry->num_rows>0){
		while($row = $qry->fetch_assoc())
		{
			extract($row);
		}
	}
	$msg = "Mastery1 Test Questions";
	$cls = "mas1ques";
	$removecls1 = "diagques";
	$removecls2 = "mas2ques";
	$type=2;
}
if($id[1]==4) //Loads Mastery2 Details
{
	$qry = $ObjDB->QueryObject("SELECT fld_mast2_ques1a AS diagmasques_1, fld_mast2_ques1b AS diagmasques_2, 
								fld_mast2_ques2a AS diagmasques_3, fld_mast2_ques2b AS diagmasques_4, 
								fld_mast2_ques3a AS diagmasques_5, fld_mast2_ques3b AS diagmasques_6 
							FROM itc_diag_question_mapping WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
	if($qry->num_rows>0){
		while($row = $qry->fetch_assoc())
		{
			extract($row);
		}
	}
	$msg = "Mastery2 Test Questions";
	$cls = "mas2ques";
	$removecls1 = "mas1ques";
	$removecls2 = "diagques";
	$type=3;
}
$diagmasquestion_1 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_1."' AND fld_delstatus='0'");
$diagmasquestion_2 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_2."' AND fld_delstatus='0'");
$diagmasquestion_3 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_3."' AND fld_delstatus='0'");
$diagmasquestion_4 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_4."' AND fld_delstatus='0'");
$diagmasquestion_5 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_5."' AND fld_delstatus='0'");
$diagmasquestion_6 = $ObjDB->SelectSingleValue("SELECT fld_question FROM itc_question_details WHERE fld_id='".$diagmasques_6."' AND fld_delstatus='0'");

$quesnum = array('1a. ','1b. ','2a. ','2b. ','3a. ','3b. ');
?>
<section data-type='#library-diagmastery' id='library-diagmastery-diagques'>
    <!--Script to change the Step Styles-->
    <script language="javascript">
        $('#testdetails').removeClass("active-first");
        $('#review').removeClass("active-last");
        $('#<?php echo $removecls1;?>').removeClass("active-mid");
        $('#<?php echo $removecls2;?>').removeClass("active-mid");
        $('#<?php echo $cls;?>').parents().removeClass("dim");
        $('#<?php echo $cls;?>').addClass("active-mid");
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg;?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Loads the Table with Questions according to the type(Diag/Mastery1/Mastery2)-->
        <div class='row buttons rowspacer'>
            <div class='row' id="taglist">
                <div class='span10 offset1'>                    
                    <table class='table table-hover table-striped table-bordered' id='mytable'>
                        <thead class='tableHeadText'>
                            <tr style="cursor:default">
                                <th style="width:13%; text-align:center">
                                    # Questions
                                </th>
                                <th style="padding-left:30px;">
                                    Question
                                </th>
                                <th style="width:10%; text-align:center">
                                    Actions 
                                </th>                                                                  
                            </tr>
                        </thead>
                        <!-- id = "question_'Questionid as in DB'" type = 1-Diag, 2-Mastery1, 3-Mastery2
                        	$diagmasques_(1-6) -> Questionid as in DB,
                            $diagmasquestion_(1-6) -> Questions as in DB,
                        -->
                        <tbody id="create" <?php if($diagmasques_1!=0 || $diagmasques_2!=0 || $diagmasques_3!=0 || $diagmasques_4!=0 || $diagmasques_5!=0 || $diagmasques_6!=0) {?> style="display:none"<?php }?>>
                        	
                            <tr id="0">
                                <td onclick="fn_showques(1,0,<?php echo $type; ?>,<?php echo $id[0]; ?>);" colspan="3">
                                    <div>+ Click Here to Add six questions at a Time.</div>
                                </td>
                            </tr>
                        </tbody>
                        
                        <tbody id="questions" <?php if($diagmasques_1==0 && $diagmasques_2==0 && $diagmasques_3==0 && $diagmasques_4==0 && $diagmasques_5==0 && $diagmasques_6==0) {?> style="display:none" <?php }?>>
                            <tr id="question_<?php echo $diagmasques_1; ?>">                                
                                <td style="text-align:center">
                                    1.a
                                </td>
                                <td id="<?php echo $diagmasques_1; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>); ">
                                    <div id="dques_1"><?php if($diagmasques_1!=0) { echo strip_tags($diagmasquestion_1); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                  
                            </tr>
                            
                            <tr id="question_<?php echo $diagmasques_2; ?>">
                                <td style="text-align:center">
                                    1.b
                                </td>
                                <td id="<?php echo $diagmasques_2; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>);">
                                    <div id="dques_2"><?php if($diagmasques_2!=0) { echo strip_tags($diagmasquestion_2); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                 
                            </tr>
                            
                            <tr id="question_<?php echo $diagmasques_3; ?>">
                                <td style="text-align:center">
                                    2.a
                                </td>
                                <td id="<?php echo $diagmasques_3; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>);">
                                    <div id="dques_3"><?php if($diagmasques_3!=0) { echo strip_tags($diagmasquestion_3); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                 
                            </tr>
                            
                            <tr id="question_<?php echo $diagmasques_4; ?>">
                                <td style="text-align:center">
                                    2.b
                                </td>
                                <td id="<?php echo $diagmasques_4; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>);">
                                    <div id="dques_4"><?php if($diagmasques_4!=0) { echo strip_tags($diagmasquestion_4); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                  
                            </tr>
                            
                            <tr id="question_<?php echo $diagmasques_5; ?>">
                                <td style="text-align:center">
                                    3.a
                                </td>
                                <td id="<?php echo $diagmasques_5; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>);">
                                    <div id="dques_5"><?php if($diagmasques_5!=0) { echo strip_tags($diagmasquestion_5); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                
                            </tr>
                            
                            <tr id="question_<?php echo $diagmasques_6; ?>">
                                <td style="text-align:center">
                                    3.b
                                </td>
                                <td id="<?php echo $diagmasques_6; ?>" style="padding-left:30px;" onclick="fn_showques(1,this.id,<?php echo $type; ?>,<?php echo $id[0]; ?>);">
                                    <div id="dques_6"><?php if($diagmasques_6!=0) { echo strip_tags($diagmasquestion_6); } else { ?>+ Add a Question<?php }?></div>
                                </td>
                                <td style="text-align:center">
                                    <div id="up_1" class="synbtn-promote dim" style="float:left" title="Up"></div> 
                                    <div id="down_1" class="synbtn-demote dim" style="float:left" title="Down"></div>
                                    <div id="remove_1" class="synbtn-remove dim" style="float:left" title="Remove"></div>
                                </td>                                                                 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class='row rowspacer'>
                <div class='row' id="shownext" style="display:none">
                    <input type="button" class="darkButton" value="Next Step" style="width:200px; height:40px; float:right" onClick="fn_savediag(<?php echo $id[0];?>,<?php echo $id[1]; ?>,<?php echo $type; ?>);" />
                </div>
            </div>
            
            <!--Script to interchange the rows in the table-->
			<script language="javascript">
				$(document).ready(function(){
					loads();
					$("#up_1,#down_1").click(function(){
						var row = $(this).parents("tr:first");
						
						if ($(this).is("#up_1") ) {
							var row1 =$(this).parents("tr:first").attr('id');
							var row2 =$(this).parents("tr:first").attr('id');
							$(this).parents("tr:first").attr('id',row2);
							$(this).parents("tr:first").attr('id',row1);
							var td1 =$(this).parents("tr:first").prev().children('td').html();
							var td2 =$(this).parents("tr:first").children('td').html();
							$(this).parents("tr:first").prev().children('td:first').html(td2);
							$(this).parents("tr:first").children('td:first').html(td1);
							row.insertBefore(row.prev());
						} else {
							var row1 =$(this).parents("tr:first").attr('id');
							var row2 =$(this).parents("tr:first").attr('id');
							$(this).parents("tr:first").attr('id',row2);
							$(this).parents("tr:first").attr('id',row1);
							var td1 =$(this).parents("tr:first").next().children('td').html();
							var td2 =$(this).parents("tr:first").children('td').html();
							$(this).parents("tr:first").next().children('td:first').html(td2);
							$(this).parents("tr:first").children('td:first').html(td1);
							row.insertAfter(row.next());
						}
						loads();	
					});
				
					$("#remove_1 ").click(function() {
						removesections("#library-diagmastery-diagques");
						$(this).parent().parent().attr('id',('question_0'));
						$(this).parent().attr('id',('0'));
						$(this).parents("tr:first").children('td:first').next().children('div').html('+ Add a Question');
						loads();
					});
				});
            </script>
        </div>
        <input type="hidden" id="remainques" name="remainques" value="" />
    </div>
</section>
<?php
	@include("footer.php");