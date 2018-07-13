<?php
/*------
	Page - Forward Message
	Description:
		Forward the message to another user
	History:	
------*/
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-message-view')";
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	
	$subject='';
	
		$forwardmsg=$ObjDB->QueryObject("SELECT a.fld_subject AS subject , b.fld_username AS msghead, a.fld_message AS message,a.fld_created_date AS msgdate 
                                                FROM itc_message_master AS a 
                                                LEFT JOIN itc_user_master AS b ON b.fld_id =a.fld_to 
                                                WHERE a.fld_delstatus='0' AND a.fld_id='".$msgid."'");
		
	if($forwardmsg->num_rows>0){
			$res_msg = $forwardmsg->fetch_assoc();
			extract($res_msg);
		
	}
?>
<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>
<section data-type='#tools-message' id='tools-message-forward'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Forward: <?php echo $subject; ?></p>
                <p class="dialogSubTitleLight"><?php echo $msghead."-".date('h:i a m/d/y',strtotime($msgdate));?></p>
               
            </div>
        </div>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="forwardform" id="forwardform">
                 <?php	if($sessmasterprfid!=10) { ?>
                 <div class='row'>
                 	<div class='twelve columns'>
                     <dl class='field row' >   
                                    <dt class='dropdown'> <!-- class/individual -->   
                                        <div class="selectbox">
                                        Select Class/Individual<span class="fldreq">*</span> 
                                            <input type="hidden" name="sendtype" id="sendtype" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="javascript:void(0)">
                                                <span class="selectbox-option" data-option="" id="clearsubject" >Select Class/Individual to send a message </span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                              
                                                <ul role="options">
                                                        <li><a tabindex="-1" href="#" data-option="1" onclick="$('#ind').hide();$('#clas').show();$('#hiddropdowntype').val(1);$('#msgfwd').hide();">Class</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2" onclick="$('#ind').show();$('#clas').hide();$('#hiddropdowntype').val(2);$('#msgfwd').hide();">Individual</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </dt>
                                </dl>
                            </div>
                          </div>    
                          
                       <div class='row'> <!--select class to send the message-->
                        <div class='twelve columns'>
                            <dl class='field row' id='clas' style="display:none"> 
                            Select Class<span class="fldreq">*</span> 
                                <dt class='dropdown'> 
                                    <div id="clas">   
                                        <div class="selectbox">
                                            <input type="hidden" name="msgto" id="msgto" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $classid;?> " id="clear" >Select the class to send the message</span>
                                                <b class="caret1"></b>
                                            </a>
                                            
                                                    <div class="selectbox-options" >
                                                        <input type="text" class="selectbox-filter" placeholder="Search class">
                                                        <ul role="options">
                                                            <?php 
															
																$qry = $ObjDB->QueryObject("SELECT fld_id as classid, fld_class_name as uname 
                                                                                                                                                            FROM itc_class_master 
                                                                                                                                                            WHERE fld_school_id='".$schoolid."' AND fld_delstatus='0'");
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
																	extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>"  onclick="$('#msgfwd').show();"><?php echo $uname;?></a></li>
                                                                <?php
                                                                }
                                                            }
                                                            ?>     
                                                        </ul>
                                                    </div>
                                           
                                        </div>
                                    </div>
                                </dt>
                            </dl>      
                        </div>
                    </div>
                    <!--select individual to send the message-->
                    <div class='row'>
                        <div class='twelve columns'>
                            <dl class='field row' id='ind' style="display:none">
                            Select Individual<span class="fldreq">*</span>  
                                <dt class='dropdown'> 
                                    <div id="ind"> 
                                        <div class="selectbox">
                                            <input type="hidden" name="msgto1" id="msgto1" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $studid;?><?php echo $uname; ?>" id="clear">Select to individual to send the message</span>
                                                <b class="caret1"></b>
                                            </a>
                                            
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="search student">
                                                        <ul role="options">
                                                            <?php 
                                                            if($sendistid == 0 and $senshlid == 0){
                                                                if($sessmasterprfid !=5){
                                                                        $huid = $ObjDB->SelectSingleValueInt("SELECT fld_created_by   
                                                                                                            FROM itc_user_master 
                                                                                                            WHERE fld_delstatus='0' AND fld_id='".$uid."'");
                                                                    }
                                                                    else{
                                                                        $huid = $uid;
                                                                    }  
                                                                    $qry = $ObjDB->QueryObject("SELECT fld_id as studid, 
                                                                                                            concat(fld_fname,' ',fld_lname) as uname 
                                                                                                            FROM itc_user_master 
                                                                                                            WHERE fld_profile_id=10 
                                                                                                            AND fld_delstatus='0'
                                                                                                            AND fld_user_id='".$huid."'");
                                                            }
                                                            else{
                                                                $qry = $ObjDB->QueryObject("SELECT fld_id as studid, fld_username as uname 
                                                                                           FROM itc_user_master 
                                                                                           WHERE fld_profile_id=10 AND fld_delstatus='0'AND fld_school_id='".$schoolid."'");
                                                            }
																
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
																	extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $studid;?>" onclick="$('#msgfwd').show();" ><?php echo $uname;?></a></li>
                                                                <?php
                                                                }
                                                            }
                                                            ?>     
                                                        </ul>
                                                    </div>
                                        </div>
                                    </div>
                                </dt>
                            </dl>      
                        </div>
                    </div>
                    <?php } 
					else  {?>
                     <!--select teacher to send the message-->
                    <div class='row'>
                 	<div class='twelve columns'>
                    Select Teacher<span class="fldreq">*</span>
                     <dl class='field row' >   
                                    <dt class='dropdown'> 
                                        <div class="selectbox">
                                             <input type="hidden" name="teacherto" id="teacherto" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="javascript:void(0)">
                                                <span class="selectbox-option" data-option="" id="clearsubject">Select the teacher to send a message </span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="">
                                                <ul role="options">
                                                         <?php 
																$qry = $ObjDB->QueryObject("SELECT fld_id AS teacherid, concat(fld_fname,' ',fld_lname) AS uname 
																							FROM itc_user_master 
																							WHERE fld_district_id='".$sendistid."' AND  fld_school_id='".$schoolid."' 
																								AND fld_user_id='".$indid."' AND (fld_profile_id='7' OR fld_profile_id='8' OR fld_profile_id='9') 
																								AND fld_delstatus='0'AND fld_activestatus='1'");
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
																	extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>"onclick="$('#msgfwd').show();" ><?php echo $uname;?></a></li>
                                                                <?php
                                                                }
                                                            }
                                                            ?>     
                                                </ul>
                                            </div>
                                        </div>
                                    </dt>
                                </dl>
                            </div>
                          </div>    
                    
                    <?php } ?>
                    
                    <input type="hidden" id="hiddropdowntype" name="hiddropdowntype" value="3" />
                    <div class='row'>
                        <div class='twelve columns'>
                        Message<span class="fldreq">*</span>
                            <dl class='field row' >
                               <dt>
                               	<?php $msgcontent = "<br><br><br> Fwd:-".$msghead."<br><div class='messagesReFwd'>".$message."</div>";?>
                                <div contenteditable="true" id="messagefwd" class="messagesBody" style="height:350px; overflow-y:auto; word-wrap: break-word;">
                                	<?php echo $msgcontent; ?>
                                </div>
                               </dt>
                          </dl>
                        </div>
                    </div>
                    
                </form>
                <div class='row' style="padding-top:20px;">
                        
                        <p id="msgfwd" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px; display:none">
                            <a onClick="fn_forwardmsg('<?php echo $subject; ?>');">forward</a>
                        </p>
                        
                        <p class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a onClick="<?php echo $btncancel;?>">cancel</a>
                        </p>
                    </div>
            </div>
            <script type='text/javascript'>
                    function fn_loadeditor1(){
                        tinyMCE.init({
                            mode: "exact",
                            theme : "advanced",
                            elements : "messagefwd",
                            plugins : "",
                            theme_advanced_buttons1 : "bold,italic,underline,fontselect,fontsizeselect,forecolor,backcolor",
                            theme_advanced_buttons2 :"",
                            theme_advanced_buttons3 : "",
                            theme_advanced_toolbar_location : "top",
                            theme_advanced_toolbar_align : "left",
                            theme_advanced_resizing : false,
                            theme_advanced_statusbar_location: "",
                            relative_urls : false,
                            remove_script_host : false,
                            convert_urls : false
                        });
                    }
                    setTimeout("fn_loadeditor1()",500);
            </script>
            <script type="text/javascript" language="javascript">
				/***addd category validate****/
				$(function(){
					$("#forwardform").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));
							error.addClass('msg'); //.append("<span class='caret'></span>");
						},
						rules: {
							
							message:{required: true}
						},
					
						messages: {
							message:{required: "please enter message"}
							
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
        </div>
    </div>
</section>
<?php
	@include("footer.php");
