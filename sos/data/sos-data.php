<?php 
	@include("sessioncheck.php");
	
	/*
		Created By - Muthukumar. D
		Page - sos-modules
		Description:
			Show the Tags textbox, New Module and Saved Module(Module name) buttons.
	
		Actions Performed:
			Tag - Used to filter the details
			New Module - Redirects to Module details form - sos-modules-newmodule.php
			Save Module - Redirects to Module actions form - sos-modules-actions.php
		
		History:
	
	
	*/
	
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
	$date=date("Y-m-d");
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){	
			$id = explode('_',$sid[$i]);
			if($id[1]=='module'){				
				$sqry.= " AND a.fld_id =".$id[0];
			}
			else{	
				$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping 
				                               WHERE fld_tag_id='".$sid[$i]."' 
											   AND fld_access='1' AND fld_tag_type='3'");
				$sqry = "AND (";
				$j=1;
				while($itemres = $itemqry->fetch_assoc()){
					extract($itemres);
					if($j==$itemqry->num_rows){
						$sqry.=" a.fld_id=".$fld_item_id.")";
					}
					else{
						$sqry.=" a.fld_id=".$fld_item_id." or";
					}
					$j++;
				}
			}
		}		
	}
?>

<section data-type='2home' id='sos-data'>
    <script type="text/javascript" charset="utf-8">	
        $.getScript('sos/data/sos-data.js');
    </script>
  
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Data</p>
                <p class="dialogSubTitle"></p>
            </div>
        </div>
        
        <!--Dispaly New/Saved Modules in Grid view-->
        <div class='row buttons rowspacer' id="modulelist">
        	<?php 
			if($sessmasterprfid == 9 OR $sessmasterprfid == 8) { ?>
                <a class='skip btn mainBtn' href='#sos-data' id='btnsos-data-newdata' name='0,0'>
                    <div class='icon-synergy-add-dark'></div>
                    <div class='onBtn'>New<br />Data</div>
                </a>
            <?php 
			}
			?>
          
            <?php
			if($sessmasterprfid == 9 OR $sessmasterprfid == 8)
			{ //For Pitsco & Content Admin
				 $qry=$ObjDB->QueryObject("SELECT fld_id AS sheetid,fld_data_sheetname AS sheetname,fn_shortname (fld_data_sheetname, 1) AS shortname FROM itc_sos_datasheet_master WHERE fld_delstatus='0' AND fld_created_by='".$uid."'");
			
			}
				if($qry->num_rows>0)
				{
					while($res = $qry->fetch_assoc())
					{
						extract($res);						
						?>
						<a class='skip btn mainBtn' href='#sos-data' id='btnsos-data-actions' name="<?php echo $sheetid.",".$sheetname;?>">
							<div class='icon-synergy-modules'></div>
							<div  class='onBtn tooltip' title="<?php echo $sheetname; ?>"><?php echo $shortname; ?></div>
						</a>
						<?php
					}
				}
            ?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");