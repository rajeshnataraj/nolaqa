<?php 
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $sadid = isset($method['sadid']) ? $method['sadid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
											FROM itc_main_tag_mapping 
											WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='10'");
			$sqry = "AND (";
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
        
        if($sadid!=0){
	$sadid = explode(',',$sadid);
		for($i=0;$i<sizeof($sadid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
								            WHERE fld_id='".$sadid[$i]."' AND fld_delstatus='0'");
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
	$.getScript("users/individuals/users-individuals-schooladmin_newschooladmin.js");
		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_school', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=10" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadsadmin();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadsadmin();
		});					
			
	});	

	function fn_loadsadmin(){
				var sid = $('#form_tags_school').val();
				$("#schoollist").load("users/individuals/users-individuals-schooladmin.php #schoollist > *",{'sid':sid});
				removesections('#users-individuals-schooladmin');
			}
                        
         $(function(){				
		var t4 = new $.TextboxList('#form_tags_schoolname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchschooladminname&tag_type=10" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadsadminname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadsadminname();
		});					
			
	});	

	function fn_loadsadminname(){
				var sid = $('#form_tags_schoolname').val();
				$("#schoollist").load("users/individuals/users-individuals-schooladmin.php #schoollist > *",{'sadid':sid});
				removesections('#users-individuals-schooladmin');
			}
        $("input[name='types']").click(function() {  
            var test = $(this).val();
            $("div.sdesc").hide();
            $("#types" + test).show(); 
        });
</script>

<section data-type='#users-individuals' id='users-individuals-schooladmin'>
	<div class='container'>
        <div class='row'>
          <div class='twelve columns'>
          	<p class="dialogTitle">School Admins</p>
            <p class="dialogSubTitleLight">&nbsp;</p>
          </div>
        </div>
            
        <?php 
          if($sessmasterprfid == 2) 
          { 
        ?>
            <div class="row" id="RadioGroup">
            <div class='twelve columns'>
                <font style="color:white">Sort/filter the School Admin list using: 
                <input type="radio" id="tag" name="types"  value="5" />Tag
                <input type="radio" id="search" name="types" checked="checked" value="6" />School Admin Name
                </font>
                 &nbsp;&nbsp;
            </div>
        </div> 
    <?php 
        }  
        ?>
            
            
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
                <p class="filterLightTitle">To filter this list, search by School Admin Name.</p>
                <div class="tag_well">
                    <input type="text" name="test3" value="" id="form_tags_schoolname" />
                </div>
            </div>
        </div>
            
        <div class='row buttons' id="schoollist">
          <a class='skip btn mainBtn' href='#users-individuals-schooladmin_newschooladmin' id='btnusers-individuals-schooladmin_newschooladmin' name='0'>
            <div class="icon-synergy-add-dark"></div>
            <div class='onBtn'>New School<br />Admin</div>
          </a>
          
          <?php 
          	if($sessmasterprfid == 6){
                $qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname, 
											fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo 
										FROM itc_user_master 
										WHERE fld_profile_id= '7' AND fld_district_id='".$sendistid."' AND fld_delstatus='0' ".$sqry." 
										ORDER BY fullname ASC");
            }
            else{ 
                $qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname, 
											fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sendistid  
										FROM itc_user_master WHERE fld_profile_id= '7' AND fld_delstatus='0' ".$sqry." 
										ORDER BY fullname ASC");
            }
                while($row=$qry->fetch_assoc())
                { 	   
					extract($row);
         	?>
                    <a class='skip btn mainBtn' href='#users-individuals-schooladmin_newschooladmin' id='btnusers-individuals-schooladmin_newschooladmin' name="<?php echo $id.",".$sendistid;?>">
                        <div class="icon-synergy-user">
                        	<?php if($photo != "no-image.png" && $photo != ''){ ?>
                        		<img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                            <?php } ?>
                        </div>
                        <div class='onBtn tooltip' title="<?php echo $fullname; ?>"><?php echo $shortname; ?></div>
                    </a>
            <?php
                }
          	?>
         
        </div>
</div>
</section>
<?php
	@include("footer.php");
