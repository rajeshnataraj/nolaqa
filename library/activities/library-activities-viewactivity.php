<?php
	@include("sessioncheck.php");
	$activityid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$filename='';
	
	$qry="SELECT a.fld_unit_id AS unitid,a.fld_activity_name AS activityname,a.fld_activity_description AS 
                          activitydescription,a.fld_activity_points AS points,b.fld_unit_name AS unitname,
						  GROUP_CONCAT(c.fld_file_name) AS filenames,GROUP_CONCAT(c.fld_file_type) AS filetypes,GROUP_CONCAT(c.fld_id) AS fiids
                  FROM itc_activity_master AS a 
				  LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id
				  LEFT JOIN itc_activity_file_mapping AS c ON a.fld_id=c.fld_activity_id
				  WHERE a.fld_id='".$activityid."' AND a.fld_delstatus='0' AND c.fld_activity_id='".$activityid."' AND c.fld_delstatus='0' ";
				  
	$qry_lessondetails = $ObjDB->QueryObject($qry);
		$res_lessondetails = $qry_lessondetails->fetch_assoc();
		extract($res_lessondetails);
		$msg = "Edit Activity";		
		
		$filenames=array_values(array_filter(explode(',',$filenames)));	
		$filetypes=array_values(array_filter(explode(',',$filetypes)));	
	    $filid=array_values(array_filter(explode(',',$fiids)));	
	$noview=array('xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3');
?>
<section data-type='2home' id='library-activities-viewactivity'>
	<script language="javascript" type="text/javascript">
		$.getScript("library/activities/library-activities-newactivity.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $activityname; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row rowspacer'>        
        	<div class="twelve columns formBase">
            	<div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<div class='row'>   
                                                 
                        <?php     if($unitname!='') { ?>
                            <div class='three columns'>
                            	<span class="wizardReportDesc">Unit:</span>
                                <div class="wizardReportData"><?php echo $unitname; ?></div>
                          	</div>
                            <?php } ?>
                             <?php     if($sessmasterprfid!='10') { ?>
                            <div class='two columns'>
                            	<span class="wizardReportDesc">Points:</span>
                                <div class="wizardReportData"><?php echo $points; ?></div>
                          	</div>
                             <?php    } ?>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Activity Description:</span>
                                <div class="wizardReportData"><?php echo $activitydescription; ?></div>
                          	</div>
                      	</div>

                        
						<div class='row rowspacer'>
                <div class='eleven columns'>
                <?php if($activityid!=0 and sizeof($filenames)!=0){?>
                  <table id="appendcontenttable" class='table table-hover table-striped table-bordered'>
                    <thead class='tableHeadText'>
                      <tr>
                        <th>File name</th>
                        <th class='centerText'>Type</th>
                        <th class='centerText'> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php  
					for($ff=0;$ff<sizeof($filenames);$ff++) {?>
                    
					 <tr id="trrow_<?php echo $filid[$ff]."_".$activityid;?>" >
                     <td><?php echo $filenames[$ff];?></td>
                     <td  class="centerText" ><?php echo $filetypes[$ff]; ?></td>
                     <td  class="centerText" >
                     <?php //synbtn-demote
					 if(!in_array(strtolower($filetypes[$ff]),$noview)) {
						 ?>
                     
                     <a onClick="viewactivityfrompreview('<?php echo strtolower($filetypes[$ff]); ?>','<?php echo $filenames[$ff]; ?>');" class="activity-view-deleteicon icon-synergy-view <?php if(strtolower($filetypes[$ff])=="docx"){?> dim <?php } ?>)"></a>&nbsp;&nbsp;
                     <?php }
					 else {?>
                     <a style="margin-left:25px;" class="activity-view-deleteicon" ></a>
                     <?php }?>
                     
                     <a  style="padding-right: 21px;" onClick="downloadactivityfiles('<?php echo strtolower($filetypes[$ff]); ?>','<?php echo $filenames[$ff]; ?>');" class="activity-view-deleteicon  synbtn-demote" ></a></td>
                     </tr>
					 <?php }
					?>
                    </tbody>
                  </table>
                  <?php }?>
                </div>
              </div>
						<?php if($filename != '') { ?>
                        <div class="row rowspacer">
                        	<div class='eight columns'>
                            	<span class="wizardReportDesc">File:</span>
                                <div class="wizardReportData"><?php echo $filename; ?></div>
                                <input type="button" id="btnlibrary-activities-preview" value="Preview" class="mainBtn" name="<?php echo $filename.",".$filetype;?>" align="right" <?php if($filetype==1 ){?>style="display:none;"<?php }?> />
                                 <input type="button" id="btnlibrary-activities-download" value="Download" onclick="fn_downloaddoc();" align="right" <?php if($filetype==2 or $filetype==3){?>style="display:none;"<?php }?>/>
                          	</div>
                            <div class='four columns'></div>
                        </div>
                        <input type="hidden" id="activityfilename" name="activityfilename" value="<?php echo $filename?>" />
                        <?php } ?>
                    </div>
             	</div>
          	</div>
      	</div>
    </div>    
</section>
<?php
	@include("footer.php");