<?php 
@include("sessioncheck.php");
$misid = isset($method['id']) ? $method['id'] : '';
$expeditionqry = $ObjDB->QueryObject("SELECT fld_mis_name, fld_ui_id
                                    FROM itc_mission_master 
                                    WHERE fld_id='$misid' AND fld_delstatus='0'");
$rowexpedition=$expeditionqry->fetch_assoc();
extract($rowexpedition);
$id[1] = $fld_mis_name;
	
?>

<section data-type='2home' id='library-missionrubric-rublist'>
    <script type="text/javascript" charset="utf-8">		
	$.getScript("library/missionrubric/library-missionrubric.js");
        
</script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
       
        <div class='row buttons rowspacer' id="rubriclist">
             <?php if($sessmasterprfid != 9){  ?>
            <a class='skip btn mainBtn' href='#library-missionrubric-newgraderubric' id='btnlibrary-missionrubric-newgraderubric'  name='<?php echo $misid.",".'0';?>'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New<br />Rubric</div>
            </a>
            
          <?php }?>  
            
            
            <?php 
            
             $pitscoadmins=$ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_user_master WHERE fld_profile_id='2' AND fld_delstatus='0' AND fld_activestatus='1'");
            
            if($sessmasterprfid == 2 || $sessmasterprfid == 3 ){ //For Pitsco & Content Admin
                    $qry = "SELECT fld_rub_name,fld_id,fld_created_by, fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$misid."' and fld_delstatus='0' AND fld_created_by IN(".$pitscoadmins.")";
         
            }
            else{				
                if($sessmasterprfid == 6){ //For District Admin
                    $qry = "SELECT fld_rub_name,fld_id,fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$misid."' and fld_delstatus='0' AND fld_created_by IN(".$pitscoadmins.",".$uid.")";
                    
            }
                else if($sessmasterprfid == 5){ //For Teacher inv
                     
                     $qry = "SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
                                UNION SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' and fld_district_id = '0' and fld_school_id = '0' and fld_user_id='".$indid."'";
                   
                      
                }
                else if($sessmasterprfid == 7){ //For School Admin
                    
                    $qry = "SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
                                UNION 
                                SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' and fld_district_id = '".$sendistid."' and fld_school_id = '0' order by fld_profile_id ASC";

                }
                else{ //For Teacher
                   
                    $qry="SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' AND fld_created_by IN(".$pitscoadmins.")
                            UNION SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id = '0'
                            UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
                            UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$misid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

                }
         
            }
            $qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
                while($res=$qry_for_get_all_expedition->fetch_assoc()){
                extract($res);
               
                $pitscoorteacher=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$misid."' AND fld_delstatus='0' AND fld_id='".$fld_id."' AND fld_created_by IN(".$pitscoadmins.")");
                    
                    $profile_id=$ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_mis_rubric_name_master where fld_id='".$fld_id."' AND fld_delstatus='0' ");
                
                    if($profile_id == 2 || $profile_id == 3 ){ //For Pitsco & Content Admin
                            ?>
                    <a class='skip btn mainBtn <?php if($pitscoorteacher==1){echo "pit"; } ?>' href='#library-missionrubric' id='btnlibrary-missionrubric-actions' name="<?php echo $misid.",".$fld_id;?>"><!-- name='<?php //echo $gradestu.",".$id[0];?>'-->
                        <div class='icon-synergy-modules'></div>
                                <div  class='onBtn tooltip' title="<?php echo $fld_rub_name; ?>"><?php echo $shortname; ?></div>
                    </a>
                            <?php 
                    }///if condition end here
                    else{
                        if($profile_id == 6){ //For District Admin
                            ?>
                            <a class='skip btn mainBtn dis' href='#library-missionrubric' id='btnlibrary-missionrubric-actions' name="<?php echo $misid.",".$fld_id;?>"><!-- name='<?php //echo $gradestu.",".$id[0];?>'-->
                                <div class='icon-synergy-modules'></div>
                                <div  class='onBtn tooltip' title="<?php echo $fld_rub_name; ?>"><?php echo $shortname; ?></div>
                            </a>
                            <?php 
                        }
                        else if($profile_id == 7){ //For School Admin
                            ?>
                            <a class='skip btn mainBtn sch' href='#library-missionrubric' id='btnlibrary-missionrubric-actions' name="<?php echo $misid.",".$fld_id;?>"><!-- name='<?php //echo $gradestu.",".$id[0];?>'-->
                                <div class='icon-synergy-modules'></div>
                                <div  class='onBtn tooltip' title="<?php echo $fld_rub_name; ?>"><?php echo $shortname; ?></div>
                            </a>
                            <?php 
                
                        }
                        else{
                ?>
                            <a class='skip btn mainBtn <?php if($pitscoorteacher==1){echo "pit"; } ?>' href='#library-missionrubric' id='btnlibrary-missionrubric-actions' name="<?php echo $misid.",".$fld_id;?>"><!-- name='<?php //echo $gradestu.",".$id[0];?>'-->
                                <div class='icon-synergy-modules'></div>
                                <div  class='onBtn tooltip' title="<?php echo $fld_rub_name; ?>"><?php echo $shortname; ?></div>
                            </a>
                    <?php
                }
                    } //else condition end here
                } ///while loop end here
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");