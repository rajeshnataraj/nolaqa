<?php 
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $padid = isset($method['padid']) ? $method['padid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id 
											FROM itc_main_tag_mapping 
											WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='13'");
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
        
        if($padid!=0){
	$padid = explode(',',$padid);
		for($i=0;$i<sizeof($padid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
								            WHERE fld_id='".$padid[$i]."' AND fld_delstatus='0'");
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
	$.getScript("users/individuals/users-individuals-pitscoadmin_newpitscoadmin.js");
		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_padmin', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=13" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadpadmin();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadpadmin();
		});					
			
	});	

	function fn_loadpadmin(){
				var sid = $('#form_tags_padmin').val();
				$("#padminlist").load("users/individuals/users-individuals-pitscoadmin.php #padminlist > *",{'sid':sid});
				removesections('#users-individuals-pitscoadmin');
			}
                        
                        $(function(){				
		var t4 = new $.TextboxList('#form_tags_padminname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchpitscoadmin&tag_type=13" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadpadminname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadpadminname();
		});					
			
	});	

	function fn_loadpadminname(){
				var sid = $('#form_tags_padminname').val();
				$("#padminlist").load("users/individuals/users-individuals-pitscoadmin.php #padminlist > *",{'padid':sid});
				removesections('#users-individuals-pitscoadmin');
			}
                        
        $("input[name='types']").click(function() {  
            var test = $(this).val();
            $("div.sdesc").hide();
            $("#types" + test).show(); 
        });
</script>

<section data-type='#users-individuals' id='users-individuals-pitscoadmin'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">Pitsco Admins</p>
		<p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
      
      <?php 
          if($sessmasterprfid == 2) 
          { 
        ?>
            <div class="row" id="RadioGroup">
        <div class='twelve columns'>
                <font style="color:white">Sort/filter the Pitsco list using: 
                <input type="radio" id="tag" name="types"  value="5" />Tag
                <input type="radio" id="search" name="types" checked="checked" value="6" />Pitsco Admin Name
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
                <input type="text" name="test3" value="" id="form_tags_padmin" />
            </div>
        </div>
    </div>
      
    <div class='sdesc row' style="padding-bottom:20px;" id="types6">
        <div class='twelve columns'>
	        <p class="filterLightTitle">To filter this list, search by Pitsco Admin Name.</p>
            <div class="tag_well">
                <input type="text" name="test3" value="" id="form_tags_padminname" />
            </div>
        </div>
    </div>
      
    <div class='row buttons' id="padminlist">
    	<a class='skip btn mainBtn' href='#users-individuals-pitscoadmin_newpitscoadmin' id='btnusers-individuals-pitscoadmin_newpitscoadmin' name='0'>
       		<div class="icon-synergy-add-dark"></div>
        	<div class='onBtn'>New Pitsco<br />Admin</div>
      	</a>
      	<?php  
            $qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname, 
											fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo 
										FROM itc_user_master 
										WHERE fld_profile_id= '2' AND fld_id>2 AND fld_delstatus='0' ".$sqry." 
										ORDER BY fullname ASC");
            while($row=$qry->fetch_assoc())
            {
				extract($row);
		?>
                <a class='skip btn mainBtn' href='#users-individuals-pitscoadmin_newpitscoadmin' id='btnusers-individuals-pitscoadmin_newpitscoadmin' name="<?php echo $id;?>">
                	<div class="icon-synergy-user">
                    	<?php if($photo != "no-image.png" && $photo != ''){ ?>
                       	 <img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
               		<div class='onBtn tooltip' title="<?php echo $fullname;?>"><?php echo $shortname;?></div>
				</a>
            	<?php
            }
            ?>
     
    </div>
  </div>
</section>
<?php
	@include("footer.php");
