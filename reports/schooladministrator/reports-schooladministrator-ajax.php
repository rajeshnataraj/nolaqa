<?php 
@include("sessioncheck.php");


$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

//*--- Load Class Lists ---*/
if($oper=="showclass" and $oper != " " )
{
    $schlid = isset($method['schlid']) ? $method['schlid'] : '';
    $distid = isset($method['distid']) ? $method['distid'] : '0';

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
            if($sessmasterprfid==2)
            {
                $qryclass= $ObjDB->QueryObject("SELECT  fld_id AS clsid, fld_class_name AS classname, fn_shortname(CONCAT(fld_class_name), 2) AS shortname
                                                FROM  itc_class_master 
                                                WHERE fld_school_id='".$schlid."' AND fld_district_id='".$distid."'  AND fld_delstatus = '0' AND fld_archive_class='0'");
            }
            else
            {
                $qryclass= $ObjDB->QueryObject("SELECT  fld_id AS clsid, fld_class_name AS classname, fn_shortname(CONCAT(fld_class_name), 2) AS shortname
                                                FROM  itc_class_master 
                                                WHERE fld_school_id='".$schoolid."'  AND fld_delstatus = '0' AND fld_archive_class='0'");
            }
             ?>
              <div class="dragtitle">Class available</div>
                  <div class="draglinkleftSearch" id="s_list15" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_15_search" name="list_15_search" onKeyUp="search_list(this,'#list15');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible15" >
                      <div id="list15" class="dragleftinner droptrue1">
                       <?php 		
                         if($qryclass->num_rows > 0){													
                              while($rowclass = $qryclass->fetch_assoc()){
                                  extract($rowclass);
                        ?>
                              <div class="draglinkleft" id="list15_<?php echo $clsid; ?>" name="<?php echo $clsid; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $clsid; ?>" title="<?php echo $classname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $clsid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $clsid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all classes</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Classes</div>
                  <div class="draglinkleftSearch" id="s_list16" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_16_search" name="list_16_search" onKeyUp="search_list(this,'#list16');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible16" >
                      <div id="list16" class="dragleftinner droptrue1">
                       
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list16','list15',0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all classes</div>
         
          </div>
      </div>
    </div>  

<?php 
} 


//*--- Load Class Lists ---*/
if($oper=="showhomepurchase" and $oper != " " )
{
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
            if($sessmasterprfid==2)
            {
                $qryclass= $ObjDB->QueryObject("SELECT  fld_id AS clsid, fld_class_name AS classname, fn_shortname(CONCAT(fld_class_name), 2) AS shortname
                                                FROM  itc_class_master 
                                                WHERE fld_school_id='0' AND fld_district_id='0' AND fld_user_id<>'0'  AND fld_delstatus = '0'");
            }
            
             ?>
              <div class="dragtitle">Class available</div>
                  <div class="draglinkleftSearch" id="s_list15" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_15_search" name="list_15_search" onKeyUp="search_list(this,'#list15');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible15" >
                      <div id="list15" class="dragleftinner droptrue1">
                       <?php 		
                         if($qryclass->num_rows > 0){													
                              while($rowclass = $qryclass->fetch_assoc()){
                                  extract($rowclass);
                        ?>
                              <div class="draglinkleft" id="list15_<?php echo $clsid; ?>" name="<?php echo $clsid; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $clsid; ?>" title="<?php echo $classname;?>"><?php echo $shortname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $clsid;?>" onclick="fn_movealllistitems('list15','list16',<?php echo $clsid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all classes</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Classes</div>
                  <div class="draglinkleftSearch" id="s_list16" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_16_search" name="list_16_search" onKeyUp="search_list(this,'#list16');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible16" >
                      <div id="list16" class="dragleftinner droptrue1">
                       
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list16','list15',0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all classes</div>
         
          </div>
      </div>
    </div>  

<?php 
} 


//*--- Load Teacher Lists ---*/
if($oper=="showteachers" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '0';
    
    if($classid=='')
    {
        $classid='0';
    }
    
  ?>
	<script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible17').slimscroll({
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
                $('#testrailvisible18').slimscroll({
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
                $("#list17").sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list17'){
                                                fn_movealllistitems('list17','list18',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list18" ).sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list18'){
                                                fn_movealllistitems('list17','list18',$(this).children(":first").attr('id'));
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
                
                $qryteacher= $ObjDB->QueryObject("SELECT b.fld_id as id, concat(b.fld_fname,'',b.fld_lname) as teachername from itc_class_teacher_mapping as a 
                left join itc_user_master as b on b.fld_id=a.fld_teacher_id where a.fld_class_id in (".$classid.") AND a.fld_flag='1' and b.fld_delstatus='0' group by a.fld_teacher_id");
           
             ?>
              <div class="dragtitle">Teacher available</div>
                  <div class="draglinkleftSearch" id="s_list17" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_17_search" name="list_17_search" onKeyUp="search_list(this,'#list17');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible17" >
                      <div id="list17" class="dragleftinner droptrue1">
                       <?php 		
                         if($qryteacher->num_rows > 0){													
                              while($rowteacher = $qryteacher->fetch_assoc()){
                                  extract($rowteacher);
                        ?>
                              <div class="draglinkleft" id="list17_<?php echo $id; ?>" name="<?php echo $id; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $id; ?>" title="<?php echo $teachername;?>"><?php echo $teachername; ?></div>
                                  <div class="clickable" id="clck_<?php echo $id;?>" onclick="fn_movealllistitems('list17','list18',<?php echo $id;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list17','list18',0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all teachers</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Teachers</div>
                  <div class="draglinkleftSearch" id="s_list18" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_18_search" name="list_18_search" onKeyUp="search_list(this,'#list18');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible18" >
                      <div id="list18" class="dragleftinner droptrue1">
                       
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list18','list17',0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all teachers</div>
         
          </div>
      </div>
    </div>  

<?php 
} 

//*--- Load Assignments Lists ---*/
if($oper=="showassignments" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
    
    if($classid=='')
    {
        $classid='0';
    }
    
    if($teacherid=='')
    {
        $teacherid='0';
    }

  ?>
	<script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible19').slimscroll({
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
                $('#testrailvisible20').slimscroll({
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
                $("#list19").sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        items: "div[class='draglinkleft']",
                        receive: function(event, ui) { 
                                $("div[class=draglinkright]").each(function(){ 
                                        if($(this).parent().attr('id')=='list19'){
                                                fn_movealllistitems('list19','list20',$(this).children(":first").attr('id'));
                                        }
                                });											
                        }
                });

                $( "#list20" ).sortable({
                        connectWith: ".droptrue1",
                        dropOnEmpty: true,
                        receive: function(event, ui) { 
                                $("div[class=draglinkleft]").each(function(){ 
                                        if($(this).parent().attr('id')=='list20'){
                                                fn_movealllistitems('list19','list20',$(this).children(":first").attr('id'));
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
            
               
                $qryipl= $ObjDB->QueryObject("SELECT 
                                                a.fld_class_id as classid,
                                                a.fld_id AS scheduleid,
                                                b.fld_lesson_id AS assids,
                                                fn_shortname(c.fld_ipl_name, 1) AS nam,
                                                c.fld_ipl_name AS fullnam,
                                                0 AS typeids,
                                                b.fld_createdby as createdby
                                                FROM
                                                itc_class_sigmath_master AS a
                                                    LEFT JOIN
                                                itc_class_sigmath_lesson_mapping AS b ON a.fld_id = b.fld_sigmath_id
                                                    LEFT JOIN
                                                itc_ipl_master AS c ON c.fld_id = b.fld_lesson_id
                                            WHERE
                                                    a.fld_class_id IN (".$classid.")
                                                    AND a.fld_createdby IN (".$teacherid.")
                                                    AND a.fld_flag = '1'
                                                    AND a.fld_delstatus = '0'
                                                    AND b.fld_flag = '1'
                                                    AND c.fld_delstatus = '0'");
               
                  $qryrot=$ObjDB->QueryObject("SELECT 
                                a.fld_class_id as classid,
                                a.fld_id AS scheduleid,
                                b.fld_module_id AS assids,
                                b.fld_type AS typeids,
                                a.fld_createdby as createdby
                            FROM
                                itc_class_rotation_schedule_mastertemp AS a
                                    LEFT JOIN
                                itc_class_rotation_moduledet AS b ON b.fld_schedule_id = a.fld_id

                            WHERE
                                    a.fld_class_id IN (".$classid.")
                                    AND a.fld_createdby IN (".$teacherid.")
                                    AND a.fld_delstatus = '0'
                                    AND b.fld_flag = '1'");
                
                $qrywca=$ObjDB->QueryObject("SELECT 
                                                    a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        a.fld_module_id AS assids,
                                                        5 AS typeids,
                                                        a.fld_createdby as createdby,
                                                        b.fld_module_name as modulename,
                                                        'MODULE' as type
                                                FROM
                                                    itc_class_indassesment_master AS a
                                                        LEFT JOIN
                                                    itc_module_master AS b ON a.fld_module_id = b.fld_id
                                                WHERE
                                                        a.fld_class_id IN (".$classid.")
                                                        AND a.fld_createdby IN (".$teacherid.")
                                                        AND a.fld_flag = '1'
                                                        AND a.fld_delstatus = '0'
                                                        AND a.fld_moduletype = '1'
                                                        AND b.fld_delstatus = '0'
                                                GROUP BY a.fld_id
                                                UNION all
                                                SELECT 
                                                    a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        a.fld_module_id AS assids,
                                                        6 AS typeids,
                                                        a.fld_createdby as createdby,
                                                        b.fld_mathmodule_name as modulename,
                                                        'MATHW' as type
                                                FROM
                                                    itc_class_indassesment_master AS a
                                                        LEFT JOIN
                                                    itc_mathmodule_master AS b ON a.fld_module_id = b.fld_id
                                                WHERE
                                                        a.fld_class_id IN (".$classid.")
                                                        AND a.fld_createdby IN (".$teacherid.")
                                                        AND a.fld_flag = '1'
                                                        AND a.fld_delstatus = '0'
                                                        AND a.fld_moduletype = '2'
                                                        AND b.fld_delstatus = '0'
                                                GROUP BY a.fld_id
                                                UNION all
                                                SELECT 
                                                    a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        a.fld_module_id AS assids,
                                                        7 AS typeids,
                                                        a.fld_createdby as createdby,
                                                        b.fld_module_name as modulename,
                                                        'QUEST' as type
                                                FROM
                                                    itc_class_indassesment_master AS a
                                                        LEFT JOIN
                                                    itc_module_master AS b ON a.fld_module_id = b.fld_id
                                                WHERE
                                                        a.fld_class_id IN (".$classid.")
                                                        AND a.fld_createdby IN (".$teacherid.")
                                                        AND a.fld_flag = '1'
                                                        AND a.fld_delstatus = '0'
                                                        AND a.fld_moduletype = '7'
                                                        AND b.fld_delstatus = '0'
                                                GROUP BY a.fld_id
                                                UNION all
                                                SELECT 
                                                    a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        a.fld_module_id AS assids,
                                                        17 AS typeids,
                                                        a.fld_createdby as createdby,
                                                        b.fld_contentname as modulename,
                                                        'CUSTOM' as type
                                                FROM
                                                    itc_class_indassesment_master AS a
                                                        LEFT JOIN
                                                    itc_customcontent_master AS b ON a.fld_module_id = b.fld_id
                                                WHERE
                                                        a.fld_class_id IN (".$classid.")
                                                        AND a.fld_createdby IN (".$teacherid.")
                                                        AND a.fld_flag = '1'
                                                        AND a.fld_delstatus = '0'
                                                        AND a.fld_moduletype = '17'
                                                        AND b.fld_delstatus = '0'
                                                GROUP BY a.fld_id
                                                ");
                
                
                               $qryexp=$ObjDB->QueryObject("SELECT 
                                                            a.fld_class_id as classid,
                                                            a.fld_id AS scheduleid,
                                                            a.fld_exp_id AS assids,
                                                            b.fld_exp_name AS expname,
                                                            15 AS typeids,
                                                            'EXP' as type,
                                                            a.fld_createdby as createdby
                                                        FROM
                                                            itc_class_indasexpedition_master AS a
                                                                LEFT JOIN
                                                            itc_exp_master AS b ON a.fld_exp_id = b.fld_id
                                                        WHERE
                                                                a.fld_class_id IN (".$classid.")
                                                                AND a.fld_createdby IN (".$teacherid.")
                                                                AND a.fld_flag = '1'
                                                                AND a.fld_delstatus = '0'
                                                                AND b.fld_delstatus = '0'
                                                        GROUP BY a.fld_id");
                               
                                 $qryexprot=$ObjDB->QueryObject("SELECT 
                                                        a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        b.fld_expedition_id AS assids,
                                                        17 AS typeids,
                                                        a.fld_createdby as createdby
                                                    FROM
                                                        itc_class_rotation_expschedule_mastertemp AS a
                                                            LEFT JOIN
                                                        itc_class_rotation_expschedulegriddet AS b ON b.fld_schedule_id = a.fld_id

                                                    WHERE
                                                            a.fld_class_id IN (".$classid.")
                                                            AND a.fld_createdby IN (".$teacherid.")
                                                            AND a.fld_delstatus = '0'
                                                            AND b.fld_flag = '1' GROUP BY assids");                      

                                    $qrymodexprot=$ObjDB->QueryObject("SELECT 
                                                        a.fld_class_id as classid,
                                                        a.fld_id AS scheduleid,
                                                        b.fld_module_id AS assids,
                                                        19 AS typeids,
                                                        a.fld_createdby as createdby
                                                    FROM
                                                        itc_class_rotation_modexpschedule_mastertemp AS a
                                                            LEFT JOIN
                                                        itc_class_rotation_modexpschedulegriddet AS b ON b.fld_schedule_id = a.fld_id

                                                    WHERE
                                                            a.fld_class_id IN (".$classid.")
                                                            AND a.fld_createdby IN (".$teacherid.")
                                                            AND a.fld_delstatus = '0' AND b.fld_type='2'
                                                            AND b.fld_flag = '1' GROUP BY assids"); 
    
                               
                               /**********Mission Code start here updated by Mohan M 4-9-2015***********/
                               
                                    $qrymis=$ObjDB->QueryObject("SELECT a.fld_class_id as classid, a.fld_id AS scheduleid, a.fld_mis_id AS assids, b.fld_mis_name AS misname,18 AS typeids,
                                                                        'Mis' as type, a.fld_createdby as createdby FROM itc_class_indasmission_master AS a
                                                                LEFT JOIN itc_mission_master AS b ON a.fld_mis_id = b.fld_id
                                                                WHERE	a.fld_class_id IN (".$classid.") AND a.fld_createdby IN (".$teacherid.") AND a.fld_flag = '1'
                                                                                AND a.fld_delstatus = '0' AND b.fld_delstatus = '0'
                                                                GROUP BY a.fld_id");
                               
                                $qrymisrot=$ObjDB->QueryObject("SELECT 
                                                                a.fld_class_id as classid,
                                                                a.fld_id AS scheduleid,
                                                                b.fld_mission_id AS assids,
                                                                20 AS typeids,
                                                                a.fld_createdby as createdby
                                                            FROM
                                                                itc_class_rotation_mission_mastertemp AS a
                                                                    LEFT JOIN
                                                                itc_class_rotation_mission_schedulegriddet AS b ON b.fld_schedule_id = a.fld_id

                                                            WHERE
                                                                    a.fld_class_id IN (".$classid.")
                                                                    AND a.fld_createdby IN (".$teacherid.")
                                                                    AND a.fld_delstatus = '0'
                                                                    AND b.fld_flag = '1'  GROUP BY assids");
                               /**********Mission Code start here updated by Mohan M 4-9-2015***********/
    
                                             $qrytest=$ObjDB->QueryObject("SELECT 
                                                                            a.fld_class_id AS classid,
                                                                            b.fld_id AS assids,
                                                                            b.fld_test_name AS testname,
                                                                            9 AS typeids,
                                                                            'Test' AS type,
                                                                            a.fld_created_by as createdby
                                                                        FROM
                                                                            itc_test_student_mapping AS a
                                                                                LEFT JOIN
                                                                            itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                        WHERE
                                                                            a.fld_class_id IN (".$classid.")
                                                                                AND a.fld_created_by IN (".$teacherid.")
                                                                                AND a.fld_flag = '1'
                                                                                AND b.fld_delstatus = '0'
                                                                                AND b.fld_ass_type = '0'
                                                                        GROUP BY b.fld_id");

                
                
                                            
            
             ?>
              <div class="dragtitle">Assignments available</div>
                  <div class="draglinkleftSearch" id="s_list19" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_19_search" name="list_19_search" onKeyUp="search_list(this,'#list19');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible19" >
                      <div id="list19" class="dragleftinner droptrue1">
                       <?php 	
                         // IPL
                         if($qryipl->num_rows > 0){													
                              while($rowipl = $qryipl->fetch_assoc()){
                                  extract($rowipl);
                        ?>
                              <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>" title="<?php echo $fullnam;?>"><?php echo $fullnam." / IPL"; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                          
                          
                         // Rot		
                         if($qryrot->num_rows > 0){													
                              while($rowrot = $qryrot->fetch_assoc()){
                                  extract($rowrot);
                                  $modulename='';
                                  $type='';
                                  if($typeids==1)
                                  {
                                      $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
			                              FROM itc_module_master AS a 
										  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$assids."'    
										  WHERE a.fld_id='".$assids."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                      $type="MODULE";
                                      $typeids=2;
                                  }
                                  else if($typeids==2)
                                  {
                                      
                                      $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version)
				                            FROM itc_mathmodule_master AS a 
											LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											WHERE a.fld_id='".$assids."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                      $type="MATH";
                                      $typeids=4;
                                  }
                                  else if($typeids==8)
                                  {
                                      $modulename=$ObjDB->SelectSingleValue("SELECT fld_contentname from itc_customcontent_master WHERE fld_id='".$assids."' AND fld_delstatus='0'");
                                      
                                      $type="CUSTOM";
                                      $typeids=8;
                                  }
                                  
                        ?>
                              <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" title="<?php echo $modulename;?>"><?php echo $modulename." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                          
                          // WCA
                         if($qrywca->num_rows > 0){													
                              while($rowca = $qrywca->fetch_assoc()){
                                  extract($rowca);
                        ?>
                              <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>" title="<?php echo $modulename;?>"><?php echo $modulename." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                          
                          
                       /**********Expedition Code start here updated by Mohan M 28-6-2016***********/
                        if($qryexp->num_rows > 0)
                        {													
                              while($rowexp = $qryexp->fetch_assoc()){
                                  extract($rowexp);
                        ?>
                              <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>" title="<?php echo $expname;?>"><?php echo $expname." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                        if($qryexprot->num_rows > 0)
                        {													
                            while($rowexprot = $qryexprot->fetch_assoc())
                            {
                                extract($rowexprot);
                          
                                $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
                                                                          FROM itc_exp_master AS a 
                                                                          LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id='".$assids."'    
                                                                          WHERE a.fld_id='".$assids."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                $type="Exp Sch";
                                $typeids=17;
                                ?>
                                <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" title="<?php echo $modulename;?>"><?php echo $modulename." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                                </div>
                                <?php 
                            }
                        }
                        if($qrymodexprot->num_rows > 0)
                        {													
                            while($rowmodexprot = $qrymodexprot->fetch_assoc())
                            {
                                extract($rowmodexprot);
                          
                                $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
                                                                          FROM itc_exp_master AS a 
                                                                          LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id='".$assids."'    
                                                                          WHERE a.fld_id='".$assids."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                $type="Mod/Exp Sch";
                                $typeids=19;
                                ?>
                                <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" title="<?php echo $modulename;?>"><?php echo $modulename." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                                </div>
                                <?php 
                            }
                        }
                       /**********Expedition Code start here updated by Mohan M 28-6-2016***********/
    
                        /**********Mission Code start here updated by Mohan M 4-9-2015***********/
                            if($qrymis->num_rows > 0)
                            {													
                                while($rowmis = $qrymis->fetch_assoc())
                                {
                                    extract($rowmis);
                                    ?>
                                    <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>" title="<?php echo $misname;?>"><?php echo $misname." / ".$type; ?></div>
                                        <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                                    </div>
                                   <?php 
                                }
                            }
                            if($qrymisrot->num_rows > 0)
                            {													
                              while($rowmisrot = $qrymisrot->fetch_assoc())
                              {
                                  extract($rowmisrot);
                                  
                                  $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mis_name,' ',b.fld_version)
                                                                              FROM itc_mission_master AS a 
                                                                              LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id='".$assids."'    
                                                                              WHERE a.fld_id='".$assids."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                  $type="Mis Sch";
                                  $typeids=20;
                                    ?>
                                  <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>">
                                      <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" title="<?php echo $modulename;?>"><?php echo $modulename." / ".$type; ?></div>
                                      <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                                  </div>
                                    <?php 
                              }
                          }
                        /**********Mission Code start here updated by Mohan M 4-9-2015***********/
                          
                          // Test
                         if($qrytest->num_rows > 0){													
                              while($rowtest = $qrytest->fetch_assoc()){
                                  extract($rowtest);
                        ?>
                              <div class="draglinkleft" id="list19_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby; ?>" name="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>">
                                  <div class="dragItemLable tooltip" id="<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;; ?>" title="<?php echo $testname;?>"><?php echo $testname." / ".$type; ?></div>
                                  <div class="clickable" id="clck_<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>" onclick="fn_movealllistitems('list19','list20','<?php echo $classid."-".$scheduleid."-".$assids."-".$typeids."-".$createdby;?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                          
                          
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list19','list20',0);" style="cursor: pointer;cursor:hand;width:  155px;float: right;">add all assignments</div>
          </div>
      </div>
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Assignments</div>
                  <div class="draglinkleftSearch" id="s_list20" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_20_search" name="list_20_search" onKeyUp="search_list(this,'#list20');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible20" >
                      <div id="list20" class="dragleftinner droptrue1">
                       
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list20','list19',0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all assignments</div>
         
          </div>
      </div>
    </div>  

<?php 
} 


if($oper=="showschools" and $oper != " " )
{
    $distid = isset($method['distid']) ? $method['distid'] : '';
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
                                <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showclasspitsco(<?php echo $schoolid;?>,<?php echo $distid;?>)"><?php echo $schoolname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>

        </div>

	<?php 
} 


if($oper=="showschoolpurchase" and $oper != " " )
{
    $distid = isset($method['distid']) ? $method['distid'] : '0';
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
                                                                            WHERE fld_district_id ='0' AND fld_delstatus='0' 
                                                                            ORDER BY fld_school_name ASC ");
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showclasspitsco(<?php echo $schoolid;?>,<?php echo $distid;?>)"><?php echo $schoolname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>

        </div>

	<?php 
} 



if($oper=="showdistpurchase" and $oper != " " )
{
?>
                         Select District
                        <div class="selectbox">
                                <input type="hidden" name="districtid" id="districtid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select District</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search District">
                                    <ul role="options" style="width:100%">
                                        <?php 

                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname FROM itc_district_master WHERE fld_delstatus='0' ORDER BY fld_district_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $districtid;?>" onclick="fn_showschool(<?php echo $districtid;?>)"><?php echo $districtname; ?></a></li>
                                                <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        
                  
<?php
}
	@include("footer.php");
?>


	
