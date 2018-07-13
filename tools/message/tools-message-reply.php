<?php
/*------
	Page - Reply Message
	Description:
		Reply the particular message
	History:	
------*/
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-message-view')";
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	
	/*----- Variable deceleration ---*/
	$subject='';
	
	$replymsg=$ObjDB->QueryObject("SELECT a.fld_id AS MID, b.fld_username AS msghead , 
									a.fld_subject AS SUBJECT, a.fld_message AS message,a.fld_created_date AS msgdate 
								FROM itc_message_master AS a 
								LEFT JOIN itc_user_master AS b ON b.fld_id =a.fld_to
								WHERE a.fld_delstatus='0' AND a.fld_id='".$msgid."'");
	if($replymsg->num_rows>0){
			$res_msg = $replymsg->fetch_assoc();
			extract($res_msg);
	}
?>

<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>

<section data-type='#tools-message' id='tools-message-reply'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Reply: <?php echo $subject; ?></p>
                <p class="dialogSubTitleLight"><?php echo $msghead."-".date('h:i a m/d/y',strtotime($msgdate));?></p>
            </div>
        </div>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="reviewform" id="reviewform">
                    <div class='row'>
                      <div class='twelve columns'>
                      Message<span class="fldreq">*</span>
                          <dl class='field row' >
                               <dt>
                               		<?php $msgcontent = '<br><br><br>------<br><div class="messagesReFwd">'.$message.'</div>';?>
                                	<div contenteditable="true" id="messagereply" class="messagesBody" style="height:350px; overflow-y:auto; word-wrap: break-word;">
                                		<?php echo $msgcontent; ?>
                                	</div>
                          	   </dt>
                          </dl>
                        </div>
                    </div>
                	<div class='row' style="padding-top:20px;">
               			 <?php 
							$senderid=$ObjDB->QueryObject("SELECT fld_id as mid ,fld_from AS sender , fld_subject as subject 
															FROM itc_message_master 
															WHERE fld_delstatus='0' AND fld_id='".$msgid."'");
							if($senderid->num_rows>0){
								$resmsg = $senderid->fetch_assoc();
								extract($resmsg);
							}
						?>
                        
                        <p onClick="fn_reply(<?php echo $sender;?>,<?php echo $msgid; ?>,'<?php echo $subject; ?>');" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >send</a>
                        </p>
                        
                        <p onClick="<?php echo $btncancel;?>" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >cancel</a>
                        </p>
                    </div>
                 </form>
                <script type='text/javascript'>
                    function fn_loadeditor1(){
                        tinyMCE.init({
                            mode: "exact",
                            theme : "advanced",
                            elements : "messagereply",
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
                </div>
            </div>
    </div>
</section>
<?php
	@include("footer.php");
