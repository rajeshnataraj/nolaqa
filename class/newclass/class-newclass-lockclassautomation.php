<?php 
@include("sessioncheck.php");

/***********************/
/* Created By Mohan M lock */
/***********************/
$clsid = isset($method['id']) ? $method['id'] : '';
$startdate = '';
$enddate = '';
$bydateshour = '';
$bydatesmin = '00';
$bydateehour = '';
$bydateemin = '00';
$bydatesampm = 'AM';
$bydateeampm = 'AM';

$timezones='3';
$defaulttimezone='CST';
$dayrange='2';

$value='Save';
?>


<section data-type='#class-newclass' id='class-newclass-lockclassautomation'>
    <style>
        .selectbox > .selectbox-toggle {
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            color: #000;
            display: inline-block;
            padding: 4px;
            text-decoration: none;
            width: 100%;
            height:90%;
        }
        .selectbox > .selectbox-toggle1 {
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            color: #000;
            display: inline-block;
            padding: 4px;
            text-decoration: none;
            width: 66%;
        }
         .selectbox > .selectbox-toggle11 {
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            color: #000;
            display: inline-block;
            padding: 4px;
            text-decoration: none;
            width: 85%;
        }

        .row .three.columns {
            width: 16.404%;
        }
        .row .two.columns {
            width: 13.404%;
        }

        .row .one.columns {
            width: 10.404%;
        }

        .dropdown1 {
            width: 124%;
        }
    </style>  

    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Lock class automation</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
	
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                          <form id="lockclassform" name="lockclassform">
                              
                        <!-- ************ Time Zones Start Here *************** -->                           
                            <div class="row">
                                <div class='one columns'>
                                   <p style="margin-top: 6px;margin-left: 6px;">   Time Zones</p>
                                </div>
                                <div class='two columns'>
                                    <dl class='field row'>
                                        <div class="selectbox">
                                            <input type="hidden" name="timezones" id="timezones" value="<?php echo $timezones; ?>" onchange="$(this).valid();"   />
                                            <a class="selectbox-toggle" style="" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $timezones; ?>" style="width:78%"><p id="tz"><?php echo $defaulttimezone ;?></p></span>
                                                <b class="caret1"  style="margin-left: 103px; margin-top: -50px;"></b>
                                            </a>
                                            <div class="selectbox-options" style='min-width:139px'>
                                                <input type="text" class="selectbox-filter" placeholder="Search Time Zones" style="width:76%;" >
                                                <ul role="options" style="width:139px;">
                                                    <li><a tabindex="-1" href="#" data-option="1" onclick="fn_hiddenval(1)">PST</a></li>
                                                    <li><a tabindex="-1" href="#" data-option="2" onclick="fn_hiddenval(2)">MST</a></li>
                                                    <li><a tabindex="-1" href="#" data-option="3" onclick="fn_hiddenval(3)">CST</a></li>
                                                    <li><a tabindex="-1" href="#" data-option="4" onclick="fn_hiddenval(4)">EST</a></li>
                                                </ul>
                                            </div>
                                        </div> 
                                    </dl>
                                </div>                                
                               
                            </div>
                        <!-- ************ Time Zones End Here *************** -->    
                              
                              
                              
            <!-- ************ By Date Start *************** --> 
                    <div class="row" id="bydatea">
                        <div class="bydate" id="Types1">
                            <div class='three columns'>
                                Start Date
                               <dl class='field row'>
                                   <dt class='text'>
                                        <input  id="startdate" name="startdate" class="quantity" placeholder='Start Date'type='text'  readonly="readonly" value="<?php echo $startdate;?>" >
                                   </dt>                                        
                               </dl>
                            </div>
                             <div class='one columns'>
                                 <p style="margin-left: -4px;">    Hour</p>
                                 <dt class='dropdown1'  style="margin-left: -5px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydateshour" id="bydateshour" value="<?php echo $bydateshour; ?>"  onchange="$(this).valid(); fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span id="bydateshr" class="selectbox-option input-medium" data-option="<?php echo $bydateshour; ?>" id="clearsubject" style="width:90%;">00</span>
                                            <b class="caret1" style="margin-left: 43px;"></b>
                                        </a>
                                        <div class="selectbox-options" style="width:65%; min-width:69px;">
                                          <ul role="options" style="width:71px; " >
                                            <?php                                                     
                                                for($i=1; $i<=12;$i++){?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php if(strlen($i)==1){ echo "0".$i;}else{ echo $i; }?>"><?php  
                                                    if(strlen($i)==1){ echo "0".$i;}else{ echo $i; }?></a></li>
                                                <?php 
                                                }?>      
                                            </ul>
                                        </div>
                                    </div>
                                </dt>                                       
                            </div> 
                            
                            <div class='one columns'>
                                <p style="margin-left: -22px;"> Minute</p>
                                <dl class='field row'>   
                                    <dt class='dropdown1' style="margin-left: -23px;">   
                                        <div class="selectbox">
                                            <input type="hidden" name="bydatesmin" id="bydatesmin" value="<?php echo $bydatesmin; ?>"  onchange="$(this).valid();fn_dayrange(2); " />
                                            <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $bydatesmin; ?>" id="clearsubject" style="width:90%;">00</span>
                                                <b class="caret1" style="margin-left: 43px;"></b>
                                            </a>
                                            <div class="selectbox-options" style="width:65%; min-width:69px;">
                                              <ul role="options" style="width:73px; " >
                                                   <li><a tabindex="-1" href="#" data-option="00">00</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="15">15</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="30">30</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="45">45</a></li>

                                                </ul>
                                            </div>
                                        </div>
                                    </dt>                                       
                                </dl>
                            </div> 
                             <div class='one columns'>
                               <p style="margin-left: -42px;">  &nbsp;</p>
                                <dt class='dropdown1' style="margin-left: -42px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydatesampm" id="bydatesampm" value="<?php echo $bydatesampm; ?>"  onchange="$(this).valid(); fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydatesampm; ?>" id="clearsubject" style="width:90%;">AM</span>
                                            <b class="caret1" style="margin-left: 38px;"></b>
                                        </a>
                                        <div class="selectbox-options" style="width:65%; min-width:69px;">
                                          <ul role="options" style="width:71px; " >
                                             <li><a tabindex="-1" href="#" data-option="AM">AM</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="PM">PM</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dt>     
                            </div> 
                            
                            <div class='three columns' style="margin-left: -28px;">
                                End Date
                                <dl class='field row'>
                                    <dt class='text'  >
                                      <input placeholder='End Date' required='' type='text' id="enddate" name="enddate" readonly="readonly" value="<?php echo $enddate;?>">
                                    </dt>                                        
                                </dl>
                            </div>
                         <div class='one columns'>
                             <p style="margin-left: -6px;">  Hour</p>
                                 <dt class='dropdown1 ' style="margin-left: -5px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydateehour" id="bydateehour" value="<?php echo $bydateehour; ?>"  onchange="$(this).valid(); fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydateehour; ?>" id="clearsubject" style="width:90%;">00</span>
                                            <b class="caret1" style="margin-left: 43px;"></b>
                                        </a>
                                        <div class="selectbox-options" style="width:65%; min-width:69px;">
                                          <ul role="options" style="width:71px; " >
                                            <?php                                                     
                                                for($i=1; $i<=12;$i++){?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php if(strlen($i)==1){ echo "0".$i;}else{ echo $i; }?>"><?php  
                                                    if(strlen($i)==1){ echo "0".$i;}else{ echo $i; }?></a></li>
                                                <?php 
                                                }?>      
                                            </ul>
                                        </div>
                                    </div>
                                </dt>                                       
                            </div> 
                            
                            <div class='one columns'>
                               <p style="margin-left: -25px;"> Minute</p>
                                <dl class='field row'>   
                                    <dt class='dropdown1' style="margin-left: -24px;"> 
                                        <div class="selectbox">
                                            <input type="hidden" name="bydateemin" id="bydateemin" value="<?php echo $bydateemin; ?>"  onchange="$(this).valid(); fn_dayrange(2);" />
                                            <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $bydateemin; ?>" id="clearsubject" style="width:90%;">00</span>
                                                <b class="caret1" style="margin-left: 43px;"></b>
                                            </a>
                                            <div class="selectbox-options" style="width:65%; min-width:69px;">
                                              <ul role="options" style="width:73px; " >
                                                   <li><a tabindex="-1" href="#" data-option="00">00</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="15">15</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="30">30</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="45">45</a></li>

                                                </ul>
                                            </div>
                                        </div>
                                    </dt>                                       
                                </dl>
                            </div> 
                            <div class='one columns'>
                                <p style="margin-left: 178px; margin-top:-66px;">  &nbsp;</p>
                                <dt class='dropdown1' style="margin-left: 171px; margin-top:0px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydateeampm" id="bydateeampm" value="<?php echo $bydateeampm; ?>"  onchange="$(this).valid(); fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydateeampm; ?>" id="clearsubject" style="width:90%;">AM</span>
                                            <b class="caret1" style="margin-left: 38px;"></b>
                                        </a>
                                        <div class="selectbox-options" style="width:65%; min-width:69px;">
                                          <ul role="options" style="width:71px; " >
                                               <li><a tabindex="-1" href="#" data-option="AM">AM</a></li>
											   <li><a tabindex="-1" href="#" data-option="PM">PM</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dt>     
                            </div> 
                        </div>  
                        
                  
                <!-- ************ By Date End *************** --> 

                <!-- ************ Repeat Event Code Start Here *************** --> 
                    <div class="row" id="repeatevent" style="display: none;">
                        <div class='three columns'>
                               <p style="margin-top: 31px;margin-left:-682px;"> Repeat Event</p>
                        </div>
                        <div class="weekdays" id="weekdays">
                            <?php
                            for($m=1;$m<=7;$m++)
                            {
                                if($m==1) { $weekday="Monday"; } 
                                else if($m==2){ $weekday="Tuesday"; }
                                else if($m==3){ $weekday="Wednesday"; }
                                else if($m==4){ $weekday="Thursday"; }
                                else if($m==5){ $weekday="Friday"; }
                                else if($m==6){ $weekday="Saturday"; }
                                else if($m==7){ $weekday="Sunday"; }
                                ?>  
                                <div class='row <?php if($m==1){ echo "rowspacer"; }else{ echo ""; } ?>'> 
                                    <div class='four columns' >   
                                        <div <?php if($m==1){ ?> style="margin-left: 108px; margin-top: -22px;" <?php }else{ ?> style="margin-left: 108px;" <?php } ?>  >
                                            <input type="checkbox" id="wdaychk<?php echo $m; ?>" value="<?php echo $m; ?>" />
                                            <?php echo $weekday; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            } ?>
                        </div>
                    </div>
                  </div>
                <!-- ************ Repeat Event Code End Here *************** --> 

                    <div class='savelockclass' id="savelock">
                        <div class='row  rowspacer'>
                           <div class='twelve columns'>
                               <input type="button" id="btnstep" class="darkButton" style="width: 200px; height: 42px; margin-left: 331px; margin-top: -4px;" value="<?php echo $value;?>" onClick="fn_savelockclass(<?php echo $clsid;?>);" />
                            </div>
                        </div>
                    </div>


                    <?php //utc to cst
                    $timestamp = date("Y-m-d H:i:s");

                    $sda=date('Y-m-d',strtotime($timestamp));
                    $dstval=$ObjDB->SelectSingleValueInt("SELECT fld_dst_differ FROM itc_zdst_day_mapping WHERE fld_date='".$sda."'");

                    if($dstval==1)
                    {
                        $val =6;
                    }
                    else
                    {
                        $val =5;
                    }

                    $timestamp = strtotime($timestamp);
                    $timestamp -= $val * 3600;
                    $startdatecst=date('Y-m-d', $timestamp);
                    $sdatecsttimeh= date('h', $timestamp);
                    $sdatecsttimem= date('i', $timestamp);
                    $sdatecsttimea= date('A', $timestamp);

                    $sdatecstday = date('l', strtotime($startdatecst));

                    ?>
                    <input type="hidden" id="cstsdate" name="cstsdate" value="<?php echo $startdatecst;?>">
                    <input type="hidden" id="cstsdatetimeh" name="cstsdatetimeh" value="<?php echo $sdatecsttimeh;?>">
                    <input type="hidden" id="cstsdatetimem" name="cstsdatetimem" value="<?php echo $sdatecsttimem;?>">
                    <input type="hidden" id="cstsdatetimea" name="cstsdatetimea" value="<?php echo $sdatecsttimea;?>">
                    <input type="hidden" id="cstsday" name="cstsday" value="<?php echo $sdatecstday;?>">
                    
                    <input type="hidden" id="dayrange" name="dayrange" value="<?php echo $dayrange;?>">

                    <?php    
                    $lockclscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_lockclassautomation WHERE fld_class_id='".$clsid."' AND fld_delstatus='0'");
                    if($lockclscount != '0')
                    {
                        ?>
                        <div class="row">
                            <div class='rowspacer formBase'>  
                                <div id="expsetting" class='row rowspacer'>  
                                    <div class='span10 offset1'>
                                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                             <thead>
                                                <tr style="cursor:default;">
                                                    <th width="8%">Start Date</th>
                                                    <th width="11%" class='centerText'>Start Time</th>
                                                    <th width="8%" class='centerText'>End Date</th>
                                                    <th width="10%" class='centerText'>End Time</th>
                                                    <th width="12%" class='centerText'>Actions</th>
                                                    <th width="13%" class='centerText'>Repeated Event</th>
                                                    <th width="18%" class='centerText'></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                            <style> .bcolor{background: #F1F1F3;}</style>
                                                <?php
                                                $qrylockclass= $ObjDB->QueryObject("SELECT fld_id as rowid, fld_date_range as drange, fld_startdate AS startdate, fld_enddate AS enddate, fld_starthour AS shour, fld_startmin AS smin, fld_startampm as sampm,
                                                                                            fld_endhour AS ehour, fld_endmin AS emin, fld_endampm AS eampm, fld_timezone_type AS timezone, fld_event_enableordisable AS enableordisable
                                                                                            FROM itc_class_lockclassautomation WHERE fld_class_id='".$clsid."' AND fld_delstatus='0'");
                                                if($qrylockclass->num_rows > 0)
                                                {													
                                                    while($rowlockclass = $qrylockclass->fetch_assoc())
                                                    {
                                                        extract($rowlockclass);
                                                        $weekday='';
                                                        if($timezone==1)
                                                        {
                                                            $timezonename='PST';
                                                        }
                                                        else if($timezone==2)
                                                        {
                                                            $timezonename='MST';
                                                        }
                                                        else if($timezone==3)
                                                        {
                                                             $timezonename='CST';
                                                        }
                                                        else if($timezone==4)
                                                        {
                                                             $timezonename='EST';
                                                        }
                                                        
                                                        if($drange=='1')
                                                        {
                                                            $qryrepeatevent= $ObjDB->QueryObject("SELECT fld_user_sday, fld_sday_no FROM itc_class_locakclassautomation_repeatevent WHERE fld_lock_id='".$rowid."' AND fld_delstatus='0'");
                                                            if($qryrepeatevent->num_rows > 0)
                                                            {	
                                                                $n=1;
                                                              $sdayno=array();
                                                                while($rowrepeatevent = $qryrepeatevent->fetch_assoc())
                                                                {
                                                                    extract($rowrepeatevent);
                                                                    
                                                                    if($n=='1')
                                                                    {
                                                                        if($n==($qryrepeatevent->num_rows))
                                                                        {
                                                                            $weekday=$fld_user_sday;
                                                                        }
                                                                        else
                                                                        {
                                                                            $weekday=$fld_user_sday.",";
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        if($n==($qryrepeatevent->num_rows))
                                                                        {
                                                                            $weekday=$weekday." ".$fld_user_sday."<br>";
                                                                        }
                                                                        else
                                                                        {
                                                                             $weekday=$weekday." ".$fld_user_sday.",<br>";
                                                                        }
                                                                    }
                                                                    $n++;
                                                                    $sdayno[]=$fld_sday_no;
                                                                }
                                                            }                                                          
                                                            $chkboxflag=array();
                                                            $chkval=array();
                                                            for($s=0;$s<7;$s++)
                                                            {
                                                               $ss=$s+1;                                                              
                                                                if(in_array($ss,$sdayno))
                                                                {
                                                                    $chkboxflag[]=1;
                                                                }
                                                                else
                                                                {
                                                                    $chkboxflag[]=0;
                                                                }
                                                                 $chkval[]=$ss;
                                                            }                                                           
                                                        }
                                                      

                                                        if(strlen($smin)==1){ $smin= "0".$smin;}else{ $smin= $smin; } 
                                                        if(strlen($emin)==1){ $emin= "0".$emin;}else{ $emin= $emin; } 
                                                        ?>
                                                        <tr class="Btn" id="exp-rubric-<?php echo $rowid; ?>">
                                                            <td width="12%" id="<?php echo $rowid; ?>" ><?php echo $startdate ;?></td>
                                                            <td width="9%" id="<?php echo $rowid; ?>" class='centerText'><?php echo $shour.":".$smin." ".$sampm;?></td>
                                                            <td width="12%" id="<?php echo $rowid; ?>" class='centerText'><?php echo $enddate ;?></td>
                                                            <td width="9%" id="<?php echo $rowid; ?>" class='centerText'><?php echo $ehour.":".$emin." ".$eampm;?></td>
                                                            <td width="12%" id="<?php echo $rowid; ?>" class='centerText'>                                                              
                                                                 <div class="icon-synergy-edit mainBtn tooltip edit_btn_<?php echo $rowid; ?>" title="Edit" onclick="fn_editlockclassdet('<?php echo ($rowid); ?>','<?php echo $clsid; ?>','<?php echo $drange; ?>','<?php echo $startdate; ?>','<?php echo $shour; ?>','<?php echo $smin; ?>','<?php echo $sampm; ?>','<?php echo $enddate; ?>','<?php echo $ehour; ?>','<?php echo $emin; ?>','<?php echo $eampm; ?>','<?php echo $timezone; ?>','<?php echo $timezonename; ?>');" style="float: left; padding-right: 10px; margin-left: 15px; margin-top: 0px;"></div>
                                                                
                                                                <div class="icon-synergy-trash tooltip" title="Delete" style="float: left; padding-right: 10px; margin-top: 0px;" onclick="fn_deletelockclassdet('<?php echo ($rowid); ?>','<?php echo $clsid; ?>','2');"></div>    
                                                               
                                                            </td>
                                                            <td width="14%" id="<?php echo $rowid; ?>" class='centerText'>
                                                             <?php echo $weekday; ?>
                                                            </td>
                                                            <td width="18%" id="<?php echo $lockclassid; ?>" class='centerText'>  
                                                                <?php 
                                                                if($drange=='1')
                                                                {   ?>
                                                                    <input style="margin-right:-4px;width:73px;height: 35px;" onclick="fn_enablelockclassdet('<?php echo $rowid; ?>','<?php echo $clsid; ?>' ,'<?php echo $drange; ?>','<?php echo $startdate; ?>','<?php echo $shour; ?>','<?php echo $smin; ?>','<?php echo $sampm; ?>','<?php echo $enddate; ?>','<?php echo $ehour; ?>','<?php echo $emin; ?>','<?php echo $eampm; ?>','<?php echo $timezone; ?>','<?php echo $timezonename; ?>','<?php echo json_encode($chkval);?>','<?php echo json_encode($chkboxflag);?>');" type="button" class="module-extend-button <?php if($enableordisable=='1') { echo 'dim'; }else { echo '';  }?>" id="enable_btn_<?php echo $rowid; ?>" value="Enable" />&nbsp;&nbsp; <input style="margin-right:-4px;width:73px;height: 35px;" onclick="fn_deletelockclassdet('<?php echo ($rowid); ?>','<?php echo $clsid; ?>','1');" type="button" class="module-extend-button <?php if($enableordisable=='0') { echo 'dim'; }else { echo '';  }?>" id="disable_btn_<?php echo $rowid; ?>" value="Disable" />
                                                                    <?php 
                                                                } 
                                                                  ?>                                                                                                                                     
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {           ?>
                                                    <tr id="exp-rubric-0">
                                                        <td colspan="8" align="center">  </td>
                                                    </tr> <?php
                                                }  ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <?php
                    }
                    ?> 
                  
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $lockflag=$ObjDB->SelectSingleValueInt("SELECT fld_lock 
                                                    FROM itc_class_master 
                                                    WHERE fld_id='".$clsid."'");
    if($lockflag==1)
    {
        ?>
            <script type="text/javascript" language="javascript">
                $('#locked').removeClass('icon-synergy-unlocked');
                $('#locked').addClass('icon-synergy-locked');
            </script> 
        <?php
    }
    else
    {
        ?>
            <script type="text/javascript" language="javascript">
                $('#locked').removeClass('icon-synergy-locked');
                $('#locked').addClass('icon-synergy-unlocked');
            </script> 
        <?php
    }
    ?>
</section>

<script type="text/javascript" language="javascript">
    $( "#startdate" ).datepicker({
        minDate: '-currentdate',
        onSelect: function(selected)
        {
            $("#enddate").datepicker("option","minDate", selected);
            $(this).parents().parents().removeClass('error');
        }
    });
    $( "#enddate" ).datepicker({
        minDate: '-currentdate',
        onSelect: function(selected)
        {
            $("#startdate").datepicker("option","maxDate", selected);
            $(this).parents().parents().removeClass('error');
            fn_dayrange(1);
        }
         
    });


    $(function(){
        $("#lockclassform").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) 
            {
                $(element).parents('dl').addClass('error');
                error.appendTo($(element).parents('dl'));
                error.addClass('msg'); 		
            },
            rules: 
            { 
                startdate: { required: true  },
                enddate: { required: true, greaterThan: "#startdate" },
            }, 
            messages:
            { 
                startdate:{  required: "Select the start date" },		  
                enddate: {   required: "Select the end date", greaterThan: "Enddate must be greater" },
            },
            highlight: function(element, errorClass, validClass) 
            {
                $(element).parent('dl').addClass(errorClass);
                $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass)
            {
                if($(element).attr('class') == 'error')
                {
                    $(element).parents('dl').removeClass(errorClass);
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            },
            onkeyup: false,
            onblur: true
        });
    });	
</script>

<?php
    @include("footer.php");
