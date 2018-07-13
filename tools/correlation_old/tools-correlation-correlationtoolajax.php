<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
        
        if($oper=="showstate" and $oper != " ")
        {
            
            ?>
            Select State<span class="fldreq">*</span>
                <dl class='field row'>   
                        <div class="selectbox">
                             <input type="hidden" name="stateid" id="stateid" value="<?php echo $stdbid;?>">
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="1">Select State</span><b class="caret1"></b>
                             </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search State" />
                                <ul role="options">
                                   <?php 
                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_name AS stdbname from itc_standards_bodies ORDER BY stdbname");
                                    while($res=$qry->fetch_assoc())
                                    {
                                                                                                extract($res);	
                                                                                        ?>
                                    <li><a tabindex="-1" href="#" data-option="<?php echo $stdbid;?>" onclick="fn_showdocuments(<?php echo $stdbid; ?>);"><?php echo $stdbname; ?></a></li>
                                        <?php 
                                    }?>
                                </ul>
                            </div>
                        </div>
                </dl>  

            
            <?php
        }
        
        
        
	/*--- Load document dropdown ---*/
	if($oper=="showdocuments" and $oper != " " )
	{
		$stid = isset($method['stid']) ? $method['stid'] : ''; 
		
		
		?>
                Select Documents<span class="fldreq">*</span>
                <div class="selectbox">
                        <input type="hidden" name="documentsubid" id="documentsubid" value="<?php echo $subjid;?>">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Documents</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Documents">
                            <ul role="options" style="width:100%">
                                <?php 

                                $qry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,2) as shortname, a.fld_doc_guid AS docguid, 
                                                            b.fld_id as subjid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
                                                            b.fld_sub_year AS year, b.fld_sub_guid AS guid
                                                            FROM itc_correlation_documents a
                                                            LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
                                                            WHERE  a.fld_authority_id='".$stid."'");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                            extract($row);
                                            $stddocs = $documenttitle." | ". $subjectname." (".$year.")";	
                                            ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $subjid;?>" onclick="fn_showgrades('<?php echo $subjid;?>')"><?php echo $shortname." | ". $shortsubjname." (".$year.")"; ?></a></li>
                                            <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                        </div>

       
		<?php
	}
	
	/*--- Load document dropdown ---*/
	if($oper=="showgrades" and $oper != " " )
	{
         $subjid = isset($method['subid']) ? $method['subid'] : '';
         
         ?>
                Select Grades<span class="fldreq">*</span>
                <div class="selectbox">
                        <input type="hidden" name="grades" id="grades" value="<?php echo $gguid; ?>">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Grades</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Grades">
                            <ul role="options" style="width:100%">
                                <?php 

                                $qry = $ObjDB->QueryObject("SELECT fld_grade_guid AS gguid, fld_grade_name AS gradename,fld_id as gradeid,
                                                            fn_shortname(fld_grade_name,1) as shortgrdname
                                                            FROM itc_correlation_grades 
                                                            WHERE fld_sub_id='".$subjid."'");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                            extract($row);
                                           ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $gguid;?>" onclick="fn_showinnerstandards('<?php echo $gradeid;?>')"><?php echo $gradename; ?></a></li>
                                            <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                        </div>

       
		<?php
		
               
	}
        if($oper=="showstandrads" and $oper != " " )
	{
         $gradeidss = isset($method['gradeids']) ? $method['gradeids'] : '';
         
         ?>
                Select Standard<span class="fldreq">*</span>
                <div class="selectbox">
                        <input type="hidden" name="standradids" id="standradids" value="<?php echo $standardid; ?>">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Standard</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Standard">
                            <ul role="options" style="width:100%">
                                <?php 

                                $qry = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardid,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='".$gradeidss."'");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                            extract($row);
                                           ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $stdgguid;?>" onclick="fn_showinnerstandards('<?php echo $standardid;?>')">
                                                <?php echo $standardname."(".$nameid.")"; ?></a></li>
                                            <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                        </div>

       
		<?php
		
               
	}
        
        if($oper=="showinnerstandrads" and $oper!= "")
            {
            
             $gradeids = isset($method['gradeids']) ? $method['gradeids'] : '';
             
             ?>
                <ul>
                    <li><input type="checkbox" id="selecctall" /> Select All</li>
                </ul>
                <br>
                
              <?php
             $qrystandard = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardids,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='".$gradeids."'");
                                if($qrystandard->num_rows>0){
                                    while($rowstandard = $qrystandard->fetch_assoc())
                                    {
                                            extract($rowstandard);
             
             ?>
                <font color="blue">Big Idea <?php echo $nameid; ?></font>
                <br>
                <div class="selectbox">
                    <?php 
                    
                     $qry = $ObjDB->QueryObject("SELECT fld_innerstandard_guid AS innerstdgguid, fld_innerstandard_name AS innerstandardname,fld_id as innerstandardid,
                                                            fld_innerstandardname_id as innernameid
                                                            FROM itc_correlation_innerstandards 
                                                            WHERE fld_standard_id='".$standardids."'");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                            extract($row);
                                            ?>
                                            <ul style="list-style: none; ">
                                            <li>
                                                <input class="checkbox-class" type="checkbox" name="deepinnerid" id="deepinnerid_<?php echo $innerstandardid;?>" value="<?php echo $innerstdgguid;?>"  > 
                                                <?php echo $innernameid." ".$innerstandardname; ?>
                                            </li>
                                            </ul>
                                            <?php
                                            
                                            $deepqry = $ObjDB->QueryObject("SELECT fld_deepinnerstandard_guid AS deepinnerstdgguid, fld_deepinnerstandard_name AS deepinnerstandardname,fld_id as deepinnerstandardid,
                                                            fld_deepinnerstandardname_id as deepinnernameid
                                                            FROM itc_correlation_deepinnerstandards 
                                                            WHERE fld_innerstandard_id='".$innerstandardid."'");
                                            
                                            if($deepqry->num_rows>0){
                                                while($deeprow = $deepqry->fetch_assoc())
                                                {
                                                        extract($deeprow);
                                                        ?>
                                            <ul style="list-style: none; text-indent: 20px; ">
                                               
                                            <li>
                                                <input class="checkbox-class"  type="checkbox" name="deepinnerid" id="deepids_<?php echo $deepinnerstandardid; ?>" value="<?php echo $deepinnerstdgguid; ?>" >
                                            <?php echo $deepinnernameid." ".$deepinnerstandardname; ?>
                                            </li>
                                            </ul>
                                            <?php
                                             $subdeepqry = $ObjDB->QueryObject("SELECT fld_subdeepinnerstandard_guid AS subdeepinnerstdgguid, fld_subdeepinnerstandard_name AS subdeepinnerstandardname,fld_id as subdeepinnerstandardid,
                                                            fld_subdeepinnerstandardname_id as subdeepinnernameid
                                                            FROM itc_correlation_subdeepinnerstandards 
                                                            WHERE fld_deepinnerstandard_id='".$deepinnerstandardid."'");
                                             
                                            if($subdeepqry->num_rows>0){
                                                while($subdeeprow = $subdeepqry->fetch_assoc())
                                                {
                                                        extract($subdeeprow);
                                                                    ?>
                                            <ul style="list-style: none; text-indent: 20px; ">
                                               
                                            <li>
                                                <input class="checkbox-class"  type="checkbox" name="subdeepinnerid" id="subdeepids_<?php echo $subdeepinnerstandardid; ?>" value="<?php echo $subdeepinnerstdgguid; ?>" >
                                            <?php echo $subdeepinnernameid." ".$subdeepinnerstandardname; ?>
                                            </li>
                                            </ul>
                                                        <?php
                                                        }
                                                         }
                                                }
                                            }
                                            
                                            
                                            
                                            
                                            
                                    } // while ends
                                    
                                } // if ends 
                    
                    ?>
                 </div>
                <br><br>
                <?php
                                    } // if ends of standard
                                } // while ends of standard
                                ?><script>
                                $(document).ready(function() {
                                    $('#selecctall').click(function(event) {  //on click
                                        if(this.checked) { // check select status
                                            $('.checkbox-class').each(function() { //loop through each checkbox
                                                this.checked = true;  //select all checkboxes with class "checkbox1"              
                                            });
                                        }else{
                                            $('.checkbox-class').each(function() { //loop through each checkbox
                                                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                                            });        
                                        }
                                    });

                                });
                                </script>
                                    
                                    <?php
             
            
        }
	

if($oper=="showproducts" and $oper != " " )
{ 	
    $type = isset($method['type']) ? $method['type'] : 0;
    $typeids=explode(",",$type);
   
    ?>
                <script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible13').slimscroll({
					width: '410px',
					height:'366px',
					size: '3px',
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
					wheelStep: 1,
				
				});
				
				$('#testrailvisible14').slimscroll({
					width: '410px',
					height:'370px',
					size: '3px',
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
					wheelStep: 1,
				});
				$("#list7").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list7'){
								
								fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});
				$("#list8" ).sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkleft]").each(function(){ 						
							if($(this).parent().attr('id')=='list8'){								
								fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});
			}); 
		</script>
                                <?php 

                                if($sessmasterprfid==2)
	{ 

	  $qryipls="SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname	(a.fld_ipl_name,2) AS shortname, 
				1 AS typ, a.fld_asset_id AS assetid
				FROM itc_ipl_master AS a
				LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
				WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1'";

	 $qryunits="SELECT a.fld_id AS id, a.fld_unit_name AS nam,fn_shortname(a.fld_unit_name,2) AS shortname, 
				2 AS typ, a.fld_asset_id as assetid
	  			FROM itc_unit_master as a
				WHERE a.fld_delstatus='0'";
	  

	  $qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname,
 					3 AS typ, a.fld_asset_id AS assetid
					FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
	  
	  $qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid 
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
          /**Expedition Report Start here**/
          $qryexpedition="SELECT a.fld_id AS id, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS nam, 
                        fn_shortname (CONCAT(a.fld_exp_name, ' ', b.fld_version), 2) AS shortname,5 AS typ, a.fld_asset_id as assetid
							  FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'
							  ORDER BY a.fld_exp_name ASC ";
	  /**Expedition Report End here**/
	}

       
	 ?>
                <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Products</div>
                <div class="dragWell" id="testrailvisible13" >
                    <div id="list7" class="dragleftinner droptrue3">
                    	<div class="draglinkleftSearch" id="s_list7" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                                </dt>
                            </dl>
                        </div>
                        <?php
                            for($t=0;$t<sizeof($typeids);$t++){

                             if($typeids[$t]==1)
	  {
		   $qry=$qryipls." ORDER BY nam";
	  }
                              else if($typeids[$t]==2)
	  {
		   $qry=$qryunits." ORDER BY nam";
	  }  
                              else if($typeids[$t]==3)
	  {
		   $qry=$qrymodules." ORDER BY nam";
	  }  
                            else if($typeids[$t]==4)
	  {
		   $qry=$qrymathmodules." ORDER BY nam";
	  }  
                              else if($typeids[$t]==5)
	  {
		   $qry=$qryexpedition;
	  }  
          
	 
	  	$productqry = $ObjDB->QueryObject($qry);
                if($productqry->num_rows > 0)
                {
                    while($productqryrow = $productqry->fetch_assoc())
                    {
                        extract($productqryrow);
                        
                       
                            ?>
                            
                            <div class="draglinkleft" id="list7_<?php echo $id."_".$typ; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $id."_".$typ; ?>" title="<?php echo $nam;?>"><?php echo $shortname;?></div>
                                <div class="clickable" id="clck_<?php echo $id."_".$typ; ?>" onclick="fn_movealllistitems('list7','list8',1,'<?php echo $id."_".$typ; ?>');"></div>
                        </div>
                      	<?php
                       
                     } // while ends
                  } // if ends
               } // for ends					
                        ?>
                        </div>
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list7','list8',0);" style="cursor:pointer;cursor:hand;">add all Products</div>
            </div>
        </div>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected Products<span class="fldreq">*</span></div>
                <div class="dragWell" id="testrailvisible14">
                    <div id="list8" class="dragleftinner droptrue3">
                        <?php
	
                        
                                    if($productqry->num_rows > 0)
                                    {
                                        while($productqryrow = $productqry->fetch_assoc())
                                        {
                                            extract($productqryrow);


                            ?>
                            
                            <div class="draglinkleft" id="list8_<?php echo $id."_".$typ; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $id."_".$typ; ?>" title="<?php echo $nam;?>"><?php echo $shortname;?></div>
                                <div class="clickable" id="clck_<?php echo $id."_".$typ; ?>" onclick="fn_movealllistitems('list7','list8',1,'<?php echo $id."_".$typ; ?>');"></div>
                            </div>
                      	<?php
                        
                     } // while ends
                  } // if ends
                        	
                        ?>
                    </div>	
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list8','list7',0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right; ">remove all Products</div>
            </div>
        </div>

                        <?php
	
} 


/************Expedition code start here**************/       

if($oper=="showdestination" and $oper != " " )
{
 $expeid = isset($method['expid']) ? $method['expid'] : 0;
   
    $expids = explode('_',$expeid);
     
?>
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible1').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list9").sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list9'){
                                                fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list10" ).sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list10'){
                                                fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
              
        });																	
    </script>  
                            
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
               <?php
   
         $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_exp_destination_master WHERE fld_exp_id='".$expids[0]."' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
             ?>
              <div class="dragtitle">Destinations available</div>
                  <div class="draglinkleftSearch" id="s_list9" >
       <dl class='field row'>   
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible0" >
                      <div id="list9" class="dragleftinner droptrue1">
                          <?php 
                         if($qrydest->num_rows > 0){													
                              while($rowsdest = $qrydest->fetch_assoc()){
                                  extract($rowsdest);
                                ?>
                              <div class="draglinkleft" id="list9_<?php echo $destid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $expids[0]; ?>,<?php echo $destid;?>);"></div>
                              </div>
                                <?php 
                              }
                          }
                      ?>
                   </div>
               </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all destinations</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Destinations</div>
                  <div class="draglinkleftSearch" id="s_list10" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                          </dt>
       </dl>  
                  </div>
                  <div class="dragWell" id="testrailvisible1" >
                      <div id="list10" class="dragleftinner droptrue1">
    <?php
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list10_<?php echo $destid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $expids[0]; ?>,<?php echo $destid;?>);"></div>
                              </div>
                      <?php 
}
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all destinations</div>

          </div>
      </div>
    </div>  


<?php
}



if($oper=="showtasks" and $oper != " " )
{
$destidsall = isset($method['destids']) ? $method['destids'] : '';

$destid = explode(',',$destidsall);
?>
<script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible2').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible3').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list11").sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list11'){
                                                fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list12" ).sortable({
                        connectWith: ".droptrue2",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list12'){
                                                fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });

        });																	
    </script>  
         <div class='six columns'>
          <div class="dragndropcol">
            <div class="dragtitle">Tasks available</div>
                  <div class="draglinkleftSearch" id="s_list11" >
       <dl class='field row'>   
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this,'#list11');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible2" >
                      <div id="list11" class="dragleftinner droptrue2">
                          <?php 
                        for($i=0; $i<sizeof($destid); $i++)
                           {
                        $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                               AS shortname
                                                               FROM itc_exp_task_master
                                                               WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                            if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                ?>
                              <div class="draglinkleft" id="list11_<?php echo $taskid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $taskid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $destid[$i]; ?>,<?php echo $taskid;?>);"></div>
                              </div>
                                <?php 
                              }
                          }
                      }
                      ?>
                   </div>
               </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all Tasks</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Tasks</div>
                  <div class="draglinkleftSearch" id="s_list12" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_12_search" name="list_12_search" onKeyUp="search_list(this,'#list12');" />
                          </dt>
       </dl>  
                  </div>
                  <div class="dragWell" id="testrailvisible3" >
                      <div id="list12" class="dragleftinner droptrue2">
    <?php
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list12_<?php echo $taskid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $taskid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $destid[$i]; ?>,<?php echo $taskid;?>);"></div>
                              </div>
                      <?php 
}
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list12','list11',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all Tasks</div>

          </div>
      </div>

<?php
}



if($oper=="showresource" and $oper != " ")
{
    $taskidall = isset($method['taskids']) ? $method['taskids'] : ''; 
    $taskid = explode(',',$taskidall);
?>
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible4').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible5').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list13").sortable({
                        connectWith: ".droptrue3",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list13'){
                                                fn_movealllistitems('list13','list14',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list14" ).sortable({
                        connectWith: ".droptrue3",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list14'){
                                                fn_movealllistitems('list14','list13',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });

        });																	
    </script>  
                            
      <div class='six columns'>
          <div class="dragndropcol">
            <div class="dragtitle">Resources available</div>
                  <div class="draglinkleftSearch" id="s_list13" >
       <dl class='field row'>   
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_13_search" name="list_13_search" onKeyUp="search_list(this,'#list13');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible4" >
                      <div id="list13" class="dragleftinner droptrue3">
                          <?php 
                        for($j=0; $j<sizeof($taskid); $j++)
                           {
                        $qrycount = $ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id) FROM itc_exp_resource_master AS a 
                                                                        LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                        WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '".$schoolid."' ORDER BY a.fld_order"); 

                               if($qrycount!=0)
                               {

                               $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                       LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                       WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '".$schoolid."' ORDER BY a.fld_order");
                               }
                               else
                               {

                               $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                       LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                       WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '0' ORDER BY a.fld_order");
                               }
                               
                               if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                ?>
                              <div class="draglinkleft" id="list13_<?php echo $resoid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $resoid; ?>" title="<?php echo $resoname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $taskid[$j]; ?>,<?php echo $resoid;?>);"></div>
                              </div>
                                <?php 
                              }
                          }
                      }
                      ?>
                   </div>
               </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list13','list14',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all Resources</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Resources</div>
                  <div class="draglinkleftSearch" id="s_list14" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_14_search" name="list_12_search" onKeyUp="search_list(this,'#list14');" />
                          </dt>
       </dl>  
                  </div>
                  <div class="dragWell" id="testrailvisible5" >
                      <div id="list14" class="dragleftinner droptrue3">
    <?php
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list14_<?php echo $resoid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $resoid; ?>" title="<?php echo $resoname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $taskid[$j]; ?>,<?php echo $resoid;?>);"></div>
                              </div>
                      <?php 
}
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list14','list13',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all Resources</div>

          </div>
      </div>


    <?php
}



/************Expedition code start here**************/       

/*
 * starts for selecting production by tag types
 */
if($oper=="makecorrelation" and $oper != " " )
{
    $prdids = isset($method['productid']) ? $method['productid'] : '';
    $ptypeid = isset($method['ptype']) ? $method['ptype'] : '';
    $resids = isset($method['resid']) ? $method['resid'] : '';
    $standardguids = isset($method['guids']) ? $method['guids'] : '';
    $finalstandard=explode(",",$standardguids);
    $finalptypeid=explode(",",$ptypeid);
    $finalprdids=explode(",",$prdids);
    $finalresids=explode(",",$resids);
    
    $resultofstandards=array();
    
    for($i=0;$i<sizeof($finalstandard);$i++){
        
      
    for($a=0;$a<sizeof($finalprdids);$a++)
    {
        $finalprdtypeids=explode("_",$finalprdids[$a]);
        
        if($finalprdtypeids[1]!=5)
        {
            $prdguid = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                            FROM itc_correlation_products 
                                            WHERE fld_prd_type='".$finalprdtypeids[1]."' 
                                            AND fld_prd_sys_id='".$finalprdtypeids[0]."' AND fld_exp_type='0'");
        }
        else if($finalprdtypeids[1]==5)
        {
                  $prdguid = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                            FROM itc_correlation_products 
                                            WHERE fld_prd_type='".$finalprdtypeids[1]."' 
                                            AND fld_prd_sys_id='".$finalprdtypeids[0]."' AND fld_exp_type='3'");
              
          
        }
            
                $resultofstandards[]=$finalstandard[$i]."~".$prdguid."~".$finalprdtypeids[1]."~".$finalprdtypeids[0];
          } // for ends products
       } // for ends standards
    echo json_encode($resultofstandards);
}

if($oper =="correlationsignature" and $oper != " " )
{
     $passetguid = isset($method['passetguid']) ? $method['passetguid'] : '';
     $standguids = isset($method['standguids']) ? $method['standguids'] : '';
     
     $icnt = isset($method['icnt']) ? $method['icnt'] : '';
     $ptype= isset($method['ptype']) ? $method['ptype'] : '';
     $prdid= isset($method['prdid']) ? $method['prdid'] : '';
     
     $partnerID   = 'pitsco';
     $partnerKey  = 'q044Qjav7i8dzgCJ6riUWA';
     $authExpires = time() + 86400;
     $userID      = '';
     $url = 'http://api.academicbenchmarks.com/rest/v3/standards/'.$standguids.'/assets/'.$passetguid.'?';
     $url .= 'partner.id=' . $partnerID;
     $message = $authExpires . "\n" . $userID;
     $sig = urlencode(base64_encode(hash_hmac('sha256',$message,$partnerKey,true)));

  $url .= '&auth.signature=' . $sig;
  $url .= '&auth.expires=' . $authExpires;
 
  $url .= '&user.id=' . $userID;
  
  echo $icnt."~".$url."~".$ptype."~".$prdid."~".$standguids."~".$passetguid;
   
    
}

if($oper=="savealignment" and $oper != " " )
{
    $prdids = isset($method['prdid']) ? $method['prdid'] : '';
    $ptypeid = isset($method['ptype']) ? $method['ptype'] : '';
    $stateid = isset($method['stateid']) ? $method['stateid'] : '';
    $documentid = isset($method['documentid']) ? $method['documentid'] : '';
    $grades = isset($method['grades']) ? $method['grades'] : '';
    $standardguids = isset($method['guid']) ? $method['guid'] : ''; 
    $alignmenttypeids = isset($method['alignmenttype']) ? $method['alignmenttype'] : ''; 
    $prdassetidguids = isset($method['prdassetid']) ? $method['prdassetid'] : '';
    $finalstandard=explode(",",$standardguids);
   
    
    for($i=0;$i<sizeof($finalstandard);$i++)
    {
        $gradid = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_grades 
                                            WHERE fld_grade_guid='".$grades."'");
        
         $qrystandard = $ObjDB->QueryObject("SELECT fld_standard_guid AS stdgguid, fld_standard_name AS standardname,fld_id as standardids,
                                                            fld_standardname_id as nameid
                                                            FROM itc_correlation_standards 
                                                            WHERE fld_grade_id='".$gradid."'");
        if($qrystandard->num_rows>0){
         while($rowstandard = $qrystandard->fetch_assoc())
            {
                    extract($rowstandard);

                    $totstd[]=$standardids;

                $qry = $ObjDB->QueryObject("SELECT fld_innerstandard_guid AS innerstdgguid, fld_innerstandard_name AS innerstandardname,fld_id as innerstandardid,
                                                      fld_innerstandardname_id as innernameid
                                                      FROM itc_correlation_innerstandards 
                                                      WHERE fld_standard_id='".$standardids."'");
                          if($qry->num_rows>0){
                              while($row = $qry->fetch_assoc())
                              {
                               extract($row);

                               $totstd[]=$innerstandardid;
                                            
                                $deepqry = $ObjDB->QueryObject("SELECT fld_deepinnerstandard_guid AS deepinnerstdgguid, fld_deepinnerstandard_name AS deepinnerstandardname,fld_id as deepinnerstandardid,
                                                               fld_deepinnerstandardname_id as deepinnernameid
                                                               FROM itc_correlation_deepinnerstandards 
                                                               WHERE fld_innerstandard_id='".$innerstandardid."'");

                                       if($deepqry->num_rows>0){
                                           while($deeprow = $deepqry->fetch_assoc())
                                           {
                                            extract($deeprow);

                                            $totstd[]=$deepinnerstandardid;

                                            $subdeepqry = $ObjDB->QueryObject("SELECT fld_id as subdeepinnerstandardid
                                                                               FROM itc_correlation_subdeepinnerstandards 
                                                                               WHERE fld_deepinnerstandard_id='".$deepinnerstandardid."'");

                                               if($subdeepqry->num_rows>0){
                                                while($subdeeprow = $subdeepqry->fetch_assoc())
                                                {
                                                   extract($subdeeprow);

                                                    $totstd[]=$subdeepinnerstandardid;
                                                }
                                               }
                                            }
                                        }
                                } // while ends
                              } // if ends 
                            } // if ends of standard
                        } // while ends of standard
                        
        if($ptypeid!=5)
        {
            $cunt = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_alignment 
                                            WHERE fld_prdid='".$prdids."' AND fld_ptype='".$ptypeid."' 
                                            AND fld_deepinnerstandard='".$finalstandard[$i]."' AND fld_resoid='0' AND fld_delstatus='0'");
            
            
            if($cunt==0){
                if($alignmenttypeids==2)
                {
                      $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('".$stateid."','".$documentid."', '".$grades."','".$finalstandard[$i]."','".$ptypeid."','".$prdids."','0','".date('Y-m-d H:i:s')."','".$uid."','1')");
               
                }
                else{
                $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('".$stateid."','".$documentid."', '".$grades."','".$finalstandard[$i]."','".$ptypeid."','".$prdids."','0','".date('Y-m-d H:i:s')."','".$uid."','0')");
                }                
            }
            else{
                if($alignmenttypeids==2)
                {
                     $ObjDB->NonQuery("UPDATE itc_correlation_alignment set fld_delstatus='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
                                            WHERE fld_state_id='".$stateid."' AND fld_documentsubid='".$documentid."' AND fld_gradeid='".$grades."' 
                                            AND fld_deepinnerstandard='".$finalstandard[$i]."' AND fld_ptype='".$ptypeid."' AND fld_prdid='".$prdids."' AND fld_resoid='0'");
                     
                   
                    $stndcunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id)
                                                            FROM itc_correlation_alignment 
                                                            WHERE fld_gradeid='".$grades."' AND fld_prdid='".$prdids."' AND fld_ptype='".$ptypeid."' 
                                                            AND fld_resoid='0' AND fld_delstatus='1'");


                    if($stndcunt== sizeof($totstd))
                    {
                        $prdasset = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                FROM itc_correlation_products 
                                WHERE fld_prd_sys_id='".$prdids."' AND fld_prd_type='".$ptypeid."' 
                                AND fld_exp_type='0'");

                        $ObjDB->NonQuery("UPDATE itc_correlation_productsgradeout set fld_flag='1',fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_standardguid='".$grades."' AND fld_productid='".$prdasset."'");

                    }
                }
            }
            
        }
        else if($ptypeid==5)
        {
            $cunt = $ObjDB->SelectSingleValue("SELECT fld_id
                                            FROM itc_correlation_alignment 
                                            WHERE fld_prdid='".$prdids."' AND fld_ptype='".$ptypeid."' 
                                            AND fld_deepinnerstandard='".$finalstandard[$i]."' AND fld_resoid='".$prdids."' AND fld_delstatus='0'");
             if($cunt==0){
                 
                  $ObjDB->NonQuery("INSERT INTO itc_correlation_alignment(fld_state_id, fld_documentsubid, fld_gradeid, fld_innerstandard, fld_deepinnerstandard, fld_ptype, fld_prdid, fld_resoid, fld_createddate, fld_createdby,fld_delstatus) 
                                                    VALUES ('".$stateid."','".$documentid."', '".$grades."','".$standradids."','".$finalstandard[$i]."','".$ptypeid."','".$prdids."','".$prdids."','".date('Y-m-d H:i:s')."','".$uid."','0')");
                  
                
            }
            else{
                 if($alignmenttypeids==2)
                {
                     $ObjDB->NonQuery("UPDATE itc_correlation_alignment set fld_delstatus='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
                                            WHERE fld_state_id='".$stateid."' AND fld_documentsubid='".$documentid."' AND fld_gradeid='".$grades."' 
                                            AND fld_deepinnerstandard='".$finalstandard[$i]."' AND fld_ptype='".$ptypeid."' AND fld_prdid='".$prdids."' AND fld_resoid='".$prdids."'");
                     
                     $stndcunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id)
                                                            FROM itc_correlation_alignment 
                                                            WHERE fld_gradeid='".$grades."' AND fld_prdid='".$prdids."' AND fld_ptype='".$ptypeid."' 
                                                            AND fld_resoid='".$prdids."' AND fld_delstatus='1'");


                    if($stndcunt== sizeof($totstd))
                    {
                        $prdasset = $ObjDB->SelectSingleValue("SELECT fld_prd_asset_id
                                                                FROM itc_correlation_products 
                                                                WHERE fld_prd_sys_id='".$prdids."' AND fld_prd_type='".$ptypeid."' 
                                                                AND fld_exp_type='3'");

                        $ObjDB->NonQuery("UPDATE itc_correlation_productsgradeout set fld_flag='1',fld_updated_date='".date('Y-m-d H:i:s')."' WHERE fld_standardguid='".$grades."' AND fld_productid='".$prdasset."'");

                    }
                }
            }
        }
        
    }
   
}
	@include("footer.php");
