<?php
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(',',$id);

$fld_startdate="0000-00-00";
$fld_enddate="0000-00-00";

$qry=$ObjDB->NonQuery("SELECT fld_schedule_id,fld_id,fld_stagename,fld_startdate,fld_enddate,fld_orientationmod,fld_numberofrotation,fld_adjacentflag FROM itc_class_triad_schedule_insstagemap WHERE fld_id='".$id[2]."' and fld_flag=1");

if($qry->num_rows>0)
{
$row=$qry->fetch_assoc();
extract($row);

if($fld_startdate=="0000-00-00")
	{
		$prevendate=$ObjDB->SelectSingleValue("SELECT  fld_enddate FROM itc_class_triad_schedule_insstagemap WHERE fld_id<'".$id[2]."' AND fld_schedule_id='".$fld_schedule_id."' AND fld_flag='1' AND fld_enddate<>'0000-00-00' ORDER BY fld_id DESC LIMIT 0,1");	
	
		
		if($prevendate!='0000-00-00')
		{
		$fld_startdate=date("Y-m-d",strtotime($prevendate. "+1 weekdays"));
	?>
     <script> fn_setenddatetriad(); </script>
    <?php
		}
	}
}

if($id[1]==1)
{
	$stageid=$id[0];
	$stagevalname='Stage '.$id[0];
	$stagetypeid=1;
	$stagetypename='Teacher Led';	
}
else if($id[0]==2 and $id[1]==2)
{
	$stageid=2;
	$stagevalname='Stage 2';
	$stagetypeid=2;
	$stagetypename='Orientation';
}
else if($id[0]==2 and $id[1]==3)
{
	$stageid=2;
	$stagevalname='Stage 2';
	$stagetypeid=3;
	$stagetypename='Triad Rotation';
}
else if($id[0]==3 and $id[1]==3)
{
	$stageid=3;
	$stagevalname='Stage 3';
	$stagetypeid=3;
	$stagetypename='Triad Rotation';
}
else if($id[0]==4 and $id[1]==3)
{
	$stageid=4;
	$stagevalname='Stage 4';
	$stagetypeid=3;
	$stagetypename='Triad Rotation';
}
else if($id[0]==5 and $id[1]==3)
{
	$stageid=5;
	$stagevalname='Stage 5';
	$stagetypeid=3;
	$stagetypename='Triad Rotation';
}



$stagename=$fld_stagename;
$orientationmodid=$fld_orientationmod;
if($fld_orientationmod==1)
{
	$orientationmodname='Orientation module';
}
else
{
	$orientationmodname='';
}

if($id[1]!='')
{
	$instype="edit";
}
else
{
	$instype="create";
}

if($qry->num_rows==0)
{
$stagename=$id[3];	
}
?>
<section data-type='#class-newclass' id='class-newclass-instructionstagetriad'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle">Instruction Stage</p>
                <p class="darkSubTitle">Add a stage of instruction to your schedule using the tools below. Click "Save Instruction" when complete.</p>
            </div>
        </div>	
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form id="stageform" name="stageform">
                        
                        	<div class='row rowspacer'>
                                <div class='four columns'>
                                Select Stage Value<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="stagevalue" id="stagevalue" value="<?php echo $stageid;?>"  onchange="fn_loadstagetypetriad()" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""><?php if($stageid!=0){ echo $stagevalname;} else{?>Select Stage Value <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($stageid==0){?>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Stage Value">
                                                        <ul role="options">
                                                      
                                                                <li><a tabindex="-1" href="#" data-option="1">Stage 1</a></li>
                                                                <li><a tabindex="-1" href="#" data-option="2">Stage 2</a></li>
                                                                <li><a tabindex="-1" href="#" data-option="3">Stage 3</a></li>
                                                                <li><a tabindex="-1" href="#" data-option="4">Stage 4</a></li>
                                                                <li><a tabindex="-1" href="#" data-option="5">Stage 5</a></li>
                                                           
                                                        </ul>
                                                    </div>
                                               <?php }?>
                                            </div>
                                        </dt>                                         
                                    </dl>
                                </div>             
							
                                <div class='four columns'>
                                 Select Stage Type<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox" id="triadstagetype">
                                                <input type="hidden" name="stagetype" id="stagetype" value="<?php echo $stagetypeid;?>"  onchange="fn_loaddefinetriad()" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""><?php if($stagetypeid!=0){ echo $stagetypename;} else{?>Select Stage Type <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($stagetypeid==0){?>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Stage Type">
                                                        <ul role="options">
                                                      
                                                                <li><a tabindex="-1" href="#" data-option="1">Teacher led</a></li>
                                                                <?php
																	if($id[1]==2)
																	{
																	?>
                                                                <li><a tabindex="-1" href="#" data-option="2">Orientation</a></li>
                                                                <?php
																	}
																	?>
                                                                <li><a tabindex="-1" href="#" data-option="3">Triad Rotation</a></li>
                                                               
                                                           
                                                        </ul>
                                                    </div>
                                               <?php }?>
                                            </div>
                                        </dt>                                         
                                    </dl>
                                </div> 
                                
                             	<div class='two columns'>
                                Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input  id="distartdate" name="distartdate" class="quantity" placeholder='start date' type='text' readonly value="<?php if($fld_startdate!='0000-00-00'){echo date('m/d/Y',strtotime($fld_startdate)) ;}?>" >
                                        </dt>                                        
                                    </dl>
                               </div>
                               
                             
                               <div class='two columns edate'>
                               End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input  id="dienddate" name="dienddate" class="quantity" placeholder='end date' type='text' readonly value="<?php if($fld_enddate!='0000-00-00'){echo date('m/d/Y',strtotime($fld_enddate)) ;}?>" >
                                        </dt>                                        
                                    </dl>
                               </div>  
							</div>
                            
                            
                         <div class='row rowspacer orientationdd' <?php if($id[1]==1 or $id[1]==3){?>style="display:none;<?php }?>">
                                <div class='four columns'>
                                Select Orientation<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="orientationmod" id="orientationmod" value="<?php echo $orientationmodid;?>"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""><?php if($orientationmodid!=0){ echo $orientationmodname;} else{?>Select Orientation type <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($orientationmodid==0){?>
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Module">
                                                        <ul role="options">
                                                      
                                                                
                                                                <li><a tabindex="-1" href="#" data-option="1">Orientation Module</a></li>
                                                               
                                                                
                                                           
                                                        </ul>
                                                    </div>
                                               <?php }?>
                                            </div>
                                        </dt>                                         
                                    </dl>
                                </div>             
							</div>
                            
                             <div class='row rowspacer rotationdd' <?php if($id[1]!=3){?>style="display:none;<?php }?>">
                                <div class='four columns'>
                                Select Rotation<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="rotation" id="rotation" value="<?php if($fld_numberofrotation!='0'){echo $fld_numberofrotation;}?>" onchange="fn_setenddatetriad();"/>
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option=""><?php if($fld_numberofrotation!=0){ echo $fld_numberofrotation;} else{?>Select Rotation <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search Rotation">
                                                        <ul role="options">
                                                      
                                                                <li><a tabindex="-1" href="#" data-option="3">3</a></li>
                                                                <li><a tabindex="-1" href="#" data-option="6">6</a></li>
                                                                
                                                           
                                                        </ul>
                                                    </div>
                                               
                                            </div>
                                        </dt>                                         
                                    </dl>
                                </div>             
							</div>
                            
                            <div class='row rowspacer'>
                            	 <div class='six columns'>
                                 Stage name<span class="fldreq">*</span>
                                    <dl class='field row' style="width:858px">
                                      <dt class='text'>
                                        <input placeholder='Stage Name' required='' type='text' id="stagename" name="stagename" value="<?php echo $stagename;?>" maxlength="100">
                                      </dt>
                                    </dl>      
               					 </div>
                            </div>
                            
                             <div class='row rowspacer'>
                            	 <div class='ten columns'>
                                 <div style="float:left;"><input type="checkbox" name="adjdate" id="adjdate" <?php if($fld_adjacentflag==1 or $fld_adjacentflag==0){?> checked="checked" <?php } ?>/> </div>
                                 <div style="float:left; padding-top:2px; padding-left:5px;"><label for="adjdate">Subsequent schedule dates will be changed when either the Start or End date is changed.</label></div>
								 </div>
								 
                            </div>
                            
                            
                            <div id="loadtriadmodule"></div>
                            <input type="hidden" name="instype" id="instype" value="<?php echo $instype;?>" />
                            <input type="hidden" name="insstageid" id="insstageid" value="<?php echo $fld_id;?>" />
                            
                             <div class="row">
                                <div class="tRight">                                    
                                    
                                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Save Instruction" onClick="fn_checktriadstagedate();" />
                                </div>
                            </div>
                            
                        </form>
                    </div>
                 </div>
             </div>
         </div>
     </div>
</section>

 <script type="text/javascript" language="javascript">
        $( "#distartdate" ).datepicker( {
            onSelect: function(dateText,inst){
             $(this).parents().parents().removeClass('error');
			  fn_setenddatetriad();
            }
          }
        );
		
		$( "#dienddate" ).datepicker( {
            onSelect: function(dateText,inst){
             $(this).parents().parents().removeClass('error');
            }
          }
        );
		
		$(function(){
			$("#stageform").validate({
				ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));
						error.addClass('msg'); 	
				},
				rules: { 
					stagevalue: { required: true },
					stagetype: { required: true },
					distartdate: { required: true },
					dienddate: { required: true },
					orientationmod: { 
					required:{ 
						depends: function(element){
								return $("#stagetype").val()==2
							}
						}
					},
					rotation: { 
					required:{ 
						depends: function(element){
								return $("#stagetype").val()==3
							}
						}
					},
					
					stagename: { required: true }
				}, 
				messages: { 
					stagevalue:{  required:  "please select stagevalue"},                
					stagetype:{  required: "please select stagetype" },
					distartdate:{  required: "please enter start date" },
					dienddate:{ required: "Select the end date", greaterThan: "Enddate must be greater"},
					orientationmod:{  required:  "please select module"},
					rotation:{	required:  "please select rotation"},									
					stagename: {  required: "please enter stage name" }										
												
				},
				highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
					$(element).addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if($(element).attr('class') == 'error'){
							$(element).parents('dl').removeClass(errorClass);
							$(element).removeClass(errorClass).addClass(validClass);
					}
				},
				onkeyup: false,
				onblur: true
			});
		});
</script>
<?php
	@include("footer.php");   
   