<?php 
	@include("sessioncheck.php");	
?> 
<script type="text/javascript" charset="utf-8">	
	$.getScript("users/blockusers/users-blockusers.js");
			
</script>
<section data-type='users' id='users-blockusers'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Blocking Users</p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
        
        <div class="row">
            <div class='six columns'>   
                Users Types
                <dl class='field row'>
                    <div class="selectbox">
                        <input type="hidden" name="ddlusers" id="ddlusers" value="" onchange="fn_selectusers(this.value);" >
                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Type of Users</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">	
                                <input type="text" class="selectbox-filter" placeholder="Search District">		    
                            <ul role="options" style="width:100%;">
                                <li><a tabindex="1" href="#" data-option="-1">All Users</a></li>
                                <li><a tabindex="-1" href="#" data-option="-2">Districts</a></li>
                                <li><a tabindex="-1" href="#" data-option="-3">Schools</a></li>
                                <li><a tabindex="-1" href="#" data-option="-4">School Purchase</a></li>
                                                                
                                <?php
                                $shlqry = $ObjDB->QueryObject("SELECT fld_prf_main_id as pid, fld_profile_name as pname
                                                                FROM itc_profile_master 
                                                                WHERE  fld_prf_main_id NOT IN(1,2,3,4,11) and fld_delstatus='2'");

                                while($rowshl = $shlqry->fetch_assoc()){ 
                                extract($rowshl);
                                if($pname =="Teacher Individual" ){
                                    $pname = "Home Purchase";
                                }
                                ?>
                                        <li><a href="#" data-option="<?php echo $pid;?>"><?php echo $pname;?></a></li>
                                <?php 
                                }?> 
                            </ul>
                        </div>
                    </div> 
                </dl>
            </div>
        </div>
        
         <div class='row' id="break">
           <?php echo "<br/>";?>
       </div>
        
        <div id="schoollist" style="display: none;"> </div>
        <div id="userslist1" style="display: none;"> </div>        
    </div>   
</section>
<?php
	@include("footer.php");