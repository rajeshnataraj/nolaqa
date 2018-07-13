<?php 
/*
	Created By - Muthukumar. D
	Page - reports-classroom-classroomajax.php
	History:
*/
	@include("sessioncheck.php");
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	
/*--- Load Student Dropdown ---*/
if($oper=="showschoolpurchase" and $oper != " " )
{   
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



    <div class="row rowspacer" >
      <div class ='tweleve columns'>
      <div class='six columns'>
          <div class="dragndropcol">
              
         <?php 
            $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
                                        FROM itc_school_master 
                                        WHERE fld_delstatus='0'");
         ?>
              <div class="dragtitle">School available</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkleft" id="list9_<?php echo $schoolid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $schoolid; ?>" title="<?php echo $schoolname;?>"><?php echo $schoolname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $schoolid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $schoolid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all schools</div>
          </div>
      </div>
      
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Schools</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkright" id="list10_<?php echo $schoolid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $schoolid; ?>" title="<?php echo $schoolname;?>"><?php echo $schoolname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $schoolid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $schoolid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all schools</div>
         
      </div>    </div>
      </div>
    </div>  
<?php
}
          
/*--- Load Student Dropdown ---*/
if($oper=="showhomepurchase" and $oper != " " )
{    
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

    <div class="row rowspacer" >
      <div class ='tweleve columns'>
      <div class='six columns'>
          <div class="dragndropcol">
              
         <?php 
            $qry = $ObjDB->QueryObject("SELECT  CONCAT(`fld_lname`,' ',`fld_fname` ) AS fullname ,fld_id  AS hmeid
                                                FROM `itc_user_master`  
                                                WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' AND fld_user_id<>'' ORDER BY fullname");
         ?>
              <div class="dragtitle">Users available</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkleft" id="list9_<?php echo $hmeid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $hmeid; ?>" title="<?php echo $fullname;?>"><?php echo $fullname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $hmeid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $hmeid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all users</div>
          </div>
      </div>
      
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Users</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkright" id="list10_<?php echo $hmeid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $hmeid; ?>" title="<?php echo $fullname;?>"><?php echo $fullname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $hmeid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $hmeid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all users</div>
         </div>
        </div>
      </div>
    </div>  
<?php
}

/*--- Load Student Dropdown ---*/
if($oper=="showdistpurchase" and $oper != " " )
{
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
<div class="row rowspacer" >
    <div class ='tweleve columns'>
      <div class='six columns'>
          <div class="dragndropcol">
              
         <?php 
            $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname 
                                                                    FROM itc_district_master 
                                                                    WHERE fld_delstatus='0'");
         ?>
              <div class="dragtitle">Districts available</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkleft" id="list9_<?php echo $districtid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $districtid; ?>" title="<?php echo $districtname;?>"><?php echo $districtname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $districtid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $districtid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all districts</div>
          </div>
      </div>
      
      <div class='six columns'>
        <div class="dragndropcol">
                  <div class="dragtitle">Selected Districts</div>
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
                                if($qry->num_rows>0){
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                              <div class="draglinkright" id="list10_<?php echo $districtid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $districtid; ?>" title="<?php echo $districtname;?>"><?php echo $districtname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $districtid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $districtid;?>);"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right;">remove all districts</div>
          </div>  
       </div>
      </div>
    </div>  
<?php
}
    
@include("footer.php");