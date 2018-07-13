<?php
	@include("sessioncheck.php");
/*------
	Page - Passport
	Description:
		1.Teacher can block/unblock Expedition Resource hyperlink in student passport
		2.Student can view the expedition using passport 
	History:	
------*/?>
<script src="tools/passport/tools-passport.js" type="text/javascript" language="javascript"></script>

<style type="text/css">

.container1{
  width:500px;
  display:block;
  margin:50px auto;
}
.progress {
  overflow: hidden;
  height: 20px;
  background-color: #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
  box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    margin-bottom: 20px;
}
.progress-bar {
  width: 0;
  height: 100%;
  color: #fff;
  text-align: center;
  background-color: #0ae;

  
 
}
.progress-striped .progress-bar {
      background-image: -webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
      background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
      background-size: 40px 40px;
}
.progress.active .progress-bar {
  -webkit-animation: progress-bar-stripes 2s linear infinite;
  animation: progress-bar-stripes 2s linear infinite;
  -moz-animation: progress-bar-stripes 2s linear infinite;
}



  </style>


<?php
$id = isset($method['id']) ? $method['id'] : '';

?>
<section data-type='#tools-passport' id='tools-passport-passport'>
  
  <div class='container'>
	    <div class='row'>
	      <div class='twelve columns'>
	        <p class="dialogTitle">Passport</p>
	        <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
	      </div>
	    </div>
<?php if($sessmasterprfid==9){ ?>
   <div class='row formBase rowspacer' id="minheightstyle">
        	<div class='eleven columns centered insideForm'>
                <div class="row">
                    <div class='six columns'>
                        <!--Shows Class Dropdown-->
                        <div id="clspass">   
                            <dl class='field row'>
                            Class
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
                                            $qry = $ObjDB->QueryObject("SELECT a.fld_id AS classid,a.fld_class_name AS classname
																		FROM itc_class_master AS a
																		LEFT JOIN itc_class_indasexpedition_master AS b on a.fld_id=b.fld_class_id
																		WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' 
																		AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
																		FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
																		group by a.fld_class_name");
                                            if($qry->num_rows>0){
                                                while($row = $qry->fetch_assoc())
                                                {
                                                    extract($row);

                                                    ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onClick="$('#expeditiondiv').show(); fn_showexpedition(<?php echo $classid; ?>)"><?php echo $classname; ?></a></li>
                                                    <?php
                                                }
                                            }?>      
                                        </ul>
                                    </div>
                                </div> 
                            </dl>
                        </div>
                    </div>

	                    <div class='six columns'>   
	                        
	                        	<div id="expeditiondiv" style="display:none">
	                            	 
	                            </div>
	                        
	                    </div>



                </div>
            </div>
            
        </div>
        <?php } ?>

 <?php if($sessmasterprfid==10){ ?>
&nbsp;
<div class='row formBase'>
       		<div class='eleven columns centered insideForm'>
               <div class="main clearfix">
        <div class="bb-custom-wrapper">
          
          <div id="bb-bookblock" class="bb-bookblock">
          <?php
          
          /** shows the expeditions for the student **/
         $label2= 'AND DATE(a.fld_startdate) <= DATE(NOW())';
     
         $qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid, 
                                        a.fld_id AS scheduleid, c.fld_exp_name AS expname
                                        FROM `itc_class_indasexpedition_master` AS a 
                                        LEFT JOIN `itc_class_exp_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
                                        LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
                                        LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
                                        AND b.fld_student_id='".$uid."' ".$label2." 
                                        AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')");
          
          if($qryexp->num_rows>0){
                    while($rowexp = $qryexp->fetch_assoc())
                    {
                        extract($rowexp);

          
          
           ?>
            <div class="bb-item">
              <p style="margin-left: 25px;"></p>
              <p>Expedition Name: <?php echo $expname; ?> </p>

 <?php 
/**
 * 
 * For displaying Expedition Name,
 * Destinations,
 * Tasks,
 * Resources
 * 
 * **/
 $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                        FROM itc_exp_destination_master as a
                                        LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                        LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                        LEFT JOIN itc_class_indasexpedition_master as d on b.fld_license_id=d.fld_license_id
                                        WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$scheduleid."' AND c.fld_user_id='".$indid."' 
                                            AND c.fld_school_id='".$schoolid."' AND a.fld_delstatus = '0' GROUP BY destid");
     
   if($qrydestdetails->num_rows>0)
    {
       ?>
       <div style="height:400px; overflow:auto;">
       <?php
        while($rowdestdetails = $qrydestdetails->fetch_assoc())
        {
            extract($rowdestdetails);
        ?>
                  <ul class="tree" style="margin-left: 15px;">
                        <li>
                            
                            <label><?php echo $destname;?></label>
                            <ul class='expanded'>
                                <script>
                                    
                                 </script>
                            <?php
                            /** For selecting and displaying tasks related to the destination **/
                            
                                $qrytaskdetails=$ObjDB->QueryObject("SELECT fld_id as taskid,fld_task_name AS taskname 
                                                                    FROM itc_exp_task_master 
                                                                    WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");
                                if($qrytaskdetails->num_rows>0)
                                    {
                                    while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                         ?>
                                         <li>
                                           
                                            <label><?php echo $taskname;?></label>
                                            <script>
                                               
                                            </script>
                                            <ul>
                                                 <?php 
                                                   /** For selecting and displaying resources related to the task **/  
                                   $qryresourcedetails=$ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_name AS resname
                                                                                        FROM itc_exp_resource_master
                                                                                        WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");
                                                    
                                                    if($qryresourcedetails->num_rows>0)
                                                        {
                                                        while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                            {
                                                                extract($rowresourcedetails);
//                                                                
                                                            ?>
                                                            <li>
                                                               
                                                                <label><?php echo $resname;?></label>
                                                            </li>
                                                                                                                       
                                                             <?php
                                                    
                                                    }
                                                    }
                                                    ?>
                                            </ul>
                                        </li>
                                    <?php
                                
                                }
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
            <?php
     
        }
        ?>
       </div>
        <?php
    }


?>


            </div>
                    <?php } // end of while 
                    } // end of if $qryexp
                    ?>  
          </div>
         <nav>
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
         </nav>
         
        </div>
      </div>
            </div>
         </div> 

<?php  } ?>
    </div>

</section>

<script language="javascript" type="text/javascript">

      var Page = (function() {
        
        var config = {
            $bookBlock : $( '#bb-bookblock' ),
            $navNext : $( '#bb-nav-next' ),
            $navPrev : $( '#bb-nav-prev' ),
            $navFirst : $( '#bb-nav-first' ),
            $navLast : $( '#bb-nav-last' )
          },
          init = function() {
            config.$bookBlock.bookblock( {
              speed : 800,
              shadowSides : 0.2,
              shadowFlip : 0.7
            } );
            initEvents();
          },
          initEvents = function() {
            
            var $slides = config.$bookBlock.children();

            // add navigation events
            config.$navNext.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'next' );
              return false;
            } );

            config.$navPrev.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'prev' );
              return false;
            } );

            config.$navFirst.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'first' );
              return false;
            } );

            config.$navLast.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'last' );
              return false;
            } );
            
            // add swipe events
            $slides.on( {
              'swipeleft' : function( event ) {
                config.$bookBlock.bookblock( 'next' );
                return false;
              },
              'swiperight' : function( event ) {
                config.$bookBlock.bookblock( 'prev' );
                return false;
              }
            } );

            // add keyboard events
            $( document ).keydown( function(e) {
              var keyCode = e.keyCode || e.which,
                arrow = {
                  left : 37,
                  up : 38,
                  right : 39,
                  down : 40
                };

              switch (keyCode) {
                case arrow.left:
                  config.$bookBlock.bookblock( 'prev' );
                  break;
                case arrow.right:
                  config.$bookBlock.bookblock( 'next' );
                  break;
              }
            } );
          };

          return { init : init };

      })();
       Page.init();
    </script>
<?php
	@include("footer.php");

	?>
