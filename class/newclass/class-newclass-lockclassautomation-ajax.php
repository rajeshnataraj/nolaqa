<?php 
/***********************/
/* Created By Mohan M */
/***********************/

@include("sessioncheck.php");
$date = date("Y-m-d H:i:s");
$cdate = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';
        
 if($oper=="savelockclass" and $oper != " " )
{  
    try
    {       
        $classid = isset($method['classid']) ? $method['classid'] : '0'; 
        
        $bydatestartdate = isset($method['startdate']) ? ($method['startdate']) : ''; 
        $bydateshour = isset($method['bydateshour']) ? $method['bydateshour'] : ''; 
        $bydatesmin = isset($method['bydatesmin']) ? $method['bydatesmin'] : ''; 
        $bydatesampm = isset($method['bydatesampm']) ? $method['bydatesampm'] : ''; 
        $bydateenddate = isset($method['enddate']) ? $method['enddate'] : ''; 
        $bydateehour = isset($method['bydateehour']) ? $method['bydateehour'] : ''; 
        $bydateemin = isset($method['bydateemin']) ? $method['bydateemin'] : ''; 
        $bydateeampm = isset($method['bydateeampm']) ? $method['bydateeampm'] : '';
        
        $dayrange = isset($method['dayrange']) ? $method['dayrange'] : ''; 
        
        $wchkval = isset($method['wchkval']) ? $method['wchkval'] : ''; 
        $wchkflag = isset($method['wchkflag']) ? $method['wchkflag'] : '';
        $wdayval = explode(',',$wchkval);
        $wdaychkflag = explode(',',$wchkflag);        
       
        
        $byrowid = isset($method['byrowid']) ? $method['byrowid'] : '0'; 
        $timezonetype = isset($method['timezone']) ? $method['timezone'] : '0'; 
        
        $enableflag = isset($method['enableflag']) ? $method['enableflag'] : '0'; 
     
        /****** If the date is day light saving time or not code start here*********/
       
        //Start date
        $stimestamps=date('Y-m-d',strtotime($bydatestartdate));
        $stimestamps = strtotime($stimestamps);

        $sstartdatedl=date('d', $stimestamps);
        $sstartmonthdl=date('m', $stimestamps);
        $sstartyeardl=date('Y', $stimestamps);

        //End date
        $etimestamps=date('Y-m-d',strtotime($bydateenddate));
        $etimestamps = strtotime($etimestamps);

        $estartdatedl=date('d', $etimestamps);
        $estartmonthdl=date('m', $etimestamps);
        $estartyeardl=date('Y', $etimestamps);            
       

        //Get the Day light saving time start and end date for the year
        $dstsdate=$ObjDB->SelectSingleValueInt("SELECT fld_sdate FROM itc_zdst_master WHERE fld_year='".$sstartyeardl."'"); 
        $dstedate=$ObjDB->SelectSingleValueInt("SELECT fld_edate FROM itc_zdst_master WHERE fld_year='".$estartyeardl."'");

        /*******Get the month name code start here***************/
        switch ($sstartmonthdl) 
        {
             case "1":
                 $smon="Jan";
                 break;
             case "2":
                 $smon="Feb";
                 break;
             case "3":
                 $smon="Mar";
                 break;
              case "4":
                 $smon="Apr";
                 break;
             case "5":
                 $smon="May";
                 break;
             case "6":
                 $smon="Jun";
                 break;
              case "7":
                 $smon="Jul";
                 break;
             case "8":
                 $smon="Aug";
                 break;
             case "9":
                 $smon="Sep";
                 break;
              case "10":
                 $smon="Oct";
                 break;
             case "11":
                 $smon="Nov";
                 break;
             case "12":
                 $smon="Dec";
                 break;
             default:
                 $smon="Jan";
        }

        switch ($estartmonthdl) 
        {
             case "1":
                 $emon="Jan";
                 break;
             case "2":
                 $emon="Feb";
                 break;
             case "3":
                 $emon="Mar";
                 break;
              case "4":
                 $emon="Apr";
                 break;
             case "5":
                 $emon="May";
                 break;
             case "6":
                 $emon="Jun";
                 break;
              case "7":
                 $emon="Jul";
                 break;
             case "8":
                 $emon="Aug";
                 break;
             case "9":
                 $emon="Sep";
                 break;
              case "10":
                 $emon="Oct";
                 break;
             case "11":
                 $emon="Nov";
                 break;
             case "12":
                 $emon="Dec";
                 break;
             default:
                 $emon="Jan";
        }

        /*******Get the month name code End here***************/       
        
        /***********Start Date is daylight saving time or not code start here****************/
        if($smon=='Mar' || $smon=='Apr' || $smon=='May' || $smon=='June' || $smon=='July' || $smon=='Aug' || $smon=='Sep' || $smon=='Oct')
        {
            if($smon=='Mar')
            {
                if($dstsdate<=$sstartdatedl)
                {
                    $sdaylight="-5";
                }
                else
                {
                    $sdaylight="-6";
                }
            }
            else
            {
                $sdaylight="-5";
            }
        }

        if($smon=='Nov')
        {
            if($dstedate>$sstartdatedl)
            {
                $sdaylight="-5";
            }
            else
            {
                $sdaylight="-6";
            }
        }

        if($smon=='Dec' || $smon=='Jan' || $smon=='Feb')
        {
             $sdaylight="-6";
        }
        /***********Start Date is daylight saving time or not code end here****************/

        /***********End Date is daylight saving time or not code start here****************/
        if($emon=='Mar' || $emon=='Apr' || $emon=='May' || $emon=='June' || $emon=='July' || $emon=='Aug' || $emon=='Sep' || $emon=='Oct')
        {
            if($emon=='Mar')
            {
                if($dstsdate<=$estartdatedl)
                {
                    $edaylight="-5";
                }
                else
                {
                    $edaylight="-6";
                }
            }
            else
            {
                $edaylight="-5";
            }
        }

        if($emon=='Nov')
        {
            if($dstedate>$estartdatedl)
            {
                $edaylight="-5";
            }
            else
            {
                $edaylight="-6";
            }

        }

        if($emon=='Dec' || $emon=='Jan' || $emon=='Feb')
        {
             $edaylight="-6";
        }
        /***********End Date is daylight saving time or not code End here****************/

        if($sdaylight=='-6')
        {
            $sdaylightornot=1;
        }
        else 
        {
            $sdaylightornot=0;
        }

        if($edaylight=='-6')
        {
            $edaylightornot=1;
        }
        else 
        {
            $edaylightornot=0;
        }

        if($timezonetype==1)
        {
            $sval=$sdaylightornot+7;
            $eval=$edaylightornot+7;
        }
        else if($timezonetype==2)
        {
            $sval=$sdaylightornot+6;
            $eval=$edaylightornot+6;
        }
        else if($timezonetype==3)
        {
            $sval=$sdaylightornot+5;
            $eval=$edaylightornot+5;
        }
        else
        {
            $sval=$sdaylightornot+4;
            $eval=$edaylightornot+4;
        }        
       

        /*******Start Date*******/
        
        if(strlen($bydatesmin) == '1')
        {
            $bydatesmin='00';           
        }
        else
        {
            $bydatesmin=$bydatesmin;             
        }
        
        $sda=date('Y-m-d',strtotime($bydatestartdate));
        $timeconvert=$bydateshour.":".$bydatesmin." ".$bydatesampm;
        $stconvert1= date("H:i:s", strtotime($timeconvert));
        /*Convert  CST to UTC  */
        $timestamp = strtotime($sda." ".$stconvert1);
        $timestamp +=  $sval*3600;
        /*Convert  CST to UTC  */
        $sdatetime=date('Y-m-d', $timestamp);
        $stconvert=date('H:i:s', $timestamp);

        $shourdaylight= date('h', $timestamp);
        $smindaylight= date('i', $timestamp);
        $sampmdaylight= date('A', $timestamp);

        /*******End Date*******/
        
        if(strlen($bydateemin) == '1')
        {
            $bydateemin='00';           
        }
        else
        {
            $bydateemin=$bydateemin;             
        }
        
        $eda=date('Y-m-d',strtotime($bydateenddate));
        $timeconvert1=$bydateehour.":".$bydateemin." ".$bydateeampm;
        $etconvert1= date("H:i:s", strtotime($timeconvert1));
         /*Convert  CST to UTC  */
        $timestamp1 = strtotime($eda." ".$etconvert1);
        $timestamp1 += $eval*3600;
        /*Convert  CST to UTC  */
        $edatetime=date('Y-m-d', $timestamp1);        $etconvert=date('H:i:s', $timestamp1);

        $ehourdaylight= date('h', $timestamp1);
        $emindaylight= date('i', $timestamp1);
        $eampmdaylight= date('A', $timestamp1);

        /********insert original value for show********/
        $bydatestartdate1=$bydatestartdate;  $bydateenddate1=$bydateenddate;
        $bydateshour1=$bydateshour;          $bydateehour1=$bydateehour;
        $bydatesmin1=$bydatesmin;            $bydateemin1=$bydateemin;   
        $bydatesampm1=$bydatesampm;          $bydateeampm1=$bydateeampm;
            
        /****** If the date is day light saving time or not code end here*********/
        
            
        if($byrowid == '0' or $byrowid == 'undefined')
        {
            $qry="";
        }
        else
        {
            
            $qry="AND fld_id<>'$byrowid'";
        }
       
        if($dayrange=='1') // Day Range type 1 Repeat Event code start here
        { 
            /******User Select date find In between dates code start here**********/
                $date_from= date('Y-m-d',strtotime($sdatetime));
                $date_from = strtotime($date_from);
                $date_to = date('Y-m-d',strtotime($edatetime));;  
                $date_to = strtotime($date_to);
                $betweendates=array();
                $m=array();
                //count in between dates
                for ($m=$date_from; $m<=$date_to; $m+=86400)
                {  
                    $betweendates[]=date("Y-m-d", $m);
                } 
                $existday=array();
                $countdates=sizeof($betweendates);
                for($j=0;$j<sizeof($betweendates);$j++) 
                {
                    //get the day for date
                    $date = $betweendates[$j];
                    $weekday = date('l', strtotime($date)); // note: first arg to date() is lower-case L
                    $existday[]=$weekday;
                }              

                $dayofstartdate= $existday[0];
                $enddateofday=sizeof($existday)-1;
                $dayoflastdate= $existday[$enddateofday]; 
            
            $dayofstartdate1=$dayofstartdate; $dayoflastdate1=$dayoflastdate;
            

            /******User Select date find In between dates code End here**********/

            /*********Repeat day code start here**********/
                $repeateventday=array();

                for($i=0;$i<sizeof($wdayval);$i++)
                {
                        if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                        else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                        else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                        else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                        else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                        else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                        else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }

                        if($wdaychkflag[$i]=='1')
                        {
                            $repeateventday[]=$sweekday;
                        }
                }              

            /*********Repeat day code End here**********/

            /********* find out inbetween day using Array Intersect****/
                $result = array_intersect($repeateventday, $existday);               

                $repeateventcount=sizeof($repeateventday);
                $stimestamps=date('Y-m-d',strtotime($bydatestartdate));
                $userstartdate = date('l', strtotime($stimestamps));
                $date_from= date('Y-m-d',strtotime($sdatetime));
            
            
                $userstartdateconvert = date('l', strtotime($date_from));
                $date_to= date('Y-m-d',strtotime($edatetime));
                $userenddateconvert = date('l', strtotime($date_to));
               
            /****** Find the user start date previous day code start here ************/
                switch ($dayofstartdate) 
                {
                    case "Monday":
                             $userstartpreviouesday="Sunday";
                             break;
                    case "Tuesday":
                             $userstartpreviouesday="Monday";
                             break;
                    case "Wednesday":
                            $userstartpreviouesday="Tuesday";
                             break;
                    case "Thursday":
                             $userstartpreviouesday="Wednesday";
                             break;
                    case "Friday":
                             $userstartpreviouesday="Thursday";
                             break;
                    case "Saturday":
                             $userstartpreviouesday="Friday";
                             break;
                    case "Sunday":
                             $userstartpreviouesday="Saturday";
                             break;
                    default:
                             $userstartpreviouesday="Sunday";
                }

            /***************User select start date is convert next date means code start here****************/
              $repeateventdaye=array();
              for($i=0;$i<sizeof($wdayval);$i++)
              {
                      if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                      else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                      else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                      else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                      else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                      else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                      else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }

                      if($wdaychkflag[$i]=='1')
                      {
                          $repeateventdaye[]=$eweekday;
                      }
              }
              $result1 = array_intersect($repeateventdaye, $existday);
            /***************User select start date is convert next date means code end here****************/

            /****** Find the user start date previous day code ecd here ************/

            if(!$repeateventday=='')
            {
                /********** Same Date for User Select code start here**************/
                if($dayofstartdate==$dayoflastdate)
                {                   
                    /********Find the user select date is same or convert another day code start here*******/
                    if(strcmp($userstartdate, $dayofstartdate) == 0)
                    {                        
                         $lcktype='1 ';
                    }
                    else
                    {                       
                        $lcktype='4 ';
                    }
                    /********Find the user select date is same or convert another day code end here*******/

                    if($sampmdaylight=='PM' &&  $eampmdaylight=='AM')/********its not coming*******/
                    {                        
                        $flag=0;                       
                    }
                    else if($sampmdaylight=='AM' &&  $eampmdaylight=='PM')
                    {                        
                        $flag=1;

                    }
                    else if($sampmdaylight=='PM' &&  $eampmdaylight=='PM')
                    {
                        $curstarttime_in_24_hour_format  = date("H", strtotime($shourdaylight.":".$smindaylight." ".$sampmdaylight));
                        $curendtime_in_24_hour_format  = date("H", strtotime($ehourdaylight.":".$emindaylight." ".$eampmdaylight));

                        if($curstarttime_in_24_hour_format < $curendtime_in_24_hour_format)
                        {                            
                            $flag=1;

                        }
                        else if($curstarttime_in_24_hour_format <= $curendtime_in_24_hour_format)
                        {
                            if($smindaylight < $emindaylight)
                            {                              
                                $flag=1;
                            }
                            else
                            {                                
                                $flag=0;                             
                            }
                        }
                        else
                        {                           
                            $flag=0;                         
                        }
                    }
                    else if($sampmdaylight=='AM' &&  $eampmdaylight=='AM')
                    {
                        $curstarttime_in_24_hour_format  = date("H", strtotime($shourdaylight.":".$smindaylight." ".$sampmdaylight));
                        $curendtime_in_24_hour_format  = date("H", strtotime($ehourdaylight.":".$emindaylight." ".$eampmdaylight));

                        if($curstarttime_in_24_hour_format < $curendtime_in_24_hour_format)
                        {                            
                            $flag=1;
                        }
                        else if($curstarttime_in_24_hour_format <= $curendtime_in_24_hour_format)
                        {
                            if($smindaylight < $emindaylight)
                            {                                
                                $flag=1;
                            }
                            else
                            {                               
                                $flag=0;                               
                            }
                        }
                        else
                        {                            
                            $flag=0;                         
                        }
                    }
                }
                /********** Same Date for User Select code End here**************/

                /************One Date Different for user select code start here**********/
                else if (strcmp($userstartdateconvert, $userstartdate) == 0) //if user select day and convertion day or equal or not
                {                   
                    $lcktype='2';
                    if($sampmdaylight=='PM' &&  $eampmdaylight=='AM')
                    {                       
                        $flag=1;
                    } //else if PM and AM code End here
                    else if($sampmdaylight=='PM' &&  $eampmdaylight=='PM')
                    {
                          $flag=1;
                    } //else if PM and PM code End here
                    else if($sampmdaylight=='AM' &&  $eampmdaylight=='AM')
                    {
                         $flag=1;
                            
                    } //else if AM and AM code End here
                }
                /************One Date Different for user select code End here**********/

                /***************User select start date is convert next date means code start here****************/
                else
                {                    
                    $lcktype='3';
                    if($sampmdaylight=='AM' &&  $eampmdaylight=='AM') //only this code working
                    {
                        $flag=1;
                    }
                }
                /***************User select start date is convert next date means code End here****************/
            }
            else
            {                
                $lcktype=5;
                $flag=1;
            }            
           
            $lockclscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_event_enableordisable='1' $qry"); 

            $qrylockclass= $ObjDB->QueryObject("SELECT fld_id as lockclassid, fld_date_range as drange, fld_sdate_daylight AS sdate,fld_edate_daylight AS edate,fld_shour_daylight AS shour,fld_smin_daylight AS smin,fld_sampm_daylight as sampm,
                                                        fld_ehour_daylight AS ehour,fld_emin_daylight AS emin,fld_eampm_daylight AS eampm 
                                                        FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_event_enableordisable='1' $qry ");
         
            if($qrylockclass->num_rows > 0)
            {													
                while($rowlockclass = $qrylockclass->fetch_assoc())
                {
                    extract($rowlockclass);
                   
                    $startdate[]=$sdate;
                    $starthour[]=$shour;
                    $startmin[]=$smin;
                    $startampm[]=$sampm;
                    $enddate[]=$edate;
                    $endhour[]=$ehour;
                    $endmin[]=$emin;
                    $endampm[]=$eampm;
                    $lockclsid[]=$lockclassid;
                    $daterange[]=$drange;
                     
                }//while loop end
            }//if loop end
            else
            { //new record store to db and class have only one record that time it will be update[edit]
                if($byrowid == '0' or $byrowid == 'undefined')
                {
                    if($flag==1)
                    {
                        if($lcktype==1)
                        {
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                            (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                            fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                            fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                            fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                            fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                    VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                            '".$bydateshour."', '".$bydateehour."', '".$bydatesmin."', '".$bydateemin."', '".$bydatesampm."','".$bydateeampm."', '".$dayofstartdate."','".$dayoflastdate."', 
                                                                            '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                            '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                            '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Monday"; $eweekdayno=1; } 
                                else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; $eweekdayno=2; }
                                else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop 
                            echo "success";
                        }
                        else if($lcktype==2)
                        {
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                            (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                            fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                            fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                            fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                            fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                    VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                            '".$bydateshour."', '".$bydateehour."', '".$bydatesmin."', '".$bydateemin."', '".$bydatesampm."','".$bydateeampm."','".$dayofstartdate."','".$dayoflastdate."', 
                                                                            '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                            '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                            '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')"); 
                            
                           
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }
                                
                                if($wdaychkflag[$i]=='1')
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==3)
                        {
                            
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour."', '".$bydateehour."', '".$bydatesmin."', '".$bydateemin."', '".$bydatesampm."','".$bydateeampm."','".$dayofstartdate."','".$dayoflastdate."',
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");                             
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Tuesday"; $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Wednesday"; $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Thursday"; $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Friday"; $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Saturday"; $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Sunday"; $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Monday"; $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==4)
                        {
                            
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour."', '".$bydateehour."', '".$bydatesmin."', '".$bydateemin."', '".$bydatesampm."','".$bydateeampm."','".$dayofstartdate."','".$dayoflastdate."',
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Monday"; $sweekday="Tuesday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Tuesday"; $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Wednesday"; $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Thursday"; $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Friday"; $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Saturday"; $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Sunday"; $sweekday="Monday"; $eweekday="Monday"; $eweekdayno=1; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==5)
                        {
                           
                              $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour."', '".$bydateehour."', '".$bydatesmin."', '".$bydateemin."', '".$bydatesampm."','".$bydateeampm."','".$dayofstartdate."','".$dayoflastdate."', 
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                              echo "success";
                        }
                    }
                    else
                    {
                        echo "fail";
                    }
                }
                else
                {
                    if($enableflag=='1')
                    {
                        
                        if($flag==1)
                        {
                              $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_event_enableordisable='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                            
                             echo "success"; 
                        }
                        else
                        {
                             echo "fail";
                        }
                        
                    }
                    else
                    {
                        if($flag==1)
                        {
                            if($lcktype==1)
                            {

                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour."', fld_startmin='".$bydatesmin."', 
                                                fld_startampm='".$bydatesampm."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour."', 
                                                fld_endmin='".$bydateemin."', fld_endampm='".$bydateeampm."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate."', fld_edate_day='".$dayoflastdate."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Monday"; $eweekdayno=1; } 
                                    else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; $eweekdayno=2; }
                                    else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no,  fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$sweekday."', fld_user_eday='".$eweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";

                            }
                            else if($lcktype==2)
                            {

                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour."', fld_startmin='".$bydatesmin."', 
                                                fld_startampm='".$bydatesampm."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour."', 
                                                fld_endmin='".$bydateemin."', fld_endampm='".$bydateeampm."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate."', fld_edate_day='".$dayoflastdate."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                             
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$sweekday."', fld_user_eday='".$eweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";
                            }
                            else if($lcktype==3)
                            {

                                 $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour."', fld_startmin='".$bydatesmin."', 
                                                fld_startampm='".$bydatesampm."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour."', 
                                                fld_endmin='".$bydateemin."', fld_endampm='".$bydateeampm."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate."', fld_edate_day='".$dayoflastdate."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Tuesday"; $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Wednesday"; $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Thursday"; $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Friday"; $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Saturday"; $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Sunday"; $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Monday"; $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                             $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                        (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date, fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$usersweekday."', fld_user_eday='".$usereweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";
                            }
                            else if($lcktype==4)
                            {
                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour."', fld_startmin='".$bydatesmin."', 
                                                fld_startampm='".$bydatesampm."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour."', 
                                                fld_endmin='".$bydateemin."', fld_endampm='".$bydateeampm."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate."', fld_edate_day='".$dayoflastdate."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Monday"; $sweekday="Tuesday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Tuesday"; $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Wednesday"; $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Thursday"; $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Friday"; $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Saturday"; $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Sunday"; $sweekday="Monday"; $eweekday="Monday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$usersweekday."', fld_user_eday='".$usereweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop
                                echo "success";
                            }
                            else if($lcktype==5)
                            {

                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour."', fld_startmin='".$bydatesmin."', 
                                                fld_startampm='".$bydatesampm."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour."', 
                                                fld_endmin='".$bydateemin."', fld_endampm='".$bydateeampm."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate."', fld_edate_day='".$dayoflastdate."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1', fld_lock_type='".$lcktype."'
                                                 WHERE fld_lock_id='".$byrowid."'");

                                echo "success";
                            }
                        }
                        else
                        {
                            echo "fail";
                        }
                    }
                    
                }
            }//else end            
            
            /*********If the class has more then one record means code start here***********/
            if($lockclscount != '0') 
            {
                for($i=0;$i<sizeof($lockclsid);$i++) 
                {                   
                    /**Database record has more then one dates means code start here*/
                    if($daterange[$i]=='2') //Lock type 2 [by week] if start /*****************Working Fine for all types***************************/
                    {                 
                        /********Repeat Event and one date difference are correct  means code start here***********/
                        if($flag==1)
                        {                     
                            if(strlen($startmin[$i]) == '1')
                            {
                                $startmin[$i]='00';           
                            }
                            else
                            {
                                $startmin[$i]=$startmin[$i];             
                            }

                            if(strlen($endmin[$i]) == '1')
                            {
                                $endmin[$i]='00';           
                            }
                            else
                            {
                                $endmin[$i]=$endmin[$i];             
                            }

                            $bydateshour= date('h', $timestamp);
                            $bydatesmin= date('i', $timestamp);
                            $bydatesampm= date('A', $timestamp);

                            $bydateehour= date('h', $timestamp1);
                            $bydateemin= date('i', $timestamp1);
                            $bydateeampm= date('A', $timestamp1);

                            /**********24 hours Convertion****************/
                                $starttime_in_24_hour_format  = date("H", strtotime($starthour[$i].":".$startmin[$i]." ".$startampm[$i]));
                                $endtime_in_24_hour_format  = date("H", strtotime($endhour[$i].":".$endmin[$i]." ".$endampm[$i]));
                                $curstarttime_in_24_hour_format  = date("H", strtotime($bydateshour.":".$bydatesmin." ".$bydatesampm));
                                $curendtime_in_24_hour_format  = date("H", strtotime($bydateehour.":".$bydateemin." ".$bydateeampm));
                            /**********24 hours Convertion****************/                            
                            
                            /******* current date Start*******/                                 
                                $date_from= date('Y-m-d',strtotime($sdatetime));
                                $date_from = strtotime($date_from);                               
                                $date_to = date('Y-m-d',strtotime($edatetime));  
                                $date_to = strtotime($date_to);

                                $m=array();
                                $betweendates=array();
                                //count in between dates
                                for ($m=$date_from; $m<=$date_to; $m+=86400) 
                                {  
                                    $betweendates[]=date("Y-m-d", $m);
                                }                                

                                $enddateofday=sizeof($betweendates)-1;
                                $begindate= $betweendates[0];
                                $lastdate= $betweendates[$enddateofday];
                            /******* current date End*******/

                            /*********Datebase date Sart*********/
                                $date_from1 = strtotime($startdate[$i]); 
                                $date_to1 = strtotime($enddate[$i]); 
                                $n=array();
                                $betweendates1=array();

                                //count in between dates
                                for ($n=$date_from1; $n<=$date_to1; $n+=86400) 
                                {  
                                    $betweendates1[]=date("Y-m-d", $n);
                                }                                 
                                $enddateofday=sizeof($betweendates1)-1;
                                $dayofbegindate= $betweendates1[0];
                                $dayoflastdate= $betweendates1[$enddateofday];
                            /*********Datebase date Sart*********/

                            $result = array_intersect($betweendates1, $betweendates);//intersection                           
                            
                            if($lcktype==1)
                            {
                                if((strtotime($dayoflastdate) == strtotime($begindate)) && (strtotime($dayoflastdate) == strtotime($lastdate)))
                                {                                    
                                    if($endampm[$i] == $bydatesampm)
                                    {
                                        if($endhour[$i] == $bydateshour)
                                        {
                                            if($endmin[$i] <= $bydatesmin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {  
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($endampm[$i]=='AM')
                                        {
                                            if($bydatesampm=='PM')
                                            {
                                                $bydatestartstatus="success";
                                            }
                                        }
                                        else
                                        {
                                            if($bydatesampm=='AM')
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                    }
                                }
                                else if((strtotime($dayofbegindate) == strtotime($begindate)) && (strtotime($dayofbegindate) == strtotime($lastdate)))
                                {                                   
                                    if($startampm[$i] == $bydateeampm)
                                    {
                                        if($starthour[$i] == $bydateehour)
                                        {
                                            if($startmin[$i] >= $bydateemin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {  
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($startampm[$i]=='AM')
                                        {
                                            if($bydateeampm=='PM')
                                            {
                                                 $bydatestartstatus="fail";
                                            }
                                        }
                                        else
                                        {
                                            if($bydateeampm=='AM')
                                            {
                                                $bydatestartstatus="success";
                                            }
                                        }
                                    }
                                }
                                else 
                                {
                                     $bydatestartstatus="success";
                                }
                            }
                            else
                            {
                                if(empty($result))
                                {
                                    $bydatestartstatus="success";
                                }
                                else if(strtotime($dayoflastdate) == strtotime($begindate))
                                {                                    
                                    if($endampm[$i] == $bydatesampm)
                                    {
                                        if($endhour[$i] == $bydateshour)
                                        {
                                            if($endmin[$i] <= $bydatesmin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {  
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($bydatesampm=='AM')
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="success";
                                        }  
                                    }
                                }  
                                else if(strtotime($dayofbegindate) == strtotime($lastdate))
                                {
                                    if($startampm[$i] == $bydateeampm)
                                    {
                                        if($starthour[$i] == $bydateehour)
                                        {
                                            if($startmin[$i] >= $bydateemin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($starthour[$i] == $bydateehour)
                                        {
                                            if($startmin[$i] >= $bydateemin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                }  
                                else 
                                {
                                    $bydatestartstatus="fail";
                                }
                            }

                            if($bydatestartstatus=='success')
                            { 
                                $innerflag=1;
                            }
                            else
                            {
                                $innerflag=0;
                                //  break;
                            }                            
                          
                            /*********if the date and Time is not cross to start date and end date for ond day difference code start here ***********/
                            if($innerflag==1)
                            {
                                /*******Database Record******/
                                    $existday11=array();
                                    for($j=0;$j<sizeof($betweendates1);$j++) 
                                    {
                                        //get the day for date
                                        $date11 = $betweendates1[$j];
                                        $weekday11 = date('l', strtotime($date11)); // note: first arg to date() is lower-case L
                                        $existday11[]=$weekday11;
                                    }                                  
                                    $enddateofdaydb=sizeof($existday11)-1;
                                    $dayofbegindatedb= $existday11[0];
                                    $dayoflastdatedb= $existday11[$enddateofdaydb];
                                /*******Database Record******/

                                if($lcktype==1) /// Same Date for start date and end date means
                                {                                   
                                    $reventforsday=array();
                                    if(strtotime($dayoflastdate) <= strtotime($begindate))
                                    {                                       
                                        $deepinnerflag=1;
                                    }
                                    else if(strtotime($dayofbegindate) >= strtotime($lastdate))
                                    {
                                        for($b=0;$b<sizeof($wdayval);$b++)
                                        {
                                            if($wdayval[$b]==1){ $sweekday="Monday";   $eweekday="Monday";  } 
                                            else if($wdayval[$b]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; }
                                            else if($wdayval[$b]==3){ $sweekday="Wednesday"; $eweekday="Wednesday";  }
                                            else if($wdayval[$b]==4){ $sweekday="Thursday"; $eweekday="Thursday";  }
                                            else if($wdayval[$b]==5){ $sweekday="Friday"; $eweekday="Friday"; }
                                            else if($wdayval[$b]==6){ $sweekday="Saturday"; $eweekday="Saturday";  }
                                            else if($wdayval[$b]==7){ $sweekday="Sunday"; $eweekday="Sunday";  }

                                            if($wdaychkflag[$b]=='1')
                                            {
                                                $reventforsday[]=$sweekday;
                                            }
                                        }
                                        
                                        $repeatevent= array_unique($reventforsday);
                                        $repeateventorder=array_values($repeatevent);                                        
                                        $userdayofstartdate= $repeateventorder[0];
                                        $userenddateofday=sizeof($repeateventorder)-1;
                                        $userdayoflastdate= $repeateventorder[$userenddateofday]; 
                               
                                        $repeateventd=array_intersect($existday11, $repeatevent);//intersection
                                        $result=array_values($repeateventd);
                                        $repeateventcountforday=sizeof($result);                                       
                                        if(empty($result))
                                        {
                                            $deepinnerflag=1;
                                        }
                                        else
                                        {
                                            if($repeateventcountforday==1)
                                            { //Repeat Event Dates One Code start Here
                                                if(strcmp($dayofbegindatedb, $result[0]) == 0) //Database start date is equal to repeated 
                                                {                                                    
                                                    if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                    {   
                                                        if($startmin[$i] >= $emindaylight)
                                                        {
                                                            $deepinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    }
                                                    else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                    {   
                                                        $deepinnerflag=1;
                                                    }
                                                    else
                                                    {
                                                        $deepinnerflag=0;
                                                        break;
                                                    }
                                                }
                                                else if(strcmp($dayoflastdatedb, $result[0]) == 0)/// not working for last day  $endtime_in_24_hour_format
                                                {                                                   
                                                    if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                    {   
                                                        if($endmin[$i] <= $smindaylight)
                                                        {
                                                            $deepinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    }
                                                    else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                    {   
                                                        $deepinnerflag=1;
                                                    }
                                                    else
                                                    {
                                                        $deepinnerflag=0;
                                                        break;
                                                    }
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }//Repeat Event Dates One Code End Here
                                            else if($repeateventcountforday==2)
                                            { //Repeat Event Dates two Code start Here
                                                $countflag=0;
                                                for($m=0;$m<sizeof($result);$m++)
                                                {                                                  
                                                    if(strcmp($dayofbegindatedb, $result[$m]) == 0) //Database start date is equal to repeated 
                                                    {
                                                        if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                        {   
                                                            if($startmin[$i] >= $emindaylight)
                                                            {
                                                                $countflag+=1;
                                                            }
                                                            else
                                                            {
                                                                $countflag+=0;
                                                                break;
                                                            }
                                                        }
                                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                        {   
                                                            $countflag+=1;
                                                        }
                                                        else
                                                        {
                                                            $countflag+=0;
                                                            break;
                                                        }
                                                    }
                                                    else if(strcmp($dayoflastdatedb, $result[$m]) == 0)//Database end date is equal to repeated 
                                                    {
                                                        if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                        {   
                                                            if($endmin[$i] <= $smindaylight)
                                                            {
                                                                $countflag+=1;
                                                            }
                                                            else
                                                            {
                                                                $countflag+=0;
                                                                break;
                                                            }
                                                        }
                                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                        {   
                                                            $countflag+=1;
                                                        }
                                                        else
                                                        {
                                                            $countflag+=0;
                                                            break;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $countflag+=0;
                                                        break;
                                                    }
                                                }//for loop end here
                                                if(sizeof($result)==$countflag)
                                                {
                                                    $deepinnerflag=1;
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }//Repeat Event Dates two Code end Here
                                            else
                                            {
                                                $deepinnerflag=0;
                                                break;
                                            }
                                        } //Else Code End Here
                                    } //else if code end here 
                                }
                                else if($lcktype==2 || $lcktype==3 || $lcktype==4)
                                {                                    
                                    if(strtotime($dayoflastdate) <= strtotime($begindate))
                                    {
                                        $deepinnerflag=1;
                                    }
                                    else if(strtotime($dayofbegindate) >= strtotime($lastdate))
                                    {
                                        $reventforsday=array();
                                        if($lcktype==2) ///one date difference 
                                        {
                                            for($m=0;$m<sizeof($wdayval);$m++)
                                            {
                                                if($wdayval[$m]==1){ $sweekday="Monday";   $eweekday="Tuesday"; } 
                                                else if($wdayval[$m]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; }
                                                else if($wdayval[$m]==3){ $sweekday="Wednesday"; $eweekday="Thursday";}
                                                else if($wdayval[$m]==4){ $sweekday="Thursday"; $eweekday="Friday";  }
                                                else if($wdayval[$m]==5){ $sweekday="Friday"; $eweekday="Saturday"; }
                                                else if($wdayval[$m]==6){ $sweekday="Saturday"; $eweekday="Sunday"; }
                                                else if($wdayval[$m]==7){ $sweekday="Sunday"; $eweekday="Monday";  }

                                                if($wdaychkflag[$m]=='1')
                                                {    
                                                    $reventforsday[]=$sweekday;
                                                    $reventforsday[]=$eweekday;
                                                }
                                            }
                                        }
                                        else if($lcktype==3) /// User select start date and end date is convert next date means
                                        {
                                            for($k=0;$k<sizeof($wdayval);$k++)
                                            {
                                                if($wdayval[$k]==1){ $sweekday="Tuesday";   $eweekday="Wednesday";  } 
                                                else if($wdayval[$k]==2){  $sweekday="Wednesday"; $eweekday="Thursday"; }
                                                else if($wdayval[$k]==3){  $sweekday="Thursday"; $eweekday="Friday";  }
                                                else if($wdayval[$k]==4){  $sweekday="Friday"; $eweekday="Saturday"; }
                                                else if($wdayval[$k]==5){  $sweekday="Saturday"; $eweekday="Sunday";  }
                                                else if($wdayval[$k]==6){  $sweekday="Sunday"; $eweekday="Monday"; }
                                                else if($wdayval[$k]==7){  $sweekday="Monday"; $eweekday="Tuesday"; }

                                                if($wdaychkflag[$k]=='1')
                                                {
                                                    $reventforsday[]=$sweekday;
                                                    $reventforsday[]=$eweekday;
                                                }
                                            }
                                        }
                                        else if($lcktype==4) ///user start date convert next date means [same date]
                                        {
                                            for($n=0;$n<sizeof($wdayval);$n++)
                                            {
                                                if($wdayval[$n]==1){ $usersweekday="Monday";   $usereweekday="Tuesday"; $sweekday="Tuesday";   $eweekday="Wednesday";  } 
                                                else if($wdayval[$n]==2){ $usersweekday="Tuesday";   $usereweekday="Wednesday"; $sweekday="Wednesday"; $eweekday="Thursday";  }
                                                else if($wdayval[$n]==3){ $usersweekday="Wednesday";   $usereweekday="Thursday"; $sweekday="Thursday"; $eweekday="Friday";  }
                                                else if($wdayval[$n]==4){ $usersweekday="Thursday";   $usereweekday="Friday"; $sweekday="Friday"; $eweekday="Saturday";  }
                                                else if($wdayval[$n]==5){ $usersweekday="Friday";   $usereweekday="Saturday"; $sweekday="Saturday"; $eweekday="Sunday";  }
                                                else if($wdayval[$n]==6){ $usersweekday="Saturday";   $usereweekday="Sunday"; $sweekday="Sunday"; $eweekday="Monday";  }
                                                else if($wdayval[$n]==7){ $usersweekday="Sunday";   $usereweekday="Monday"; $sweekday="Monday"; $eweekday="Tuesday";  }

                                                if($wdaychkflag[$n]=='1')
                                                {
                                                     $reventforsday[]=$sweekday;
                                                }
                                            }
                                        }

                                        $repeateventunique= array_unique($reventforsday);
                                        $repeateventorder=array_values($repeateventunique);                                        
                                        $userdayofstartdate=$repeateventorder[0];
                                        $userenddateofday=sizeof($repeateventorder)-1;
                                        $userdayoflastdate=$repeateventorder[$userenddateofday]; 

                                        $repeateventintersect=array_intersect($existday11, $repeateventunique);//intersection
                                        $result=array_values($repeateventintersect);
                                        $repeateventcountforday=sizeof($result);                                       

                                        if(empty($result))
                                        {
                                            $deepinnerflag=1;
                                        }
                                        else
                                        {                                            
                                            if($repeateventcountforday==1)
                                            { //Repeat Event Dates one Code start Here
                                                if(strcmp($dayofbegindatedb, $result[0]) == 0) //Database start date is equal to repeated 
                                                {                                                  
                                                    if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                    {   
                                                        if($startmin[$i] >= $emindaylight)
                                                        {
                                                            $deepinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    }
                                                    else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                    {   
                                                        $deepinnerflag=1;
                                                    }
                                                    else
                                                    {
                                                        $deepinnerflag=0;
                                                        break;
                                                    }
                                                }
                                                else if(strcmp($dayoflastdatedb, $result[0]) == 0)/// not working for last day  $endtime_in_24_hour_format
                                                {                                                   
                                                    if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                    {   
                                                        if($endmin[$i] <= $smindaylight)
                                                        {
                                                            $deepinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    }
                                                    else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                    {   
                                                        $deepinnerflag=1;
                                                    }
                                                    else
                                                    {
                                                        $deepinnerflag=0;
                                                        break;
                                                    }
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            } //Repeat Event Dates one Code end Here
                                            else if($repeateventcountforday==2)
                                            { //Repeat Event Dates two Code start Here
                                                $countflag=0;
                                                if(strcmp($dayofbegindatedb, $userdayoflastdate) == 0) //Database start date is equal to repeated 
                                                {
                                                    if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                    {   
                                                        if($startmin[$i] >= $emindaylight)
                                                        {
                                                             $countflag+=1;
                                                        }
                                                        else
                                                        {
                                                            $countflag+=0;                                                           
                                                        }
                                                    }
                                                    else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                    {   
                                                         $countflag+=1;
                                                    }
                                                    else
                                                    {
                                                        $countflag+=0;                                                       
                                                    }
                                                }
                                                if(strcmp($dayoflastdatedb, $userdayofstartdate) == 0)//D atabase end date is equal to repeated  
                                                {                                                    
                                                    if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                    {   
                                                        if($endmin[$i] <= $smindaylight)
                                                        {
                                                            $countflag+=1;
                                                        }
                                                        else
                                                        {
                                                            $countflag+=0;                                                           
                                                        }
                                                    }
                                                    else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                    {   
                                                         $countflag+=1;
                                                    }
                                                    else
                                                    {
                                                        $countflag+=0;                                                        
                                                    }
                                                }

                                                if($countflag=='2')
                                                {
                                                    $deepinnerflag=1;
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            } //Repeat Event Dates two Code end Here
                                            else
                                            {
                                                $deepinnerflag=0;
                                                break;
                                            }
                                        }
                                    } //Else If Code end here
                                }
                                else if($lcktype==5)//no repeat event select
                                {                                    
                                    $deepinnerflag=1;
                                }
                            } /*********if the date and Time is not cross to start date and end date for ond day difference code start here ***********/
                            else
                            {                                
                                $deepinnerflag=0;
                                 break;
                            }
                        } /********Repeat Event and one date difference are correct  means code end here***********/
                        else
                        {
                            $innerflag=0;
                            break;
                        }
                    } //Lock type 2 [by week] if start /*****************Working Fine for all types***************************/
                    /**Database record has more then one dates means code start here*/
                    else  //Type 1 Code Start Here /*****************Working Fine for all types***************************/
                    {                      
                        if($flag==1)
                        {                            
                            if(strlen($startmin[$i]) == '1')
                            {
                                $startmin[$i]='00';           
                            }
                            else
                            {
                                $startmin[$i]=$startmin[$i];             
                            }

                            if(strlen($endmin[$i]) == '1')
                            {
                                $endmin[$i]='00';           
                            }
                            else
                            {
                                $endmin[$i]=$endmin[$i];             
                            }

                            $bydateshour= date('h', $timestamp);
                            $bydatesmin= date('i', $timestamp);
                            $bydatesampm= date('A', $timestamp);

                            $bydateehour= date('h', $timestamp1);
                            $bydateemin= date('i', $timestamp1);
                            $bydateeampm= date('A', $timestamp1);

                            /**********24 hours Convertion****************/
                                $starttime_in_24_hour_format  = date("H", strtotime($starthour[$i].":".$startmin[$i]." ".$startampm[$i]));
                                $endtime_in_24_hour_format  = date("H", strtotime($endhour[$i].":".$endmin[$i]." ".$endampm[$i]));
                                $curstarttime_in_24_hour_format  = date("H", strtotime($bydateshour.":".$bydatesmin." ".$bydatesampm));
                                $curendtime_in_24_hour_format  = date("H", strtotime($bydateehour.":".$bydateemin." ".$bydateeampm));
                            /**********24 hours Convertion****************/                            
                            
                            /******* current date Start*******/                                 
                                $date_from= date('Y-m-d',strtotime($sdatetime));
                                $date_from = strtotime($date_from);                                
                                $date_to = date('Y-m-d',strtotime($edatetime));  
                                $date_to = strtotime($date_to);

                                $m=array();
                                $betweendates=array();
                                //count in between dates
                                for ($m=$date_from; $m<=$date_to; $m+=86400) 
                                {  
                                    $betweendates[]=date("Y-m-d", $m);
                                }                                 

                                $enddateofday=sizeof($betweendates)-1;
                                $begindate= $betweendates[0];
                                $lastdate= $betweendates[$enddateofday];
                            /******* current date End*******/

                            /*********Datebase date Sart*********/
                                $date_from1 = strtotime($startdate[$i]); 
                                $date_to1 = strtotime($enddate[$i]); 
                                $n=array();
                                $betweendates1=array();

                                //count in between dates
                                for ($n=$date_from1; $n<=$date_to1; $n+=86400) 
                                {  
                                    $betweendates1[]=date("Y-m-d", $n);
                                }                                 
                                $enddateofday=sizeof($betweendates1)-1;
                                $dayofbegindate= $betweendates1[0];
                                $dayoflastdate= $betweendates1[$enddateofday];
                            /*********Datebase date Sart*********/

                            $result = array_intersect($betweendates1, $betweendates);//intersection                           
                            
                            if($lcktype==1 || $lcktype==4)
                            {
                                if($begindate == $dayoflastdate and $lastdate == $dayofbegindate)
                                {
                                    $dbsetime=array(); $dbeetime=array();
                                    $usetime=array(); $uesetime=array();                                    

                                    if($starttime_in_24_hour_format>=$endtime_in_24_hour_format)
                                    {
                                        for($db=$starttime_in_24_hour_format; $db<=24; $db++) 
                                        {  
                                            if($db=='24')
                                            {
                                                $dbsetime[]=0;
                                            }
                                            else
                                            {
                                                $dbsetime[]=$db;
                                            }
                                        }                                         
                                        for($dbe=0; $dbe<=$endtime_in_24_hour_format; $dbe++) 
                                        {  
                                            if($dbe=='24')
                                            {
                                                $dbeetime[]=0;
                                            }
                                            else
                                            {
                                                $dbeetime[]=$dbe;
                                            }
                                        }                                         
                                        $dbssetime=array_merge($dbsetime, $dbeetime);//intersection
                                        $dbfsetime=array_values(array_unique($dbssetime));//intersection
                                    }
                                    else
                                    {
                                        for($db1=$starttime_in_24_hour_format; $db1<=$endtime_in_24_hour_format; $db1++) 
                                        {  
                                            $dbfsetime[]=$db1;
                                        }                                        
                                    }


                                    if($curstarttime_in_24_hour_format>=$curendtime_in_24_hour_format)
                                    {
                                        for($dbus=$curstarttime_in_24_hour_format; $dbus<=24; $dbus++) 
                                        {  
                                            if($dbus=='24')
                                            {
                                                $usetime[]=0;
                                            }
                                            else
                                            {
                                                $usetime[]=$dbus;
                                            }
                                        }                                         
                                        for($dbue=0; $dbue<=$curendtime_in_24_hour_format; $dbue++) 
                                        {  
                                            if($dbue=='24')
                                            {
                                                $uesetime[]=0;
                                            }
                                            else
                                            {
                                                $uesetime[]=$dbue;
                                            }

                                        }                                         
                                        $useretime=array_merge($usetime, $uesetime);//intersection
                                        $usersetime=array_values(array_unique($useretime));//intersection
                                    }
                                    else
                                    {
                                        for($use=1; $use<=$curstarttime_in_24_hour_format; $use++) 
                                        {  
                                            $usersetime[]=$use;
                                        } 
                                    }

                                    $dbusertime=array_intersect($dbsetime, $usetime);//intersection                                   

                                    $dbuseresult=array_values($dbusertime);
                                    if(empty($dbuseresult))
                                    {
                                        $bydatestartstatus="success";
                                    }
                                    else
                                    {
                                        if($starttime_in_24_hour_format==$curendtime_in_24_hour_format)
                                        {
                                            if($startmin[$i] >= $emindaylight)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($endtime_in_24_hour_format==$curstarttime_in_24_hour_format)
                                        {
                                            if($endmin[$i] <= $smindaylight)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                } //start date is same as End week day And  End date is same as Start week day
                                else if((strtotime($dayoflastdate) == strtotime($begindate)) && (strtotime($dayoflastdate) == strtotime($lastdate)))
                                {                                      
                                    if($endampm[$i] == $bydatesampm)
                                    {
                                        if($endhour[$i] == $bydateshour)
                                        {
                                            if($endmin[$i] <= $bydatesmin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {  
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($endampm[$i]=='AM')
                                        {
                                            if($bydatesampm=='PM')
                                            {
                                                $bydatestartstatus="success";
                                            }
                                        }
                                        else
                                        {
                                            if($bydatesampm=='AM')
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                    }
                                }
                                
                                else /********* find out inbetween dates for perivous record****/
                                { 
                                     $bydatestartstatus="success";
                                }
                            }
                            else
                            {
                                if(empty($result))
                                {
                                    $bydatestartstatus="success";
                                }
                                else if(strtotime($dayoflastdate) == strtotime($begindate))
                                {                                   
                                    if($endampm[$i] == $bydatesampm)
                                    {
                                        if($endhour[$i] == $bydateshour)
                                        {
                                            if($endmin[$i] <= $smindaylight)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {  
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($bydatesampm=='AM')
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="success";
                                        }  
                                    }
                                }  
                                else if(strtotime($dayofbegindate) == strtotime($lastdate))
                                {
                                    if($startampm[$i] == $bydateeampm)
                                    {
                                        if($starthour[$i] == $bydateehour)
                                        {
                                            if($startmin[$i] <= $bydateemin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                    else
                                    {
                                        if($starthour[$i] == $bydateehour)
                                        {
                                            if($startmin[$i] >= $bydateemin)
                                            {
                                                $bydatestartstatus="success";
                                            }
                                            else
                                            {
                                                $bydatestartstatus="fail";
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {
                                            $bydatestartstatus="success";
                                        }
                                        else
                                        {
                                            $bydatestartstatus="fail";
                                        }
                                    }
                                }  
                                else 
                                {
                                    $bydatestartstatus="fail";
                                }
                            }

                            if($bydatestartstatus=='success')
                            { 
                                $innerflag=1;
                            }
                            else
                            {
                                $innerflag=0;                             
                            }                          
                          
                            /*********if the date and Time is not cross to start date and end date for ond day difference code start here ***********/
                            if($innerflag==1)
                            {
                                if($lcktype==1 || $lcktype==2 || $lcktype==3 || $lcktype==4)
                                {
                                    $reventforsday=array();
                                    if($lcktype==1)
                                    {
                                        for($b=0;$b<sizeof($wdayval);$b++)
                                        {
                                            if($wdayval[$b]==1){ $sweekday="Monday";   $eweekday="Monday";  } 
                                            else if($wdayval[$b]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; }
                                            else if($wdayval[$b]==3){ $sweekday="Wednesday"; $eweekday="Wednesday";  }
                                            else if($wdayval[$b]==4){ $sweekday="Thursday"; $eweekday="Thursday";  }
                                            else if($wdayval[$b]==5){ $sweekday="Friday"; $eweekday="Friday"; }
                                            else if($wdayval[$b]==6){ $sweekday="Saturday"; $eweekday="Saturday";  }
                                            else if($wdayval[$b]==7){ $sweekday="Sunday"; $eweekday="Sunday";  }

                                            if($wdaychkflag[$b]=='1')
                                            {
                                                $reventforsday[]=$sweekday;
                                                $reventforsday[]=$eweekday;
                                            }
                                        }
                                        array_push($reventforsday,$userstartdateconvert);
                                    }
                                    else if($lcktype==2) ///one date difference 
                                    {
                                        for($m=0;$m<sizeof($wdayval);$m++)
                                        {
                                            if($wdayval[$m]==1){ $sweekday="Monday";   $eweekday="Tuesday"; } 
                                            else if($wdayval[$m]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; }
                                            else if($wdayval[$m]==3){ $sweekday="Wednesday"; $eweekday="Thursday";}
                                            else if($wdayval[$m]==4){ $sweekday="Thursday"; $eweekday="Friday";  }
                                            else if($wdayval[$m]==5){ $sweekday="Friday"; $eweekday="Saturday"; }
                                            else if($wdayval[$m]==6){ $sweekday="Saturday"; $eweekday="Sunday"; }
                                            else if($wdayval[$m]==7){ $sweekday="Sunday"; $eweekday="Monday";  }

                                            if($wdaychkflag[$m]=='1')
                                            {    
                                                $reventforsday[]=$sweekday;
                                                $reventforsday[]=$eweekday;
                                            }
                                        }
                                        array_push($reventforsday,$userstartdateconvert,$userenddateconvert);
                                    }
                                    else if($lcktype==3) /// User select start date and end date is convert next date means
                                    {
                                        for($k=0;$k<sizeof($wdayval);$k++)
                                        {
                                            if($wdayval[$k]==1){ $sweekday="Tuesday";   $eweekday="Wednesday";  } 
                                            else if($wdayval[$k]==2){  $sweekday="Wednesday"; $eweekday="Thursday"; }
                                            else if($wdayval[$k]==3){  $sweekday="Thursday"; $eweekday="Friday";  }
                                            else if($wdayval[$k]==4){  $sweekday="Friday"; $eweekday="Saturday"; }
                                            else if($wdayval[$k]==5){  $sweekday="Saturday"; $eweekday="Sunday";  }
                                            else if($wdayval[$k]==6){  $sweekday="Sunday"; $eweekday="Monday"; }
                                            else if($wdayval[$k]==7){  $sweekday="Monday"; $eweekday="Tuesday"; }

                                            if($wdaychkflag[$k]=='1')
                                            {
                                                $reventforsday[]=$sweekday;
                                                $reventforsday[]=$eweekday;
                                            }
                                        }
                                        array_push($reventforsday,$userstartdateconvert,$userenddateconvert);
                                    }
                                    else if($lcktype==4)
                                    {
                                        for($z=0;$z<sizeof($wdayval);$z++)
                                        {
                                            if($wdayval[$z]==1){ $usersweekday="Monday";   $usereweekday="Monday"; $sweekday="Tuesday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                            else if($wdayval[$z]==2){ $usersweekday="Tuesday";   $usereweekday="Tuesday"; $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                            else if($wdayval[$z]==3){ $usersweekday="Wednesday";   $usereweekday="Wednesday"; $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                            else if($wdayval[$z]==4){ $usersweekday="Thursday";   $usereweekday="Thursday"; $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                            else if($wdayval[$z]==5){ $usersweekday="Friday";   $usereweekday="Friday"; $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                            else if($wdayval[$z]==6){ $usersweekday="Saturday";   $usereweekday="Saturday"; $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }
                                            else if($wdayval[$z]==7){ $usersweekday="Sunday";   $usereweekday="Sunday"; $sweekday="Monday"; $eweekday="Monday"; $eweekdayno=1; }

                                            if($wdaychkflag[$z]=='1')
                                            {
                                                 $reventforsday[]=$sweekday;
                                                 $reventforsday[]=$eweekday;
                                            }
                                         }
                                        array_push($reventforsday,$userstartdateconvert);
                                    }
                                    $repeateventunique= array_unique($reventforsday);
                                    $repeateventorder=array_values($repeateventunique);
                                   
                                    $userdayofstartdate=$repeateventorder[0];
                                    $userenddateofday=sizeof($repeateventorder)-1;
                                    $userdayoflastdate=$repeateventorder[$userenddateofday]; 
                                    
                                    $repeateventday=array();
                                    $qryrepeatevent= $ObjDB->QueryObject("SELECT fld_sday,fld_eday FROM itc_class_locakclassautomation_repeatevent WHERE fld_lock_id='".$lockclsid[$i]."' AND fld_delstatus='0'");
                                    if($qryrepeatevent->num_rows > 0)
                                    {	
                                        while($rowrepeatevent = $qryrepeatevent->fetch_assoc())
                                        {
                                            extract($rowrepeatevent);
                                            $repeateventday[]=$fld_sday;
                                            $repeateventday[]=$fld_eday;
                                        }
                                    }

                                    $repeatevent= array_unique($repeateventday);
                                    $repeateventd=array_values($repeatevent);                                   
                                    $enddateofdaydb=sizeof($repeateventd)-1;
                                    $dayofbegindatedb= $repeateventd[0];
                                    $dayoflastdatedb= $repeateventd[$enddateofdaydb];
                                    

                                    $repeateventintersect=array_intersect($repeateventd, $repeateventorder); //intersection
                                    $result=array_values($repeateventintersect);
                                    $repeateventcountforday=sizeof($result);                                    
                                   
                                    
                                    $dblcktype=$ObjDB->SelectSingleValueInt("SELECT fld_lock_type FROM itc_class_locakclassautomation_repeatevent WHERE fld_lock_id='".$lockclsid[$i]."' AND fld_delstatus='0'");
                                    $array_unique = array_unique($result);                                    
                                    $array_diff = array_diff_assoc($result, $array_unique);                                     
                                    
                                    if($dblcktype==1 || $dblcktype==4)
                                    {
                                        if($lcktype==1 || $lcktype==4)
                                        {
                                            if(empty($result))
                                            {
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {
                                                $dbsetime=array(); $dbeetime=array();
                                                $usetime=array(); $uesetime=array();                                                

                                                if($starttime_in_24_hour_format>=$endtime_in_24_hour_format)
                                                {
                                                    for($db=$starttime_in_24_hour_format; $db<=24; $db++) 
                                                    {  
                                                        if($db=='24')
                                                        {
                                                            $dbsetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $dbsetime[]=$db;
                                                        }
                                                    }                                                     
                                                    for($dbe=0; $dbe<=$endtime_in_24_hour_format; $dbe++) 
                                                    {  
                                                        if($dbe=='24')
                                                        {
                                                            $dbeetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $dbeetime[]=$dbe;
                                                        }
                                                    }                                                     
                                                    $dbssetime=array_merge($dbsetime, $dbeetime);//intersection
                                                    $dbfsetime=array_values(array_unique($dbssetime));//intersection
                                                }
                                                else
                                                {
                                                    for($db1=$starttime_in_24_hour_format; $db1<=$endtime_in_24_hour_format; $db1++) 
                                                    {  
                                                        $dbfsetime[]=$db1;
                                                    }                                                    
                                                }


                                                if($curstarttime_in_24_hour_format>=$curendtime_in_24_hour_format)
                                                {
                                                    for($dbus=$curstarttime_in_24_hour_format; $dbus<=24; $dbus++) 
                                                    {  
                                                        if($dbus=='24')
                                                        {
                                                            $usetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $usetime[]=$dbus;
                                                        }
                                                    }                                                     
                                                    for($dbue=0; $dbue<=$curendtime_in_24_hour_format; $dbue++) 
                                                    {  
                                                        if($dbue=='24')
                                                        {
                                                            $uesetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $uesetime[]=$dbue;
                                                        }

                                                    }                                                    
                                                    $useretime=array_merge($usetime, $uesetime);//intersection
                                                    $usersetime=array_values(array_unique($useretime));//intersection
                                                }
                                                else
                                                {
                                                    for($use=1; $use<=$curstarttime_in_24_hour_format; $use++) 
                                                    {  
                                                        $usersetime[]=$use;
                                                    } 
                                                }

                                                $dbusertime1=array_intersect($dbfsetime, $usersetime);//intersection                                              

                                                $dbuseresult1=array_values($dbusertime1);
                                                if(empty($dbuseresult1))
                                                {
                                                    $dinnerflag=1;
                                                }
                                                else
                                                { 
                                                    if($starttime_in_24_hour_format==$curendtime_in_24_hour_format)
                                                    {
                                                        if($startmin[$i] >= $emindaylight)
                                                        {
                                                            $dinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $dinnerflag=0;                                                           
                                                        }
                                                    }
                                                    else if($endtime_in_24_hour_format==$curstarttime_in_24_hour_format)
                                                    {
                                                        if($endmin[$i] <= $smindaylight)
                                                        {
                                                            $dinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $dinnerflag=0;                                                           
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $dinnerflag=0;                                                        
                                                    }
                                                }

                                                if($dinnerflag==1)
                                                {                                                   
                                                    $deepinnerflag=1;
                                                }
                                                else
                                                {                                                   
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }//Else Code End Here
                                        }
                                        else if($lcktype==2 || $lcktype==3)
                                        {
                                            if(empty($result))
                                            {
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {                                               
                                                if(empty($array_diff))
                                                {                                                    
                                                    if($repeateventcountforday==1)
                                                    { //Repeat Event Dates one Code start Here
                                                        if(strcmp($dayoflastdatedb, $result[0]) == 0)/// not working for last day  $endtime_in_24_hour_format
                                                        {                                                          
                                                            if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                            {   
                                                                if($endmin[$i] <= $smindaylight)
                                                                {
                                                                    $deepinnerflag=1;
                                                                }
                                                                else
                                                                {
                                                                    $deepinnerflag=0;
                                                                    break;
                                                                }
                                                            }
                                                            else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                            {   
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }
                                                       else if(strcmp($dayofbegindatedb, $result[0]) == 0) //Database start date is equal to repeated 
                                                        {                                                            
                                                            if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                            {   
                                                                if($startmin[$i] <= $emindaylight)
                                                                {
                                                                    $deepinnerflag=1;
                                                                }
                                                                else
                                                                {
                                                                    $deepinnerflag=0;
                                                                    break;
                                                                }
                                                            }
                                                            else if($starttime_in_24_hour_format <= $curendtime_in_24_hour_format)
                                                            {   
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }
                                                       
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    } //Repeat Event Dates one Code end Here
                                                    else 
                                                    {
                                                        $dbrepsday=array();
                                                        $dbrepeday=array();
                                                        $dblckrepeattype=array();
                                                        $qrylockclassrepeat= $ObjDB->QueryObject("SELECT fld_sday AS repeatsday,fld_eday AS repeateday,fld_lock_type AS lockrepeattype FROM itc_class_locakclassautomation_repeatevent
                                                                                                    WHERE fld_lock_id='".$lockclsid[$i]."' AND fld_delstatus='0'; ");

                                                        if($qrylockclassrepeat->num_rows > 0)
                                                        {													
                                                            while($rowlockclassrepeat = $qrylockclassrepeat->fetch_assoc())
                                                            {
                                                                extract($rowlockclassrepeat);

                                                                $dbrepsday[]=$repeatsday;
                                                                $dbrepeday[]=$repeateday;
                                                                $dblckrepeattype[]=$lockrepeattype;
                                                            }
                                                        }                                                       

                                                        $userreventforsday=array();
                                                        $userreventforeday=array();
                                                        if($lcktype==2) ///one date difference 
                                                        {
                                                            for($m=0;$m<sizeof($wdayval);$m++)
                                                            {
                                                                if($wdayval[$m]==1){ $sweekday="Monday";   $eweekday="Tuesday"; } 
                                                                else if($wdayval[$m]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; }
                                                                else if($wdayval[$m]==3){ $sweekday="Wednesday"; $eweekday="Thursday";}
                                                                else if($wdayval[$m]==4){ $sweekday="Thursday"; $eweekday="Friday";  }
                                                                else if($wdayval[$m]==5){ $sweekday="Friday"; $eweekday="Saturday"; }
                                                                else if($wdayval[$m]==6){ $sweekday="Saturday"; $eweekday="Sunday"; }
                                                                else if($wdayval[$m]==7){ $sweekday="Sunday"; $eweekday="Monday";  }

                                                                if($wdaychkflag[$m]=='1')
                                                                {    
                                                                    $userreventforsday[]=$sweekday;
                                                                    $userreventforeday[]=$eweekday;
                                                                }
                                                            }
                                                            array_push($userreventforsday,$userstartdateconvert);
                                                            array_push($userreventforeday,$userenddateconvert);
                                                        }
                                                        else if($lcktype==3)
                                                        {
                                                            for($i=0;$i<sizeof($wdayval);$i++)
                                                            {
                                                                if($wdayval[$i]==1){ $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                                                else if($wdayval[$i]==2){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                                                else if($wdayval[$i]==3){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                                                else if($wdayval[$i]==4){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                                                else if($wdayval[$i]==5){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                                                else if($wdayval[$i]==6){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                                                else if($wdayval[$i]==7){ $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }

                                                                if($wdaychkflag[$i]=='1')
                                                                {
                                                                    $userreventforsday[]=$sweekday;
                                                                    $userreventforeday[]=$eweekday;
                                                                }
                                                            }
                                                            array_push($userreventforsday,$userstartdateconvert);
                                                            array_push($userreventforeday,$userenddateconvert);
                                                        }

                                                        for($r=0;$r<sizeof($dbrepsday);$r++)
                                                        {
                                                            for($e=0;$e<sizeof($userreventforsday);$e++)
                                                            {                                                                
                                                                if(($userreventforsday[$e] == $dbrepsday[$r]) AND ($userreventforeday[$e] == $dbrepeday[$r]))
                                                                {
                                                                    $dbsetime=array(); $dbeetime=array();
                                                                    $usetime=array(); $uesetime=array();                                                                  
                                                                    
                                                                    if($starttime_in_24_hour_format>=$endtime_in_24_hour_format)
                                                                    {
                                                                        for($db=$starttime_in_24_hour_format; $db<=24; $db++) 
                                                                        {  
                                                                            if($db=='24')
                                                                            {
                                                                                $dbsetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $dbsetime[]=$db;
                                                                            }
                                                                        }                                                                         
                                                                        for($dbe=0; $dbe<=$endtime_in_24_hour_format; $dbe++) 
                                                                        {  
                                                                            if($dbe=='24')
                                                                            {
                                                                                $dbeetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $dbeetime[]=$dbe;
                                                                            }
                                                                        }                                                                         
                                                                        $dbssetime=array_merge($dbsetime, $dbeetime);//intersection
                                                                        $dbfsetime=array_values(array_unique($dbssetime));//intersection
                                                                    }
                                                                    else
                                                                    {
                                                                        for($db1=$starttime_in_24_hour_format; $db1<=$endtime_in_24_hour_format; $db1++) 
                                                                        {  
                                                                            $dbfsetime[]=$db1;
                                                                        }                                                                        
                                                                    }
                                                                    
                                                                    
                                                                    if($curstarttime_in_24_hour_format>=$curendtime_in_24_hour_format)
                                                                    {
                                                                        for($dbus=$curstarttime_in_24_hour_format; $dbus<=24; $dbus++) 
                                                                        {  
                                                                            if($dbus=='24')
                                                                            {
                                                                                $usetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $usetime[]=$dbus;
                                                                            }
                                                                        }                                                                        
                                                                        for($dbue=0; $dbue<=$curendtime_in_24_hour_format; $dbue++) 
                                                                        {  
                                                                            if($dbue=='24')
                                                                            {
                                                                                $uesetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $uesetime[]=$dbue;
                                                                            }

                                                                        }                                                                         
                                                                        $useretime=array_merge($usetime, $uesetime);//intersection
                                                                        $usersetime=array_values(array_unique($useretime));//intersection
                                                                    }
                                                                    else
                                                                    {
                                                                        for($use=curstarttime_in_24_hour_format; $use<=$curendtime_in_24_hour_format; $use++) 
                                                                        {  
                                                                            $usersetime[]=$use;
                                                                        } 
                                                                    }

                                                                    $dbusertime=array_intersect($dbfsetime, $usersetime);//intersection                                                                    
                                                                    $dbuseresult=array_values($dbusertime);
                                                                    if(empty($dbuseresult))
                                                                    {
                                                                        $deepinnerflag=1;
                                                                    }
                                                                    else
                                                                    {
                                                                        if($starttime_in_24_hour_format==$curendtime_in_24_hour_format)
                                                                        {
                                                                            if($startmin[$i] >= $emindaylight)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                        else if($endtime_in_24_hour_format==$curstarttime_in_24_hour_format)
                                                                        {
                                                                            if($endmin[$i] <= $smindaylight)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            $deepinnerflag=0;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                else if($userreventforsday[$e] == $dbrepeday[$r] or $userreventforeday[$e] == $dbrepsday[$r])
                                                                {  //start date is same as End week day or End date is same as Start week day
                                                                    if($dbrepeday[$r] == $userreventforsday[$e]) /*** bydate start date[ start day ] is equal to already stored in db byweek end week[ end day ] ***/
                                                                    {  
                                                                        if($endampm[$i] == $bydatesampm)
                                                                        {
                                                                            if($endhour[$i] == $bydateshour)
                                                                            {
                                                                                if($endmin[$i] <= $bydatesmin)
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                                            {
                                                                                $deepinnerflag=1;

                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        } //if condition end for $endampm
                                                                        else
                                                                        {
                                                                            if($bydateeampm=='AM')
                                                                            {
                                                                                if($startampm[$i]=='PM')
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                    } /*** bydate start date[ start day ] is equal to already stored in db byweek end week[ end day ] ***/
                                                                    else
                                                                    {
                                                                        if($startampm[$i] == $bydateeampm)
                                                                        {
                                                                            if($starthour[$i] == $bydateehour)
                                                                            {
                                                                                if($startmin[$i] >= $bydateemin)
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        } 
                                                                        else
                                                                        {
                                                                            if($bydateeampm=='AM')
                                                                            {
                                                                                if($startampm[$i]=='PM')
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                    }//else
                                                                }//if condition or
                                                            }//FOR $E
                                                            if($deepinnerflag==1)
                                                            {
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }//FOR $R
                                                    }//else code
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                    } //DB Lock Type 1 or 4
                                    else if($dblcktype==2 || $dblcktype==3)
                                    {
                                        if($lcktype==2 || $lcktype==3)
                                        {
                                            if(empty($result))
                                            {
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {                                               
                                                if(empty($array_diff))
                                                {                                             
                                                    if($repeateventcountforday==1)
                                                    { //Repeat Event Dates one Code start Here
                                                        if(strcmp($dayofbegindatedb, $result[0]) == 0) //Database start date is equal to repeated 
                                                        {                                                           
                                                            if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                                            {   
                                                                if($startmin[$i] >= $emindaylight)
                                                                {
                                                                    $deepinnerflag=1;
                                                                }
                                                                else
                                                                {
                                                                    $deepinnerflag=0;
                                                                    break;
                                                                }
                                                            }
                                                            else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                            {   
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }
                                                        else if(strcmp($dayoflastdatedb, $result[0]) == 0)/// not working for last day  $endtime_in_24_hour_format
                                                        {                                                            
                                                            if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                                            {   
                                                                if($endmin[$i] <= $smindaylight)
                                                                {
                                                                    $deepinnerflag=1;
                                                                }
                                                                else
                                                                {
                                                                    $deepinnerflag=0;
                                                                    break;
                                                                }
                                                            }
                                                            else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                            {   
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $deepinnerflag=0;
                                                            break;
                                                        }
                                                    } //Repeat Event Dates one Code end Here
                                                    else 
                                                    {
                                                        $dbrepsday=array();
                                                        $dbrepeday=array();
                                                        $dblckrepeattype=array();
                                                        $qrylockclassrepeat= $ObjDB->QueryObject("SELECT fld_sday AS repeatsday,fld_eday AS repeateday,fld_lock_type AS lockrepeattype FROM itc_class_locakclassautomation_repeatevent
                                                                                                    WHERE fld_lock_id='".$lockclsid[$i]."' AND fld_delstatus='0'; ");

                                                        if($qrylockclassrepeat->num_rows > 0)
                                                        {													
                                                            while($rowlockclassrepeat = $qrylockclassrepeat->fetch_assoc())
                                                            {
                                                                extract($rowlockclassrepeat);

                                                                $dbrepsday[]=$repeatsday;
                                                                $dbrepeday[]=$repeateday;
                                                                $dblckrepeattype[]=$lockrepeattype;
                                                            }
                                                        }
                                                       

                                                        $userreventforsday=array();
                                                        $userreventforeday=array();
                                                        if($lcktype==2) ///one date difference 
                                                        {
                                                            for($m=0;$m<sizeof($wdayval);$m++)
                                                            {
                                                                if($wdayval[$m]==1){ $sweekday="Monday";   $eweekday="Tuesday"; } 
                                                                else if($wdayval[$m]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; }
                                                                else if($wdayval[$m]==3){ $sweekday="Wednesday"; $eweekday="Thursday";}
                                                                else if($wdayval[$m]==4){ $sweekday="Thursday"; $eweekday="Friday";  }
                                                                else if($wdayval[$m]==5){ $sweekday="Friday"; $eweekday="Saturday"; }
                                                                else if($wdayval[$m]==6){ $sweekday="Saturday"; $eweekday="Sunday"; }
                                                                else if($wdayval[$m]==7){ $sweekday="Sunday"; $eweekday="Monday";  }

                                                                if($wdaychkflag[$m]=='1')
                                                                {    
                                                                    $userreventforsday[]=$sweekday;
                                                                    $userreventforeday[]=$eweekday;
                                                                }
                                                            }
                                                            array_push($userreventforsday,$userstartdateconvert);
                                                            array_push($userreventforeday,$userenddateconvert);
                                                        }
                                                        else if($lcktype==3)
                                                        {
                                                            for($i=0;$i<sizeof($wdayval);$i++)
                                                            {
                                                                if($wdayval[$i]==1){ $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                                                else if($wdayval[$i]==2){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                                                else if($wdayval[$i]==3){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                                                else if($wdayval[$i]==4){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                                                else if($wdayval[$i]==5){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                                                else if($wdayval[$i]==6){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                                                else if($wdayval[$i]==7){ $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }

                                                                if($wdaychkflag[$i]=='1')
                                                                {
                                                                    $userreventforsday[]=$sweekday;
                                                                    $userreventforeday[]=$eweekday;
                                                                }
                                                            }
                                                            array_push($userreventforsday,$userstartdateconvert);
                                                            array_push($userreventforeday,$userenddateconvert);
                                                        }                                                      
                                                        

                                                        for($r=0;$r<sizeof($dbrepsday);$r++)
                                                        {
                                                            for($e=0;$e<sizeof($userreventforsday);$e++)
                                                            {                                                                
                                                                if(($userreventforsday[$e] == $dbrepsday[$r]) AND ($userreventforeday[$e] == $dbrepeday[$r]))
                                                                {
                                                                    $dbsetime=array(); $dbeetime=array();
                                                                    $usetime=array(); $uesetime=array();                                                                    
                                                                    
                                                                    if($starttime_in_24_hour_format>=$endtime_in_24_hour_format)
                                                                    {
                                                                        for($db=$starttime_in_24_hour_format; $db<=24; $db++) 
                                                                        {  
                                                                            if($db=='24')
                                                                            {
                                                                                $dbsetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $dbsetime[]=$db;
                                                                            }
                                                                        }                                                                         
                                                                        for($dbe=0; $dbe<=$endtime_in_24_hour_format; $dbe++) 
                                                                        {  
                                                                            if($dbe=='24')
                                                                            {
                                                                                $dbeetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $dbeetime[]=$dbe;
                                                                            }
                                                                        }                                                                        
                                                                        $dbssetime=array_merge($dbsetime, $dbeetime);//intersection
                                                                        $dbfsetime=array_values(array_unique($dbssetime));//intersection
                                                                    }
                                                                    else
                                                                    {
                                                                        for($db1=$starttime_in_24_hour_format; $db1<=$endtime_in_24_hour_format; $db1++) 
                                                                        {  
                                                                            $dbfsetime[]=$db1;
                                                                        }                                                                        
                                                                    }
                                                                    
                                                                    
                                                                    if($curstarttime_in_24_hour_format>=$curendtime_in_24_hour_format)
                                                                    {
                                                                        for($dbus=$curstarttime_in_24_hour_format; $dbus<=24; $dbus++) 
                                                                        {  
                                                                            if($dbus=='24')
                                                                            {
                                                                                $usetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $usetime[]=$dbus;
                                                                            }
                                                                        }                                                                         
                                                                        for($dbue=0; $dbue<=$curendtime_in_24_hour_format; $dbue++) 
                                                                        {  
                                                                            if($dbue=='24')
                                                                            {
                                                                                $uesetime[]=0;
                                                                            }
                                                                            else
                                                                            {
                                                                                $uesetime[]=$dbue;
                                                                            }

                                                                        }                                                                         
                                                                        $useretime=array_merge($usetime, $uesetime);//intersection
                                                                        $usersetime=array_values(array_unique($useretime));//intersection
                                                                    }
                                                                    else
                                                                    {
                                                                        for($use=1; $use<=$curstarttime_in_24_hour_format; $use++) 
                                                                        {  
                                                                            $usersetime[]=$use;
                                                                        } 
                                                                    }

                                                                    $dbusertime=array_intersect($dbfsetime, $usersetime);//intersection                                                                    
                                                                    $dbuseresult=array_values($dbusertime);
                                                                    if(empty($dbuseresult))
                                                                    {
                                                                        $deepinnerflag=1;
                                                                    }
                                                                    else
                                                                    {
                                                                        if($starttime_in_24_hour_format==$curendtime_in_24_hour_format)
                                                                        {
                                                                            if($startmin[$i] >= $emindaylight)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                        else if($endtime_in_24_hour_format==$curstarttime_in_24_hour_format)
                                                                        {
                                                                            if($endmin[$i] <= $smindaylight)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            $deepinnerflag=0;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                else if($userreventforsday[$e] == $dbrepeday[$r] or $userreventforeday[$e] == $dbrepsday[$r])
                                                                {  //start date is same as End week day or End date is same as Start week day
                                                                    if($dbrepeday[$r] == $userreventforsday[$e]) /*** bydate start date[ start day ] is equal to already stored in db byweek end week[ end day ] ***/
                                                                    {  
                                                                        echo $endampm[$i]."==".$bydatesampm;
                                                                        if($endampm[$i] == $bydatesampm)
                                                                        {
                                                                            if($endhour[$i] == $bydateshour)
                                                                            {
                                                                                if($endmin[$i] <= $bydatesmin)
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                                                            {
                                                                                $deepinnerflag=1;

                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        } //if condition end for $endampm
                                                                        else
                                                                        {
                                                                            if($bydateeampm=='AM')
                                                                            {
                                                                                if($startampm[$i]=='PM')
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                    } /*** bydate start date[ start day ] is equal to already stored in db byweek end week[ end day ] ***/
                                                                    else
                                                                    {
                                                                        if($startampm[$i] == $bydateeampm)
                                                                        {
                                                                            if($starthour[$i] == $bydateehour)
                                                                            {
                                                                                if($startmin[$i] >= $bydateemin)
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                                                            {
                                                                                $deepinnerflag=1;
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        } 
                                                                        else
                                                                        {
                                                                            if($bydateeampm=='AM')
                                                                            {
                                                                                if($startampm[$i]=='PM')
                                                                                {
                                                                                    $deepinnerflag=1;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $deepinnerflag=0;
                                                                                    break;
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $deepinnerflag=0;
                                                                                break;
                                                                            }
                                                                        }
                                                                    }//else
                                                                }//if condition or
                                                            }//FOR $E
                                                            if($deepinnerflag==1)
                                                            {
                                                                $deepinnerflag=1;
                                                            }
                                                            else
                                                            {
                                                                $deepinnerflag=0;
                                                                break;
                                                            }
                                                        }//FOR $R
                                                    }//else code
                                                }
                                                else
                                                {
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }
                                        }
                                        else if($lcktype==1 || $lcktype==4)
                                        {
                                            if(empty($result))
                                            {
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {
                                                $dbsetime=array(); $dbeetime=array();
                                                $usetime=array(); $uesetime=array();                                                

                                                if($starttime_in_24_hour_format>=$endtime_in_24_hour_format)
                                                {
                                                    for($db=$starttime_in_24_hour_format; $db<=24; $db++) 
                                                    {  
                                                        if($db=='24')
                                                        {
                                                            $dbsetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $dbsetime[]=$db;
                                                        }
                                                    }                                                    
                                                    for($dbe=0; $dbe<=$endtime_in_24_hour_format; $dbe++) 
                                                    {  
                                                        if($dbe=='24')
                                                        {
                                                            $dbeetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $dbeetime[]=$dbe;
                                                        }
                                                    }                                                   
                                                    $dbssetime=array_merge($dbsetime, $dbeetime);//intersection
                                                    $dbfsetime=array_values(array_unique($dbssetime));//intersection                                                    
                                                }
                                                else
                                                {
                                                    for($db1=$starttime_in_24_hour_format; $db1<=$endtime_in_24_hour_format; $db1++) 
                                                    {  
                                                        $dbfsetime[]=$db1;
                                                    }                                                    
                                                }


                                                if($curstarttime_in_24_hour_format>=$curendtime_in_24_hour_format)
                                                {
                                                    for($dbus=$curstarttime_in_24_hour_format; $dbus<=24; $dbus++) 
                                                    {  
                                                        if($dbus=='24')
                                                        {
                                                            $usetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $usetime[]=$dbus;
                                                        }
                                                    }                                                   
                                                    for($dbue=0; $dbue<=$curendtime_in_24_hour_format; $dbue++) 
                                                    {  
                                                        if($dbue=='24')
                                                        {
                                                            $uesetime[]=0;
                                                        }
                                                        else
                                                        {
                                                            $uesetime[]=$dbue;
                                                        }

                                                    }                                                   
                                                    $useretime=array_merge($usetime, $uesetime);//intersection
                                                    $usersetime=array_values(array_unique($useretime));//intersection
                                                }
                                                else
                                                {
                                                    for($use=curstarttime_in_24_hour_format; $use<=$curendtime_in_24_hour_format; $use++) 
                                                    {  
                                                        $usersetime[]=$use;
                                                    }                                                    
                                                }

                                                $dbusertime1=array_intersect($dbfsetime, $usersetime);//intersection
                                               print_r($dbusertime1);

                                                $dbuseresult1=array_values($dbusertime1);
                                                if(empty($dbuseresult1))
                                                {
                                                    $dinnerflag=1;
                                                }
                                                else
                                                { 
                                                    if($starttime_in_24_hour_format==$curendtime_in_24_hour_format)
                                                    {
                                                        if($startmin[$i] >= $emindaylight)
                                                        {
                                                            $dinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $dinnerflag=0;                                                           
                                                        }
                                                    }
                                                    else if($endtime_in_24_hour_format==$curstarttime_in_24_hour_format)
                                                    {
                                                        if($endmin[$i] <= $smindaylight)
                                                        {
                                                            $dinnerflag=1;
                                                        }
                                                        else
                                                        {
                                                            $dinnerflag=0;                                                            
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $dinnerflag=0;                                                        
                                                    }
                                                }

                                                if($dinnerflag==1)
                                                {                                                    
                                                    $deepinnerflag=1;
                                                }
                                                else
                                                {                                                 
                                                    $deepinnerflag=0;
                                                    break;
                                                }
                                            }//Else Code End Here
                                        }
                                    }
                                    else
                                    {
                                       $deepinnerflag=1;
                                    }
                                        
                                }  
                                else if($lcktype==5)//no repeat event select
                                {                                    
                                    $deepinnerflag=1;
                                }
                            } /*********if the date and Time is not cross to start date and end date for ond day difference code start here ***********/
                            else
                            {
                                 $innerflag=0;                                
                                 break;
                            }
                        } /********Repeat Event and one date difference are correct  means code end here***********/
                        else
                        {
                            $innerflag=0;
                            break;
                        }
                    } //Type 1 Code end Here /*****************Working Fine for all types***************************/                   
                }//for loop end here
                
                
                if($flag == '1' &&  $innerflag=='1' && $deepinnerflag=='1') 
                {
                    if($byrowid == '0' or $byrowid == 'undefined')
                    {
                        if($lcktype==1)
                        {
                             $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                            (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                            fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                            fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                            fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                            fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                    VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                            '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."', '".$dayofstartdate1."','".$dayoflastdate1."', 
                                                                            '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                            '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                            '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Monday"; $eweekdayno=1; } 
                                else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; $eweekdayno=2; }
                                else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==2)
                        {
                           
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                            (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                            fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                            fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                            fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                            fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                    VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                            '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."','".$dayofstartdate1."','".$dayoflastdate1."', 
                                                                            '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                            '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                            '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')"); 
                            
                           
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }
                                
                                if($wdaychkflag[$i]=='1')
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==3)
                        {
                            
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."','".$dayofstartdate1."','".$dayoflastdate1."',
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");                             
                            
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Tuesday"; $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Wednesday"; $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Thursday"; $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Friday"; $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Saturday"; $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Sunday"; $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Monday"; $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==4)
                        {
                            
                            $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."','".$dayofstartdate1."','".$dayoflastdate1."',
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                            for($i=0;$i<sizeof($wdayval);$i++)
                            {
                                if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Monday"; $sweekday="Tuesday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Tuesday"; $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Wednesday"; $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Thursday"; $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Friday"; $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Saturday"; $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }
                                else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Sunday"; $sweekday="Monday"; $eweekday="Monday"; $eweekdayno=1; }

                                if($wdaychkflag[$i]=='1')
                                {
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                fld_created_by, fld_created_date, fld_lock_type)	
                                                      VALUES('".$lockid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                '".$uid."', '".$cdate."', '".$lcktype."')");

                                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                        SET fld_repeat_event='1' 
                                                            WHERE fld_id='".$lockid."' AND fld_delstatus='0'");

                                }
                            }//for loop
                            echo "success";
                        }
                        else if($lcktype==5)
                        {
                           
                              $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                                        (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                                        fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                                        fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                                        fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                                        fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                                                VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                                        '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."','".$dayofstartdate1."','".$dayoflastdate1."', 
                                                                        '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                                        '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                                        '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                              echo "success";
                        }
                    }
                    else
                    {
                        if($enableflag=='1')
                        {
                             $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_event_enableordisable='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0' ");
                            
                            
                             echo "success";
                        }
                        else
                        {
                            if($lcktype==1)
                            {
                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Monday"; $eweekdayno=1; } 
                                    else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Tuesday"; $eweekdayno=2; }
                                    else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no,  fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$sweekday."', fld_user_eday='".$eweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";

                            }
                            else if($lcktype==2)
                            {

                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $sweekday="Monday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $sweekday="Tuesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$sweekday."', '".$eweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$sweekday."', fld_user_eday='".$eweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";
                            }
                            else if($lcktype==3)
                            {

                                 $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Tuesday"; $sweekday="Tuesday";   $eweekday="Wednesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Wednesday"; $sweekday="Wednesday"; $eweekday="Thursday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Thursday"; $sweekday="Thursday"; $eweekday="Friday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Friday"; $sweekday="Friday"; $eweekday="Saturday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Saturday"; $sweekday="Saturday"; $eweekday="Sunday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Sunday"; $sweekday="Sunday"; $eweekday="Monday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Monday"; $sweekday="Monday"; $eweekday="Tuesday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                             $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                        (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date, fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$usersweekday."', fld_user_eday='".$usereweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";
                            }
                            else if($lcktype==4)
                            {
                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1' 
                                                 WHERE fld_lock_id='".$byrowid."'");                               
                                for($i=0;$i<sizeof($wdayval);$i++)
                                {
                                    if($wdayval[$i]==1){ $usersweekday="Monday";   $usereweekday="Monday"; $sweekday="Tuesday";   $eweekday="Tuesday"; $eweekdayno=2; } 
                                    else if($wdayval[$i]==2){ $usersweekday="Tuesday";   $usereweekday="Tuesday"; $sweekday="Wednesday"; $eweekday="Wednesday"; $eweekdayno=3; }
                                    else if($wdayval[$i]==3){ $usersweekday="Wednesday";   $usereweekday="Wednesday"; $sweekday="Thursday"; $eweekday="Thursday"; $eweekdayno=4; }
                                    else if($wdayval[$i]==4){ $usersweekday="Thursday";   $usereweekday="Thursday"; $sweekday="Friday"; $eweekday="Friday"; $eweekdayno=5; }
                                    else if($wdayval[$i]==5){ $usersweekday="Friday";   $usereweekday="Friday"; $sweekday="Saturday"; $eweekday="Saturday"; $eweekdayno=6; }
                                    else if($wdayval[$i]==6){ $usersweekday="Saturday";   $usereweekday="Saturday"; $sweekday="Sunday"; $eweekday="Sunday"; $eweekdayno=7; }
                                    else if($wdayval[$i]==7){ $usersweekday="Sunday";   $usereweekday="Sunday"; $sweekday="Monday"; $eweekday="Monday"; $eweekdayno=1; }


                                    if($wdaychkflag[$i]=='1')
                                    {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                                    FROM itc_class_locakclassautomation_repeatevent 
                                                                                    WHERE fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_sday_no='".$wdayval[$i]."'");

                                        if($cnt==0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_class_locakclassautomation_repeatevent
                                                                    (fld_lock_id, fld_class_id, fld_sday_no, fld_eday_no, fld_user_sday, fld_user_eday,
                                                                    fld_sday, fld_eday, fld_sday_stime, fld_eday_etime,
                                                                    fld_created_by, fld_created_date,  fld_lock_type)	
                                                          VALUES('".$byrowid."', '".$classid."', '".$wdayval[$i]."', '".$eweekdayno."', '".$usersweekday."', '".$usereweekday."',
                                                                    '".$sweekday."', '".$eweekday."', '".$stconvert."', '".$etconvert."', 
                                                                    '".$uid."', '".$cdate."', '".$lcktype."')");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                                SET fld_delstatus='0', fld_sday_no='".$wdayval[$i]."', fld_user_sday='".$usersweekday."', fld_user_eday='".$usereweekday."',
                                                                fld_eday_no='".$eweekdayno."', fld_sday='".$sweekday."', fld_eday='".$eweekday."', fld_sday_stime='".$stconvert."',
                                                                fld_eday_etime='".$etconvert."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_lock_type='".$lcktype."'
                                                                WHERE fld_id='".$cnt."' AND fld_lock_id='".$byrowid."' AND fld_class_id='".$classid."'");

                                            $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                                SET fld_repeat_event='1' 
                                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                                        }
                                    }
                                }//for loop

                                echo "success";
                            }
                            else if($lcktype==5)
                            {

                                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                                 $ObjDB->NonQuery("UPDATE itc_class_master 
                                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                            WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                 SET fld_delstatus='1', fld_lock_type='".$lcktype."'
                                                 WHERE fld_lock_id='".$byrowid."'");

                                echo "success";
                            }
                        }
                    }
                }
                else
                {
                    echo "fail";
                }
            }//if code end here
            /*********If the class has more then one record means code End here***********/
        }  // Day Range type 1 Repeat Event code end here
        else if($dayrange=='2') // Day Range type 2 more then one day code start here
        {
            $dayofstartdate1 = date('l', strtotime($bydatestartdate));
            $dayoflastdate1 = date('l', strtotime($bydateenddate));
            
            $lockclscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_event_enableordisable='1' $qry"); 

            $qrylockclass= $ObjDB->QueryObject("SELECT fld_id as lockclassid, fld_date_range as drange, fld_sdate_daylight AS sdate,fld_edate_daylight AS edate,fld_shour_daylight AS shour,fld_smin_daylight AS smin,fld_sampm_daylight as sampm,
                                                        fld_ehour_daylight AS ehour,fld_emin_daylight AS emin,fld_eampm_daylight AS eampm 
                                                        FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_event_enableordisable='1' $qry ");
         
            if($qrylockclass->num_rows > 0)
            {													
                while($rowlockclass = $qrylockclass->fetch_assoc())
                {
                    extract($rowlockclass);
                   
                    $startdate[]=$sdate;
                    $starthour[]=$shour;
                    $startmin[]=$smin;
                    $startampm[]=$sampm;
                    $enddate[]=$edate;
                    $endhour[]=$ehour;
                    $endmin[]=$emin;
                    $endampm[]=$eampm;
                    $lockclsid[]=$lockclassid;
                    $daterange[]=$drange;
                     
                }//while loop end
            }//if loop end
            else
            { //new record store to db and class have only one record that time it will be update[edit]
                if($byrowid == '0' or $byrowid == 'undefined')
                {
                   $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                        VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."', '".$dayofstartdate1."','".$dayoflastdate1."', 
                                                '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");
                    
                    echo "success";
                }
                else
                {
                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                        SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                            fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                            fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                            fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                            fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                            fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                            fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                        WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");
                    
                    
                    $ObjDB->NonQuery("UPDATE itc_class_master 
                                            SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                WHERE fld_id='".$classid."' AND fld_delstatus='0'");
                    
                    $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                            SET fld_delstatus='1', fld_updated_date='".$cdate."' 
                                                WHERE fld_class_id='".$classid."' AND fld_lock_id='".$byrowid."' AND fld_delstatus='0'");
                     
                    $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                            SET fld_repeat_event='0' 
                                                WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");
                   
                    echo "success";
                }
            }//else end
            
            if($lockclscount != '0') //no record for that perticular class
            {
                for($i=0;$i<sizeof($lockclsid);$i++) 
                {
                    
                    if(strlen($startmin[$i]) == '1')
                    {
                        $startmin[$i]='00';           
                    }
                    else
                    {
                        $startmin[$i]=$startmin[$i];             
                    }

                    if(strlen($endmin[$i]) == '1')
                    {
                        $endmin[$i]='00';           
                    }
                    else
                    {
                        $endmin[$i]=$endmin[$i];             
                    }

                    $bydateshour= date('h', $timestamp);
                    $bydatesmin= date('i', $timestamp);
                    $bydatesampm= date('A', $timestamp);

                    $bydateehour= date('h', $timestamp1);
                    $bydateemin= date('i', $timestamp1);
                    $bydateeampm= date('A', $timestamp1);


                    $starttime_in_24_hour_format  = date("H", strtotime($starthour[$i].":".$startmin[$i]." ".$startampm[$i]));
                    $endtime_in_24_hour_format  = date("H", strtotime($endhour[$i].":".$endmin[$i]." ".$endampm[$i]));                    
                 

                    $curstarttime_in_24_hour_format  = date("H", strtotime($bydateshour.":".$bydatesmin." ".$bydatesampm));
                    $curendtime_in_24_hour_format  = date("H", strtotime($bydateehour.":".$bydateemin." ".$bydateeampm));                   
                  
                    
                    /******* current date Start*******/ 
                        
                        $date_from= date('Y-m-d',strtotime($sdatetime));
                        $date_from = strtotime($date_from);                        
                        $date_to = date('Y-m-d',strtotime($edatetime));  
                        $date_to = strtotime($date_to);

                    $m=array();
                    $betweendates=array();
                    //count in between dates
                    for ($m=$date_from; $m<=$date_to; $m+=86400) 
                    {  
                        $betweendates[]=date("Y-m-d", $m);
                    }                    

                    $enddateofday=sizeof($betweendates)-1;
                    $begindate= $betweendates[0];
                    $lastdate= $betweendates[$enddateofday];
                    
                    $dayofstartdate1 = date('l', strtotime($begindate));
                    $dayoflastdate1 = date('l', strtotime($lastdate));

                /******* current date End*******/

                /*********Datebase date Sart*********/
                    $date_from1 = strtotime($startdate[$i]); 
                    $date_to1 = strtotime($enddate[$i]); 
                    $n=array();
                    $betweendates1=array();

                    //count in between dates
                    for ($n=$date_from1; $n<=$date_to1; $n+=86400) 
                    {  
                        $betweendates1[]=date("Y-m-d", $n);
                    }                  
                    $enddateofday=sizeof($betweendates1)-1;
                    $dayofbegindate= $betweendates1[0];
                    $dayoflastdate= $betweendates1[$enddateofday];
                /*********Datebase date Sart*********/
                    if($daterange[$i]=='1')
                    {                      
                        if(strtotime($dayoflastdate) == strtotime($begindate))
                        {                            
                            if($endampm[$i] == $bydatesampm)
                            {
                                if($endhour[$i] == $bydateshour)
                                {
                                    if($endmin[$i] <= $bydatesmin)
                                    {
                                        $innerflag=1;
                                    }
                                    else
                                    {
                                        $innerflag=0;                                      
                                    }
                                }
                                else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                {  
                                    $innerflag=1;
                                }
                                else
                                {
                                    $innerflag=0;                                   
                                }
                            } 
                            else
                            {
                                if($endampm[$i]=='AM')
                                {
                                    if($bydatesampm=='PM')
                                    {
                                        $innerflag=1;
                                    }
                                }
                                else
                                {
                                    if($bydatesampm=='AM')
                                    {
                                        $innerflag=0;                                        
                                    }
                                }
                            }
                        }
                        else if(strtotime($dayoflastdate) < strtotime($begindate))
                        {
                            $innerflag=1;
                        }
                        else if(strtotime($dayofbegindate) >= strtotime($lastdate))
                        {                           
                            if(strtotime($dayofbegindate) == strtotime($lastdate))
                            {                                
                                if($startampm[$i] == $bydateeampm)
                                {
                                    if($starthour[$i] == $bydateehour)
                                    {
                                        if($startmin[$i] >= $bydateemin)
                                        {
                                             $innerflag=2;
                                        }
                                        else
                                        {
                                            $innerflag=0;                                           
                                        }
                                    }
                                    else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                    {
                                        $innerflag=2;
                                    }
                                    else
                                    {
                                        $innerflag=0;                                        
                                    }
                                }
                                else
                                {
                                    if($startampm[$i]=='AM')
                                    {
                                        if($bydateeampm=='PM')
                                        {
                                            $innerflag=0;                                            
                                        }
                                    }
                                    else
                                    {
                                        if($bydateeampm=='AM')
                                        {
                                            $innerflag=2;
                                        }
                                    }
                                }
                            }
                            else 
                            {
                               $innerflag=2;
                            }
                        }                        
                        
                        if($innerflag=='1')
                        {
                            /******* current user date start*******/
                                $existday=array();
                                $countdates=sizeof($betweendates);
                                for($j=0;$j<sizeof($betweendates);$j++) 
                                {
                                //get the day for date
                                $date = $betweendates[$j];
                                $weekday = date('l', strtotime($date)); // note: first arg to date() is lower-case L
                                $existday[]=$weekday;
                                }                              

                                $userdayofstartdate= $existday[0];
                                $userenddateofday=sizeof($existday)-1;
                                $userdayoflastdate= $existday[$userenddateofday]; 

                            /******* current user date End*******/
                           
                            /*********Datebase date Sart*********/
                                $repeateventday=array();                            
                                $qryrepeatevent= $ObjDB->QueryObject("SELECT fld_sday,fld_eday FROM itc_class_locakclassautomation_repeatevent WHERE fld_lock_id='".$lockclsid[$i]."' AND fld_delstatus='0'");
                                if($qryrepeatevent->num_rows > 0)
                                {	
                                    while($rowrepeatevent = $qryrepeatevent->fetch_assoc())
                                    {
                                        extract($rowrepeatevent);
                                        $repeateventday[]=$fld_sday;
                                        $repeateventday[]=$fld_eday;
                                    }
                                }
                          
                                $repeatevent= array_unique($repeateventday);
                                $repeateventd=array_values($repeatevent);                               
                                $dbdayofstartdate1= $repeateventd[0];
                                $dbenddateofday1=sizeof($repeateventd)-1;
                                $dbdayoflastdate1=$repeateventd[$dbenddateofday1]; 
                            /*********Datebase date Sart*********/

                            $resultrepeat = array_intersect($repeateventd, $existday);//intersection                           
                            
                            $resultrepeat=array_values($resultrepeat);                           
                            $repeateventdaycount=sizeof($resultrepeat);
                             
                            if(empty($resultrepeat))
                            {
                                 $deepinnerflag=1;                              
                            }
                            else
                            {
                                if($repeateventdaycount=='1')
                                {                                   
                                    if(strcmp($dbdayofstartdate1, $resultrepeat[0]) == 0) //Database start date is equal to repeated 
                                    {                                       
                                        if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                        {   
                                            if($startmin[$i] >= $emindaylight)
                                            {                                     
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {                                               
                                                $deepinnerflag=0;
                                                break;
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {                                               
                                            $deepinnerflag=1;
                                        }
                                        else
                                        {                                           
                                            $deepinnerflag=0;
                                            break;
                                        }
                                    }
                                    else if(strcmp($dbdayoflastdate1, $resultrepeat[0]) == 0)//Database end date is equal to repeated 
                                    {                                        
                                        if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                        {   
                                            if($endmin[$i] <= $smindaylight)
                                            {                                               
                                                $deepinnerflag=1;
                                            }
                                            else
                                            {                                                
                                                $deepinnerflag=0;
                                                break;
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {                                               
                                            $deepinnerflag=1;
                                        }
                                        else
                                        {                                            
                                            $deepinnerflag=0;
                                            break;
                                        }
                                    }
                                    else
                                    {
                                         $deepinnerflag=0;
                                         break;
                                    }
                                }// If Condition End Here
                                else if($repeateventdaycount=='2')
                                {
                                    $countflag=0;
                                    if(strcmp($dbdayofstartdate1, $userdayoflastdate) == 0) //Database start date is equal to repeated 
                                    {                                        
                                        if($starttime_in_24_hour_format == $curendtime_in_24_hour_format)
                                        {   
                                            if($startmin[$i] >= $emindaylight)
                                            {                                                
                                                 $countflag+=1;
                                            }
                                            else
                                            {                                              
                                                $countflag+=0;                                             
                                            }
                                        }
                                        else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                        {                                               
                                             $countflag+=1;
                                        }
                                        else
                                        {                                            
                                            $countflag+=0;                                         
                                        }
                                    }
                                    
                                    if(strcmp($dbdayoflastdate1, $userdayofstartdate) == 0)//Database end date is equal to repeated  
                                    {                                       
                                        if($endtime_in_24_hour_format == $curstarttime_in_24_hour_format)
                                        {   
                                            if($endmin[$i] <= $smindaylight)
                                            {                                     
                                                 $countflag+=1;
                                            }
                                            else
                                            {                                            
                                                 $countflag+=0;                                             
                                            }
                                        }
                                        else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                        {                                               
                                             $countflag+=1;
                                        }
                                        else
                                        {                                            
                                            $countflag+=0;                                         
                                        }
                                    }
                                    
                                    if($countflag=='2')
                                    {
                                        $deepinnerflag=1;
                                    }
                                    else
                                    {
                                        $deepinnerflag=0;
                                        break;
                                    }
                                    
                                   
                                } // ELSE If Condition End Here
                                else
                                {
                                    $deepinnerflag=0;
                                    break;
                                }
                            }
                        }
                        else if($innerflag=='2')
                        {                           
                            $deepinnerflag=1;
                        }
                        else
                        {
                            $deepinnerflag=0;
                            break;
                        }
                    }
                    
                    else if($daterange[$i]=='2')
                    {                        
                        $result = array_intersect($betweendates1, $betweendates);//intersection                     
                        if(empty($result))
                        {
                            $deepinnerflag=1;
                        }
                        else if(strtotime($dayoflastdate) == strtotime($begindate))
                        {                           
                            if($endampm[$i] == $bydatesampm)
                            {
                                if($endhour[$i] == $bydateshour)
                                {
                                    if($endmin[$i] <= $bydatesmin)
                                    {
                                         $deepinnerflag=1;
                                    }
                                    else
                                    {
                                         $deepinnerflag=0;
                                         break;
                                    }
                                }                                
                                else if($endtime_in_24_hour_format <= $curstarttime_in_24_hour_format)
                                {  
                                   $deepinnerflag=1;
                                }
                                else
                                {
                                    $deepinnerflag=0;
                                    break;
                                }
                            }
                            else
                            {
                                if($bydatesampm=='AM')
                                {
                                    $deepinnerflag=0;
                                    break;
                                }
                                else
                                {
                                   $deepinnerflag=1;
                                }  
                            }
                        }  
                        else if(strtotime($dayofbegindate) == strtotime($lastdate))
                        {                            
                            if($startampm[$i] == $bydateeampm)
                            {
                                if($starthour[$i] == $bydateehour)
                                {
                                    if($startmin[$i] >= $bydateemin)
                                    {
                                        $deepinnerflag=1;
                                    }
                                    else
                                    {
                                        $deepinnerflag=0;
                                        break;
                                    }
                                }                                
                                else if($starttime_in_24_hour_format >= $curendtime_in_24_hour_format)
                                {
                                   $deepinnerflag=1;
                                }
                                else
                                {
                                    $deepinnerflag=0;
                                    break;
                                }
                            }
                            else
                            {
                                if($bydateeampm=='AM')
                                {
                                    $deepinnerflag=0;
                                    break;
                                }
                                else
                                {
                                    $deepinnerflag=1;
                                }  
                            }
                        }  
                        else 
                        {
                            $deepinnerflag=0;
                            break;
                        }
                    }//else if
                    
                }//for loop               
                
                if($deepinnerflag=='1')
                {
                    if($byrowid == '0' or $byrowid == 'undefined')
                    {
                       $lockid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_lockclassautomation
                                                    (fld_class_id, fld_date_range, fld_startdate, fld_enddate, 
                                                    fld_starthour, fld_endhour, fld_startmin, fld_endmin, fld_startampm, fld_endampm, fld_sdate_day, fld_edate_day,
                                                    fld_created_by, fld_created_date, fld_sdate, fld_edate, fld_stime, fld_etime, 
                                                    fld_timezone_type, fld_sdate_daylight, fld_shour_daylight, fld_smin_daylight, fld_sampm_daylight, fld_edate_daylight, 
                                                    fld_ehour_daylight, fld_emin_daylight, fld_eampm_daylight, fld_event_enableordisable)	
                                            VALUES('".$classid."', '".$dayrange."', '".date('Y-m-d',strtotime($bydatestartdate))."', '".date('Y-m-d',strtotime($bydateenddate))."',
                                                    '".$bydateshour1."', '".$bydateehour1."', '".$bydatesmin1."', '".$bydateemin1."', '".$bydatesampm1."','".$bydateeampm1."', '".$dayofstartdate1."','".$dayoflastdate1."', 
                                                    '".$uid."', '".$cdate."', '".$sdatetime."', '".$edatetime."', '".$stconvert."', '".$etconvert."', 
                                                    '".$timezonetype."', '".$sdatetime."', '".$shourdaylight."', '".$smindaylight."', '".$sampmdaylight."', '".$edatetime."',
                                                    '".$ehourdaylight."', '".$emindaylight."', '".$eampmdaylight."', '1')");

                        echo "success";
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                            SET fld_startdate='".date('Y-m-d',strtotime($bydatestartdate))."', fld_starthour='".$bydateshour1."', fld_startmin='".$bydatesmin1."', 
                                                fld_startampm='".$bydatesampm1."',fld_enddate='".date('Y-m-d',strtotime($bydateenddate))."' , fld_endhour='".$bydateehour1."', 
                                                fld_endmin='".$bydateemin1."', fld_endampm='".$bydateeampm1."' , fld_sdate='".$sdatetime."' , fld_edate='".$edatetime."', 
                                                fld_stime='".$stconvert."', fld_etime='".$etconvert."' , fld_timezone_type='".$timezonetype."', fld_sdate_daylight='".$sdatetime."',
                                                fld_shour_daylight='".$shourdaylight."', fld_smin_daylight='".$smindaylight."', fld_sampm_daylight='".$sampmdaylight."',
                                                fld_edate_daylight='".$edatetime."', fld_ehour_daylight='".$ehourdaylight."', fld_emin_daylight='".$emindaylight."', fld_event_enableordisable='1',
                                                fld_eampm_daylight='".$eampmdaylight."', fld_updated_by='".$uid."', fld_updated_date='".$cdate."', fld_flag='0', fld_date_range='".$dayrange."', fld_sdate_day='".$dayofstartdate1."', fld_edate_day='".$dayoflastdate1."'
                                            WHERE fld_id='".$byrowid."' AND fld_class_id='".$classid."' AND fld_delstatus='0'");


                        $ObjDB->NonQuery("UPDATE itc_class_master 
                                                SET fld_lock='0', fld_updated_date='".$cdate."' 
                                                    WHERE fld_id='".$classid."' AND fld_delstatus='0'");

                        $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent 
                                                SET fld_delstatus='1', fld_updated_date='".$cdate."' 
                                                    WHERE fld_class_id='".$classid."' AND fld_lock_id='".$byrowid."' AND fld_delstatus='0'");

                        $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation 
                                                SET fld_repeat_event='0' 
                                                    WHERE fld_id='".$byrowid."' AND fld_delstatus='0'");

                        echo "success";
                    }
                }
                else
                {
                     echo "fail";
                }
            }
        } // Day Range type 2 more then one day code end here
    }
    catch(Exception $e)
    {
        echo "fail";
    }
    
}
        
/*--- Delete the Lock Class statement ---*/
if($oper=="deleteelockclass" and $oper != " " )
{
    try
    {
        $lockid= isset($method['lockclassid']) ? $method['lockclassid'] : '';
        $clsid= isset($method['classid']) ? $method['classid'] : '0';
        $delordisable= isset($method['delordisable']) ? $method['delordisable'] : '0';
            
        $flag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_class_lockclassautomation WHERE fld_id='".$lockid."'");

        $validate_assetid=true;
        if($rowid!=0)  $validate_assetid=validate_datatype($rowid,'int');
        if($validate_assetid)
        {
            if($delordisable=='1')///Event Disable
            {
                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                         SET fld_event_enableordisable='0', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
                                         WHERE fld_id='".$lockid."' AND fld_class_id='".$clsid."'");
                
                $ObjDB->NonQuery("UPDATE itc_class_locakclassautomation_repeatevent
                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
                                         WHERE fld_id='".$lockid."' AND fld_class_id='".$clsid."'");
                
            
            }
            else //Event Delete
            {
                $ObjDB->NonQuery("UPDATE itc_class_lockclassautomation
                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
                                         WHERE fld_id='".$lockid."' AND fld_class_id='".$clsid."'");
            }
            
            if($flag==1)
            {
                $ObjDB->NonQuery("UPDATE itc_class_master SET fld_lock='0' WHERE fld_id='".$clsid."'");
            }
        }
        else
        {
                echo "fail";	
        }
    }
    catch(Exception $e)
    {
            echo "fail";
    }

    echo "success";
}
/*--- Delete the Lock Class statement ---*/

/*******Edit Lock Class *******/
if($oper=="bydateform" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '0'; 
    $type = isset($method['type']) ? $method['type'] : '0';    
    $rowid = isset($method['rowid']) ? $method['rowid'] : '0'; 
   
        $qrylockclass= $ObjDB->QueryObject("SELECT fld_id as lockclassid, fld_date_range as drange, fld_startdate AS sdate, fld_enddate AS edate, fld_starthour AS shour ,fld_startmin AS smin, fld_startampm as sampm,
                                                fld_endhour AS ehour, fld_endmin AS emin, fld_endampm AS eampm 
                                                FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_id='".$rowid."' AND fld_delstatus='0'");

        if($qrylockclass->num_rows > 0)
        {													
            while($rowlockclass = $qrylockclass->fetch_assoc())
            {
                extract($rowlockclass);

                $startdate=$sdate;
                $bydateshour=$shour;
                $bydatesmin=$smin;
                $bydatesampm=$sampm;
                $enddate=$edate;
                $bydateehour=$ehour;
                $bydateemin=$emin;
                $bydateeampm=$eampm;

                $stardate= date('m/d/Y',strtotime($startdate));
                $endate= date('m/d/Y',strtotime($enddate));

                    ?> 
                    <div class="row" id="bydate1">
                       <div class="bydate" id="Types1">
                            <div class='three columns'>
                                Start Date
                               <dl class='field row'>
                                   <dt class='text'>
                                        <input  id="startdate" name="startdate" class="quantity" placeholder='Start Date'type='text'  readonly="readonly" value="<?php echo $stardate;?>" >
                                   </dt>                                        
                               </dl>
                            </div>
                             <div class='one columns'>
                                 <p style="margin-left: -4px;">    Hour</p>
                                 <dt class='dropdown1'  style="margin-left: -5px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydateshour" id="bydateshour" value="<?php echo $bydateshour; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span id="bydateshr" class="selectbox-option input-medium" data-option="<?php echo $bydateshour; ?>" id="clearsubject" style="width:90%;"><?php  if(strlen($bydateshour)==1){ echo "0".$bydateshour;}else{ echo $bydateshour; } ?></span>
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
                                            <input type="hidden" name="bydatesmin" id="bydatesmin" value="<?php echo $bydatesmin; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                            <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $bydatesmin; ?>" id="clearsubject" style="width:90%;"><?php  if(strlen($bydatesmin)==1){ echo "0".$bydatesmin;}else{ echo $bydatesmin; } ?></span>
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
                                        <input type="hidden" name="bydatesampm" id="bydatesampm" value="<?php echo $bydatesampm; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydatesampm; ?>" id="clearsubject" style="width:90%;"><?php echo $bydatesampm; ?></span>
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
                                      <input placeholder='End Date' required='' type='text' id="enddate" name="enddate" readonly="readonly" value="<?php echo $endate;?>">
                                    </dt>                                        
                                </dl>
                            </div>
                         <div class='one columns'>
                             <p style="margin-left: -6px;">  Hour</p>
                                 <dt class='dropdown1 ' style="margin-left: -5px;">   
                                    <div class="selectbox" >
                                        <input type="hidden" name="bydateehour" id="bydateehour" value="<?php echo $bydateehour; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydateehour; ?>" id="clearsubject" style="width:90%;"><?php  if(strlen($bydateehour)==1){ echo "0".$bydateehour;}else{ echo $bydateehour; } ?></span>
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
                                            <input type="hidden" name="bydateemin" id="bydateemin" value="<?php echo $bydateemin; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                            <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $bydateemin; ?>" id="clearsubject" style="width:90%;"><?php  if(strlen($bydateemin)==1){ echo "0".$bydateemin;}else{ echo $bydateemin; } ?></span>
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
                                        <input type="hidden" name="bydateeampm" id="bydateeampm" value="<?php echo $bydateeampm; ?>"  onchange="$(this).valid();fn_dayrange(2);" />
                                        <a class="selectbox-toggle1" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $bydateeampm; ?>" id="clearsubject" style="width:90%;"><?php echo $bydateeampm; ?></span>
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
                        <input type="hidden" name="byrowid" id="byrowid" value="<?php echo $rowid; ?>"/>
                    </div> 
                <?php 
                if($drange=='1')
                { 
                    ?>
                    <!-- ************ Repeat Event Code Start Here *************** --> 
                    <div class="row" id="repeatevent" style="">
                        <div class='three columns'>
                               <p style="margin-top: 31px;"> Repeat Event</p>
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
                                        <div <?php if($m==1){ ?> style="margin-left: -32px; margin-top:10px;" <?php }else{ ?>style="margin-left: 108px;" <?php }?>>
                                            <input type="checkbox" id="wdaychke<?php echo $m; ?>" value="<?php echo $m; ?>" />
                                            <?php echo $weekday; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            } ?>
                        </div>
                    </div>
                    <?php
                    
                    $qryrepeatevent= $ObjDB->QueryObject("SELECT fld_sday_no FROM itc_class_locakclassautomation_repeatevent WHERE fld_lock_id='".$rowid."' AND fld_delstatus='0'");
                    if($qryrepeatevent->num_rows > 0)
                    {	
                        while($rowrepeatevent = $qryrepeatevent->fetch_assoc())
                        {
                            extract($rowrepeatevent);
                            ?>
                            <script>
                                $('#wdaychke'+<?php echo $fld_sday_no; ?>).prop('checked', true);
                            </script>

                            <?php
                        }
                    }
                }//Repeat Week
            } //while loop end
        }//if loop end    
    ?>
    
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
                fn_dayrange();
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
}
/*******Edit Lock Class *******/

if($oper=="hiddenval" and $oper != " " )
{
    $timestamp = date("Y-m-d H:i:s");
    
    $sda=date('Y-m-d',strtotime($timestamp));
    $dstval=$ObjDB->SelectSingleValueInt("SELECT fld_dst_differ FROM itc_zdst_day_mapping WHERE fld_date='".$sda."'");
       
    $timezonetype = isset($method['timezonetype']) ? $method['timezonetype'] : '0'; 
    
    if($timezonetype==1)
    {
        if($dstval==1)
        {
            $val =8;
        }
        else
        {
            $val =7;
        }
       
    }
    else if($timezonetype==2)
    {
        if($dstval==1)
        {
            $val =7;
        }
        else
        {
            $val =6;
        }
    }
    else if($timezonetype==3)
    {
        if($dstval==1)
        {
            $val =6;
        }
        else
        {
            $val =5;
        }
    }
    else
    {
        if($dstval==1)
        {
            $val =5;
        }
        else
        {
            $val =4;
        }
    }
    
    $timestamp = date("Y-m-d H:i:s");
    $timestamp = strtotime($timestamp);
    $timestamp -= $val * 3600;
    $startdatecst=date('Y-m-d', $timestamp);
    $sdatecsttimeh= date('h', $timestamp);
    $sdatecsttimem= date('i', $timestamp);
    $sdatecsttimea= date('A', $timestamp);

    $sdatecstday = date('l', strtotime($startdatecst));
    
    
    echo "success~".$startdatecst."~".$sdatecsttimeh."~".$sdatecsttimem."~".$sdatecsttimea."~".$sdatecstday;
}



if($oper=="showrepeatweek" and $oper != " " )
{
    $sweekday = isset($method['sweekday']) ? $method['sweekday'] : '0'; 
    $eweekday = isset($method['eweekday']) ? $method['eweekday'] : '0';    
    $sdayoreday = isset($method['sdayoreday']) ? $method['sdayoreday'] : '0';   
    
    
        if($sweekday>$eweekday)
        {
            $b=0;                    
            $eday=$eweekday;
            $sdateofday=$sweekday;
            for($i=$sdateofday;$i<7;$i++)
            {
                $b=$b+1;
            }
            $edateofday= $b+$sdateofday+$eday;
            $sdateofday=$sweekday;           
            $qrylockclassdays= $ObjDB->QueryObject("SELECT fld_day as classdays FROM itc_class_lockclassautomation_days WHERE fld_id BETWEEN $sdateofday AND $edateofday;");  

        }
        else
        {
            $sweekday=$sweekday;
            $qrylockclassdays= $ObjDB->QueryObject("SELECT fld_day as classdays FROM itc_class_lockclassautomation_days WHERE fld_id BETWEEN $sweekday AND $eweekday;");  
        }
    
     
    if($qrylockclassdays->num_rows > 0)
    {													
        while($rowlockclassdays = $qrylockclassdays->fetch_assoc())
        {
            extract($rowlockclassdays);
            $existwekdays[]=$classdays;
        }
    }     
    
    
    $sday = array("Sunday");
    $mday = array("Monday");
    $tday = array("Tuesday");
    $wday = array("Wednesday");
    $thday = array("Thursday");
    $fday = array("Friday");
    $saday = array("Saturday");
    
    $sunday = array_intersect($existwekdays, $sday); 
    $monday = array_intersect($existwekdays, $mday);
    $tuesday = array_intersect($existwekdays, $tday);
    $wednesday = array_intersect($existwekdays, $wday);
    $thursday = array_intersect($existwekdays, $thday);
    $friday = array_intersect($existwekdays, $fday);
    $saturday = array_intersect($existwekdays, $saday);

    if(!empty($sunday)){ $sflag=1; } else{ $sflag=0; }
    if(!empty($monday)){ $mflag=1; } else{ $mflag=0; }
    if(!empty($tuesday)){ $tflag=1; } else{ $tflag=0; } 
    if(!empty($wednesday)){ $wflag=1; } else{ $wflag=0; }
    if(!empty($thursday)){ $thflag=1; } else{ $thflag=0; }
    if(!empty($friday)){ $fflag=1; } else{ $fflag=0; } 
    if(!empty($saturday)){ $saflag=1; } else{ $saflag=0; }     
    

    ?>
Repeat Event 
    <input type="button" id="btnstep1" class="darkButton" <?php if($sflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?>value="<?php echo "S";?>" />
    <input type="button" id="btnstep2" class="darkButton" <?php if($mflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?>value="<?php echo "M";?>" />
    <input type="button" id="btnstep3" class="darkButton" <?php if($tflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?>value="<?php echo "T";?>" />
    <input type="button" id="btnstep4" class="darkButton" <?php if($wflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?>value="<?php echo "W";?>" />
    <input type="button" id="btnstep5" class="darkButton" <?php if($thflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?>value="<?php echo "T";?>" />
    <input type="button" id="btnstep6" class="darkButton" <?php if($fflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?> value="<?php echo "F";?>" />
    <input type="button" id="btnstep7" class="darkButton" <?php if($saflag=='1'){ ?>style="width:39px; height:35px;margin-left: 10px; background:#6EB7FF;"<?php } else {?> style="width:39px; height:35px;margin-left: 10px;"<?php } ?> value="<?php echo "S";?>" />


    <?php 
}

/*--- Date Range for Lock Class statement ---*/
if($oper=="calculatedayrange" and $oper != " " )
{
    try
    {
		
		$bydatestartdate = isset($method['startdate']) ? ($method['startdate']) : ''; 
        $bydateshour = isset($method['bydateshour']) ? $method['bydateshour'] : ''; 
        $bydatesmin = isset($method['bydatesmin']) ? $method['bydatesmin'] : ''; 
        $bydatesampm = isset($method['bydatesampm']) ? $method['bydatesampm'] : ''; 
        
        $bydateenddate = isset($method['enddate']) ? $method['enddate'] : ''; 
        $bydateehour = isset($method['bydateehour']) ? $method['bydateehour'] : ''; 
        $bydateemin = isset($method['bydateemin']) ? $method['bydateemin'] : ''; 
        $bydateeampm = isset($method['bydateeampm']) ? $method['bydateeampm'] : '';
        
        $timezonetype = isset($method['timezone']) ? $method['timezone'] : '0'; 
        
        /****** If the date is day light saving time or not code start here*********/
       
        //Start date
        $stimestamps=date('Y-m-d',strtotime($bydatestartdate));
        $stimestamps = strtotime($stimestamps);

        $sstartdatedl=date('d', $stimestamps);
        $sstartmonthdl=date('m', $stimestamps);
        $sstartyeardl=date('Y', $stimestamps);

        //End date
        $etimestamps=date('Y-m-d',strtotime($bydateenddate));
        $etimestamps = strtotime($etimestamps);

        $estartdatedl=date('d', $etimestamps);
        $estartmonthdl=date('m', $etimestamps);
        $estartyeardl=date('Y', $etimestamps);            
        
        //Get the Day light saving time start and end date for the year
        $dstsdate=$ObjDB->SelectSingleValueInt("SELECT fld_sdate FROM itc_zdst_master WHERE fld_year='".$sstartyeardl."'"); 
        $dstedate=$ObjDB->SelectSingleValueInt("SELECT fld_edate FROM itc_zdst_master WHERE fld_year='".$estartyeardl."'");

        /*******Get the month name code start here***************/
        switch ($sstartmonthdl) 
        {
             case "1":
                 $smon="Jan";
                 break;
             case "2":
                 $smon="Feb";
                 break;
             case "3":
                 $smon="Mar";
                 break;
              case "4":
                 $smon="Apr";
                 break;
             case "5":
                 $smon="May";
                 break;
             case "6":
                 $smon="Jun";
                 break;
              case "7":
                 $smon="Jul";
                 break;
             case "8":
                 $smon="Aug";
                 break;
             case "9":
                 $smon="Sep";
                 break;
              case "10":
                 $smon="Oct";
                 break;
             case "11":
                 $smon="Nov";
                 break;
             case "12":
                 $smon="Dec";
                 break;
             default:
                 $smon="Jan";
        }

        switch ($estartmonthdl) 
        {
             case "1":
                 $emon="Jan";
                 break;
             case "2":
                 $emon="Feb";
                 break;
             case "3":
                 $emon="Mar";
                 break;
              case "4":
                 $emon="Apr";
                 break;
             case "5":
                 $emon="May";
                 break;
             case "6":
                 $emon="Jun";
                 break;
              case "7":
                 $emon="Jul";
                 break;
             case "8":
                 $emon="Aug";
                 break;
             case "9":
                 $emon="Sep";
                 break;
              case "10":
                 $emon="Oct";
                 break;
             case "11":
                 $emon="Nov";
                 break;
             case "12":
                 $emon="Dec";
                 break;
             default:
                 $emon="Jan";
        }

        /*******Get the month name code End here***************/      
        
        
        /***********Start Date is daylight saving time or not code start here****************/
        if($smon=='Mar' || $smon=='Apr' || $smon=='May' || $smon=='June' || $smon=='July' || $smon=='Aug' || $smon=='Sep' || $smon=='Oct')
        {
            if($smon=='Mar')
            {
                if($dstsdate<=$sstartdatedl)
                {
                    $sdaylight="-5";
                }
                else
                {
                    $sdaylight="-6";
                }
            }
            else
            {
                $sdaylight="-5";
            }
        }

        if($smon=='Nov')
        {
            if($dstedate>$sstartdatedl)
            {
                $sdaylight="-5";
            }
            else
            {
                $sdaylight="-6";
            }
        }

        if($smon=='Dec' || $smon=='Jan' || $smon=='Feb')
        {
             $sdaylight="-6";
        }
        /***********Start Date is daylight saving time or not code end here****************/

        
        
        /***********End Date is daylight saving time or not code start here****************/
        if($emon=='Mar' || $emon=='Apr' || $emon=='May' || $emon=='June' || $emon=='July' || $emon=='Aug' || $emon=='Sep' || $emon=='Oct')
        {
            if($emon=='Mar')
            {
                if($dstsdate<=$estartdatedl)
                {
                    $edaylight="-5";
                }
                else
                {
                    $edaylight="-6";
                }
            }
            else
            {
                $edaylight="-5";
            }
        }

        if($emon=='Nov')
        {
            if($dstedate>$estartdatedl)
            {
                $edaylight="-5";
            }
            else
            {
                $edaylight="-6";
            }

        }

        if($emon=='Dec' || $emon=='Jan' || $emon=='Feb')
        {
             $edaylight="-6";
        }
        /***********End Date is daylight saving time or not code End here****************/

        if($sdaylight=='-6')
        {
            $sdaylightornot=1;
        }
        else 
        {
            $sdaylightornot=0;
        }

        if($edaylight=='-6')
        {
            $edaylightornot=1;
        }
        else 
        {
            $edaylightornot=0;
        }

        if($timezonetype==1)
        {
            $sval=$sdaylightornot+7;
            $eval=$edaylightornot+7;
        }
        else if($timezonetype==2)
        {
            $sval=$sdaylightornot+6;
            $eval=$edaylightornot+6;
        }
        else if($timezonetype==3)
        {
            $sval=$sdaylightornot+5;
            $eval=$edaylightornot+5;
        }
        else
        {
            $sval=$sdaylightornot+4;
            $eval=$edaylightornot+4;
        }        
       

        /*******Start Date*******/
        $sda=date('Y-m-d',strtotime($bydatestartdate));
        $timeconvert=$bydateshour.":".$bydatesmin." ".$bydatesampm;
        $stconvert1= date("H:i:s", strtotime($timeconvert));
        /*Convert  CST to UTC  */
        $timestamp = strtotime($sda." ".$stconvert1);
        $timestamp +=  $sval*3600;
        /*Convert  CST to UTC  */
        $sdatetime=date('Y-m-d', $timestamp);
        $stconvert=date('H:i:s', $timestamp);

        $shourdaylight= date('h', $timestamp);
        $smindaylight= date('i', $timestamp);
        $sampmdaylight= date('A', $timestamp);

        /*******End Date*******/
        $eda=date('Y-m-d',strtotime($bydateenddate));
        $timeconvert1=$bydateehour.":".$bydateemin." ".$bydateeampm;
        $etconvert1= date("H:i:s", strtotime($timeconvert1));
         /*Convert  CST to UTC  */
        $timestamp1 = strtotime($eda." ".$etconvert1);
        $timestamp1 += $eval*3600;
        /*Convert  CST to UTC  */
        $edatetime=date('Y-m-d', $timestamp1);
        $etconvert=date('H:i:s', $timestamp1);

        $ehourdaylight= date('h', $timestamp1);
        $emindaylight= date('i', $timestamp1);
        $eampmdaylight= date('A', $timestamp1);

        /********insert original value for show********/
        $bydatestartdate1=$bydatestartdate;  $bydateenddate1=$bydateenddate;
        $bydateshour1=$bydateshour;          $bydateehour1=$bydateehour;
        $bydatesmin1=$bydatesmin;            $bydateemin1=$bydateemin;   
        $bydatesampm1=$bydatesampm;          $bydateeampm1=$bydateeampm;
            
        /****** If the date is day light saving time or not code end here*********/
         
		$date_from= date('Y-m-d',strtotime($sdatetime));
		$date_from = strtotime($date_from);
		$date_to = date('Y-m-d',strtotime($edatetime));;  
		$date_to = strtotime($date_to);
		$betweendates=array();
		$m=array();
		//count in between dates
		for ($m=$date_from; $m<=$date_to; $m+=86400)
		{  
			$betweendates[]=date("Y-m-d", $m);
		} 
		$existday=array();
		for($j=0;$j<sizeof($betweendates);$j++) 
		{
			//get the day for date
			$date = $betweendates[$j];
			$weekday = date('l', strtotime($date)); // note: first arg to date() is lower-case L
			$existday[]=$weekday;
		}                
		
        
        
        
        $date_from= date('Y-m-d',strtotime($sdatetime));
        $date_to = date('Y-m-d',strtotime($edatetime));;  
        
        $date1 = new DateTime($date_from."T".$stconvert);
        $date2 = new DateTime($date_to."T".$etconvert);

        $diff = $date2->diff($date1);
        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);
        $minutes  = (($diff->i /60) * 60);
        
        echo "success~".$hours."~". $minutes."~".sizeof($existday);

        

    }
    catch(Exception $e)
    {           
    }

    
}
/*--- Date Range for Lock Class statement ---*/


