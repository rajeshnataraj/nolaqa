<?php
@include("sessioncheck.php");
$date=date("Y-m-d");
?>
<section data-type='2home' id='sos-library'>
    <script type="text/javascript" charset="utf-8">		
		$.getScript("sos/library/sos-library.js");	
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle">Unit</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
			<?php
                        
                        
                        $qrymenuname = $ObjDB->QueryObject("SELECT a.fld_id as unitid,a.fld_unit_name as unitname,a.fld_unit_icon as uicon
                                                                    FROM itc_sosunit_master as a
                                                                    LEFT JOIN itc_license_sosunit_mapping as b ON a.fld_id=b.fld_unit_id
                                                                    LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                                                    WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' 
                                                                    AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_access='1'
                                                                    AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' GROUP BY unitid ORDER BY unitname ");

                        echo '<script>
                            console.log("SELECT a.fld_id as unitid,a.fld_unit_name as unitname,a.fld_unit_icon as uicon FROM itc_sosunit_master as a LEFT JOIN itc_license_sosunit_mapping as b ON a.fld_id=b.fld_unit_id LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id WHERE a.fld_delstatus=\'0\' AND c.fld_school_id=\'" + '. $schoolid .' + "\' AND c.fld_user_id=\' + '. $indid .' + \' AND c.fld_delstatus=\'0\' AND b.fld_access=\'1\' AND c.fld_start_date<=\'" + '. $date .' + "\' AND c.fld_end_date>=\'" + '. $date .' + "\' GROUP BY unitid ORDER BY unitname ");
                        </script>';
                        if($qrymenuname->num_rows > 0){ 
													
				while($rowmenuname = $qrymenuname->fetch_assoc())
				{
					extract($rowmenuname);
			?>
             <a class='skip btn mainBtn' href='#sos-library-showphases' id='btnsos-library-showphases' name='<?php echo $unitid;?>'>            
						
                                                <div class="icon-synergy-user">
                                                <?php  if($uicon != "no-image.png" && $uicon != ''){ 
                                                    ?>
                                                    <img class="thumbimg" src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$uicon; ?>&w=40&h=40&q=100" />
                                                <?php } ?>
                                            </div>
						<div class='onBtn tooltip'><?php echo ucfirst($unitname);?></div>
					</a>
			<?php
				}
                        }
                        else{
                            
                        }
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
