<style>
    p{
        font-size: 1.8rem;
    }
</style>
<?php
/*------
	Page - View Message
	Description:
		View the upcoming message
	History:	
------*/
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-message')";
	$id = isset($method['id']) ? $method['id'] : '';
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	
	/*------ Variable deceleration -----*/
	$msgsubject='';
	$msghead='';
	$msgdate='';
	$msgcontent='';
	
	$usertype="fld_from";
	if($id==0)/******inbox*********/
	{
		$status="From";
		
	}
	else if($id==1)/*****sent box*********/
	{
		$status="To";
		$usertype="fld_to";
	}
	else /*****archive box*********/
	{
		$status="Archive";
	}
			$qrymsg=$ObjDB->QueryObject("SELECT a.fld_id AS msgid, CONCAT(b.fld_fname,' ',b.fld_lname) AS msghead, a.fld_subject AS msgsubject, 
											a.fld_message AS message,a.fld_readstatus, a.fld_created_date AS recdate, a.fld_from AS mesgsenderid
										FROM itc_message_master AS a
										LEFT JOIN itc_user_master AS b ON b.fld_id =".$usertype." 
										WHERE a.fld_delstatus='0' AND a.fld_id='".$msgid."'");
			if($qrymsg->num_rows>0){
				$res_msg = $qrymsg->fetch_assoc();
				extract($res_msg);
				$msghead=$msghead;
				$subject=$msgsubject;

                $msgcontent=$message;
                // The Regular Expression filter
                $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.([a-zA-Z]){2,}(\/[\/a-zA-Z0-9$\-\.\?\=\+\_\&\#\!\(\)\'\*]*)*/";

                // The Text you want to filter for urls
                $text = $msgcontent;
                if(strpos($text, 'href="') == 0){

                    // Check if there is a url in the text
                    if(preg_match($reg_exUrl, $text, $url)) {

                    // make the urls hyper links
                    $msgcontant = preg_replace($reg_exUrl, '<a target="_blank" href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $text);

                        // if no urls in the text just return the text
                        $msgcontent = $text;

                    }
                }
                else {

                    // if no urls in the text just return the text
                    $msgcontent = $text;

                }
                $msgcontent = str_replace('<a', '<a target="_blank" ', $msgcontent);

                $msgdate=$recdate;
                                $msgsenderid=$mesgsenderid;
                                
                                /*****************Changed by Mohan M*******************/
                                $timestamp=strtotime($msgdate);
                                
                                $timestamp -= 5 * 3600;
                                $startdatecst=date('m/d/y', $timestamp);
                                $sdatecsttimeh= date('h', $timestamp);
                                $sdatecsttimem= date('i', $timestamp);
                                $sdatecsttimea= date('a', $timestamp);
                                
                                $finaldate=$sdatecsttimeh.":".$sdatecsttimem." ".$sdatecsttimea." ".$startdatecst;
                                
                              /*****************Changed by Mohan M*******************/
				if($id==0 && $fld_readstatus==0 )
				{
			      $ObjDB->NonQuery("UPDATE itc_message_master 
				  				SET fld_readstatus='1' ,fld_updated_by='".$uid."' 
								WHERE fld_id='".$msgid."'");
				}
				}
                                
                        /* file upload codeing start line changed by chandru */
                        $filesname=array();
                        $filetype=array();
                        $ids=array();
                        $fileupload=$ObjDB->QueryObject("SELECT a.fld_id AS id,a.fld_messageid AS msgid,a.fld_file_name AS filname,a.fld_file_type AS filtype FROM itc_message_upload_mapping AS a 
                                                            LEFT JOIN itc_message_master AS b ON b.fld_id=a.fld_messageid WHERE b.fld_delstatus='0' AND a.fld_messageid='".$msgid."'");
                        
                        if($fileupload->num_rows>0)
                        {
                            while($row = $fileupload->fetch_assoc())
                            {
                                extract($row);
                                $ids[]=$id;
                                $filesname[]=$filname;
                                $filetype[]=$filtype;
                            }
                            
                        }
                        
                        
                       
                        /*file upload codeing end line changed by chandru */
?>
<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>
<section data-type='#tools-message' id='tools-message-view'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle"><?php echo $msgsubject;?></p>
                <p class="dialogSubTitleLight"><?php echo  $status." - ".$msghead." - ".$finaldate;?></p>
            </div>
        </div>   
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="reviewform" id="reviewform">
                    <div class='row'>
                        <div class='twelve columns'>
                            <dl class='field row' >
                               <dt>
                                    <div id="messageview" style="height:350px; overflow-y:auto; word-wrap: break-word;">
                                            <?php echo $msgcontent;?>
                                        <!-- file upload code start line -->
                                        <?php for ($i=0;$i<sizeof($filesname);$i++) { ?>
                                        <div style="padding-top:20px;">
                                       
                                            <?php
                                                echo $filesname[$i];
                                                
                                                 $filename=$ObjDB->SelectSingleValue("SELECT fld_file_name AS filname, fld_file_type AS filtype FROM itc_message_upload_mapping 
                                                                                                    WHERE fld_messageid='".$msgid."' AND fld_id='".$ids[$i]."'");                                                 
                                                    
                                                 
                                                 
                                            ?>
                                            <?php  if($filetype[$i]=='pdf'){ ?>
                                            <p  <a href="#message" onClick="fn_download(<?php echo $msgid?>,<?php echo $ids[$i]?>)" class='darkButton' style="float: right;height: 25px;margin-right: 300px;padding-bottom: 3px;padding-top: 4px;width: 125px;"</a>
                                                 <a>preview</a>
                                            </p>
                                          
                                           <?php } else { ?><p  <a href="#message" target=_new onClick="fn_download(<?php echo $msgid?>,<?php echo $ids[$i]?>)" class='darkButton' style="float: right;height: 25px;margin-right: 300px;padding-bottom: 3px;padding-top: 4px;width: 125px;"</a>
                                                                <a>preview</a>
                                                          </p><?php } ?>
                                            
                                    </div>
                                        <?php } ?>
                                       <!-- file upload code end line -->

                                    </div>
                                </dt>
                          </dl>      
                        </div>
                    </div>                   
                   
                    <div class='row' style="padding-top:20px;">
                    
                        <p  onClick="fn_delete(<?php echo $msgid?>,<?php echo $id?>)" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a>delete</a>
                        </p>
                        <?php if($id==0){ ?>
                        <p onClick="fn_archive(<?php echo $msgid?>)" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >archive</a>
                        </p>
                        <?php if($sessmasterprfid !=6 and $sessmasterprfid !=2) {?>
                        <p onClick="fn_forward(<?php echo $msgid?>);" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >forward</a>
                        </p>
                        <?php } if($msgsenderid!='2'){ /*******Delete All the Message code developed by Mohan M 21-11-2015************/ ?>
                        <p onClick="fn_replymsg(<?php echo $msgid; ?>);" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >reply</a>
                        </p>
                        <?php } } ?>
                        <p onClick="<?php echo $btncancel;?>" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >back</a>
                        </p>
                </div>
                 </form>  
                </div>
            </div>
        </div>
</section>
<?php
	@include("footer.php");  

