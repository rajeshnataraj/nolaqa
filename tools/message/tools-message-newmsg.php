<?php
/*------
	Page - New Message
	Description:
		Creating new message
	History:	
------*/
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-message-message')";
        
        /* file upload codeing start line*/
        $repositoryname='';
	$filetype='';
	$filetypename='';
	$filename='';
	$timestamp = time();
	$fileuploadkey=md5('nanonino' . $timestamp);
	$filetypearray=array('jpg','jpeg','png','xlsx','xls','doc','docx','pdf','ppt','pptx','odp','stl');
        /**check file extension***/
        $path=__FULLCNTASSETPATH__.$filename;
        $fileext = pathinfo($path, PATHINFO_EXTENSION);
        /* file upload codeing end line */
?>
<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
        
        /* file upload codeing start line */
        $('#messageUploader').uploadify({
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo $fileuploadkey;?>',
			'oper'      : 'message' 
		},
		 'height': 40,
		 'width':200,
		'fileSizeLimit' : '15MB',
		'swf'      : 'uploadify/uploadify.swf',
		'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
		'multi':true,
		'buttonText' : 'Attach',
		'removeCompleted' : true,
		'fileTypeExts' : '*.jpg; *.jpeg; *.png; *.xls; *.xlsx; *.docx; *.doc; *.pdf; *.ppt; *.pptx; *.odp; *.stl;',
		'fileDesc'  : "Allowed Files only",
		'onFallback' : function() {
                    alert('Flash was not detected or flash version is not supgoported.');
                    window.location="http://www.adobe.com/go/getflashplayer";
		 },
		'onUploadSuccess' : function(file, data, response) {
			filetype=file.type;
			filesize=file.size;
			/******File upload size checking created by chandru start line******/
			'<?php $totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'"); ?>'
			var filesize=file.size;
			var totsize = '<?php echo $totsize; ?>';
			
			var maxsize = 262144000; // 250 MB
			var allowsize = parseInt(maxsize)-parseInt(totsize);
			if (parseInt(allowsize) >= 1073741824) 
			{
    			var bytes = (parseInt(allowsize) / 1073741824).toFixed(2) + ' GB';
			} 
			else if (parseInt(allowsize) >= 1048576) 
			{
   				var bytes = (parseInt(allowsize) / 1048576).toFixed(2) + ' MB';
			} 
			else if (parseInt(allowsize) >= 1024) 
			{
    			var bytes = (parseInt(allowsize) / 1024).toFixed(2); // KB
				bytes = (parseInt(bytes) / 1024).toFixed(2) + ' MB'; 
			} 
			else if (parseInt(allowsize) > 1) 
			{
    			var bytes = parseInt(allowsize) + ' bytes';
			} 
			else if (parseInt(allowsize) == 1) 
			{
    			var bytes = parseInt(allowsize) + ' byte';
			} 
			else 
			{
    			var bytes = '0 byte';
			}
			<?php
			if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
		   	{
			?>
				if((parseInt(totsize) > '256901120') && (parseInt(totsize) < '262144000')) // check to greter then 245 MB and less then 250 MB   
				{
					if(parseInt(filesize) > parseInt(allowsize))
					{
						var dataparam = "oper=filedelete"+"&filename="+data;
						$.Zebra_Dialog('You have only '+bytes+' space limit.</br>Please try to upload a file with size '+bytes+' or less',
						{
							'type': 'confirmation',
							'buttons': [
							{caption: 'Ok', callback: function() {
								$.ajax({
								type: 'post',
								url: 'tools/message/tools-message-message-ajax.php',
								data: dataparam,
								beforeSend: function(){
								},
								success: function (data) {	
									$('#uploadfilename').hide();
								}
							});
							}}
							]
						});
					}
				}
				else if(parseInt(totsize) > '262144000') // check to greter then 250 mb
				{
					var dataparam = "oper=filedelete"+"&filename="+data;
					$.Zebra_Dialog('Unfortunately this file cannot be uploaded.</br>You have reached your limit 250 MB storage capacity',
					{
						'type': 'confirmation',
						'buttons': [
						{caption: 'Ok', callback: function() {
							$.ajax({
							type: 'post',
							url: 'tools/message/tools-message-message-ajax.php',
							data: dataparam,
							beforeSend: function(){
							},
							success: function (data) {	
								$('#uploadfilename').hide();
							}
						});
						}}
						]
					});
				}
				else
				{
					$('#uploadfilename').html('fileupload');
				}
				
			<?php
			} ?>
			/******File upload size checking created by chandru end line******/
			filetype=filetype.replace('.','');
                        var newname = data;
                        //Files names
                        var upname=$('#multiuploadfilename').val();
                        if(upname=='')
                        {
                            var first=$('#multiuploadfilename').val(data);
                        }
                        else
                        {
                            var first=$('#multiuploadfilename').val();
                            var multi = first+"~"+newname;
                            var files=$('#multiuploadfilename').val(multi);
                            
                        }
                        //File types
                        var upfiletype=$('#filetypeformat').val();
                        if(upfiletype=='')
                        {
                            var first=$('#filetypeformat').val(filetype);
                        }
                        else
                        {
                            var first=$('#filetypeformat').val();
                            var multi = first+"~"+filetype;
                            var filetyes=$('#filetypeformat').val(multi);
                        }
						//file size
						var mfsize = $('#multifilesize').val();
						if(mfsize == '')
						{
							var first = $('#multifilesize').val(filesize);
						}
						else
						{
							var first = $('#multifilesize').val();
							var multi = first+"~"+filesize;
							var filesize = $('#multifilesize').val(multi);
						}
                       
			if (newname.length > 30) {
                            newname = newname.substr(0, 27);
                            newname = newname+"...";
                        }
                        var fileupload=$('#multiuploadfilename').val();
                        var fileupload = fileupload.split("~").join("<br />");

                        $('#uploadfilename').html(fileupload);
			$('#messagefilename').val(data);
			$('#messagefileformat').val(filetype);
			$('#msgfilesize').val(filesize);
			 
	
		 },
		 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		}
		
	});
        /* file upload codeing end line */
</script>
<section data-type='#tools-message' id='tools-message-newmsg'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Send a message</p>
                <!--<p class="dialogSubTitleLight">Compose your new message using the form below.</p>-->
                <p class="dialogSubTitleLight">Select the recipient(s) of the message. Compose the message, and click "Send".</p>
            </div>
        </div>
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="mailform" id="mailform">
                 <?php	if($sessmasterprfid!=10) { ?>
                        <div class='row rowspacer'>
                            <?php if($sessmasterprfid!=2) { ?>
                            <div class='six columns'>
                                <dl class='field row' >   
                                    <dt class='dropdown'> <!-- class/individual -->   
                                        <div class="selectbox">
                                        Select Class/Individual<span class="fldreq">*</span> 
                                            <input type="hidden" name="sendtype" id="sendtype" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="javascript:void(0)">
                                                <span class="selectbox-option" data-option="" id="clearsubject">Select Class/Individual to send a message </span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">

                                                <ul role="options">
                                                        <li><a tabindex="-1" href="#" data-option="1" onclick="$('#ind').hide();$('#clas').show();$('#hiddropdowntype').val(1);$('#msgsend').hide();">Class</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2" onclick="$('#ind').show();$('#clas').hide();$('#hiddropdowntype').val(2);$('#msgsend').hide();">Individual</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </dt>
                                </dl>
                            </div>
                            <?php } else {?>
                            <div class="six columns">
                                Select User Type
                                <input type="hidden" name="hiddropdowntype" id="hiddropdowntype" value="4">
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                            
                                              <input type="hidden" name="msgto4" id="msgto4" value="">
                                              <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option" data-option="" id="clearsubject">Select user to send a message </span>
                                                <b class="caret1"></b>
                                              </a>
                                              <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search user" >
                                                <ul role="options">
                                                    <?php                                                    
                                                   
                                                        $userqry = $ObjDB->QueryObject("SELECT fld_profile_name as username,fld_prf_main_id as id
                                                                                           FROM itc_profile_master 
                                                                                            WHERE fld_prf_main_id NOT IN (1,3,4,11)
                                                                                             ORDER BY username ASC");
                                                        while($rowuser = $userqry->fetch_assoc()){
                                                            extract($rowuser);
                                                             ?>
                                                                <li><a href="#" data-option="<?php echo $id;?>" onclick="$('#msgsend').show();fn_showusers('<?php echo $id; ?>');"><?php echo $username;?></a></li>
                                                        <?php 
                                                        }?>       
                                                </ul>
                                              </div>
                                            </div>
                                        </dt>
                                    </dl>
                                </div> 
                             
                            <?php } ?>
                            
                            <div class='six columns' style="margin-top:30px;">
                                <dl class='field row' >   
                                    <dt>
                                      <label id="chkbox1" class="checkbox" for="" style="width:190px;">
                                        <input id="chkbox" type="checkbox" style="display:none;" value="" name="chkbox">
                                        <span></span>
                                        This message is an <a style="pointer-events: none;color:#F00">Alert</a>
                                    </label>
                                	</dt>
                                </dl>
                            </div>
                            
                            <div class="row rowspacer">
                            <div class='twelve columns' id="dpusers">
                            
                          </div>    
                        </div>
                          </div>    
                          <!--select class to send the message-->
                          <div class='row rowspacer'>
                        <div class='twelve columns'>
                            <dl class='field row' id='clas' style="display:none">  
                            Select Class<span class="fldreq">*</span>
                                <dt class='dropdown'> 
                                    <div id="clas"> 
                                        <div class="selectbox">
                                            <input type="hidden" name="msgto" id="msgto" value=""  onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $classid;?> " id="clear">Select to send message</span>
                                                <b class="caret1"></b>
                                            </a>
                                            
                                                    <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Search class">
                                                        <ul role="options">
                                                            <?php 
															
																$qry = $ObjDB->QueryObject("SELECT fld_id as classid,
																								fld_class_name as uname
																							FROM itc_class_master 
																							WHERE fld_school_id='".$schoolid."'
																								 AND fld_archive_class='0' AND fld_delstatus='0'");
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
																	extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="$('#msgsend').show();" ><?php echo $uname;?></a></li>
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
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                                <span class="selectbox-option input-medium"  data-option="<?php echo $studid;?><?php echo $uname; ?>" id="clear">Select to send message</span>
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
                                                                $qry = $ObjDB->QueryObject("SELECT fld_id as studid, 
                                                                                                       concat(fld_fname,' ',fld_lname) as uname 
                                                                                                       FROM itc_user_master 
                                                                                                       WHERE fld_profile_id=10 
                                                                                                       AND fld_delstatus='0'
                                                                                                       AND fld_school_id='".$schoolid."'");
                                                            }																					
																
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
								extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $studid;?>" onclick="$('#msgsend').show();"><?php echo $uname;?></a></li>
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
                                                <span class="selectbox-option" data-option="" id="clearsubject">Select teacher to send a message </span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="">
                                                <ul role="options">
                                                         <?php 
																$qry = $ObjDB->QueryObject("SELECT fld_id AS teacherid, concat(fld_fname,' ',fld_lname) AS uname FROM itc_user_master WHERE fld_district_id='".$sendistid."' 
AND  fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND (fld_profile_id='7' OR fld_profile_id='8' OR fld_profile_id='9') AND fld_delstatus='0'AND fld_activestatus='1'");
                                                            if($qry->num_rows > 0)
                                                            {
                                                                while($row= $qry->fetch_assoc())
                                                                {
																	extract($row);
                                                                ?>
                                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>" onclick="$('#msgsend').show();"  ><?php echo $uname;?></a></li>
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
                        <div class='six columns'>
                        Subject<span class="fldreq">*</span>
                            <dl class='field row' style="width:858px">
                                <dt class='text'>
                                	<input placeholder='Subject:' required='' type='text' id="msgsubject" name="msgsubject" value="">
                                </dt>
                            </dl>      
                        </div>
                    </div>            
                    <div class='row'>
                        <div class='twelve columns'>
                        Message<span class="fldreq">*</span>
                            <dl class='field row' >
                               <dt>
                               		<div contenteditable="true" id="message" name="message" class="messagesBody" style="height:350px; overflow-y:auto; word-wrap: break-word;"></div>
                                </dt>
                            </dl> 
                        <!-- file upload codeing start line -->
                        <div class='six columns'>	
                            Uploaded file name: 
                            <div id="uploadfilename"></div>
                            <div id="multiupload"></div>
                            <input type="hidden" id="multiuploadfilename" name="multiuploadfilename" value=""/>
                            <input type="hidden" id="filetypeformat" name="filetypeformat" value=""/>
							<input type="hidden" id="multifilesize" name="multifilesize" value=""/>
							
                            
                        </div>
                        <div class='six columns'>
							<p class='lableRight'>You can upload files in any of the following formats: .jpeg, .png, .doc, .pdf, .xls.</p><br>
                            <dl class='field row'>
                         		<input id="messageUploader" name="messageUploader" type="file" multiple>
                                <div id="queue"></div>
                            </dl>
                        </div>
                        <input type="hidden" id="messagefilename" name="messagefilename" value="<?php echo $filename;?>" />
                        <input type="hidden" id="messagefileformat" name="messagefileformat" value="<?php echo $fileext;?>" />
						<input type="hidden" id="msgfilesize" name="msgfilesize" value=""/>
                        <!-- file upload codeing end line -->
                        </div>
                    </div>
                    
                </form>
                <div class='row' style="padding-top:20px;"  >
                        
                        <p  onClick="fn_sendmsg();" id="msgsend" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px; display:none">
                            <a  id="msgsend">send</a>
                        </p>
                        
                        <p onClick="<?php echo $btncancel;?>" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >cancel</a>
                        </p>
               </div>
            </div>
            <script type="text/javascript" language="javascript">
				/***addd category validate****/
				$(function(){
					$("#mailform").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));
							error.addClass('msg'); 
						},
						
						rules: {
							sendtype:{required: true},
							msgsubject:{required: true},
							hiddropdowntype:{required: true}
						},
					
						messages: {
							
							sendtype:{required: "please select  receiver type"},
							msgsubject:{required: "please enter subject"},
							hiddropdowntype:{required: "please select  receiver"}
							
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
                        <script type='text/javascript'>
                            function fn_loadeditor1(){
                                tinyMCE.init({
                                    mode: "exact",
                                    theme : "advanced",
                                    elements : "message",
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
</section>
<?php
	@include("footer.php");
