<?php 
/*------
	Page - users-schools
	Description:
		List the schools avilable in the system
	History:	
------*/
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $schid = isset($method['schid']) ? $method['schid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
										FROM itc_main_tag_mapping 
										WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='14'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_item_id." or";
				}
				$j++;
			}
		}
	}
        
        if($schid!=0){
	$schid = explode(',',$schid);
		for($i=0;$i<sizeof($schid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_school_master 
								            WHERE fld_id='".$schid[$i]."' AND fld_delstatus='0'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_id." or";
				}
				$j++;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_school', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=14" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadschool();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadschool();
		});					
			
	});	

	function fn_loadschool(){
		var sid = $('#form_tags_school').val();
		$("#schoollist").load("users/schools/users-schools.php #schoollist > *",{'sid':sid});
		removesections('#users-schools');
	}
        
        $(function(){				
		var t4 = new $.TextboxList('#form_tags_schoolname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchschoolname&tag_type=14" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadschoolname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadschoolname();
		});					
			
	});	

	function fn_loadschoolname(){
		var sid = $('#form_tags_schoolname').val();
		$("#schoollist").load("users/schools/users-schools.php #schoollist > *",{'schid':sid});
		removesections('#users-schools');
	}
        
        /* for radio button options in title  */
        $("input[name='types']").click(function() {  
            var test = $(this).val();
            $("div.sdesc").hide();
            $("#types" + test).show(); 
        });
</script>

<section data-type='#users' id='users-schools'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">Schools</p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
      
      <?php 
          if($sessmasterprfid == 2) 
          { 
     ?>
        <div class="row" id="RadioGroup">
        <div class='twelve columns'>
            <font style="color:white">Sort/filter the school list using: 
            <input type="radio" id="tag" name="types"  value="5" />Tag
            <input type="radio" id="search" name="types" checked="checked" value="6" />School Name
            </font>
             &nbsp;&nbsp;
	</div>
    </div> 
<?php }  ?>
      
      
    <div class='sdesc row' style="padding-bottom:20px;display:none;" id="types5">
        <div class='twelve columns'>
        	<p class="filterLightTitle">To filter this list, search by Tag Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_school" />
            </div>
        </div>
    </div>
      
    <div class='sdesc row' style="padding-bottom:20px;" id="types6">
        <div class='twelve columns'>
        	<p class="filterLightTitle">To filter this list, search by School Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_schoolname" />
            </div>
        </div>
    </div>
      
    <div class='row buttons' id="schoollist">
        <a class='skip btn mainBtn' href='#users-newschool' id='btnusers-schools-newschool' name='0'>
            <div class='icon-synergy-add-dark'></div>
            <div class='onBtn'>New<br />School</div>
        </a>
      
       <?php
	   	if($sessmasterprfid == 6){
			$qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname, fn_shortname(fld_school_name,1) AS shortname, fld_id AS shlid,
											fld_district_id AS shldistid, fld_school_logo AS shllogo 
										FROM itc_school_master 
										WHERE fld_district_id='".$sendistid."' AND fld_delstatus='0' AND fld_district_id !=0");
		}
		else{
            $qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname, fn_shortname(fld_school_name,1) AS shortname, fld_id AS shlid, 
											fld_district_id AS shldistid, fld_school_logo AS shllogo 
										FROM itc_school_master 
										WHERE fld_delstatus='0' AND fld_district_id !=0 ".$sqry." order by shlname,shortname");
		}
            while($rowshl=$qry->fetch_assoc())
            {
				extract($rowshl);

			?>
				<a class='skip btn mainBtn' href='#users-newschool' id='btnusers-schools-schools_actions' name="<?php echo $shlid.",".$shlname.",".$shldistid;?>">
                    <div class="icon-synergy-school">
                    	<?php if($shllogo != "no-image.png" and $shllogo != ""){ ?>
                    		<img class="thumbimg" src="thumb.php?src=<?php echo __CNTSLPATH__.$shllogo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $shlname;?>"><?php echo $shortname; ?></div>
				</a>
            	<?php
            }
            ?>
      
    </div>
  </div>
</section>
<?php
	@include("footer.php");
