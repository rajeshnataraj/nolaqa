<?php 
	@include("sessioncheck.php");	
?> 
<script type="text/javascript" charset="utf-8">	
	$.getScript("tools/mytags/tools-mytags.js");
	
	$('#tablecontents').slimscroll({
		height:'auto',
		railVisible: false,
                size:'7px',
                alwaysVisible: true,
		allowPageScroll: false,
		railColor: '#F4F4F4',
		opacity: 9,
		color: '#88ABC2',
                wheelStep: 1
	});		
</script>
<section data-type='2home' id='tools-mytags'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php if($sessmasterprfid==2){ echo "Tags"; }else{ echo "My Tags"; } ?></p>
                <!--<p class="dialogSubTitleLight">Select a Tag to view its associations or delete a Tag by clicking the garbage bin.</p>-->
                <p class="dialogSubTitleLight">List displays custom tags you have created</p>
            </div>
        </div>        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="taglist"> 
            	<table id="test" class='table table-hover table-striped table-bordered setbordertopradius'>
                	<thead class='tableHeadText'>
                        <tr>
                        	<th width="10%"></th>
                            <th width="60%">Tag Name</th>
                            <?php if($sessmasterprfid==2){?>
                            	<th width="20%">Tag type</th>
                            <?php }?>
                            <th width="10%" class='centerText'>Delete Tag</th>                                                                  
                        </tr>
                    </thead> 
                 </table>
                <div style="max-height:400px;width:100%" id="tablecontents"  >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>                  
                        <tbody>
                            <?php 
                            
                               if($sessmasterprfid==2){
                                $qrytags = $ObjDB->QueryObject("SELECT fld_id AS tid,fld_tag_name AS tagname, fld_tag_type FROM itc_main_tag_master 
                                WHERE fld_profile_id='2' AND fld_delstatus='0' ORDER BY fld_tag_name");
                               }
                               else
                               {
                                $qrytags = $ObjDB->QueryObject("SELECT fld_id AS tid,fld_tag_name AS tagname, fld_tag_type FROM itc_main_tag_master 
                                WHERE fld_created_by='".$uid."' AND fld_delstatus='0' ORDER BY fld_tag_name");
                               }
                                if($qrytags->num_rows>0){
                                    while($restag=$qrytags->fetch_assoc()){
                                        extract($restag);
                            			?>
                                        <tr id="tr_<?php echo $tid; ?>" name="<?php echo $tid; ?>">
                                            <td width="10%" onclick="fn_rowclick(<?php echo $tid; ?>)">
                                            	<div class="icon-synergy-tags"></div>
                                            </td>
                                        
                                            <td width="60%">
                                            	<input type="text" id="txttagname_<?php echo $tid; ?>" class="tagtextbox" value="<?php echo $tagname; ?>" onKeyUp="if(event.keyCode==13) {fn_rename(<?php echo $tid; ?>)}" />
                                            </td>
                                        
											<?php if($sessmasterprfid==2){?>
                                            <td width="20%">                                            	
												<input name="radio<?php echo $tid; ?>" id="radio1_<?php echo $tid; ?>" style="cursor:pointer" value="0" onclick="fn_changetagtype(<?php echo $tid; ?>,0)" type="radio" <?php if($fld_tag_type==0) echo 'checked="checked"'; ?> >
                                                 	<label for="radio1_<?php echo $tid; ?>" style="cursor:pointer">Private</label> 
												<input name="radio<?php echo $tid; ?>" id="radio2_<?php echo $tid; ?>" style="cursor:pointer" value="1" onclick="fn_changetagtype(<?php echo $tid; ?>,1)" type="radio" <?php if($fld_tag_type==1) echo 'checked="checked"'; ?>>
													<label for="radio2_<?php echo $tid; ?>" style="cursor:pointer">Public</label>   
                                            </td>
                                            <?php }?>
                                            
                                            <td width="10%" style="padding-left:4%">
                                            	<div class="synbtn-remove" onclick="fn_deletetag(<?php echo $tid; ?>)"></div>
                                            </td>
                                        </tr>
                            <?php }
                                }
                            else{?>
                            <tr>
                                <td colspan="3">No Tags Found</td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tRight" id="submit" style="display:none;">
               <a onclick="fn_submitlist()">
                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;margin-top:20px;" value="Submit" />
                </a>
            </div>    
		</div>          
    </div>   
</section>
<?php
	@include("footer.php");