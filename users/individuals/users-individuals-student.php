<?php 
    @include("sessioncheck.php");
    $sid = isset($method['sid']) ? $method['sid'] : '0';
    $oper = isset($method['oper']) ? $method['oper'] : '';
    $stuid = isset($method['stuid']) ? $method['stuid'] : '0';//sort/filter the student list other than the “tag�? feature

    $sqry='';
    if($sid!=0)
    {
        $sid = explode(',',$sid);
        for($i=0;$i<sizeof($sid);$i++){		
                $itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
                                                                        FROM itc_main_tag_mapping 
                                                                        WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='7'");
                $sqry = "and (";
                $j=1;
                while($itemres = $itemqry->fetch_assoc()){
                        extract($itemres);
                        if($j==$itemqry->num_rows){
                                $sqry.=" fld_id=".$fld_item_id.")";
                        }
                        else{
                                $sqry.=" fld_id=".$fld_item_id." or";
                        }
                        $j++;
                }
        }
    }
        
        //sort/filter the student list other than the “tag�? feature START

if($stuid!=0){
    $stuid = explode(',',$stuid);
            for($i=0;$i<sizeof($stuid);$i++){		
                    $itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
                                                                        WHERE fld_id='".$stuid[$i]."' AND fld_delstatus='0'");
                    $sqry = "and (";
                    $j=1;
                    while($itemres = $itemqry->fetch_assoc()){
                            extract($itemres);
                            if($j==$itemqry->num_rows){
                                    $sqry.=" fld_id=".$fld_id.")";
                            }
                            else{
                                    $sqry.=" fld_id=".$fld_id." or";
                            }
                            $j++;
                    }
            }
    }

//sort/filter the student list other than the “tag�? feature END

?>
<script type="text/javascript" charset="utf-8">	
	$.getScript("users/individuals/users-individuals-student_newstudent.js");
		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_student', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=7" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_load_student();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_load_student();
		});					
			
	});	
	function fn_load_student(){
				var sid = $('#form_tags_student').val();
				$("#studentlist").load("users/individuals/users-individuals-student.php #studentlist > *",{'sid':sid});
				removesections('#users-individuals-student');
			}
        
        $('#details_icon_studentlist').hide();
        //sort/filter the student list other than the “tag�? feature START
        <?php
if($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 5 or $sessmasterprfid == 2)
{?>
$(function(){				
		var t4 = new $.TextboxList('#form_tags_studentname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchstudent&tag_type=7" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_load_student1();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_load_student1();
		});	
                
                
 function fn_load_student1(){
        var sid = $('#form_tags_studentname').val();
        $("#studentlist").load("users/individuals/users-individuals-student.php #studentlist > *",{'stuid':sid});
        removesections('#users-individuals-student');
    } 
                
		/* for radio button options in title  */
		$("input[name='types']").click(function() {
                    var test = $(this).val();
                    $("#users-individuals-student_delstudent").hide();
                    $("div.sdesc").hide();
                    $("#Types" + test).show();
                    $('#loadstudents').html('');  $('#searchstu').val('');
                    $('#studentlist').show();
                    $('#loadstudents').show();
                   
                    if(test==5 || test==6)
                    {
                        $('#loadstudents_details_icon').hide();
                        $('#details_icon_loadstudents').hide();
                        $('#details_gradeloadstudent_filter').hide();
                        $('#studentlist').show();
                        
                    }
                    else if(test==7)
                    {
                        $('#loadstudents_details_icon').hide();
                        setTimeout("removesections('#studentlist');",1500);
                    }
                    else if(test==8)
                    {
                        $('#loadstudents_details_icon').hide();
                        $('#details_icon_titleview').hide();
                        $('#details_icon_loadstudents').hide();
                        $('#details_icon_titleview').show();
                    }
                    
                    

		});				
		 $('#Types5').hide();
                 $('#Types6').show();
	});	

   <?php
}?>
//sort/filter the student list other than the “tag�? feature END
</script>

<section data-type='#users-individuals' id='users-individuals-student'>
  <div class='container'>
    <div class='row'>
       <div class='twelve columns'>
            <p class="dialogTitle" style="float:left">Students</p>
            <?php 
            if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
            { ?>
                <span style="padding-left:724px; cursor: pointer;">
                    <image src="img/list.png" width="28"  class="mouse tooltip" id="details_icon_img" title="View Student List" onclick="fn_details();"/>
                    <image src="img/user.png" width="28"  class="mouse tooltip" id="large_icon_img" title="View Student Icons" onclick="fn_large();"/>
                </span>
      <?php } ?>
            <p class="dialogSubTitleLight">&nbsp;</p>
        </div>
    </div>
   
  <?php
        if($sessmasterprfid == 2)
        {
        ?>
                   <div class="row rowspacer" style="padding-bottom:40px;"> 
                        <div class='six columns'>   
                            <p class="lightSubTitle">Category</p>
                         <dl class='field row'>
                            <div class="selectbox" style="width:100%">
                                <input type="hidden" name="categoryid" id="categoryid" value="Select Category">
                                <a class="selectbox-toggle" style="width:60%" role="button" data-toggle="selectbox" href="#"><span class="selectbox-option input-medium" data-option="">Select Category</span><b class="caret1"></b></a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Category">		    
                                    <ul role="options" style="width:97%;">
                                        <li>
                                            <a tabindex="-1" href="#" data-option="1" onclick="fn_schoolpurchasestu();">School Purchase</a>
                                        </li>
                                        <li>
                                            <a tabindex="-1" href="#" data-option="2" onclick="javascript:$('#districtind').hide();$('#shpurchase').hide();$('#RadioGroup').show();$('#Types6').show();fn_session(0,0);fn_homepurchasestu();">Home Purchase</a>
                                        </li>
                                        <li>
                                            <a tabindex="-1" href="#" data-option="3" onclick="javascript:$('#districtind').show();$('#shpurchase').hide();">District</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                  </div>
      
      
                 <div class="row rowspacer" id="shpurchase" style="padding-bottom:40px;display:none;">
                    <div class='six columns'> 
                        <div id="purchasediv"></div>
                    </div>
                </div> 
                   
                    <div class="row" style="padding-bottom:40px;display:none;" id="districtind"> 
                    <div class='six columns'> <p class="lightSubTitle">District</p>
                         <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="districtid" id="districtid" value="">
                                <a class="selectbox-toggle" style="width:60%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="">Select District</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search District">
                                    <ul role="options" style="width:97%">
                                        <?php 

                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname FROM itc_district_master WHERE fld_delstatus='0' ORDER BY fld_district_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $districtid;?>" onclick="fn_showschoolind(<?php echo $districtid;?>)"><?php echo $districtname; ?></a></li>
                                                <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                
                    <!--Shows school names dropdown-->
                    <div class='six columns'> 
                        <!--Shows School Dropdown-->
                        <div id="schooldiv" style="display:none"></div>
                    </div>
            	</div>
             
        <?php } if($sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 5 or $sessmasterprfid == 7 or $sessmasterprfid == 2) //sort/filter the student list other than the “tag�? feature START
{ ?>
    <div class="row" id="RadioGroup" <?php if($sessmasterprfid == 2){?> style="display:none;"<?php } ?>>
	<div class='twelve columns'>
            <font color="white">Sort/filter the student list using: 
                <input type="radio" id="tag" name="types"  value="5" <?php if($sessmasterprfid == 2){?>onclick="fn_showstudents(0,0);"<?php } ?> />Tag
                <input type="radio" id="search" name="types" checked="checked" value="6" <?php if($sessmasterprfid == 2){?>onclick="fn_showstudents(0,0);"<?php } ?>/>Student Name
            <?php if($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 5) { ?>
                <input type="radio" id="classname" name="types" value="7" />Class Name
            <?php } ?>
            <?php if($sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 7 or $sessmasterprfid == 5 or $sessmasterprfid == 2) { ?>
            <input type="radio" id="gradelevel" name="types" value="8" <?php if($sessmasterprfid == 2){?>onclick="fn_showstudents(0,0);"<?php } ?>/>Grade Level
            <?php } ?>
            </font>
             &nbsp;&nbsp;
	</div>
    </div> 
<?php }  //sort/filter the student list other than the “tag�? feature START ?>
<div class="sdesc" id="Types5" <?php if($sessmasterprfid == 2){?> style="display:none;"<?php } ?>>
    <div class='row' style="padding-bottom:20px;">
        <div class='twelve columns'>
            <!--<p class="filterLightTitle">To filter this list, search by Tag Name. </p>-->
            <p class="filterLightTitle">To search for a specific student, search by student name, Tag name, or browse through the list of students below.  To add a new student, click "Add new student".</p>
            <dl class='field row'>
                <dt>
                    <div class="tag_well">
                        <input type="text" name="test3" value="" id="form_tags_student" />
                    </div>
                </dt>               
            </dl>
        </div>
    </div>
</div>
<?php if($sessmasterprfid == 7 or $sessmasterprfid == 9  or $sessmasterprfid == 8 or $sessmasterprfid == 5 or $sessmasterprfid == 2) //sort/filter the student list other than the “tag�? feature START
{ ?>
<div class="sdesc" id="Types6" style="display: none;">
    <div class='row' style="padding-bottom:20px;">
        <div class='twelve columns'>
            <!--<p class="filterLightTitle">To filter this list, search by Student Name.</p>-->
            <p class="filterLightTitle">To search for a specific student, search by student name, Tag name, or browse through the list of students below.  To add a new student, click "Add new student".</p>
            <dl class='field row'>
                <dt>
                    <div class="tag_well">
                        <input type="text" name="test3" value="" id="form_tags_studentname" />
                    </div>
                </dt>               
            </dl>
        </div>
    </div>
</div>
<div class="sdesc" id="Types7" style="display: none;">
    <div class='row' style="padding-bottom:20px;">
        <div class='six columns'>
        	<!--<p class="filterLightTitle">To filter this list, search by Class Name.</p>-->
                <p class="filterLightTitle">To search for a specific student, search by student name, Tag name, or browse through the list of students below.  To add a new student, click "Add new student".</p>
                    <dl class='field row'>   
                        <dt class='dropdown'>
                            <style>
                                .dropdown .caret1
                                {
    
                                    float: left;
                                   margin-top: 10px;
                                }
                                .selectbox-options
                                {
                                    width:59%;
                                }
                                .selectbox .selectbox-toggle{
                                     width:59%;
                                }
                            </style>   
                            <div class="selectbox">
                                <input type="hidden" name="selectstu" id="selectstu" value="<?php //echo $stuid; ?>" /><!--  -->
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" id="searchstu" style="width:248px;">Select Class</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                         <input type="text" class="selectbox-filter"  placeholder="Search Student ">
                                         <ul role="options" style="width:270px;">
                                             <?php 
                                             $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
													fld_step_id AS stepid, fld_flag AS flag 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
													AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
													AND fld_flag='1')) group by classname");
                                                 if($qryclass->num_rows>0){
                                                     $j=1;
                                                     while($rowclassdetails = $qryclass->fetch_assoc())
                                                     {
                                                     extract($rowclassdetails);
                                                                                                  ?>
                                             <input type="hidden" id="classiddetails" value="<?php echo $classname; ?>"/>
                                             <li><a tabindex="-1"  href="#" data-option="<?php echo $classid;?>"  onclick="fn_showclastudent(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
                                             <?php
                                                 $j++;
                                                ?>
                                                   <input type="hidden" name="classids" id="classids" value="<?php echo $classid; ?>" />
                                                    <?php  }
                                                      }
                                                 else
                                                 { ?>
                                                 <div class="wizardReportData">No Students</div><?php
                                                 }
                                                
                                             ?>
                                         
                                         </ul>
                                     </div>
                            </div>
                        </dt>                                       
                    </dl>
        </div>
    </div>
</div>      
      
    <div class="sdesc" id="Types8" style="display: none;">
    <div class='row' style="padding-bottom:20px;">
        <div class='six columns'>
        	<!--<p class="filterLightTitle">To filter this list, search by Grade Level.</p>-->
                <p class="filterLightTitle">To search for a specific student, search by student name, Tag name, or browse through the list of students below.  To add a new student, click "Add new student".</p>
                    <dl class='field row'>   
                        <dt class='dropdown'>
                            <style>
                                .dropdown .caret1
                                {

                                    float: left;
                                   margin-top: 10px;
                                }
                                .selectbox-options
                                {
                                    width:59%;
                                }
                                .selectbox .selectbox-toggle{
                                     width:59%;
                                }
                            </style>   
                            <div class="selectbox">
                                <input type="hidden" name="selectgradestu" id="selectgradestu" value="" /><!--  -->
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" id="searchstu" style="width:248px;">Select Grade Level</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter"  placeholder="Search Grade Level ">
                                        <ul role="options" style="width:270px;">
                                           <?php                                                     
                                           for($i=1; $i<=12;$i++){?>
                                               <li><a tabindex="-1" href="#" data-option="<?php echo $i;?>" onclick="fn_showgradestudent(<?php echo $i;?>)"><?php echo $i; ?></a></li>
                                           <?php 
                                           }?> 
                                        </ul>
                                    </div>
                            </div>
                        </dt>                                       
                    </dl>
        </div>
    </div>
</div>
<?php } //sort/filter the student list other than the “tag�? feature END ?>  
      
    <!-- new code for dashboard icons or list -->
    <style>
           .textcenter{
               padding-top:14px;
               padding-bottom: 15px;
               width: 144px;
               color: white;
           }
           .floatleft{
               float:left;
           }
           .heading{
               margin-right: 90px;
           }
           .btnbox{
               width: 710px;
               height: 1%;
               margin-left: 0px;
               background-color: white;
           }
           .liststyle{
               list-style: none;
               width: 176px;
               float: left;
               text-align: center;
               padding-top: 5px;
               cursor: pointer;
               
           }
           .ScrollStyle
            {
                max-height: 250px;
                overflow-x: hidden; /*for horizontal scroll bar */
                overflow-y: auto;
                width: 710px;
                margin-left: 0px;
                margin-top: -23px;
            }
             .symbals
            {
                margin-right:4px;
                margin-top:14px;
                height:24px;
            }
           
       </style> 
    
    <!-- new code for dashboard icons or list -->
    
    
      
    <div class='row buttons' id="studentlist">
        <div id="large_icon_studentlist" style="float:left;">
            <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
              <div class="icon-synergy-add-dark"></div>
              <div class='onBtn'>New Student</div>
            </a>

            <?php if($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 5) //sort/filter the student list other than the “tag�? feature START
            { ?>
                <a class='skip btn mainBtn' href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
                    <div class="icon-synergy-add-dark"></div>
                    <div class='onBtn'>Delete Students</div>
                </a>

            <?php } ?>
        </div>
        <?php
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { ?>
        <div id="details_icon_studentlist" style="display:none;">
            <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
                <div style="margin-left:233px; float: left;">
                    <div class="floatleft"><img src="img/add.jpg" class="symbals"/></div>
                    <div class="textcenter" onclick="fn_newstudent();">New Student</div>
                </div>
            </a>
            <?php if($sessmasterprfid == 9 or $sessmasterprfid == 8 or $sessmasterprfid == 5) //sort/filter the student list other than the “tag�? feature START
            { ?>
                <a  href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
                    <div style="margin-left:10px; float: left;">
                        <div class="floatleft"><img src="img/delete2.jpg" class="symbals"/></div>
                        <div class="textcenter " onclick="fn_delstudent();">Delete Students</div>
                    </div>
                </a>
                <?php 
            } ?>
        </div>
        <?php
        }
        if($sessmasterprfid == 5 or ($sessmasterprfid == 9 and $indid !=0))
        {
            if($sessmasterprfid == 9 and $indid !=0){
                    $uid1 = $ObjDB->SelectSingleValue("SELECT fld_user_id 
                                                                                          FROM itc_user_master 
                                                                                          WHERE fld_id='".$uid."'");
            }
            else{
                   $uid1 = $uid; 
            }

            $qry = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, CONCAT(fld_lname,' ',fld_fname) AS fullname,  
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, 
										fld_district_id AS sdistid, fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_user_id='".$uid1."' AND fld_delstatus='0' ".$sqry." 
									ORDER BY fld_fname ASC");
            
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, 
										fld_district_id AS sdistid, fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_user_id='".$uid1."' AND fld_delstatus='0' ".$sqry." 
									ORDER BY fld_fname ASC");
        }
        else if($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8)
        {
            $qry = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, fld_district_id AS sdistid, 
										fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' ".$sqry." 
									ORDER BY fld_fname ASC");
            
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname,fld_lname AS lastname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, fld_district_id AS sdistid, 
										fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' ".$sqry." 
									ORDER BY fld_fname ASC");
        }
         
        if($sqry!='' AND $sessmasterprfid == 2)
        {
            $qry = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
                                                                               fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                               fld_school_id AS sshlid, fld_user_id AS suserid 
                                                                       FROM itc_user_master 
                                                                       WHERE fld_profile_id= '10' AND fld_delstatus='0' ".$sqry." 
                                                                       ORDER BY fld_fname ASC");
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname,fld_lname AS lastname, 
                                                                               fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                               fld_school_id AS sshlid, fld_user_id AS suserid 
                                                                       FROM itc_user_master 
                                                                       WHERE fld_profile_id= '10' AND fld_delstatus='0' ".$sqry." 
                                                                       ORDER BY fld_fname ASC");
         } ?>
        <?php
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { ?>
        <div class="row rowspacer" id="details_icon_titleview" style="margin-left:135px;padding-top:36px; display: none;">
            <div class="row btnbox" style="background-color:#EEECE1;">
                <li class="liststyle mouse" id="firstval" onclick="fn_first(0);">First Name</li>
                <li class="liststyle mouse" id="lastval" onclick="fn_first(1);">Last Name</li>
                <li class="liststyle mouse" id="userval" onclick="fn_first(2);">User Name</li>
                <li class="liststyle mouse" id="passval" >Password</li>
            </div>
        </div>
        <?php
        } ?>
        <div id="large_icon_recordlist">
           <?php
           while($row = $qry->fetch_assoc()){
                extract($row);
                ?>
                <a class='skip btn main' href='#' onclick="fn_profile(this)" name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                    <div class="icon-synergy-user">
                        <?php if($photo != "no-image.png" && $photo != ''){ ?>
                        <img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $fullname;?>"><?php echo $shortname;?></div>
                </a>
                <?php
           } ?> 
        </div> 
    <?php
    if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
    { ?>
        <div id="details_icon_recordlist" style="margin-left:135px;padding-top:0px; display: none;">
            <div class="ScrollStyle" id="first_click"> <?php
                while($row1 = $qry1->fetch_assoc())
                {
                    extract($row1);
                    ?>
                    <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                        <div class="row" style="paddind-top:20px;">
                            <div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
                                <li class="liststyle"><?php echo $lastname;?></li>
                                <li class="liststyle"><?php echo $firstname;?></li>
                                <li class="liststyle"><?php echo $username;?></li>
                                <li class="liststyle"></li>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?> 
            </div>  
        </div> 
<?php } ?>
        <div id="details_icon_recordlist_desc" style="margin-left:135px;"></div>
    </div>
    
    <div id="loadstudents" style="display:none;"></div>
    <div id="loadstudents_details_icon"  style="display:none;"></div>
    <div id="details_icon_loadstudents"  style="display:none;"></div>
    <div id="details_icon_gradeloadstudents" style="display:none;"></div>
    <div id="details_gradeloadstudent_filter" style="display:none;"></div>
      
      
    <input type="hidden" id="firstname" name="first" value="0"/>
    <input type="hidden" id="lastname" name="lastname" value="0"/>
    <input type="hidden" id="username" name="username" value="0"/>
    <input type="hidden" id="password" name="password" value="0"/>
    <input type="hidden" id="listview" name="listview" value="2">
    
    <input type="hidden" id="classfirstname" name="classfirstname" value="0"/>
    <input type="hidden" id="classlastname" name="classlastname" value="0"/>
    <input type="hidden" id="classusername" name="classusername" value="0"/>
    <input type="hidden" id="classpassword" name="classpassword" value="0"/>
    
    <input type="hidden" id="gradefirstname" name="gradefirstname" value="0"/>
    <input type="hidden" id="gradelastname" name="gradelastname" value="0"/>
    <input type="hidden" id="gradeusername" name="gradeusername" value="0"/>
    <input type="hidden" id="gradepassword" name="gradepassword" value="0"/>
      
  </div>
</section>
<?php
	@include("footer.php");
