<?php
/*
	Created By - Vijayalakshmi PHP Programmer
	Page - library-expedition-materiallist
	Description:
	          Shows the newly added material extend name which has 5 option as rename, copy, delete , edit and view. 
        privilages :
	          anyone has created materials for a task   by  another person's existing one that cannot rename , delete or edit except his own materials for a task  
        Actions Performed:
        	  it can be used to add, edit , copy, view and delete materials for a task to each expedition
	History:
	 Update on :21/5/2014 by Vijayalakshmi PHP Programmer (copy the existing file with existing records)
 * DB: itc_exp_extendmaterials_master
*/
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$exp_id=$id[0];
$exp_name='';
$exped_qry = $ObjDB->QueryObject("SELECT fld_exp_name as expname FROM itc_exp_master 
					WHERE fld_id='".$exp_id."' AND fld_delstatus='0'");
$rowexped_qry = $exped_qry->fetch_assoc();
    if($exped_qry->num_rows>0)
    {
     extract($rowexped_qry);	
    }									  
									  
?>
<script language="javascript" type="text/javascript">
/*** here loading script file which related to extend content text****/
    $.getScript('library/expedition/library-extend.js');
    $('#tablecontentsm').slimscroll({
            height:'auto',
            size: '7px',
            alwaysVisible: true,
            wheelstep: 1,
            railVisible: false,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 9,
            color: '#88ABC2',
    });
</script>
<section data-type='library_expedition' id='library-expedition-materiallist'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Materials List</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <div class='row'>
            <div class='span10 offset1' id="licenselist">
                <table id="extendtable" class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="20%"><?php echo $expname; ?></th>
                            <th width="20%" class='centerText'>&nbsp;</th>
                            <th class='centerText'>
                                
          <!-- functions are called from /library/expedition/library-extend.js -->  
          
                                <div class="add-btn" onclick="fn_showextendpopform('<?php echo md5($exp_id); ?>','0','new');this.disabled='disabled';">
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
                
                <!--   starts to fetch the extend content from 'itc_exp_extendmaterials_master' table -->	
                
                <?php 
            
                    $extids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_ext_id ) FROM itc_license_extcontent_mapping AS a 
                                                            LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
                                                            WHERE b.fld_school_id='".$schoolid."' and b.fld_user_id='".$indid."' AND a.fld_module_id='".$exp_id."'
                                                            AND b.fld_delstatus='0' AND a.fld_active='1'"); 
                    if($extids=='')
                    {
                        $extids=0;
                    }   
               
                  $qry=$ObjDB->QueryObject("SELECT fld_id,fld_extend_text,fld_created_name,fld_created_by,fld_exp_id 
                                                FROM itc_exp_extendmaterials_master WHERE ((fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') 
                                                OR (fld_id IN (".$extids."))) AND fld_exp_id='".$exp_id."'  AND fld_delstatus='0'"); 
               ?>
                <div style="max-height:400px;width:100%;" id="tablecontentsm" >
                     <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="extendtable">
                         <tbody>
                             <?php 
                           if($qry->num_rows>0)
                           {
                               while($rowexpextend=$qry->fetch_object()){
                               $exttendid=$rowexpextend->fld_id;
                               $exttendtext=$rowexpextend->fld_extend_text;
                               $exttendcrtedname=$rowexpextend->fld_created_name;
                               $exttendcrateby=$rowexpextend->fld_created_by;
                               $extendexpid=$rowexpextend->fld_exp_id;
                                $access=true;
                                    if($exttendcrateby!=$uid)
                                    {
                                        $access=false;
                                        
                                    } 
                                   
                            ?>
                              <!-- functions are called from /library/expedition/library-extend.js -->  
                    <tr class="Btn" id="exp-extend-<?php echo $exttendid; ?>">
                     <td  width="20%" id="extendtxt-<?php echo $exttendid; ?>" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendtext; ?></td>               
                     <td width="20%" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendcrtedname; ?></td>               
                     <td class="createnewtd">
                        <div style="margin-left: 10px;" > 
                             <!-- For view button -->
                         <div  class="view-btn mainBtn" name="<?php echo $exp_id; ?>,<?php echo $exttendid ?>" id="btnlibrary-expedition-viewtaskmatlist"></div>
                            <!-- For rename button -->
                         <?php   if($exttendcrateby!=$uid) {  ?>
                         <div  class="rename-btn dim"></div>
                         <!-- For copy button -->
                         <div class="copy-btn dim"></div>
                          <!-- For Delete button -->
                         <div class="delete-btn dim"></div>
                           <!-- For Edit content button -->
                         <div class="edit-btn mainBtn dim" name="" id=""></div>
                         <?php } else { ?>
                         <div  class="rename-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>" onclick="fn_showextendform('<?php echo md5($exp_id); ?>','<?php echo $exttendid ?>','rename')"></div>
                         <!-- For copy button -->
                         <div onclick="fn_showextendform('<?php echo md5($exp_id); ?>','<?php echo $exttendid ?>','copy');" class="copy-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>">
                         </div>
                          <!-- For Delete button -->
                         <div onclick="deleteextendtext(<?php echo $exttendid;?>);" class="delete-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>">
                         </div>
                           <!-- For Edit content button -->
                         <div class="edit-btn mainBtn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>" name="<?php echo $exp_id; ?>,<?php echo $exttendid; ?>,<?php echo $uid; ?>,<?php echo $access; ?>" id="btnlibrary-expedition-viewmateriallist">
                         </div>
                         <?php } ?>
                        </div> 
                      </td>               
                 </tr>
                    <?php
                        }

                    }
                    else
                        { ?>

                        <tr class="Btn" id="exp-extend-0">
                         <td colspan="3" class="createnewtd">&nbsp;&nbsp;&nbsp;No Records</td>               
                        </tr>
                      <?php  }    ?> 
                   </tbody>
              </table>
                </div>
          </div>
        </div>
    </div>
</section>
<?php

@include("footer.php");