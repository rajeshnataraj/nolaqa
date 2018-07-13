<?php 
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);
        
        $qrychecktype=$ObjDB->QueryObject("SELECT fld_question_type as checktype, fld_score as tpct, fld_flag as flagid, fld_nextflag as nextflag  FROM itc_test_master WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
        $rowtype=$qrychecktype->fetch_assoc();
        extract($rowtype);
        if($checktype !=0){
             $id[2] = $checktype;
         }
?>
<section data-type='test-testassign' id='test-testassign-testquestion'>
	<script language="javascript">
		$('#newtest').removeClass("active-first");
		$('#testreview').removeClass("active-last");
		$('#testquestion').parents().removeClass("dim");
		$('#testquestion').addClass("active-mid");
		
		$('#tablecontents').slimscroll({
			height:'auto',
			railVisible: false,
			allowPageScroll: false,
			railColor: '#F4F4F4',
			opacity: 9,
			color: '#88ABC2',
		});
                
                $('#tablecontentsr').slimscroll({
			height:'auto',
			railVisible: false,
			allowPageScroll: false,
			railColor: '#F4F4F4',
			opacity: 9,
			color: '#88ABC2',
		});

                $('#random').hide();
                $('#manual').hide();
                $('#neststep').hide();
                $('#neststepr').hide();
                $('#randomtext').hide();
                $('#neststepempty').hide();
	</script> 
        <?php
            if(($flagid ==1) and ($id[2]==2)){
            ?> <script>$('#randomtext').show();$('#neststepempty').show();$('#emptyflag').val('1');</script><?php
        }
        ?>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Assessment Question</p>
            	<p class="dialogSubTitleLight">Edit your list of questions below. Click Next Step to continue forward.</p>
            </div>
        </div>
        
        <div class='row'>
        <div class="six columns">
        Select Question Type<span class="fldreq">*</span>
            <dl class='field row'>
                <dt class='dropdown'>
                    <div class="selectbox<?php if($checktype !=0){ ?> dim <?php } ?>">
                       <input type="hidden" name="qttype" id="qttype" value="<?php echo $id[2];?>" onchange="fn_testtype(this.value,'<?php echo $id[0];?>');"/> 
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="javascript:void(0)">
                        <span class="selectbox-option" data-option="" id="clearsubject"><?php if($id[2]==1){ echo "Manual";?> <script> $('#manual').show();$('#neststep').show();$('#neststepr').hide();$('#break').hide();</script> <?php } else if($id[2]==2){ echo "Random";?> <script> $('#random').show();$('#neststepr').show();$('#neststep').hide();$('#break').hide();</script> <?php } else { echo "Select Question Type"; }?> </span>
                        <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">

                            <ul role="options">
                                <li><a tabindex="-1" href="#" data-option="1" onclick="$('#random').hide();$('#manual').show();$('#hiddropdowntype').val(1);$('#neststep').show();$('#neststepr').hide();$('#break').hide();">Manual</a></li>
                                <li><a tabindex="-1" href="#" data-option="2" onclick="$('#random').show();$('#manual').hide();$('#hiddropdowntype').val(2);$('#neststep').hide();$('#break').hide();">Random</a></li>   
                            </ul>
                        </div>
                    </div>
                </dt>
            </dl>
        </div>
       </div>
       
       <div class='row' id="break">
           <?php echo "<br/><br/><br/>";?>
       </div>
       
        
        <div class='row rowspacer' id="random">
            <div class='row' id="randomtext">
                <div class='twelve columns'>
                    <p class="dialogSubTitleLight" style="color: purple;font-size: 16px;">Note: <span style="font-size: 14px;"> When you update, existing questions will be replaced & student will lose their progress.</span></p>
                </div>
            </div>
            <div class='span10 offset1'>
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>    
                            <th width="6%" class='centerText'>#</th>
                            <th width="50%;">Section</th>
                            <th width="10%" class='centerText'>#Available <br> Questions</th>
                            <th width="10%" class='centerText'>#Questions<br>Assigned</th>
                            <th width="10%" class='centerText'>Percentage <br> Weight %</th>
                            <th width="14%" class='centerText'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="mainBtn" id="btntest-testassign-addquestionrandom" name="<?php echo $id[0]; ?>">
                           <td class="createnewtd" colspan="6"><div class="icon-synergy-add-small" style="float: left;width:3%;"></div>&nbsp;&nbsp;<div style="float: left;margin-left: 13px;margin-top: 13px;width: 50%;">Add sections to Your Assessment</div></td>               
                        </tr>
                    </tbody>
                </table>
                <div style="max-height:275px;width:100%;" id="tablecontentsr" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                       <?php 
                           
                         $qry = $ObjDB->QueryObject("select fld_id as sectionid, fld_tag_id as tagsid, fld_avl_questions as avlqus ,fld_qn_assign as qnassig,fld_pct_section as pct 
                             FROM itc_test_random_questionassign where fld_rtest_id = '".$id[0]."' and fld_delstatus ='0' ORDER BY fld_order_by ASC");
                                                                                
                          if($qry->num_rows > 0){
				$i=1;
                             while($row=$qry->fetch_assoc()){
                                extract($row);
                         ?>
                       
                                <tr name="qtags_<?php echo $sectionid;?>" id="qtags_<?php echo $sectionid;?>">
                                 <td width="6%" class='centerText' style="cursor:default;" id="rque"><?php echo $i; ?></td>
                                  <td width="50%;" onclick="fn_showsection(<?php echo $sectionid;?>);">
                                        <div class='twelve columns'>
                                            <script type="text/javascript" charset="utf-8">
                                                    

                                                    $(function(){
                                                             $('input[readonly]').focus(function(){
                                                                this.select();
                                                                });
                                                            
                                                            var t4 = new $.TextboxList('#form_tags<?php echo $sectionid;?>', 
                                                            {
                                                                   unique: true, plugins: {autocomplete: {}},
                                                                   bitsOptions:{editable:{addKeys: [188]}} });
                                                              
                                                            <?php if($tagsid!=0){
                                                               
                                                             $tids = explode(',',$tagsid);
                                                             for($z=0;$z<count($tids);$z++)
                                                             {
                                                                 $tids1 = explode('_',$tids[$z]);	
                                                            if($tids1[1]=='testengine'){
                                                                            $tquery= "Assessment questions";
                                                            }
                                                            else if($tids1[1] =='lesson'){
                                                                $tquery= $ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$tids1[0]."' and fld_delstatus ='0'");

                                                            }
                                                            else if($tids1[1] =='diagnostic'){
                                                                 $tquery= "Diagnostic Test";

                                                            }
                                                            else if($tids1[1] =='mastery1'){
                                                                 $tquery= "Mastery Test1";

                                                            }
                                                            else if($tids1[1] =='mastery2'){
                                                                 $tquery= "Mastery Test2";

                                                            }
                                                            else{
                                                                if($tids1[0] == 61){
                                                                   $tquery= "MAEP"; 
                                                                }
                                                                else {
                                                                $tquery= $ObjDB->SelectSingleValue("SELECT fld_tag_name as ty FROM itc_main_tag_master WHERE fld_id='".$tids[$z]."' and fld_delstatus ='0'");
                                                                }
                                                            }
                                                                ?>	

                                                                t4.add('<?php echo $ObjDB->EscapeStrAll($tquery);?>','<?php echo $tids[$z];?>');
                                                               
                                                                <?php
                                                                }
                                                                }?>			
                                                    });
                                            </script>
                                            <div class="tag_well dim">
                                                	
                                                <input type="text" readonly="readyonly" name="tag" style="width:450px; height:30px;" value="" id="form_tags<?php echo $sectionid;?>"  />
                                                                   
                                            </div>
                                        </div>
                                    </td>
					<td width="10%" class='centerText' style="cursor:default;">
                                            <input class='text' style="width: 40px;" readonly="readyonly"  id="quscount<?php echo $sectionid;?>" name="quscount<?php echo $sectionid;?>" type='text' value="<?php echo $avlqus;?>" />
                                        </td>
                                    <td width="10%" class='centerText' style="cursor:default;">
                                         <input class='text' style="width: 40px;"  id="qnassig<?php echo $sectionid;?>" name="qnassig<?php echo $sectionid;?>" type='text' value="<?php echo $qnassig;?>"  onkeyup="ChkValidChar(<?php echo $sectionid;?>);fn_chkqnassig(<?php echo $sectionid.",".$id[0]; ?>)" />
                                    </td>
                                    <td width="10%" class='centerText' style="cursor:default;">
                                        <input class='text' style="width: 40px;"  id="pct<?php echo $sectionid;?>" name="pct<?php echo $sectionid;?>" type='text' value="<?php echo $pct;?>" onkeyup="ChkValidChar1(<?php echo $sectionid;?>)" />
                                   </td>
                                    <td class='centerText' style="width:120px; text-align:center">
                                        <div id="up_1" class="synbtn-promote " style="float:left"></div> 
                                        <div id="down_1" class="synbtn-demote" style="float:left"></div>
                                        <div id="remove_2" class="synbtn-remove" style="float:left"></div>
                                    </td>                
                                </tr> 
                                <script>
                                        $(document).ready(function(){
                                            
                                            $('input[type="text"]').keyup(function(){
                                                   if($(this).val != ''){
                                                      $('#neststepr').show();
                                                      $('#emptyflag').val('1');
                                                   }
                                            });
                                        });
                                    
                                        $("#qnassig<?php echo $sectionid; ?>").keypress(function (e) {
                                                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                                        return false;
                                                }
                                        });
                                        
                                        $("#pct<?php echo $sectionid; ?>").keypress(function (e) {
                                                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                                        return false;
                                                }
                                        });
                                        
                                        function ChkValidChar(sectid) {
                                                
                                                var txtbx = document.getElementById("qnassig"+sectid).value;
                                                if ((txtbx.startsWith("0"))) // true
                                                {
                                                        document.getElementById("qnassig"+sectid).value = "";
                                                }
                                        }

                                        function ChkValidChar1(sectid) {
                                                var txtbx = document.getElementById("pct"+sectid).value;
                                                if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
                                                {
                                                        document.getElementById("pct"+sectid).value = "";
                                                }
                                        }
                                </script>
                        <?php
                             $i++;
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>
        
        <div class='row rowspacer' id="manual">
            <div class='span10 offset1'>
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>	
                            <th width="6%" class='centerText'>#</th>
                            <th width="50%;">Question Name</th>
                            <th width="15%" class='centerText'>Lesson Name</th>
                            <th width="15%" class='centerText'>Assessment Type</th>
                            <th class='centerText'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="mainBtn" id="btntest-testassign-addquestion" name="<?php echo $id[0]; ?>">
                           <td class="createnewtd" colspan="5"><div class="icon-synergy-add-small" style="float: left;width:3%;"></div>&nbsp;&nbsp;<div style="float: left;margin-left: 13px;margin-top: 13px;width: 50%;">Add Question to Your Assessment</div></td>               
                        </tr>
                    </tbody>
                </table>
                <div style="max-height:400px;width:100%;" id="tablecontents" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                    	<tbody>
                        <?php 
                           $qry = $ObjDB->QueryObject("SELECT a.fld_question_id AS qusid, b.fld_question AS qusname, b.fld_lesson_id as lid, 
						                               d.fld_question_type AS questype, a.`fld_order_by` FROM `itc_test_questionassign`AS a, 
													   `itc_question_details` AS b, `itc_question_type` AS d 
													   WHERE  a.fld_question_id=b.`fld_id` AND b.fld_question_type_id=d.fld_id  
													   AND a.fld_test_id='".$id[0]."' AND a.fld_delstatus='0' ORDER BY a.`fld_order_by` ASC");
                            if($qry->num_rows > 0){
								$i=1;
                                while($row=$qry->fetch_assoc()){
                                extract($row);
                        ?>	
                                <tr name="question_<?php echo $qusid;?>" id="ques_<?php echo $qusid;?>">
                                    <td width="6%" class='centerText' style="cursor:default;" id="que"><?php echo $i; ?></td>
                                    <td width="50%" class="mainBtn" id="btntest-testassign-review" name="question_<?php echo $qusid;?>_<?php echo $id[0];?>">
                                        <div style="width:450px;word-wrap: break-word;"><?php echo strip_tags($qusname); ?></div>
                                    </td>
                                    <td width="15%" class='centerText' style="cursor:default;">
                                     <?php
                                      $lessontit = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ', b.fld_version) 
                                                                            FROM itc_ipl_master  AS a 
                                                                            LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id
                                                                            WHERE a.fld_id='".$lid."' AND a.fld_delstatus='0'  
                                                                            AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
                                      echo $lessontit; ?>
                                    </td>
                                    <td width="15%" class='centerText' style="cursor:default;">
                                      <?php echo $questype; ?>
                                    </td>
                                    <td class='centerText' style="width:120px; text-align:center">
                                        <div id="up_1" class="synbtn-promote " style="float:left"></div> 
                                        <div id="down_1" class="synbtn-demote" style="float:left"></div>
                                        <div id="remove_1" class="synbtn-remove" style="float:left"></div>
                                    </td>                
                                </tr> 
                        <?php
							 $i++;
                                }
                            }
                        ?>
                    	</tbody>
                	</table>
                </div>
            </div>
        </div>
        <div class='row' style="padding-top:20px;" id="neststep">
            <div class='six columns'></div>
            <div class='twelve columns'>
            	<div class="tRight">
                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next1(<?php echo $id[0];?>,<?php echo $qry->num_rows;?>);" />
                </div>
            </div>
        </div>
        <?php if($checktype !=0){?><script> $('#neststepr').hide();</script> <?php } ?>
        <?php if($id[1] =='rsubmit'){?><script>$('#neststepr').show();</script> <?php } ?>
        
        <div id="emptyflag"></div>
        <div class='row' style="padding-top:20px;">
            <div class='six columns'></div>
            <div class='twelve columns'>
            	<div class="tRight" id="neststepempty">
                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right; margin-left: 10px;" value="Next Step" onClick="fn_nextempty(<?php echo $id[0];?>);" />
                </div>
                <div class="tRight" id="neststepr">
                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="<?php if($nextflag == 0){ echo "Next Step"; } else { echo "Update";}?>"  onClick="fn_nextr(<?php echo $id[0];?>,1,'<?php echo $tpct?>');" />
                </div>
            </div>
        </div>
	<script>
            $(document).ready(function(){
                loads();
                
                $("#up_1,#down_1").click(function(){
                    var row = $(this).parents("tr:first");
                     $('#neststepr').show();
                     $('#emptyflag').val('1');
                    if ($(this).is("#up_1") ) {
                        var row1 =$(this).parents("tr:first").prev().children('td').html();
                        var row2 =$(this).parents("tr:first").children('td').html();
                        $(this).parents("tr:first").prev().children('td:first').html(row2);
                        $(this).parents("tr:first").children('td:first').html(row1);
                        row.insertBefore(row.prev());
                    } else {
                        var row1 =$(this).parents("tr:first").next().children('td').html();
                        var row2 =$(this).parents("tr:first").children('td').html();
                        $(this).parents("tr:first").next().children('td:first').html(row2);
                        $(this).parents("tr:first").children('td:first').html(row1);
                        row.insertAfter(row.next());
                    }
                    loads();	
                });
                
                $("#remove_1 ").click(function() {
                    var delid =($(this).parents("tr").attr('name'));
                    var delid = delid.split("_");
                   
					
                    var dataparam = "oper=delequa&qid="+delid[1]+"&testid="+<?php echo $id[0];?>;

                    $.Zebra_Dialog('Are you sure you want to remove this Question?',
                    {
                            'type': 'confirmation',
                            'buttons': [
                                    {caption: 'No', callback: function() { }},
                                    {caption: 'Yes', callback: function() {	
                                            $.ajax({
                                                    type: 'post',
                                                    url: 'test/testassign/test-testassign-addquestiondb.php',
                                                    data: dataparam,	
                                                    async: false,
                                                    beforeSend: function(){
                                                            showloadingalert("Deleting, please wait.");	
                                                    },		
                                                    success:function(data) {
                                                            var data=data.split("~");		
                                                            if(data[0]=="success")
                                                            {
                                                                    $('.lb-content').html("Question deleted successfully");
                                                                    closeloadingalert();
                                                                    $("#ques_"+delid[1]).remove();
                                                                    $('td#que').each(function(index, element) {
                                                                            $(this).html(index+1);
                                                                            loads();
                                                                    });
                                                            }
                                                            if(data[1]==0)
                                                            {
                                                                    $('#btntest-testassign-testreview').addClass("dim");
                                                                    $('#btnstep').addClass("dim");
                                                            }
                                                    }
                                            });	
                                    }}
                            ]
                    });
                });
                
                $("#remove_2 ").click(function() {
                    var rdelid =($(this).parents("tr").attr('name'));
                    var rdelid = rdelid.split("_");
                   
                    $('#neststepr').show();
                    $('#emptyflag').val('1');
                   
                    var dataparam = "oper=delequarandom&rsct="+rdelid[1]+"&testid="+<?php echo $id[0];?>;

                    $.Zebra_Dialog('Are you sure you want to remove this section?',
                    {
                            'type': 'confirmation',
                            'buttons': [
                                    {caption: 'No', callback: function() { }},
                                    {caption: 'Yes', callback: function() {	
                                            $.ajax({
                                                    type: 'post',
                                                    url: 'test/testassign/test-testassign-addquestiondb.php',
                                                    data: dataparam,	
                                                    async: false,
                                                    beforeSend: function(){
                                                            showloadingalert("Removing, please wait.");	
                                                    },		
                                                    success:function(data) {
                                                            var data=data.split("~");		
                                                            if(data[0]=="success")
                                                            {
                                                                    $('.lb-content').html("Section removed successfully");
                                                                    closeloadingalert();
                                                                    $("#qtags_"+rdelid[1]).remove();
                                                                    $('td#rque').each(function(index, element) {
                                                                            $(this).html(index+1);
                                                                            loads();
                                                                    });
                                                            }
                                                            if(data[1]==0)
                                                            {
                                                                    $('#btntest-testassign-testrandomreview').addClass("dim");
                                                                    $('#btnstep').addClass("dim");
                                                            }
                                                    }
                                            });	
                                    }}
                            ]
                    });
                });
         
                
                function loads()
                {
                    $('div#up_1').each(function(index, element){
                         if(index==0){
                            $(this).addClass('dim');
                         }
                         else {
                            $(this).removeClass('dim');
                         }
                     });
                    
                    var total = $('div#down_1').length;	 
                    $('div#down_1').each(function(index, element){
                        if(index==total-1){
                            $(this).addClass('dim');
                        }
                        else {
                            $(this).removeClass('dim');
                        }
                    });	 
                }
            });
        </script>
    </div>    
</section>
<?php
	@include("footer.php");

