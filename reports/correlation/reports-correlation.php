<?php
@include("sessioncheck.php");
?>
<section data-type='2home' id='reports-correlation'>
	<script language="javascript">
   		$.getScript("reports/correlation/reports-correlation.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Correlation Report</p>
               <p class="dialogSubTitleLight">Select a report to view or print. Select New Report to create your own.</p>
                 <div class="row rowspacer"></div>
            </div>
        </div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#correlation-steps' id='btnreports-correlation-steps' name="0,1">
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New Report</div>
            </a>
            <?php if($sessmasterprfid==8 or $sessmasterprfid==9 ) {?>
            <a class='skip btn mainBtn' href='#correlation-reports' id='btnreports-correlation-request' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>Request<br /> Correlation</div>
            </a>             
            <?php } 
			$qry = $ObjDB->QueryObject("SELECT fld_id AS crptid, fld_title AS crpttitle, fn_shortname(fld_title,1) AS shortname 
										FROM itc_correlation_report_data 
										WHERE fld_delstatus=0 AND fld_created_by='".$uid."'");
            while($res=$qry->fetch_assoc()){
				extract($res);
				?>
				<a class='skip btn mainBtn' href='#correlation-reports' id='btnreports-correlation-actions' name="<?php echo $crptid.",".$crpttitle;?>">
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn' title="<?php echo $crpttitle; ?>"><?php echo $shortname; ?></div>
				</a>
				<?php
            }
			?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
