<?php 
	@include("sessioncheck.php");
	
?>
<script language="JavaScript">
$('body').css({'overflow': ''}); 
	$('body').removeAttr("style");
	$('.remarkContainer').remove();
</script>
<?php	
	$id = isset($method['id']) ? $method['id'] : '0';

		?>

<section data-type='#test-testassign' id='test-testassign-testopenquestion'>
	
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Review Your Open Response Answer</p>
            	<p class="dialogSubTitleLight">Review your Open Response Answer details below.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <div class="row">
                    <div class="four columns">

                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Assessment List:</div>
                            <?php 
                            
                    $qryassdetails = $ObjDB->QueryObject("SELECT a.fld_test_name as testname,a.fld_id as id FROM itc_test_master as a 
                                      left join itc_test_student_answer_track as b on a.fld_id=b.fld_test_id 
                                      where b.fld_answer_type_id='15' and b.fld_delstatus='0' and a.fld_delstatus='0' and a.fld_created_by='".$uid."' group by b.fld_test_id order by a.fld_id desc"); 
                            
                                    ?>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>
                                            <style>
                                                .dropdown .caret1
                                                {

                                                    float: left;
                                                    margin-top: 10px;
                                                }
                                            </style>   
                                            <div class="selectbox">
                                                <input type="hidden" name="selectass" id="selectass" value="" /><!--  -->
                                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                        <span class="selectbox-option input-medium" data-option=""  style="width:248px;">Select Assessment</span>
                                                        <b class="caret1"></b>
                                                    </a>
                                                    <div class="selectbox-options">
                                                         <input type="text" class="selectbox-filter" placeholder="Search Assessment ">
                                                         <ul role="options" style="width:270px;">
                                                             <?php 
                                                                 if($qryassdetails->num_rows>0){
                                                                     $j=1;
                                                                     while($rowassdetails = $qryassdetails->fetch_assoc())
                                                                     {
                                                                     extract($rowassdetails);
                                                                     
                                                                         if(strlen($testname)>50){ $tempassname = substr(strip_tags($testname),0,50)."..."; } else { $tempassname =strip_tags($testname);}
                                                             ?>
                                                             <li><a tabindex="-1" href="#" data-option="<?php echo $id;?>" onclick="fn_showopenresponsestudent(<?php echo $id;?>)"><?php echo $tempassname; ?></a></li>
                                                             <?php
                                                                 $j++;
                                                                      }
                                                                 }
                                                                 else
                                                                 { ?>
                                                                 <div class="wizardReportData">No Assessment</div><?php
                                                                 }
                                                             ?>
                                                         </ul>
                                                     </div>
                                            </div>
                                        </dt>                                       
                                    </dl>
                                 
                        </div>   
                    </div>
                 </div>   
                
                <div class='row rowspacer'>
                          <div class='' id="loadstudent" style="border:0px;"> 
                        </div>  
                    </div>
                  
                
                    <div class="">
                        <div class='rowspacer'>
                          <div class='' id="loadquestions" style="border:0px;"> </div>                     
        </div>
    </div>
 </div>
    </div>
 </div>
</section>