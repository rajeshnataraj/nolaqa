<?php
/*
 * created by - Vijayalakshmi PHP programmer
 * created on - 18/12/2013
 * 
 */
ini_set('display_errors', '0');
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode("~",$id);
$assessid = $id[1];
$studentid = $id[0];

	$list_answers=$ObjDB->QueryObject("SELECT a.fld_question as question,b.fld_answer as answer, b.fld_correct_answer as crtanswer, 
						b.fld_question_id as quesid,b.fld_id as answerid,d.fld_id as assesid,d.fld_score as fullscore,
						d.fld_total_question as totalqn FROM itc_question_details as a 
						LEFT JOIN itc_test_student_answer_track as b on a.fld_id=b.fld_question_id
						LEFT JOIN itc_test_master as d ON d.fld_id = b.fld_test_id
						LEFT JOIN itc_test_questionassign as c on a.fld_id=c.fld_question_id
						WHERE  a.fld_delstatus='0' AND b.fld_student_id='".$studentid."'
						AND b.fld_answer_type_id='15' AND b.fld_delstatus='0'  AND c.fld_delstatus='0' 
						AND d.fld_id IN($assessid) AND d.fld_delstatus = '0' GROUP BY b.fld_question_id ORDER BY b.fld_question_id");

?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
$('#tablecontentsop').slimscroll({
            height:'auto',
            size: '7px',
            alwaysVisible: true,
            railVisible: false,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 9,
            color: '#88ABC2',
            wheelstep:1
    });
</script>

<section data-type='2home' id='reports-openresponse-listopensourseans'>
    <div class='container'>
        <!--Load the Material Name / New material-->
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Questions & Answers</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the material Form-->
        <div class='row formBase rowspacer'>
	         <div class='row rowspacer'>
    <div class='span10 offset1' id="queslist">
   <table class='table table-hover table-striped table-bordered setbordertopradius'>
            <thead class='tableHeadText'>
                <tr>
                    <th width="5%">#</th>
                    <th width="85%" class='centerText'>Question & Answer</th>
                    <th width="10%" class='centerText'>Action</th>
                    
                </tr>
            </thead>
        </table>     

  <form name="pointearnform" id="pointearnform" method="post" action="">
     <div style="max-height:400px;width:100%;" id="tablecontentsop" >
              <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="tblTransactions">
                 <tbody>
      <?php
                     if($list_answers->num_rows > 0){
                             $i=1;
                    while($row=$list_answers->fetch_assoc()){
                    extract($row);

		$chk_commentview = $ObjDB->QueryObject("SELECT a.*  FROM itc_test_teacher_comment AS a
							JOIN itc_test_student_answer_track AS b ON a. fld_answer_id = b.fld_id AND 
							a.fld_trackupdated_date = b.fld_updated_date
							WHERE a.fld_student_id='".$studentid."' AND a.fld_answer_id ='".$answerid."' AND a.fld_delstatus = '0'");
      
      ?>
      <tr id="<?php echo $i;?>" name="<?php echo $question;?>" style="cursor: default;">
      
        <td width="5%" class="getquestnid<?php echo $i; ?>"><?php echo $i;?></td>
	<td width="85%" class="getquestnid<?php echo $i; ?>"><?php echo $question;?><strong>Answer:</strong>  <?php echo $answer; ?></td>
	<td width="10%" class="getquestnid<?php echo $i; ?>" >
<?php if($chk_commentview->num_rows > 0){  ?>
<div onclick="removesections('#reports-openresponse-listopensourseans'); fn_showanspage_ind('<?php echo $studentid?>','<?php echo $quesid; ?>','<?php echo $answerid; ?>');" class="icon-synergy-view tooltip" title="Show Comments"></div><?php } else { echo "_"; } ?></td>
      
      </tr>
     <?php
           $i++;
        }   // while ends
          }  // if ends
     ?>  

     </tbody>
        </table>
     </div>
  </form>
    </div>
</div>
 <div class='row rowspacer'></div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
