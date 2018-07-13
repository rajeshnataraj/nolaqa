<?php
@include("sessioncheck.php");
?>
<section data-type='2home' id='reports-bestfit'>
	<script language="javascript">
   		$.getScript("reports/bestfit/reports-bestfit.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Best Fit Report</p>
               <p class="dialogSubTitleLight">Select a report to view or print. Select New Report to create your own.</p>
                 <div class="row rowspacer"></div>
            </div>
        </div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#bestfit-steps' id='btnreports-bestfit-steps' name="0,1">
                <div class='icon-synergy-add-dark'></div>
               
                <div class='onBtn'>New Report</div>
            </a>
            <?php
			$qry = $ObjDB->QueryObject("SELECT fld_id AS bstrptid, fld_title AS bstrpttitle, fn_shortname(fld_title,1) AS shortname 
										FROM itc_bestfit_report_data 
										WHERE fld_delstatus=0 AND fld_created_by='".$uid."'");
                         while($res=$qry->fetch_assoc()){
		         extract($res);
				?>
                    <a class='skip btn mainBtn' href='#bestfit-reports' id='btnreports-bestfit-actions' name="<?php echo $bstrptid.",".$bstrpttitle;?>">
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn' title="<?php echo $bstrpttitle; ?>"><?php echo $shortname; ?></div>
		    </a>
                <?php
                    }
                ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");


