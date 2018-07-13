<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/* expendition drop down */
if($oper=="showexpend" and $oper != " " )
{
?> 
    Select Expedition
    <dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="expid" id="expid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Expedition</span> <b class="caret1"></b> </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                <ul role="options" style="width:100%">
                                                            <?php 
                    $qry = $ObjDB->QueryObject("SELECT a.fld_id AS expid, CONCAT( a.fld_exp_name, ' ', fld_version) AS expname, 
                                                                fn_shortname (CONCAT(a.fld_exp_name, ' ', fld_version), 1) AS shortname 
                                                         FROM itc_exp_master AS a 
                                                                                     LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_exp_id 
                                                         LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                                                         LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = a.fld_id 
                                                         WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_school_id='".$schoolid."' 
                                                                                     AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                     AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' ".$sqry." 
                                                                                     GROUP BY a.fld_id
                                                                                     ORDER BY a.fld_exp_name ASC");
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>" onclick="fn_showdestinationforexpend(<?php echo $expid;?>)"><?php echo $expname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>
        </div>
    </dl>
                   
<?php
}
/* expendition drop down */

/* Mission drop down */
if($oper=="showmison" and $oper != " " )
{
?> 
Select Mission
    <dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="expid" id="expid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Mission</span> <b class="caret1"></b> </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Mission">
                <ul role="options" style="width:100%">
                                                            <?php 
                    $qry = $ObjDB->QueryObject("SELECT a.fld_id AS misid, CONCAT( a.fld_mis_name, ' ', fld_version) AS misname, 
                                                                fn_shortname (CONCAT(a.fld_mis_name, ' ', fld_version), 1) AS shortname 
                                                         FROM itc_mission_master AS a 
                                                                                     LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_mis_id 
                                                         LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                                                         LEFT JOIN itc_mission_version_track AS d ON d.fld_mis_id = a.fld_id 
                                                         WHERE a.fld_delstatus='0'  AND d.fld_delstatus = '0' AND c.fld_school_id='".$schoolid."' 
                                                                                     AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                     AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' ".$sqry." 
                                                                                     GROUP BY a.fld_id
                                                                                     ORDER BY a.fld_mis_name ASC");
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $misid;?>" onclick="fn_showdestinationformison(<?php echo $misid;?>)"><?php echo $misname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>
        </div>
    </dl>
<?php
}
/* Mission drop down Ends */

/* Destionation for Expendition */

if($oper=="showdestinationforexpend" and $oper != " " )
{
$expid = isset($method['expid']) ? $method['expid'] : '';

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
   
         $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_exp_destination_master WHERE fld_exp_id='".$expid."' AND fld_delstatus='0' GROUP BY destid ORDER BY destid");
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $expid; ?>);"></div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $expid; ?>);"></div>
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
/* Destination for Mission starts */


if($oper=="showdestinationformison" and $oper != " " )
{
$misid = isset($method['misid']) ? $method['misid'] : '';

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
   
         $qrydest= $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 2)AS shortname FROM itc_mis_destination_master WHERE fld_mis_id='".$misid."' AND fld_delstatus='0' GROUP BY destid ORDER BY destid");
             ?>
              <div class="dragtitle">Intervals available</div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $misid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all Intervals</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Intervals</div>
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
                                  <div class="clickable" id="clck_<?php echo $destid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $destid;?>,<?php echo $misid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all Intervals</div>
         
          </div>
      </div>
    </div>  


<?php
}
/* Destination for Mission ends */

if($oper=="showtasks" and $oper != " " )
{
$destids = isset($method['destids']) ? $method['destids'] : '';
$id = isset($method['id']) ? $method['id'] : '';
$destid = explode(',',$destids);

$typeid = isset($method['typeid']) ? $method['typeid'] : '';
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
                 
        
              <div class="dragtitle"><?php if($typeid==0){ ?>Tasks available<?php }else{ ?>Resources available<?php } ?></div>
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
                           if($typeid==0){                           
                                $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                                   AS shortname
                                                                   FROM itc_exp_task_master
                                                                   WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                           }
                           else{
                                $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname,fn_shortname (CONCAT(fld_task_name), 2) 
                                                                   AS shortname
                                                                   FROM itc_mis_task_master
                                                                   WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order"); 
                               
                           }
                            
                            if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkleft" id="list11_<?php echo $taskid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $taskid; ?>" title="<?php echo $taskname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $taskid;?>" onclick="fn_movealllistitems('list11','list12',<?php echo $taskid;?>,0);"></div>
                              </div>
                      <?php 
                              }
                          }
                      }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width:  150px;float: right;"><?php if($typeid==0){ ?>add all Tasks<?php }else{ ?>add all Resources<?php } ?></div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle"><?php if($typeid==0){ ?>Selected Tasks<?php }else{ ?>Selected Resources<?php } ?></div>
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
                <div class="dragAllLink"  onclick="fn_movealllistitems('list12','list11',0,0);" style="cursor: pointer;cursor:hand;width:  170px;float: right;"><?php if($typeid==0){ ?>remove all Tasks<?php }else{ ?>remove all Resources<?php } ?></div>
         
          </div>
      </div>
    </div>  


<?php
 
}

if($oper=="showresources" and $oper != " " )
{
$taskidall = isset($method['taskids']) ? $method['taskids'] : '';
$id = isset($method['id']) ? $method['id'] : '';
$taskid = explode(',',$taskidall);

$typeid = isset($method['typeid']) ? $method['typeid'] : '';
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
                            if($typeid==0){      
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
                            else{
                                 $qrycount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_mis_resource_master AS a 
                                                                             LEFT JOIN itc_mis_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                             WHERE a.fld_task_id='".$taskid[$j]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '".$schoolid."' AND b.fld_created_by='".$uid."' ORDER BY a.fld_order"); 
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
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $resoid;?>,<?php echo $id; ?>);"></div>
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
                                  <div class="clickable" id="clck_<?php echo $resoid;?>" onclick="fn_movealllistitems('list13','list14',<?php echo $resoid;?>,<?php echo $id; ?>);"></div>
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

if($oper=="showclass" and $oper != "" )
{
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
                            
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
            <?php
            if($typeid==0){
            
                $qrydest= $ObjDB->QueryObject("SELECT w.* FROM(SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS 	shortname
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
                
                 $qrydest= $ObjDB->QueryObject("SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, 18 AS scheduletype, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                          FROM itc_class_indasmission_master AS a
                                                          LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                          WHERE c.fld_school_id = '".$schoolid."' AND c.fld_district_id='".$districtid."' AND a.fld_mis_id = '".$id."' AND c.fld_delstatus = '0' AND a.fld_delstatus = '0'
                                               UNION ALL
                                               SELECT a.fld_class_id AS clsid, a.fld_id AS scheduleid, 23 AS scheduletype, c.fld_class_name AS classname, fn_shortname(CONCAT(c.fld_class_name), 2) AS shortname
                                                          FROM itc_class_rotation_mission_schedulegriddet a 
                                                          LEFT JOIN itc_class_master AS c ON c.fld_id = a.fld_class_id
                                                          WHERE c.fld_school_id = '".$schoolid."' AND c.fld_district_id='".$districtid."' AND a.fld_mission_id = '".$id."' AND c.fld_delstatus = '0'
                                                          GROUP BY clsid
                                                          ORDER BY classname ASC");
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
                                  
                                  ?>
                                
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


if($oper=="showschools" and $oper != " " )
{
  
    $id = isset($method['id']) ? $method['id'] : '';
   
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
    <input type="hidden" name="distid" id="distid" value="<?php echo $sendistid; ?>">                        
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
            <?php
           
   
         $qrydest= $ObjDB->QueryObject("SELECT fld_id as schoolid, fld_school_name AS schoolname 
                                                                            FROM itc_school_master 
                                                                            WHERE fld_district_id ='".$districtid."' AND fld_delstatus='0' 
                                                                       ORDER BY fld_school_name ASC");
             ?>
              <div class="dragtitle">Schools available</div>
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
                                  ?>
                               
                              <div class="draglinkleft" id="list15_<?php echo $schoolid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $schoolid; ?>" title="<?php echo $schoolname;?>"><?php echo $schoolname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $schoolid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $schoolid;?>,<?php echo $sendistid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all schools</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Schools</div>
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
                              <div class="draglinkright" id="list16_<?php echo $schoolid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $schoolid; ?>" title="<?php echo $schoolname;?>"><?php echo $schoolname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $schoolid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $schoolid;?>,<?php echo $sendistid; ?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list16','list15',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all schools</div>
         
          </div>
      </div>
    </div>  
    <?php 
} 

@include("footer.php");