<?php
@include("sessioncheck.php");

$sectid= isset($method['sectid']) ? $method['sectid'] : '';
$sectid = explode("_",$sectid);
if($sectid[1] !=0){
    ?><script> $('#randomsubmit').show();</script> <?php
     $editidsect = $sectid[1];
}
else{
    ?><script> $('#randomsubmit').hide();</script> <?php
    $editidsect = 0;
}
if($sectid[0]==1){
     $qry = $ObjDB->QueryObject("select fld_rtest_id as id, fld_tag_id as tagsid, fld_avl_questions as avlqus ,fld_qn_assign as qnassig,fld_pct_section as pct 
                             FROM itc_test_random_questionassign where fld_id = '".$sectid[1]."' and fld_delstatus ='0'");
                             			                                                        
                                if($qry->num_rows > 0){
                                     $row=$qry->fetch_assoc();
                                      extract($row);
                                      $id = $id;
                                      $sid = $tagsid;
                                }
}
else{
$id= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
}

	
?>
<section data-type='home' id='test-testassign-addquestionrandom'>
	<script type="text/javascript" charset="utf-8">	
		$(function(){				
			var t4 = new $.TextboxList('#form_tags', {
				unique: true, 
				startEditableBit: false,
				inBetweenEditableBits: false,
				plugins: {
					autocomplete: {
						onlyFromValues:true,
						queryRemote: true,
						remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=19&diagtag=1&lesson=1&testquestion=1" },
						placeholder: ''
					}
				},
				bitsOptions:{editable:{addKeys: [188]}}	
                        });
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
                        t4.getContainer().addClass('textboxlist-loading');				
                        $.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
                                t4.plugins['autocomplete'].setValues(r);
                                t4.getContainer().removeClass('textboxlist-loading');					
                        }});
                        
                        t4.addEvent('bitAdd',function(bit) {
                               fn_checktags();                             
				removesections('#test-testassign-addquestion');
			});
			
			t4.addEvent('bitRemove',function(bit) {				
                                fn_checktags();
				removesections('#test-testassign-addquestion');
			});	
                    });
                    
                    function fn_checktags()
                    {
                        var rtestid = <?php echo $id;?>;
                        var tagid = $('#form_tags').val();
                        var dataparam = "oper=checktags"+"&tagid="+tagid+"&rtestid="+rtestid;                       
                        $.ajax({
                            type: 'post',
                            url: 'test/testassign/test-testassign-addquestiondb.php',
                            data: dataparam,
                            success:function(data) {                              
                                if(parseInt(data) == 0){
                                   fn_noquestions();
                                }
                                else{                                   
                                    $.Zebra_Dialog("This tag has been already assigned.", { 'type': 'information', 'buttons':  false, 'auto_close': 1000  });
                                    removesections('#test-testassign-testquestion');
                                    showpages("test-testassign-addquestionrandom","test/testassign/test-testassign-addquestionrandom.php?id="+rtestid);
                                }
                               
                              }
                    });
                    }
                    
                    function fn_noquestions()
                    {
                        var rtestid = <?php echo $id;?>;
                        var editsectionid = <?php echo $editidsect; ?>;
                        var tagid = $('#form_tags').val();
                        var dataparam = "oper=questioncountsec"+"&tagid="+tagid+"&rtestid="+rtestid+"&editsectionid="+editsectionid;                      
                        $.ajax({
                            type: 'post',
                            url: 'test/testassign/test-testassign-addquestiondb.php',
                            data: dataparam,
                            success:function(data) {                                
                                $('#tagqncount').html(data);                             
                                $('#qusass').val('');
                                $('#pect').val('');
                                if(parseInt(data) != 0 && data !=""){
                                    $('#randomsubmit').show();
                                }
                                else{
                                    $('#randomsubmit').hide();
                                }                               
                              }
                    });
                    }	
		
	</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Add a Random Question to Your Assessment</p>
            	<p class="dialogSubTitleLight">Select from available questions or create a new question using tags below. Click a row to view details.</p>
            </div>
        </div>
        
       <div class='row rowspacer'>
            <div class='span10 offset1'>
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>    
                            <th width="70%;">Section</th>
                            <th width="10%" class='centerText'>#Available <br>Questions</th>
                            <th width="10%" class='centerText'>#Questions <br>Assigned</th>
                            <th width="10%" class='centerText'>Percentage <br> weight %</th>
                         
                        </tr>
                    </thead>
                   
                        <tbody>
                            <tr>
                               <td width="68%;">
                                    <div class='twelve columns'>
                                        <div class="tag_well">
                                            <input type="text" name="test3"  value="" id="form_tags"/>
                                        </div>
                                    </div>
                                </td>
                                  <td width="10%" style="cursor:default;">
                                    <div id="tagqncount" class='centerText'>
                                       <?php echo $avlqus;?>
                                    </div>
                                </td>
                                <td width="10%" class='centerText'> 
                                    <input class='text' style="width: 40px;"  id="qusass" name="qusass" type='text'  value="<?php echo $qnassig;?>" onkeyup="ChkValidChar();fn_chkqnassigsection()"/>
                                </td>
                                <td width="10%" class='centerText'>
                                  <input class='text' style="width: 40px;"  id="pect" name="pect" type='text' value="<?php echo $pct;?>" onkeyup="ChkValidChar2()"/>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>            
       
        <div class='row rowspacer'>
            <div class='six columns'></div>
            <div class='twelve columns'>
                <div class='row' id="randomsubmit">
                	<div class="tRight">
                        <input type="button" id="btnstep" class="darkButton" style="width:140px; height:42px;float:right;" value="Submit" onClick="fn_randomsubmit(<?php echo $id;?>,'<?php echo $sectid[1];?>','rsubmit');" />
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <script>
        
        $("#qusass").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                }
        });

        $("#pect").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                }
        });
        function ChkValidChar() {
                var txtbx = document.getElementById("qusass").value;
                if ((txtbx.startsWith("0"))) // true
                {
                        document.getElementById("qusass").value = "";
                }
        }
        
        function ChkValidChar2() {
                var txtbx = document.getElementById("pect").value;
                if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
                {
                        document.getElementById("pect").value = "";
                }
        }
        
    </script>  
</section>
<?php
	@include("footer.php");