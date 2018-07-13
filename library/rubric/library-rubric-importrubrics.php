<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';
$editid = isset($method['id']) ? $method['id'] : '';

$qryexp=$ObjDB->QueryObject("SELECT a.fld_id AS expid,a.fld_exp_name AS expname FROM itc_exp_master AS a 
                                    LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
                                    WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'
                                    ORDER BY a.fld_exp_name ASC");



 if($sessmasterprfid == 2 || $sessmasterprfid == 3 ){ //For Pitsco & Content Admin
                           $qryexp=$ObjDB->QueryObject("SELECT a.fld_id AS expid, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS expname, 
                                        fn_shortname (CONCAT(a.fld_exp_name, ' ', b.fld_version), 1) AS shortname 
                                        FROM itc_exp_master AS a 
                                        LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
                                        WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' ".$sqry." 
                                        ORDER BY a.fld_exp_name ASC ");
}
else{				
        if($sessmasterprfid == 6){ //For District Admin
              $qryexp=$ObjDB->QueryObject("SELECT a.fld_id AS expid, CONCAT(a.fld_exp_name, ' ', d.fld_version ) AS expname, 
                            fn_shortname (CONCAT(a.fld_exp_name, ' ', d.fld_version), 1) AS shortname 
                            FROM  itc_exp_master AS a 
                            LEFT JOIN itc_license_exp_mapping AS b  ON a.fld_id = b.fld_exp_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                            LEFT JOIN itc_exp_version_track  AS d ON a.fld_id=d.fld_exp_id
                            WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' 
                            AND c.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_start_date<='".$date."' 
                            AND c.fld_end_date>='".$date."'
                            GROUP BY a.fld_id
                            ORDER BY a.fld_exp_name ASC");
              
             
        }
        else{ //For Remaining users
            
                  $qryexp=$ObjDB->QueryObject("SELECT a.fld_id AS expid, CONCAT( a.fld_exp_name, ' ', fld_version) AS expname, 
                            fn_shortname (CONCAT(a.fld_exp_name, ' ', fld_version), 1) AS shortname 
                            FROM itc_exp_master AS a 
                                                 LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_exp_id 
                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                            LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = a.fld_id 
                            WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_school_id='".$schoolid."' 
                                                 AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_flag='1' 
                                                 AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' 
                                                 GROUP BY a.fld_id
                                                 ORDER BY a.fld_exp_name ASC");
       }
}


?>

<script type="text/javascript" charset="utf-8">		
	$.getScript("library/rubric/library-rubric-importrubrics.js");	
</script>
<section data-type='#library-rubric' id='library-rubric-importrubrics'>
    <div class='container'>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
               <form name="form1" id="form1" enctype="multipart/form-data">
                <div class="row">
                    <div class='six columns'>
                        Select Expedition<span class="fldreq">*</span>
                            <dl class='field row'>   
                               <dt class='dropdown'>   
                                   <div class="selectbox">
                                       <input type="hidden" name="expid" id="expid" value="<?php echo $expid;?>" onchange="fn_showimportrubric();"/>
                                       <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                                           <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select Expedition</span>
                                           <b class="caret1"></b>
                                       </a>                       
                                       <div class="selectbox-options">
                                           <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                                           <ul role="options">
                                                   <?php
                                                    if($qryexp->num_rows>0)
                                                       {
                                                           while($row=$qryexp->fetch_assoc())
                                                           {
                                                               extract($row);
                                                   ?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname;?></a></li>
                                                   <?php
                                                           }
                                                       }
                                                   ?>
                                           </ul>
                                       </div>
                                   </div>
                               </dt>                                       
                           </dl>  
                     </div>
                 </div>   
                    
                <div class="row rowspacer" >
                     <div id="fileupload" class='row'>  </div>
                </div>
                <div class="row rowspacer" id="duplicate"> 


                </div>
            </form>
            <input type="hidden" id="hidlisttype" name="hidlisttype" value="all" />
            </div>
        </div>
    </div>
   
    
    
</section>
<?php
	@include("footer.php");