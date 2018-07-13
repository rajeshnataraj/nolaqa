<?php
/*
	Created By - Vijayalakshmi PHP Programmer
	Page - library-lessons-extend
	Description:
	   Basically  this option is used for teacher to  guide the student with extra information while students studying lessons.
       Shows the newly added extend text it has 4 option as rename, copy, delete and view. 
	   
	   privilages :
	   
	   anyone has created extend text  by  another person's existing one that cannot rename , delete or edit except his own extend text  
	   		
	Actions Performed:
       
	  it can be used to add, edit , copy, view and delete extend text for lesson
	   		
	History:
	 no - update

*/
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';

/***In variable id we will get  lesson id in id array of zero*****/
$id=explode(",",$id);
/**using this lesson id to get lesson name **/
$lessonid=$id[0];
$lessonname='';
$filename='';

/*
 * Select the lessonname and zipname by using 'itc_ipl_master', 'itc_ipl_version_track' tables
 */
$lessonqry=$ObjDB->QueryObject("SELECT iplmaster.fld_ipl_name AS lessonname,iplversion.fld_zip_name AS zipname FROM itc_ipl_master AS iplmaster 
                                      LEFT JOIN itc_ipl_version_track AS iplversion ON iplmaster.fld_id=iplversion.fld_ipl_id
                                      WHERE iplmaster.fld_id='".$id[0]."' AND iplversion.fld_ipl_id='".$lessonid."' AND iplversion.fld_delstatus='0'");
$rowlessonqry = $lessonqry->fetch_assoc();
    if($lessonqry->num_rows>0)
    {
     extract($rowlessonqry);	
    }									  
									  
?>
<script language="javascript" type="text/javascript">
/*** here loading script file which related to extend content text****/
$.getScript('library/lessons/library-extend.js');

$('#tablecontentsm').slimscroll({
	height:'auto',
	size: '3px',
	railVisible: false,
	allowPageScroll: false,
	railColor: '#F4F4F4',
	opacity: 9,
	color: '#88ABC2',
});
</script>
<section data-type='2home' id='library-lessons-extend'>
<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Lesson</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <div class='row'>
            <div class='span10 offset1' id="licenselist">
                <table id="extendtable" class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="22%"><?php echo $lessonname; ?></th>
                            <th width="22%" class='centerText'>&nbsp;</th>
                            <th class='centerText'>
                                
          <!-- functions are called from /library/lessons/library-extend.js -->  
          
                                <div class="bigextend" onclick="fn_showextendpopform('<?php echo md5($lessonid); ?>','0','new');">
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
                
		<!--   starts to fetch the extend content from 'itc_ipl_extendtext_master' table -->	
                
                <?php 
              
                    $extids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_ext_id ) FROM itc_license_extcontent_mapping AS a 
                                                            LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
                                                            WHERE b.fld_school_id='".$schoolid."' and b.fld_user_id='".$indid."' AND a.fld_module_id='".$lessonid."'
                                                            AND b.fld_delstatus='0' AND a.fld_active='1'"); 
                    if($extids=='')
                    {
                        $extids=0;
                    }   
                  
                  $qry=$ObjDB->QueryObject("SELECT fld_id,fld_extend_text,fld_created_name,fld_created_by,fld_lesson_id 
                                                FROM itc_ipl_extendtext_master WHERE ((fld_school_id='".$schoolid."' and fld_user_id='".$indid."') 
                                                OR (fld_id IN (".$extids."))) AND fld_lesson_id='".$lessonid."'  AND fld_delstatus='0'"); 
               ?>
                <div style="max-height:400px;width:100%;" id="tablecontentsm" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="extendtable">
                        <tbody>
						<?php 
                           if($qry->num_rows>0)
                           {
                               while($rowlessonextend=$qry->fetch_object()){
                              $exttendid=$rowlessonextend->fld_id;
                               $exttendtext=$rowlessonextend->fld_extend_text;
                               $exttendcrtedname=$rowlessonextend->fld_created_name;
                               $exttendcrateby=$rowlessonextend->fld_created_by;
                               $extendlessonid=$rowlessonextend->fld_lesson_id;
                                $access=true;
                                    if($exttendcrateby!=$uid)
                                    {
                                        $access=false;
                                        
                                    } 
                                   
                            ?>
                            
                  <!-- functions are called from /library/lessons/library-extend.js -->  
                  
                            <tr class="Btn" id="lesson-extend-<?php echo $exttendid; ?>">
                                <td  width="22%" id="extendtxt-<?php echo $exttendid; ?>" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendtext; ?></td>               
                                <td width="22%" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendcrtedname; ?></td>               
                                <td class="createnewtd">
                                   <div style="margin-left: 74px;" > 
                                       <!-- For rename button -->
                                    <div  class="rename-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>" onclick="fn_showextendform('<?php echo md5($lessonid); ?>','<?php echo $exttendid ?>','rename')"></div>
                                    <!-- For copy button -->
                                    <div onclick="fn_showextendform('<?php echo md5($lessonid); ?>','<?php echo $exttendid ?>','copy');" class="copy-btn">
                                    </div>
                                     <!-- For Delete button -->
                                    <div onclick="deleteextendtext(<?php echo $exttendid;?>);" class="delete-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>">
                                    </div>
                                      <!-- For Edit content button -->
                                    <div class="<?php if(!$access){ echo "view-btn"; $access =0;}else{ echo "edit-btn"; } ?>" 
                                    onclick="showfullscreenlessonextend('<?php echo $zipname; ?>',<?php echo $lessonid; ?>,<?php echo $exttendid; ?>,<?php echo $uid; ?>,<?php echo $access; ?>);">
                                    
                                    </div>
                                   </div> 
                                 </td>               
                            </tr>
                           <?php
                               }
                               
                           }
                           else
                               { ?>
                               
                               <tr class="Btn" id="lesson-extend-0">
                                <td colspan="3" class="createnewtd">&nbsp;&nbsp;&nbsp;No Records</td>               
                               </tr>
                             <?php  }    ?> 
                <!--   ends to fetch the extend content from 'itc_ipl_extendtext_master' table -->	
                        </tbody>
                	</table>
                </div>
            
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");