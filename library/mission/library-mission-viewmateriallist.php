<?php
/*
 * created by Mohan M PHP Programmer
 * Description :To view and edit the materials along with destinations and tasks name list reg. expedition
 * CRUD appln is used for selecting materials for particular expedition
 * DB:itc_mis_extendmaterials_mapping
 */
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$expid = $id[0];
$extendid = $id[1];
$uid = $id[2];
$access = $id[3];
$flag = 0;

?>
<section data-type='2home' id='library-mission-viewmateriallist'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View / edit</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
      <!--Load the Expedition Form-->
        <div class='row formBase'> 
          <form id="extmaterialform">
           <div class="row rowspacer <?php if($flag==1){?> dim <?php } ?>">
            <table class='table table-striped table-bordered' id="mytable">
                <thead class='tableHeadText'>
                    <tr>                        
                        <th class='centerText'>Destinations</th>
                        <th class='centerText'>Tasks</th>
                        <th class='centerText'>Materials</th>
                        <th class='centerText'>edit or delete</th>
                    </tr>
                </thead>
                <tbody> 
                  <?php
                   $qryviewexp_materials=$ObjDB->QueryObject("SELECT S2.fld_dest_name as destname,S3.fld_task_name as taskname,S4.fld_materials as materialname,S1.fld_id as expextendmaterials,S1.fld_created_by as userid
                                                                FROM itc_mis_extendmaterials_mapping AS S1 
                                                              INNER JOIN itc_mis_destination_master AS S2 ON S2.fld_id = S1.fld_destination
                                                              INNER JOIN itc_mis_task_master AS S3 ON S3.fld_id = S1.fld_task
                                                              INNER JOIN itc_mis_materials_master AS S4 ON S4.fld_id = S1.fld_material where S1.fld_extend_id = '".$extendid."' AND S1.fld_created_by='".$uid."' AND S1.fld_delstatus='0' AND S4.fld_delstatus='0'
                                                              ");
                     if($qryviewexp_materials->num_rows > 0)   {
                       $cnt=1;
                        while($row=$qryviewexp_materials->fetch_assoc())
                        {
                                extract($row);
                   
                  ?>
                    <tr class="rowd-<?php echo $cnt;?>">
                                 <td  style="cursor:default; text-align:center;" class="<?php echo $destname;?>" id="definematerial_1"><?php echo $destname;?></td>
                                 <td  style="cursor:default; text-align:center;" class="<?php echo $taskname;?>" id="definedyad_2"><?php echo $taskname;?></td>
                                 <td  style="cursor:default; text-align:center;" class="<?php echo $materialname;?>" id="definedyad_3"><?php echo $materialname;?></td>
                                  <td class='centerText'> 
                                    <span class="icon-synergy-edit <?php if($userid!=$uid){ echo "dim";} ?>"  style="font-size:18px;padding-right: 10px;" onclick="fn_editexpmaterial(<?php echo $expextendmaterials; ?>);"></span>
                                    <span class="icon-synergy-trash <?php if($userid!=$uid){ echo "dim";} ?>" style="font-size:18px;" onclick="fn_deleteexpmaterial(<?php echo $expextendmaterials.","."'rowd-".$cnt."'";?>)"></span>
                                 </td>    
                            </tr>
                                   
                        <?php	
                        $cnt++;
                           }
                   }
                   else     {    ?>
                    <tr>
                        <td colspan="4" align="center" id="norecrd"> No Records </td>
                    </tr> 
                <?php     }      ?>
                     
                    <tr id="materialformdet" style="display:none;">
                        <td class='centerText' style="cursor:default;">
                            <dl class='field row'>
                               <dt class='dropdown'>   
                                     <div class="selectbox materialbox" style="width:200px;">
                                         <input type="hidden" name="destnname" id="destnname"/>
                                         <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                             <span class="selectbox-option input-medium" data-option="" id="destn_name" style="float:left;">Select Destination</span>
                                             <b class="caret1"></b>
                                         </a>

                                             <div class="selectbox-options" style="width:210px;">
                                                 <input type="text" class="selectbox-filter" placeholder="Search Destination" style="width:180px;">
                                                 <ul role="options">
                                                                 <?php
                                                                 if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
					$qrymaterial = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 1) AS shortname, 
																fld_dest_desc AS destdesc
															FROM itc_mis_destination_master 
															WHERE fld_mis_id='".$expid."' AND fld_flag='1' AND fld_delstatus='0'");
				}
				else{				
					if($sessmasterprfid==6){ //For District Admin
						$qrymaterial = $ObjDB->QueryObject("SELECT a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 1) AS shortname, 
																	a.fld_dest_desc AS destdesc
																FROM itc_mis_destination_master AS a 
																LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_dest_id 
																LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
																WHERE a.fld_mis_id='".$expid."' AND b.fld_mis_id='".$expid."' AND a.fld_flag='1' AND b.fld_flag='1' 
																	AND a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND b.fld_delstatus='0'  
																	AND c.fld_delstatus='0' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' group by destid ORDER BY a.fld_order");
																
					}
					else{ //For Remaining users
						$qrymaterial = $ObjDB->QueryObject("SELECT a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 1) AS shortname, 
																	a.fld_dest_desc AS destdesc
																FROM itc_mis_destination_master AS a 
																LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_dest_id 
																LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
																WHERE a.fld_mis_id='".$expid."' AND b.fld_mis_id='".$expid."' AND a.fld_flag='1' AND b.fld_flag='1' 
																	AND a.fld_delstatus='0' AND c.fld_user_id='".$indid."' AND c.fld_school_id='".$schoolid."' AND b.fld_delstatus='0' 
																	AND c.fld_delstatus='0' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' group by destid ORDER BY a.fld_order");
					}
				}
                                                                 
                                                     if($qrymaterial->num_rows > 0){
                                                                while($rowsqry = $qrymaterial->fetch_assoc()){
                                                                        extract($rowsqry);
                                                                                        ?>
                        <li style="float:left;padding:3px 20px;"><a tabindex="-1" href="#" data-option="<?php echo $destid;?>"  class="tooltip" title="<?php echo $destname;?>" id="option1<?php echo $destid;?>" onclick="fn_loadtaskbox(<?php echo $destid;?>,<?php echo $extendid;?>)"><?php echo $destname;?></a></li>

                     <?php
                                                                                }
                                                                        }
                                                                ?>

                                                 </ul>
                                             </div>

                                     </div>
                                 </dt>                  
                            </dl>
                         </td>
                         <td class='centerText' style="cursor:default;" id="taskselection">
                            <dl class='field row'>   
                                 <dt class='dropdown'>   
                                     <div class="selectbox materialbox" style="width:200px;">
                                         <input type="hidden" name="taskname" id="taskname"/>
                                         <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                             <span class="selectbox-option input-medium" data-option="" id="task_name" style="float:left;">Select Task</span>
                                             <b class="caret1"></b>
                                         </a>

                                             <div class="selectbox-options" style="width:210px;">
                                                 
                                             </div>

                                     </div>
                                 </dt>                                         
                             </dl>
                           
                            </td>
                            <td class='centerText' style="cursor:default;" id="materialselection">
                             <dl class='field row'>   
                                 <dt class='dropdown'>   
                                     <div class="selectbox materialbox" style="width:200px;">
                                         <input type="hidden" name="materialname" id="materialname"/>
                                         <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                             <span class="selectbox-option input-medium" data-option="" id="material_name" style="float:left;">Select Material</span>
                                             <b class="caret1"></b>
                                         </a>

                                             <div class="selectbox-options" style="width:210px;">
                                                
                                             </div>

                                     </div>
                                 </dt>                                         
                             </dl> 
                          </td>
                            <!-- create, edit,deleted button -->
                             <td class='centerText' style="cursor:default;">
                                 <span class="icon-synergy-create"  style="font-size:20px;padding-right:10px;cursor:pointer;" onclick="fn_savematerials('addmaterial',<?php echo $expid; ?>,<?php echo $extendid; ?>,<?php echo $flag;?>,<?php echo $uid;?>);"></span>
                                 <span class="icon-synergy-close" style="font-size:18px;cursor:pointer;" onclick="fn_closematerial(<?php echo $expid; ?>,<?php echo $extendid; ?>,<?php echo $uid;?>);"></span>
                            </td>
                    </tr>
                     <tr>
                         <?php $chkuserextend= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_extendmaterials_master WHERE fld_id='".$extendid."' AND fld_created_by='".$uid."'");?>
                    	<td colspan="4" id="addmateriallist">
                        	<span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span>
   				<span onclick="$('#materialformdet').show();" class="<?php if($chkuserextend > 0) { echo ""; } else { echo "dim";} ?>">Add a material to this list</span>
                                
                        </td>
                    </tr>
                </tbody>
            </table>
           </div>
              <input type="hidden" id="expmat_id" value="" name="expmat_id">
          </form>
   <script type="text/javascript" language="javascript">
            $(function(){
                $("#extmaterialform").validate({
                        ignore: "",
                                errorElement: "dd",
                                errorPlacement: function(error, element) {
                                        $(element).parents('dl').addClass('error');
                                        error.appendTo($(element).parents('dl'));
                                        error.addClass('msg'); 	
                        },
                        rules: { 
                                destnname: { required: true },
                                taskname: { required: true },	
                                materialname: { required: true }	
                        }, 
                        messages: { 
                                destnname: {  required: "Select anyone destination" },	
                                taskname: {  required: "Select anyone task" },
                                materialname: {  required: "Select anyone material" }								
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