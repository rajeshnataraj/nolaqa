<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showclass" and $oper != " " )
{
    $type = isset($method['id']) ? $method['id'] : '';   
    ?>
		Class 
		<dl class='field row'>
			<div class="selectbox">
				<input type="hidden" name="classid" id="classid" value="">
				<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
					<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
					<b class="caret1"></b>
				</a>
				<div class="selectbox-options">
					<input type="text" class="selectbox-filter" placeholder="Search Class">
					<ul role="options" style="width:100%">
						<?php 
                                                if($type==1){
                                                      $qry = $ObjDB->QueryObject("SELECT a.fld_class_name AS classname,a.fld_id AS classid
                                                                        FROM itc_class_master AS a
                                                                        LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_class_id 
                                                                       WHERE a.fld_created_by='".$uid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_archive_class='0' GROUP BY classid ORDER BY classname");
                                               
                                                
						if($qry->num_rows>0){
                                                    while($row = $qry->fetch_assoc())
                                                    {
                                                            extract($row);
                                                            ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showexpeditionschedule(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
                                                            <?php
                                                        }
                                              }	
                                       }    
                                      else if($type==2){
                                                      $qry = $ObjDB->QueryObject("SELECT a.fld_class_name AS classname,a.fld_id AS classid
                                                                        FROM itc_class_master AS a
                                                                        LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_class_id 
                                                                       WHERE a.fld_created_by='".$uid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_archive_class='0' GROUP BY classid ORDER BY classname");     
                                            if($qry->num_rows>0){
                                                    while($row = $qry->fetch_assoc())
                                                    {
                                                            extract($row);
                                                            ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showmissionschedule(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
                                                            <?php
                                                        }
                                              }	
                                            }
                                                      ?>      
					</ul>
				</div>
			</div> 
		</dl>
		<?php
    }
    if($oper=="showexpeditionschedulebyclass" and $oper != " " )
{
    $classid = isset($method['clsid']) ? $method['clsid'] : '';
    ?>
		Schedule 
		<dl class='field row'>
			<div class="selectbox">
				<input type="hidden" name="schid" id="schid" value="">
				<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
					<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
					<b class="caret1"></b>
				</a>
				<div class="selectbox-options">
					<input type="text" class="selectbox-filter" placeholder="Search Schedule">
					<ul role="options" style="width:100%">
						<?php 
                                                      $qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-15') AS scheduleid,fld_id AS expschid, 15 AS typename 
                                                                                        FROM itc_class_indasexpedition_master
                                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                UNION ALL	
                                                                                SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-19') AS scheduleid, fld_id AS expschid,  19 AS typename 
                                                                                        FROM itc_class_rotation_expschedule_mastertemp 
                                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                UNION ALL
                                                                                SELECT a.fld_schedule_name AS schedulename, CONCAT(a.fld_id,'-20') AS scheduleid, 
                                                                                        a.fld_id AS expschid ,20 AS typename 
                                                                                        FROM itc_class_rotation_modexpschedule_mastertemp as a
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0'
                                                                                        AND a.fld_flag='1' group by scheduleid");
                                                    if($qry->num_rows>0){
                                                        while($row = $qry->fetch_assoc())
                                                        {
                                                                extract($row);
                                                            ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid."~".$typename;?>" onclick="fn_showexpedition(<?php echo $expschid;?>,<?php echo $typename;?>,<?php echo $classid;?>)"><?php echo $schedulename; ?></a></li>
                                                            <?php
                                                        }
                                              }	
                                                      ?>      
					</ul>
				</div>
			</div> 
		</dl>
		<?php
    }

/*--- Load Student Dropdown ---*/
if($oper=="showexpeditionbyclass" and $oper != " " )
{
$type = isset($method['type']) ? $method['type'] : '';
$scheid = isset($method['schid']) ? $method['schid'] : '';
$classid = isset($method['clsid']) ? $method['clsid'] : '';
$scheduleid = preg_replace("-", "", $scheid);
?> 
     <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible6').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible7').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list7").sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list7'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list8" ).sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list8'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
                
        });																	
    </script>  
    <input type="hidden" name="schtype" id="schtype" value="<?php echo $type; ?>"> 
    <input type="hidden" name="schedid" id="schedid" value="<?php echo $scheid; ?>">
    <div class="row rowspacer" id="expeditionlist">
      <div class='six columns'>
          <div class="dragndropcol">
                         
          <?php
         if($type == '15'){ 
             $qryexp= $ObjDB->QueryObject("SELECT a.fld_id AS expid FROM itc_exp_master AS a
                                           LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id WHERE b.fld_id='".$scheid."'");
         }
         if($type == '19'){
             $qryexp= $ObjDB->QueryObject("SELECT fld_exp_id as expid,fld_numberofrotation as numberofrotations 
                                                    FROM itc_class_rotation_expmoduledet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheid."' 
                                                    AND fld_flag=1 order by fld_row_id ASC");
         }
         if($type == '20'){
             $qryexp= $ObjDB->QueryObject("SELECT fld_module_id as expid,fld_numberofrotation as numberofrotations FROM itc_class_rotation_modexpmoduledet
									WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheid."' 
									AND fld_type='2' AND fld_flag=1 order by fld_row_id ASC");
         }
   
             ?>
              <div class="dragtitle">Expeditions available</div>
                  <div class="draglinkleftSearch" id="s_list7" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible6" >
                      <div id="list7" class="dragleftinner droptrue1">
                       <?php 		
                         if($qryexp->num_rows > 0){													
                              while($rowsexp = $qryexp->fetch_assoc()){
                                  extract($rowsexp);
                                  $expname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."' AND fld_delstatus='0'");
                                  ?>
                              <div class="draglinkleft" id="list7_<?php echo $expid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $expid; ?>" title="<?php echo $expname;?>"><?php echo $expname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $expid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $expid;?>,<?php echo $expid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list7','list8',0,0);" style="cursor: pointer;cursor:hand;width:  145px;float: right;">add all Expeditions</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Expeditions</div>
                  <div class="draglinkleftSearch" id="s_list8" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_8_search" name="list_8_search" onKeyUp="search_list(this,'#list8');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible7" >
                      <div id="list8" class="dragleftinner droptrue1">
                       <?php 		
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list8_<?php echo $expid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $expid; ?>" title="<?php echo $expname;?>"><?php echo $expname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $expid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $expid;?>,<?php echo $expid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list8','list7',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Expeditions</div>
         
          </div>
      </div>
    </div>  

<?php
}
         /* show mission by class starts   */ 

if($oper=="showmisschedulebyclass" and $oper != " " )
{
    $scheid = isset($method['schid']) ? $method['schid'] : '';
    $classid = isset($method['clsid']) ? $method['clsid'] : '';
    ?>
		Schedule 
		<dl class='field row'>
			<div class="selectbox">
				<input type="hidden" name="schid" id="schid" value="">
				<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
					<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
					<b class="caret1"></b>
				</a>
				<div class="selectbox-options">
					<input type="text" class="selectbox-filter" placeholder="Search Schedule">
					<ul role="options" style="width:100%">
						<?php 
                                                      $qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-18') AS scheduleid,fld_id AS expschid, 18 AS typename 
                                                                                        FROM itc_class_indasmission_master
                                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                UNION ALL	
                                                                                SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-23') AS scheduleid, fld_id AS expschid,  23 AS typename 
                                                                                        FROM itc_class_rotation_mission_mastertemp 
                                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                ");
                                                    if($qry->num_rows>0){
                                                        while($row = $qry->fetch_assoc())
                                                        {
                                                                extract($row);
                                                            ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid."~".$typename;?>" onclick="fn_showmission(<?php echo $expschid;?>,<?php echo $typename;?>,<?php echo $classid;?>)"><?php echo $schedulename; ?></a></li>
                                                            <?php
                                                        }
                                              }	
                                                      ?>      
					</ul>
				</div>
			</div> 
		</dl>
		<?php
    }
    
if($oper=="showmissionbyclass" and $oper != " " )
{
$type = isset($method['type']) ? $method['type'] : '';
$scheid = isset($method['schid']) ? $method['schid'] : '';
$classid = isset($method['clsid']) ? $method['clsid'] : '';

?> 
     <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible6').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible7').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list7").sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list7'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list8" ).sortable({
                        connectWith: ".droptrue5",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list8'){
                                                fn_movealllistitems('list7','list8',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
              
        });																	
    </script>  
    <input type="hidden" name="schtype" id="schtype" value="<?php echo $type; ?>">
    <input type="hidden" name="schedid" id="schedid" value="<?php echo $scheid; ?>">
    <div class="row rowspacer" id="missionlist">
      <div class='six columns'>
          <div class="dragndropcol">
                         
          <?php
          if($type == '18'){
          $qryexp= $ObjDB->QueryObject("SELECT b.fld_mis_id AS misid, fn_shortname (CONCAT(a.fld_mis_name), 2)AS shortname, b.fld_id as schedid FROM itc_mission_master AS a
                                                    LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_mis_id 
                                                    WHERE b.fld_class_id='".$classid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0' GROUP BY misid");
          }
          if($type == '23'){
              $qryexp= $ObjDB->QueryObject("SELECT fld_mission_id as misid,fld_numberofrotation as numberofrotations FROM itc_class_rotation_missiondet
									WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheid."' 
									AND fld_flag=1 order by fld_row_id ASC");
         }
             ?>
              <div class="dragtitle">Missions available</div>
                  <div class="draglinkleftSearch" id="s_list7" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible6" >
                      <div id="list7" class="dragleftinner droptrue1">
                       <?php 		
                         if($qryexp->num_rows > 0){													
                              while($rowsexp = $qryexp->fetch_assoc()){
                                  extract($rowsexp);
                                  $misname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$misid."' AND fld_delstatus='0'");
                                  ?>
                              <div class="draglinkleft" id="list7_<?php echo $misid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $misid; ?>" title="<?php echo $misname;?>"><?php echo $misname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $misid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $misid;?>,<?php echo $misid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list7','list8',0,0);" style="cursor: pointer;cursor:hand;width:  145px;float: right;">add all Missions</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Missions</div>
                  <div class="draglinkleftSearch" id="s_list8" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_8_search" name="list_8_search" onKeyUp="search_list(this,'#list8');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible7" >
                      <div id="list8" class="dragleftinner droptrue1">
                       <?php 		
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list8_<?php echo $misid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $misid; ?>" title="<?php echo $misname;?>"><?php echo $misname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $misid;?>" onclick="fn_movealllistitems('list7','list8',<?php echo $misid;?>,<?php echo $misid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list8','list7',0,0);" style="cursor: pointer;cursor:hand;width:  180px;float: right;">remove all Missions</div>
         
          </div>
      </div>
    </div>  

<?php
}
            /* show mission by class ends   */

if($oper=="showdestination" and $oper != " " )
{
$expidall = isset($method['expids']) ? $method['expids'] : '';
$missionall = isset($method['misids']) ? $method['misids'] : '';

$typeid = isset($method['typeid']) ? $method['typeid'] : '';
$scheid = isset($method['schid']) ? $method['schid'] : '';

$expid = explode(',',$expidall);
$misid = explode(',',$missionall);
?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
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
                        size: '7px',
                        alwaysVisible: true,
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
                <input type="hidden" name="expid" id="expid" value="<?php echo $expid; ?>">
                <input type="hidden" name="misid" id="misid" value="<?php echo $misid; ?>">
                <input type="hidden" name="scheid" id="scheid" value="<?php echo $scheid; ?>">
          <?php
         
             ?>
              <div class="dragtitle"><?php if($typeid==0){ ?>Destinations available <?php }else { ?>Intervals available <?php } ?></div>
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
                        if($typeid==0)
                        {
                            for($i=0; $i<sizeof($expid); $i++)
                             {

                                $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_exp_destination_master WHERE fld_exp_id='".$expid[$i]."' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
                           
                                if($qrydest->num_rows > 0){													
                                     while($rowsdest = $qrydest->fetch_assoc()){
                                         extract($rowsdest);
                                         ?>

                                     <div class="draglinkleft" id="list9_<?php echo $destid; ?>" >
                                         <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname;?>"><?php echo $shortname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $expid; ?>); "></div>
                                     </div>

                                        <?php 
                                     }
                                 }                                                   
                           }
                        }
                        else
                        {
                          for($i=0; $i<sizeof($misid); $i++)
                          {  
                            $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM  itc_mis_destination_master WHERE fld_mis_id='".$misid[$i]."' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
                        
                             if($qrydest->num_rows > 0){													
                                     while($rowsdest = $qrydest->fetch_assoc()){
                                         extract($rowsdest);
                                         $misname = $ObjDB->SelectSingleValue("SELECT fn_shortname (CONCAT(fld_mis_name), 2) FROM itc_mission_master WHERE fld_id='".$misid[$i]."' AND fld_delstatus='0'");
                                         ?>

                                     <div class="draglinkleft" id="list9_<?php echo $destid; ?>" >
                                         <div class="dragItemLable tooltip" id="<?php echo $destid; ?>" title="<?php echo $destname;?>"><?php echo $shortname." / ".$misname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $misid; ?>); "></div>
                                     </div>

                                        <?php 
                                     }
                                 }                                                   
                           }
                            
                        }
                                          
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  145px;float: right;"><?php if($typeid==0){ ?>add all Destinations<?php }else { ?>add all Intervals<?php } ?></div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle"><?php if($typeid==0){ ?>Selected Destinations<?php }else { ?>Selected Intervals<?php } ?></div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="if( $typeid==0){fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $expid; ?>); }else{fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $misid; ?>);}"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;"><?php if($typeid==0){ ?>remove all Destinations<?php }else { ?>remove all Intervals<?php } ?></div>
         
          </div>
      </div>
    </div>  


<?php
}

if($oper=="showtasks" and $oper != " " )
{
    $typeid = isset($method['typeid']) ? $method['typeid'] : '';
    $destidsall = isset($method['destids']) ? $method['destids'] : '';
    $scheid = isset($method['schid']) ? $method['schid'] : '';
    $destid = explode(',',$destidsall);

?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible2').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
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
                        size: '7px',
                        alwaysVisible: true,
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
                            
    <div class="row rowspacer" id="tasklist">
      <div class='six columns'>
          <div class="dragndropcol">
                 <input type="hidden" name="scheid" id="scheid" value="<?php echo $scheid; ?>">
                  <input type="hidden" name="profileid" id="profileid" value="<?php echo $sessmasterprfid; ?>">
          <?php
         
         
         ?>
              <div class="dragtitle"><?php if($typeid==0){ ?>Tasks available<?php }else { ?>Resources available<?php } ?></div>
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
                            if($typeid==0)
                            { 
                            
                              $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                                     AS shortname,fld_exp_id AS misid
                                                                     FROM itc_exp_task_master
                                                                     WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                            }
                            else
                            {
                                $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                                     AS shortname,fld_mis_id AS misid
                                                                     FROM itc_mis_task_master
                                                                     WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                                
                            }
                            
                            if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  $misname = $ObjDB->SelectSingleValue("SELECT fn_shortname (CONCAT(fld_mis_name), 2) FROM itc_mission_master WHERE fld_id='".$misid."' AND fld_delstatus='0'"); 
                                        
                                  ?>
                              <div class="draglinkleft" id="list11_<?php echo $taskid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname;?>"><?php if($typeid==0) { echo $shortname; } else { echo $shortname." / ".$misname; } ?></div>
                                  <div class="clickable" id="clck_<?php echo $taskid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $taskid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                      }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width:  160px;float: right;"><?php if($typeid==0){ ?>add all Tasks<?php }else { ?>add all Resources<?php } ?></div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle"><?php if($typeid==0){ ?>Selected Tasks<?php }else { ?>Selected Resources<?php } ?></div>
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
                                  <div class="clickable" id="clck_<?php echo $taskid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $taskid;?>,0); "></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list12','list11',0,0);" style="cursor: pointer;cursor:hand;width:  166px;float: right;"><?php if($typeid==0){ ?>remove all Tasks<?php }else { ?>remove all Resources<?php } ?></div>
         
          </div>
      </div>
    </div>  


<?php
 
}

if($oper=="showresources" and $oper != " " )
{
    $typeid = isset($method['typeid']) ? $method['typeid'] : '';
    $taskidall = isset($method['taskids']) ? $method['taskids'] : '';
    $scheid = isset($method['schid']) ? $method['schid'] : '';
    $taskid = explode(',',$taskidall);

?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible4').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
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
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list11").sortable({
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

                $( "#list12" ).sortable({
                        connectWith: ".droptrue3",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list14'){
                                                fn_movealllistitems('list13','list14',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
             
        });																	
    </script>  
                            
    <div class="row rowspacer" id="tasklist">
      <div class='six columns'>
          <div class="dragndropcol">
                    <input type="hidden" name="scheid" id="scheid" value="<?php echo $scheid; ?>">
                    <input type="hidden" name="profileid" id="profileid" value="<?php echo $sessmasterprfid; ?>">
          <?php
         
             ?>
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
                            if($typeid==0)
                            { 
                                    $qrycount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_exp_resource_master AS a 
                                                                                 LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                                 WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '".$schoolid."' AND b.fld_created_by='".$uid."' ORDER BY a.fld_order"); 
                                    if($qrycount!='0')
                                    {

                                    $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                            LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                            WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '".$schoolid."' AND b.fld_created_by='".$uid."' ORDER BY a.fld_order");
                                    }
                                    else
                                    {

                                    $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_exp_resource_master AS a 
                                                                            LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                            WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '0' ORDER BY a.fld_order");
                                    }

                            }
                            else
                            {
                               
                                $qrycount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_mis_resource_master AS a 
                                                                                 LEFT JOIN itc_mis_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                                 WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '".$schoolid."' AND b.fld_created_by='".$uid."'  ORDER BY a.fld_order"); 
                                    if($qrycount!='0')
                                    {

                                    $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_mis_resource_master AS a 
                                                                            LEFT JOIN itc_mis_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                            WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '".$schoolid."' AND b.fld_created_by='".$uid."' ORDER BY a.fld_order");
                                    }
                                    else
                                    {

                                    $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname FROM itc_mis_resource_master AS a 
                                                                            LEFT JOIN itc_mis_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                            WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_status IN (1,2) AND b.fld_school_id = '0' ORDER BY a.fld_order");
                                    }
                                
                                
                                
                            }
                            
                        
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkleft" id="list13_<?php echo $resoid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $resoid; ?>" title="<?php echo $resoname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $resoid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                        }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list13','list14',0,0);" style="cursor: pointer;cursor:hand;width:  220px;float: right;">add all Resources</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Resources</div>
                  <div class="draglinkleftSearch" id="s_list14" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_14_search" name="list_14_search" onKeyUp="search_list(this,'#list14');" />
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
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $resoid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list14','list13',0,0);" style="cursor: pointer;cursor:hand;width:  220px;float: right;">remove all Resources</div>
         
          </div>
      </div>
    </div>  


<?php
}

/******Pitsco admin Coding Start Here**********/

if($oper=="showexpforpitsco" and $oper != " " )
{
?> 
    <div class='six columns'> 
                    Select Expedition
                    <div class="selectbox">
                        <input type="hidden" name="expeditionid" id="expeditionid" value="">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Expedition</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                            <ul role="options" style="width:100%">
                                <?php 
                                $qry = $ObjDB->QueryObject("SELECT a.fld_id AS expid, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS expname, 
                                                                fn_shortname (CONCAT(a.fld_exp_name, ' ', b.fld_version), 1) AS shortname 
                                                                    FROM itc_exp_master AS a 
                                                                    LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
                                                                    WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' 
                                                                    ORDER BY a.fld_exp_name ASC ");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php  echo $expid?>" onclick="fn_showdestinationforpitsco(<?php  echo $expid;?>)"><?php echo $expname; ?></a></li>
                                        <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                    </div>
                      </div>
    
<?php
}
/* Pitsco code for mission */

if($oper=="showmisonforpitsco" and $oper != " " )
{
?> 
    <div class='six columns'> 
                    Select Mission
                    <div class="selectbox">
                        <input type="hidden" name="missionid" id="missionid" value="">
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Mission</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Mission">
                            <ul role="options" style="width:100%">
                                <?php 
                                $qry = $ObjDB->QueryObject("SELECT a.fld_id AS misid, CONCAT(a.fld_mis_name, ' ', b.fld_version) AS misname, 
                                                                fn_shortname (CONCAT(a.fld_mis_name, ' ', b.fld_version), 1) AS shortname 
                                                                    FROM itc_mission_master AS a 
                                                                    LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id = a.fld_id 
                                                                    WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' 
                                                                    ORDER BY a.fld_mis_name ASC ");
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php  echo $misid?>" onclick="fn_showdestinationforpitsco(<?php  echo $misid;?>)"><?php echo $misname; ?></a></li>
                                        <?php
                                    }
                                }?>      
                            </ul>
                        </div>
                    </div>
                      </div>
    
<?php
}
       /* Pitsco code for mission ENDS*/

       
/* Pitsco destination */

if($oper=="showdestinationforpitsco" and $oper != " " )
{
    $id = isset($method['id']) ? $method['id'] : '';
    $typeid = isset($method['typeid']) ? $method['typeid'] : '';

?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
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
                        size: '7px',
                        alwaysVisible: true,
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
            
          if( $typeid==0){
                $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_exp_destination_master 
                                                                WHERE fld_exp_id='".$id."' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
          }
          else{
              
               $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_mis_destination_master 
                                                                WHERE fld_mis_id='".$id."' AND fld_delstatus='0' GROUP BY destid ORDER BY fld_order");
          }
             ?>
              <div class="dragtitle"><?php if( $typeid==0){ ?>Destinations available<?php }else { ?>Intervals available<?php } ?></div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $id; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;"><?php if( $typeid==0){ ?>add all Destinations<?php }else { ?>add all Intervals<?php } ?></div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle"><?php if( $typeid==0){ ?>Selected Destinations<?php }else { ?>Selected Intervals<?php } ?></div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $id; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;"><?php if( $typeid==0){ ?>remove all Destinations<?php }else { ?>remove all Intervals<?php } ?></div>
         
          </div>
      </div>
    </div>  

<?php
}
    /* Pitsco destination ENDS */

if($oper=="showdistricts" and $oper != " " )
{
    $id = isset($method['id']) ? $method['id'] : '';
     $typeid = isset($method['typeid']) ? $method['typeid'] : '';
     
	?>
	Select District
	<div class="selectbox">
            <input type="hidden" name="distid" id="distid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select District</span> <b class="caret1"></b> </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search District">
                <ul role="options" style="width:100%">
                <?php
                       
                if($typeid==0){
                    
                    $qry = $ObjDB->QueryObject("SELECT b.fld_district_id AS distid, c.fld_district_name AS distname FROM itc_license_exp_mapping AS a
                                                    LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id
                                                    LEFT JOIN itc_district_master AS c ON b.fld_district_id=c.fld_id
                                                    WHERE a.fld_exp_id='".$id."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND c.fld_delstatus='0' group by distid ORDER BY distname ASC");
                }
                else{
                    
                    $qry = $ObjDB->QueryObject("SELECT b.fld_district_id AS distid, c.fld_district_name AS distname FROM itc_license_mission_mapping AS a
                                                    LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id
                                                    LEFT JOIN itc_district_master AS c ON b.fld_district_id=c.fld_id
                                                    WHERE a.fld_mis_id='".$id."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND c.fld_delstatus='0' group by distid ORDER BY distname ASC");
                }
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $distid;?>" onclick="fn_showschools(<?php echo $distid;?>,<?php echo $id;?>)"><?php echo $distname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>

        </div>

	<?php 
} 

if($oper=="showschools" and $oper != " " )
{
    $distid = isset($method['distid']) ? $method['distid'] : '';
    $id = isset($method['id']) ? $method['id'] : '';   
	?>
	Select School
	<div class="selectbox">
            <input type="hidden" name="schoolid" id="schoolid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span> <b class="caret1"></b> </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search School">
                <ul role="options" style="width:100%">
                <?php 
                
                    $qry = $ObjDB->QueryObject("SELECT fld_id as schoolid, fld_school_name AS schoolname 
                                                                            FROM itc_school_master 
                                                                            WHERE fld_district_id ='".$distid."' AND fld_delstatus='0' 
                                                                            ORDER BY fld_school_name ASC ");
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showclasses(<?php echo $schoolid;?>,<?php echo $distid;?>,<?php echo $id;?>)"><?php echo $schoolname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>

        </div>

	<?php 
} 

/*--- Load Student Dropdown ---*/
if($oper=="showclasses" and $oper != " " )
{
    $schlid = isset($method['schlid']) ? $method['schlid'] : '';
    $distid = isset($method['distid']) ? $method['distid'] : '';
    $id = isset($method['id']) ? $method['id'] : '';
    $typeid = isset($method['typeid']) ? $method['typeid'] : '';  
    
	?>
	<script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible15').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible16').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '7px',
                        alwaysVisible: true,
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,
                });
                $("#list15").sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list15'){
                                                fn_movealllistitems('list15','list16',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list16" ).sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list16'){
                                                fn_movealllistitems('list15','list16',$(this).children(":first").attr('id'));
                                        }
                                });								
                        }
                });
             
        });																	
    </script>  
                            
    <div class="row rowspacer" id="classdivforpitsco">
      <div class='six columns'>
          <div class="dragndropcol">
            <?php
  
            if($typeid==0){ 
                $qrydest= $ObjDB->QueryObject("SELECT w.* FROM(SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                       FROM itc_class_indasexpedition_master AS a
                                                       LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                       WHERE c.fld_school_id = '".$schlid."' AND c.fld_district_id='".$distid."' AND a.fld_exp_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_delstatus = '0'
                                                       
                                                       UNION ALL
                                                       SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                       FROM itc_class_rotation_expschedulegriddet AS a
                                                       LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                       WHERE c.fld_school_id = '".$schlid."' AND c.fld_district_id='".$distid."' AND a.fld_expedition_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_flag = '1'
                                                       
                                                       UNION ALL
                                                       SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                       FROM itc_class_rotation_modexpschedulegriddet AS a
                                                       LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                       WHERE c.fld_school_id = '".$schlid."' AND a.fld_type = '2' AND c.fld_district_id='".$distid."' AND a.fld_module_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_flag = '1') AS w
                                                       GROUP BY w.clsid
                                                       ORDER BY w.classname ASC");
            }
            else{
                
                
                
                $qrydest= $ObjDB->QueryObject("SELECT w.* FROM(SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                       FROM itc_class_indasmission_master AS a
                                                       LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                       WHERE c.fld_school_id = '".$schlid."' AND c.fld_district_id='".$distid."' AND a.fld_mis_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_delstatus = '0'
                                                       
                                                       UNION ALL
                                                       SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                       FROM itc_class_rotation_mission_schedulegriddet AS a
                                                       LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                       WHERE c.fld_school_id = '".$schlid."' AND c.fld_district_id='".$distid."' AND a.fld_mission_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_flag = '1') AS w
                                                       GROUP BY w.clsid
                                                       ORDER BY w.classname ASC");
                
            }
             ?>
              <div class="dragtitle">Class available</div>
                  <div class="draglinkleftSearch" id="s_list15" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list15');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible15" >
                      <div id="list15" class="dragleftinner droptrue1">
                       <?php 		
                         if($qrydest->num_rows > 0){													
                              while($rowsdest = $qrydest->fetch_assoc()){
                                  extract($rowsdest);
                                  
                                   if($typeid==0){                                                                         
                                                $schedid = $ObjDB->SelectSingleValue("SELECT b.fld_id FROM itc_exp_master AS a
                                                                                                      LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id 
                                                                                                      WHERE b.fld_class_id='".$clsid."' AND a.fld_id='".$id."'
                                                                                                       AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'  ");
                                    }
                                    else{
                                    
                                                $schedid = $ObjDB->SelectSingleValue("SELECT b.fld_id FROM itc_mission_master AS a
                                                                                                   LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_mis_id 
                                                                                                   WHERE b.fld_class_id='".$clsid."' AND a.fld_id='".$id."'
                                                                                                    AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'  ");
                                    
                                     }
                                  
                                  ?>
                                <input type="hidden" name="scheid" id="scheid" value="<?php echo $schedid; ?>">
                              <div class="draglinkleft" id="list15_<?php echo $clsid; ?>" name="<?php echo $clsid; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $clsid; ?>" title="<?php echo $destname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $clsid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $clsid;?>,<?php echo $id; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all classes</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Classes</div>
                  <div class="draglinkleftSearch" id="s_list16" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list16');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible16" >
                      <div id="list16" class="dragleftinner droptrue1">
                       <?php 		
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkright" id="list16_<?php echo $clsid; ?>" name="<?php echo $clsid."^".$schedid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $clsid; ?>" title="<?php echo $destname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $clsid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $clsid;?>,<?php echo $id; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list16','list15',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all classes</div>
         
          </div>
      </div>
    </div>  


	<?php 
} 


/******Pitsco admin Coding End Here**********/
@include("footer.php");