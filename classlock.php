<?php
	@include("sessioncheck.php");
?>

<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>

<section data-type='#classlock' id='classlock'>
    <div class='container'>
        	<div class='row'>
                <div class="span10">
                    <p class="dialogTitle">Locked Class</p>
                    <p class="dialogSubTitleLight">Below is a list of class which are currently locked.</p>
                </div>
        	</div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' > 
                <table class='table table-hover table-striped table-bordered'>
                    <thead class='tableHeadText'>
                        <tr style="cursor:default;">
                       		 <th style="padding-left:15px;">S.No</th>
                            <th style="padding-left:15px;">Class Name</th>
                             <th style="padding-left:15px;">Unlock</th>
                        </tr>
                    </thead>
                	<tbody>
                    <div id="studentdiv"> 
                    <?php 
                         $qry = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_class_name FROM itc_class_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON b.fld_class_id=a.fld_id WHERE (b.fld_teacher_id='".$uid."' or a.fld_created_by='".$uid."') AND a.fld_lock=1 AND a.fld_delstatus='0' AND b.fld_flag='1'");
                                if($qry->num_rows>0){
								   $i=1;
								   while($res = $qry->fetch_assoc()){
                                    	extract($res);
								?>
                               
                       <tr>
                       	    <td style="padding-left:15px; cursor:default;"><?php echo $i; ?></td>
						   <td style="padding-left:15px; cursor:default;"><?php echo $fld_class_name; ?></td>
                           <td style="padding-left:35px; " class="icon-synergy-locked " onClick="fn_unlock(<?php echo $fld_id;?>,'<?php echo $fld_class_name;?>');"></td>
                           
                       </tr> 
                     </div>  
                    <?php 
							$i++;
									} // while ends
                                } // if ends
								else
								{?>
									<tr><td colspan="3">No Class to unlock <script>$('#classloka').hide();</script></td></tr>
						 <?php }
                            ?>
                  	</tbody>
            	</table>
                <script language="javascript" type="text/javascript">
                                function fn_unlock(id,classname){					
                                    var dataparam = "oper=classunlock&classid="+id;	 
                                    $.ajax({
                                        type: "POST",
                                        url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
                                        data: dataparam,
                                        beforeSend:function(){			
                                            showloadingalert("Loading, please wait.");
                                        },
                                        success: function(data){			
                                            closeloadingalert();
											showloadingalert(classname+" has been Unlocked Successfully");
											setTimeout("closeloadingalert();",400);
											setTimeout('removesections("#home");',500);
											setTimeout('showpages("classlock","classlock.php")',500);
                                    	}
                                    });	
                                }
                            </script>
            </div>
        </div>
    </div>
</section>
<?php
@include("footer.php");