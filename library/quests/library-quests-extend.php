<?php
/*
	Created By - Selvakumar .VA
	Page - library-modules-extend
	Description:
	   Basically  this option using for teacher to  guide the student with extra information while students studying modules.
       Shows the newly added extend text it will have option for rename copy delete and view 
	   
	   privilages :
	   
	   anyone has created extend text another person cannot rename , delete or edit except his own extend text  
	   		
	Actions Performed:
       
	  it can be use to add, edit , copy, view and delete extend text for module
	   		
	History:
	 no - update

*/
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';
/***In variable id we will get  module id in id array of zero*****/
$id=explode(",",$id);
/**using this module id to get moulde name **/
$moduleid=$id[0];
$modulename='';
$filename='';
$moduleqry=$ObjDB->QueryObject("SELECT a.fld_module_name AS modulename,b.fld_file_name AS filename  FROM itc_module_master AS a 
                                      LEFT JOIN itc_module_version_track AS b  on a.fld_id=b.fld_mod_id
                                      WHERE a.fld_id='".$id[0]."' AND b.fld_mod_id='".$moduleid."' AND b.fld_delstatus='0'");
$rowmoduleqry = $moduleqry->fetch_assoc();
if($moduleqry->num_rows>0)
{
 extract($rowmoduleqry);	
}									  
									  
?>
<script language="javascript" type="text/javascript">
/*** here loading script file which related to extend content text****/
$.getScript('library/quests/library-extend.js');
</script>
<section data-type='2home' id='library-quests-extend'>
<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Module</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <div class='row'>
            <div class='span10 offset1' id="licenselist">
                <table id="extendtable" class='table table-hover table-striped table-bordered'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th><?php echo $modulename; ?></th>
                            <th class='centerText'>&nbsp;</th>
                            <th class='centerText'>
                                
                                <div class="bigextend" onclick="fn_showettendform('<?php echo $moduleid; ?>','0','new');" >
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 	
					   $extids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_ext_id )
													FROM itc_license_extcontent_mapping AS a 
													LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
													WHERE b.fld_school_id='".$schoolid."' and b.fld_user_id='".$indid."' AND a.fld_module_id='".$moduleid."'
														AND b.fld_delstatus='0' AND a.fld_active='1'"); 
								
                     	if($extids=='')
						{
							$extids=0;
						}
						
						
						
					   $qry=$ObjDB->QueryObject("SELECT fld_id,fld_extend_text,fld_created_name,fld_created_by,fld_module_id 
					                            FROM itc_extendtextquest_master 
												WHERE ((fld_school_id='".$schoolid."' and fld_user_id='".$indid."' ) 
												OR (fld_id IN (".$extids."))) AND fld_module_id='".$moduleid."'  AND fld_delstatus='0'"); 
												
												
												
												
					   if($qry->num_rows>0)
					   {
						   while($rowmodulextend=$qry->fetch_object()){
						  $exttendid=$rowmodulextend->fld_id;
						   $exttendtext=$rowmodulextend->fld_extend_text;
						   $exttendcrtedname=$rowmodulextend->fld_created_name;
						   $exttendcrateby=$rowmodulextend->fld_created_by;
						   $extendmoduleid=$rowmodulextend->fld_quest_id;
						   	$access=true;
								if($exttendcrateby!=$uid)
								{
									$access=false;
								}   
					    ?>
                         
                        <tr class="Btn" id="module-extend-<?php echo $exttendid; ?>">
                            <td  style="width: 170px;" id="extendtxt-<?php echo $exttendid; ?>" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendtext; ?></td>               
                            <td style="width: 150px;"  class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendcrtedname; ?></td>               
                            <td class="createnewtd">
                               <div style="margin-left: 74px;" > 
                                <div  class="rename-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>" onclick="fn_showettendform('<?php echo md5($moduleid); ?>','<?php echo $exttendid ?>','rename')"></div>
                                <div onclick="fn_showettendform('<?php echo md5($moduleid); ?>','<?php echo $exttendid ?>','copy');" class="copy-btn">
                                </div>
                                <div onclick="deleteextendtext(<?php echo $exttendid;?>);" class="delete-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>">
                                </div>
                                <div class="<?php if(!$access){ echo "view-btn";}else{ echo "edit-btn"; } ?>" 
                                onclick="showfullscreenmoduleextend('<?php echo "0,".$moduleid.",0,".$exttendid.",".$access.",".$uid;?>');">
                                
                                </div>
                               </div> 
                             </td>               
                        </tr>
                       <?php
						   }
						   
					   }
					   else
						   { ?>
                           
                           <tr class="Btn" id="module-extend-0">
                            <td colspan="3" class="createnewtd">&nbsp;&nbsp;&nbsp;No Records</td>               
                           </tr>
						 <?php  }
					   ?> 
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");