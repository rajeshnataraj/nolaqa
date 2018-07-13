<?php
/*
	Created By - Chandrasekar

*/
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';
/***In variable id we will get  module id in id array of zero*****/
$id=explode(",",$id);
/**using this module id to get moulde name **/

$expid=$id[0];
$expname='';
$filename='';
$expqry=$ObjDB->QueryObject("SELECT a.fld_exp_name AS expname,b.fld_file_name AS filename, fld_exptype AS exptype  FROM itc_exp_master AS a 
                                      LEFT JOIN itc_exp_version_track AS b  on a.fld_id=b.fld_exp_id
                                      WHERE a.fld_id='".$id[0]."' AND b.fld_exp_id='".$expid."' AND b.fld_delstatus='0'");
$rowexpqry = $expqry->fetch_assoc();
if($expqry->num_rows>0)
{
 extract($rowexpqry);	
}									  
									  
?>
<script language="javascript" type="text/javascript">
/*** here loading script file which related to extend content text****/
$.getScript('library/expedition/library-expedition-expextend.js');

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
<section data-type='2home' id='library-expedition-expextend'>
<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Expeditions</p>
                <p class="dialogSubTitle">&nbsp;</p>     
            </div>
        </div>
        <div class='row'>
            <div class='span10 offset1' id="licenselist">
                <table id="extendtable" class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th width="22%"><?php echo $expname; ?></th>
                            <th width="22%" class='centerText'>&nbsp;</th>
                            <th class='centerText'>
                                <div class="bigextend" onclick="fn_showettendform('<?php echo $expid; ?>','0','new');" ></div>
                            </th>
                        </tr>
                    </thead>
                </table>
                <?php 
                
                $extids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_ext_id )
                                                            FROM itc_license_extcontent_mapping AS a 
                                                            LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
                                                            WHERE b.fld_school_id='".$schoolid."' and b.fld_user_id='".$indid."' AND a.fld_module_id='".$expid."'
                                                                AND b.fld_delstatus='0' AND a.fld_active='1'"); 
                if($extids=='')
                {
                    $extids=0;
                }

                $qry=$ObjDB->QueryObject("SELECT fld_id,fld_extend_text,fld_created_name,fld_created_by,fld_exp_id 
                                            FROM itc_exp_extendtext_master 
                                                WHERE ((fld_school_id='".$schoolid."' and fld_user_id='".$indid."' ) 
                                                OR (fld_id IN (".$extids."))) AND fld_exp_id='".$expid."'  AND fld_delstatus='0'"); 
                 ?>
                <div style="max-height:400px;width:100%;" id="tablecontentsm" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="extendtable">
                        <tbody>
                        <?php 
                           if($qry->num_rows>0)
                           {
                                while($rowexpextend=$qry->fetch_object())
                                {
                                $exttendid=$rowexpextend->fld_id;
                                $exttendtext=$rowexpextend->fld_extend_text;
                                $exttendcrtedname=$rowexpextend->fld_created_name;
                                $exttendcrateby=$rowexpextend->fld_created_by;
                                $extendmoduleid=$rowexpextend->fld_exp_id;
                                 $access=true;
                                     if($exttendcrateby!=$uid)
                                     {
                                         $access=false;
                                     }   
                            ?>
                             
                            <tr class="Btn" id="module-extend-<?php echo $exttendid; ?>">
                                <td  width="22%" id="extendtxt-<?php echo $exttendid; ?>" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendtext; ?></td>               
                                <td width="22%" class="createnewtd">&nbsp;&nbsp;&nbsp;<?php echo $exttendcrtedname; ?></td>               
                                <td class="createnewtd">
                                   <div style="margin-left: 74px;" > 
                                        <div  class="rename-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>" onclick="fn_showettendform('<?php echo md5($expid); ?>','<?php echo $exttendid ?>','rename')"></div>
                                        <div onclick="fn_showettendform('<?php echo $expid; ?>','<?php echo $exttendid ?>','copy');" class="copy-btn">
                                        </div>
                                        <div onclick="deleteextendtext(<?php echo $exttendid;?>);" class="delete-btn <?php if($exttendcrateby!=$uid){ echo "dim";} ?>">
                                        </div>
                                        
                                        <div class="<?php if(!$access){ echo "view-btn";}else{ echo "edit-btn"; } ?>"
                                            onclick="loadiframes12(<?php echo $id[0];?>,<?php echo $uid;?>,<?php echo $exptype;?>,<?php echo $exttendid;?>)"> <!--$exptype-->
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
    </div>
</section>
<?php
	@include("footer.php");