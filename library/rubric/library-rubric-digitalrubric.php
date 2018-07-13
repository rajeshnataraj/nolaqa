<?php
@include("sessioncheck.php");

$date=date("Y-m-d H:i:s");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);


$expid=$id[0];
$rubid=$id[1];

  $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_exp_id='".$expid."' AND fld_id='".$rubid."'");

    $createbtn = "Save";
    $clasid = '';
    $msg = $rubricname;

?>

<script language="javascript" type="text/javascript">
    $.getScript("library/rubric/library-rubric-digitalrubric.js");
        <?php if($rubid != '0') {?>	              
        <?php } ?>
</script>
<section data-type='2home' id='library-rubric-digitalrubric'>
 <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
            <form name="rubricforms" id="rubricforms">
                <?php
                $qry = $ObjDB->QueryObject("SELECT fld_class_id AS classid 
                                                 FROM itc_class_indasexpedition_master 
                                                 WHERE fld_exp_id='".$expid."' AND fld_delstatus='0' AND fld_flag='1' AND fld_createdby='".$uid."' group by classid");
                if($qry->num_rows>0){
                    while($row = $qry->fetch_assoc())
                    {
                        extract($row);
                        $clsid[]=$classid;

                    }
                }
                ?>
              
                <div class="row"> 
                    <div class='six columns'> Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="<?php echo $clasid; ?>" onchange="fn_showstudent(this.value,<?php echo $expid; ?>,<?php echo $rubid; ?>);" />
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                   <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php if($clasid == '' || $clasid == 0) {?>Select Class<?php } else { echo $classname; } ?></span> 
                                   <b class="caret1"></b>
                               </a>
                               <div class="selectbox-options">
                                   <input type="text" class="selectbox-filter" placeholder="Search Class">
                                   <ul role="options" style="width:100%">
                                       <?php 
                                       for($i=0;$i<sizeof($clsid);$i++) 
                                       {
                                           $qry = $ObjDB->QueryObject("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$clsid[$i]."' AND fld_delstatus = '0'  AND fld_flag = '1'  AND fld_created_by = '".$uid."'");
                                           if($qry->num_rows>0){
                                               while($row = $qry->fetch_assoc())
                                               {
                                                       extract($row);
                                                       ?>
                                                       <li><a tabindex="-1" href="#" data-option="<?php echo $clsid[$i];?>"><?php echo $fld_class_name; ?></a></li>
                                                       <?php
                                               }
                                           }
                                       }   ?>      
                                   </ul>
                               </div>
                            </div>
                        </dl>
                    </div>
                <input type="hidden" name="rubid" id="rubid" value="<?php echo $rubid; ?>"><!--new line -->
                <input type="hidden" name="expid" id="expid" value="<?php echo $expid; ?>">
                
                    <!--Shows Class-->
                    <div class='six columns'> 
                        
                    </div>
            	</div>
                
                <!--Shows Student -->
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="studentdiv" style="display:none">

                        </div>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div id="rubricstmt" style="display:none">
                        
                    </div>
                </div>            
               
                  </form>
            </div>
        </div>
    </div>
    
</section>
<?php
	@include("footer.php");