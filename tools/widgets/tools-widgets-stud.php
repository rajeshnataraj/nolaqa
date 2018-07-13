<?php
/*----
    Created BY : MOhan M PHP Programmer.(28/10/2015)	
----*/

@include("sessioncheck.php");
$menuid= isset($method['id']) ? $method['id'] : '';
$sid = isset($method['sid']) ? $method['sid'] : '0';
?>
<section data-type='#tools-widgets' id='tools-widgets-stud'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle">Turn off Widgets based on Students</p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                            <div class='row'>
                               <div class='six columns'> Class
                                   <dl class='field row'>
                                       <div class="selectbox">
                                           <input type="hidden" name="classid" id="classid" value="">
                                           <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> 
                                              <?php
                                                    $class_name=$ObjDB->SelectSingleValue("SELECT a.fld_class_name
                                                                                        FROM itc_class_master as a
                                                                                        LEFT JOIN widgets_turnoff_student as b ON a.fld_id=b.fld_class_id 
                                                                                        WHERE a.fld_delstatus='0' AND a.fld_archive_class='0' AND b.fld_created_by='".$uid."' AND b.fld_flag='1'");
                                                    if($class_name!=''){
                                                    ?>
                                                     <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $class_name; ?></span>
                                                    <?php 
                                                   }
                                                   else{
                                                    ?>
                                                     <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                                                  <?php } ?>
                                               <b class="caret1"></b> 
                                           </a>
                                           <div class="selectbox-options">
                                               <input type="text" class="selectbox-filter" placeholder="Search Class">
                                               <ul role="options" style="width:100%">
                                                                                           <?php 
                                                   $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
                                                                                       FROM itc_class_master 
                                                                                       WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$uid."' 
                                                                                       OR fld_id IN (SELECT fld_class_id 
                                                                                                                       FROM itc_class_teacher_mapping 
                                                                                                                       WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) 
                                                                                       ORDER BY fld_class_name");
                                                   if($qry->num_rows>0){
                                                       while($row = $qry->fetch_assoc())
                                                       {
                                                               extract($row);
                                                               ?>
                                                               <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showassignments(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
                                                               <?php
                                                       }
                                                   }?>
                                               </ul>
                                           </div>
                                       </div>
                                   </dl>
                               </div>
                                <div class='six columns'>  
                                   <div id="assignmentdiv" style="display:none">

                                   </div>
                               </div> 
                           </div>

                         
                           <div class='row'>
                               <div class='twelve columns'>  
                                   <div id="studentdiv" style="display:none">

                                   </div>
                               </div>        
                           </div> 

                           <div class='row rowspacer' style="display:none" id="savereportdiv">
                               <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Save" onClick="fn_savestu(3);" />
                           </div>
                    </div>        
                </div>    
           </div>                  
       </div>    
    </div>
</section>
<?php
	@include("footer.php");
