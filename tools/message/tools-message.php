<?php
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-message-list')";
	$pageid = isset($method['id']) ? $method['id'] : '';

	if($pageid==0)/*******Inbox**************/
	{
		$title="Read messages";
		$status="from";
		
	}
	else if($pageid==1)
	{
		$title="Sent messages";
		$status="to";
	}
	else if($pageid==2)
	{
		$title="Message archive";
		$status="from";
	}
?>

<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
	
	$('#tablecontents7').slimscroll({
		height:'auto',
		railVisible: false,
		allowPageScroll: false,
		railColor: '#F4F4F4',
		opacity: 9,
		color: '#88ABC2',
	});
</script>

<section data-type='#tools-message' id='tools-message'>
    <div class='container'>
        	<div class='row'>
                <div class="span10">
                    <p class="dialogTitle"><?php echo $title; ?></p>
                    <p class="dialogSubTitleLight">Select a message below</p>
                    <?php if($pageid==1 && $sessmasterprfid==2) /*******Delete All the Message code developed by Mohan M 21-11-2015************/    

                    { ?>
                        <p  onClick="fn_deleteall(<?php echo $uid?>)" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a>Delete All</a>
                        </p>
                        <?php 
                    } /*******Delete All the Message code developed by Mohan M 21-11-2015************/ ?>
                </div>
        	</div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="msgdiv"> 
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr style="cursor: default;">
                            <th style="padding-left:15px; width:30%;"><?php echo $status;?></th>
                            <th style="padding-left:15px; width:50%;">subject</th>
                            <th style="padding-left:15px; width:20%;">date</th>
                        </tr>
                    </thead>
                </table>
                <div style="max-height:400px;width:100%;margin-bottom:0px;" id="tablecontents7" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
							<?php
                            $userstype="fld_from"; 
                            if($pageid==0)/******inbox*********/
                            {
                                $extraqry="AND fld_to='".$uid."' AND fld_archive_status='0' AND fld_todelstatus='0'";
                                
                            }
                            else if($pageid==1)/*****sent box*********/
                            {
                                $extraqry="AND fld_from='".$uid."' AND fld_fromdelstatus='0' ";
                               $userstype="fld_to";  
                            }
                            else if($pageid==2)/*****archive box*********/
                            {
                                $extraqry="AND fld_to='".$uid."' AND fld_archive_status='1' AND fld_archdelstatus='0'";
                            }
                               $qrymsg=$ObjDB->QueryObject("SELECT fld_id AS msgid,(SELECT concat(fld_fname,' ',fld_lname) 
                                                            FROM itc_user_master WHERE fld_id = ".$userstype.") AS unames,
                                                                fld_from AS msg,fld_subject AS msgsubject,
                                                                fld_message AS message,fld_readstatus as readstatus, 
                                                                fld_created_date AS recdate 
                                                            FROM itc_message_master 
                                                            WHERE fld_delstatus='0' ".$extraqry." 
                                                            ORDER BY fld_id DESC  ");
                                if($qrymsg->num_rows>0){
                                        while($row = $qrymsg->fetch_assoc())
                                        {	
                                           extract($row);?>
                                           <tr  id="tr_<?php echo $msgid;?>" onclick="fn_showmsg(<?php echo $msgid;?>,<?php echo $pageid;?>);"  >
                                           <td class='<?php if($readstatus==0 && $pageid==0){ ?>messagesUnread<?php } else {?>messagesRead<?php }?>' style="padding-left:15px;width:30%;"><?php echo $unames; ?></td>
                                           <td class='<?php if($readstatus==0 && $pageid==0){ ?>messagesUnread<?php } else {?>messagesRead<?php }?>' style="padding-left:15px;width:50%;"><?php echo $msgsubject; ?></td>
                                           <td class='<?php if($readstatus==0 && $pageid==0){ ?>messagesUnread<?php } else {?>messagesRead<?php }?>' style="padding-left:15px;width:20%;"><?php echo date('m/d/y',strtotime($recdate)); ?></td>
                                           </tr>
                                    <?php	
                                  }							  
                                }
                                else
                                  {?>
                                  <tr><td colspan="3" >No Message Found </td></tr>
                            <?php }
                            ?>
                  		</tbody>
            		</table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
