<?php
@include("sessioncheck.php");
  /****declaration part****/
$id = isset($method['id']) ? $method['id'] : 0;
$rid=$id;
$state = '';
$stdid = '';
$getdetails = $ObjDB->QueryObject("SELECT a.fld_id AS id,a.fld_email AS usermail,a.fld_fname AS fname,a.fld_lname AS lname,
									a.fld_school_id AS schoolid,
									b.fld_school_name AS schoolname,b.fld_city AS city
									FROM itc_user_master AS a
									LEFT JOIN itc_school_master AS b ON b.fld_id = a.fld_school_id
									LEFT JOIN itc_state_city AS c ON c.fld_statevalue = b.fld_state
									WHERE a.fld_id = '".$uid."' GROUP BY a.fld_id");
    $rowgetdet = $getdetails->fetch_assoc();
    extract($rowgetdet);
    ?>
<script type="text/javascript">
function fn_loadeditor(){
            tinyMCE.init({
                script_url : "tiny_mce/tiny_mce.js",
                plugins : "asciimath,asciisvg",
                theme : "advanced",
                verify_html : false,
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                mode : "exact",
                elements : "requestcomments",
                theme_advanced_toolbar_location :"hide",
                theme_advanced_toolbar_align : "left",
                theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
                + "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
                theme_advanced_resizing : false,
                theme_advanced_statusbar_location : "none",
                theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",
                 
                AScgiloc : '<?php echo __TINYPATH__;?>php/svgimg.php', //change me
                ASdloc : '<?php echo __TINYPATH__;?>plugins/asciisvg/js/d.svg', //change me  
                init_instance_callback: function(){
                ac = tinyMCE.activeEditor;
                ac.dom.setStyle(ac.getBody(), 'fontSize', '14px');
}

            });
        }
        
        setTimeout("fn_loadeditor()",2000);
        $('.textarea').css('border','none');
        $('.textarea').css('box-shadow','none');
    </script>


<section data-type='2home' id='reports-correlation-request'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Request Form</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
         <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="frmrequest" id="frmrequest"  method="post">
                    <input type="hidden" id="rid" name="rid" value="<?php echo $rid;?>" />
                    <div class="row">
                        <?php  if ($sessmasterprfid==5) { ?> 
                        <div class="six columns">                        
                            First name 
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Enter your first name' readonly type='text' name="txtfirstname" id="txtfirstname" value="<?php echo $fname; ?>"  tabindex="2" />
                            </dt>
                            </dl>
                            Last name
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Enter your last name' readonly type='text' name="txtlastname" id="txtlastname" value="<?php echo $lname; ?>" onBlur="$(this).valid();" tabindex="2" />
                            </dt>
                            </dl>
                            Email
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Email' type='text' readonly  name="txtemail" id="txtemail" value="<?php echo $usermail; ?>" onBlur="$(this).valid();" />
                            </dt>
                            </dl>
                       </div>
                      <?php } else{?>
                        <div class="six columns">                        
                            First name 
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Enter your first name' readonly type='text' name="txtfirstname" id="txtfirstname" value="<?php echo $fname; ?>"  tabindex="2" />
                            </dt>
                            </dl>
                            
                            School Name
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='School Name' readonly type='text' name="schoolname" id="schoolname" value="<?php echo $schoolname; ?>" onBlur="$(this).valid();" />
                            </dt>
                            </dl>
                            Required Delivery Date
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Date'  type='text' name="requestdate" id="requestdate" value="<?php echo  date("Y/m/d"); ?>" onBlur="$(this).valid();"/>
                            </dt>
                            </dl>

                        </div>
                         <?php }?>
                        <div class='six columns'>
                            Last name
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Enter your last name' readonly type='text' name="txtlastname" id="txtlastname" value="<?php echo $lname; ?>" onBlur="$(this).valid();" tabindex="2" />
                            </dt>
                            </dl>
                            Email
                            <dl class='field row'>
                            <dt class='text'>
                            <input placeholder='Email' type='text' readonly  name="txtemail" id="txtemail" value="<?php echo $usermail; ?>" onBlur="$(this).valid();" />
                            </dt>
                            </dl>
                                
                               <?php
                                                           
    if($id != '0'){   
                                          
    $qry = $ObjDB->QueryObject("SELECT b.fld_id AS state, a.fld_standard_id AS stdid, b.fld_name AS statenames 
                                FROM itc_correlation_report_data AS a 
                                LEFT JOIN itc_standards_bodies AS b ON a.fld_std_body=b.fld_id 
                                WHERE a.fld_id='".$id."'");
    $rowcrp = $qry->fetch_assoc();
    extract($rowcrp);

}

                            ?>
<script language="javascript" type="text/javascript">
     <?php if($id != '0' and $state != '') {?>   
        setTimeout("fn_showgrades1('<?php echo $stdid; ?>',<?php echo $id; ?>,'<?php echo $state; ?>');",1000);
    <?php } ?>
 </script>
                           
                Select State<span class="fldreq">*</span>
                                <dl class='field row'>   
                                    <dt class='dropdown'>   
                                        <div class="selectbox" >
                                            <input type="hidden" name="selectstate" id="selectstate" value="" onchange="fn_showdocuments1(this.value,<?php echo $id; ?>);" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="1">Select State</span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options" >
                                                <input type="text" class="selectbox-filter" placeholder="Search State" />
                                                <ul role="options" >
                           <?php
                                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_name AS stdbname from itc_standards_bodies ORDER BY stdbname");
                                                    while($res=$qry->fetch_assoc())
                                                    {
                                                        extract($res);  
                            ?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $stdbid;?>"><?php echo $stdbname; ?></a></li>
                                                        <?php 
                                                    }?>
                                                </ul>
                                            </div>
                                        </div>
                                    <dt>
                                </dl>  
                        </div>
            </div>    



<div class="row rowspacer">
                                <div class='twelve columns' id="dpdocuments1" style="display: none">
                                
                        </div>  
                </div>


                            <div class="row rowspacer">
                                <div class='twelve columns' id="divdocgrades1" style="display: none">

    </div>
</div>    
<script type="text/javascript" language="javascript">
var productid = [];
</script>
                               <?php
$productdetails=array();
                            
    $titletype='0';
                                         
    if($titletype=='0')
    {
        $titlename="Show All Titles";
    }
    else if($titletype=='1')
    {
       $titlename="IPLs"; 
    }
    else if($titletype=='2')
    {
       $titlename="Units"; 
    }
    else if($titletype=='3')
    {
       $titlename="Modules"; 
    }
    else if($titletype=='4')
    {
       $titlename="Math Modules"; 
    }


                            ?>
<script language="javascript" type="text/javascript">
    fn_showproducts1(<?php echo $titletype; ?>,<?php echo $id; ?>);
</script>
 <script type="text/javascript" language="javascript">
        $(function() {                                
            $('#testrailvisible141').slimscroll({
                width: '410px',
                height:'370px',
                railVisible: true,
                size: '7px',
                alwaysVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                  wheelStep: 1,
            });
 $("#list21" ).sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function(event, ui) {
                    $("div[class=draglinkleft]").each(function(){ 
                        if($(this).parent().attr('id')=='list21'){                          
                            fn_movealllistitems('list20','list21',1,$(this).attr('id').replace('list20_',''));
                            fn_saveselect();
                            fn_validategrade();
                            }
                    });
                        }
            });
        }); 
    </script>    

                     <div class="row rowspacer">
                            <div class='six columns'>
                                Select Title<span class="fldreq">*</span>
                                <dl class='field row'>   
                                    <dt class='dropdown'>   
                                        <div class="selectbox">
                                            <input type="hidden" name="showtitle" id="showtitle" value="<?php echo $titletype;?>" onchange="fn_showproducts1(this.value,<?php echo $id; ?>);$('#hidselecteddropdown').val(this.value);fn_changetype();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="1"><?php if($titlename == '') {?>Show All Titles<?php } else { echo $titlename; } ?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Owner" />
                                                <ul role="options">
                                                <li><a tabindex="-1" href="#" data-option="0">Show All Titles</a></li>
                                                <li><a tabindex="-1" href="#" data-option="1">IPLs</a></li>
                                                <li><a tabindex="-1" href="#" data-option="2">Units</a></li>
                                                <li><a tabindex="-1" href="#" data-option="3">Modules</a></li>
                                                <li><a tabindex="-1" href="#" data-option="4">Math Modules</a></li>
                                                </ul>
                        </div>  
                </div>
                                    </dt>
                                </dl>                            
        </div>
 </div>
                       <div class="row rowspacer" >
                            <div class='six columns' id="loadproducts1"></div>
<div class='six columns'>
       <div class="dragndropcol">
                                   <div class="dragtitle">Selected Products<span class="fldreq">*</span></div> 
                                   <div class="dragWell" id="testrailvisible141">
                                       <div id="list21" class="dragleftinner droptrue3">
                               <?php
                                        for($i=0;$i<sizeof($productdetails);$i++) {   ?>
                              
                                <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkright" id="list21_<?php  echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>" >
                                        <div class="dragItemLable tooltip" title="<?php echo $productdetails[$i]['nam'];?>" id="<?php echo  $productdetails[$i]['id']."~".$productdetails[$i]['type']."~".$productdetails[$i]['productid']; ?>"><?php echo $productdetails[$i]['shortname'];?></div>
                                        <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsrequest('list20','list21',1,'<?php echo $productdetails[$i]['id']."_".$productdetails[$i]['type']; ?>');"></div>
                                </div> 
                                          
                           


                                        <?php   }  ?>
                        </div>  
                </div>
                                   <div class="dragAllLink" onclick="fn_movealllistitemsrequest('list21','list20',0);fn_saveselect();fn_validateproducts();"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">remove all products</div>
        </div>
 </div>
                        </div>

                      <script type="text/javascript" language="javascript">
                      function fn_changetype()
		       {
                            $("div[id^=list21_]").each(function()
                                 { 
                                    var pid = $(this).attr('name');

                                        if($('#hidselecteddropdown').val() != $(this).attr('name') && $('#hidselecteddropdown').val()!=0)
                                        {
                                                $(this).hide().addClass('dim');
                                        }
                                        else
                                        {
                                                $(this).show().removeClass('dim');
                                        }
                                  });
                          }
                        </script>  
        

         <div class="row rowspacer" >
                    <div class='eleven columns'> 
                         comments
                             <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your comments' id="requestcomments" name="requestcomments" style="height:100px; width:800px; border-color:#FFF;" ></textarea>
                                </dt>                                
                             </dl>
                                
                            </div>
                                </div>

                
                         <div class="row ">
                            <input class="btn " name="btnstep" type="button" id="btnstep" onclick="fn_request(<?php echo $id;?>)"  style="width:200px; height:42px;float:right;" value="Request Correlation"/>
                        </div>
                       </div>
 </div>
                </form>
            </div>
        </div>
            </div>
       
</section>
<script language="javascript" type="text/javascript">
            $(function(){
            $("#requestdate").datepicker( {
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText,inst){
                    $(this).parents().parents().removeClass('error');
                }
            });
            
            <?php 
                if($id == 0) {
            ?>
                $("#requestdate").datepicker("setDate", new Date());
            <?php
                }
            ?>
       
});
    </script>
<?php
    @include("footer.php");
                        