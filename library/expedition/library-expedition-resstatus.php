<?php 
	@include("sessioncheck.php");	
        $expeditionid = isset($method['id']) ? $method['id'] : '0';
?> 
<script type="text/javascript" charset="utf-8">	
	$.getScript('library/expedition/expedition.js');
			
</script>
<section data-type='2home' id='library-expedition-resstatus'>
    <div class='container'>
       <div class='row rowspacer'>
                    <div class='span10 offset1' id="userslist"> 
                        <table id="test" class='table table-hover table-striped table-bordered setbordertopradius'>
                                <thead class='tableHeadText'>
                                <tr>

                                    <th width="80%">Expedition Name</th>
                                    <th width="20%">Resources</th>

                                </tr>
                            </thead> 
                         </table>
                        <div style="max-height:400px;width:100%" id="tablecontents"  >
                            <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>                  
                                <tbody>
                                    <?php 
                                    
                                        $qryexp = $ObjDB->QueryObject("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version) AS expname, a.fld_res_onoff_status AS resstatus, a.fld_id AS id
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                   
                                    
                                        
                                        if($qryexp->num_rows>0){
                                            while($resexp=$qryexp->fetch_assoc()){
                                                extract($resexp);
                                                
                                                
                                                
                                        ?>
                                                <tr>


                                                    <td width="80%"><?php echo $expname; ?></td>
                                                    <td width="20%">  
                                                        <input name="radio<?php echo $id; ?>" id="radio1_<?php echo $id; ?>" value="1" type="radio" <?php if($resstatus==1) echo 'checked="checked"'; ?> onclick="fn_resstatus(<?php echo $id; ?>,1)" >                                                
                                                            <label class="radio <?php if($resstatus==1) echo "checked"; ?>" for="radio1_<?php echo $id; ?>" >
                                                                <span></span> Without
                                                            </label>
                                                        <input name="radio<?php echo $id; ?>" id="radio2_<?php echo $id; ?>" value="0" type="radio" <?php if($resstatus==0) echo 'checked="checked"'; ?>  onclick="fn_resstatus(<?php echo $id; ?>,0)">
                                                            <label class="radio <?php if($resstatus==0) echo "checked"; ?>" for="radio2_<?php echo $id; ?>">
                                                                <span></span> With
                                                            </label>                                                
                                                    </td>



                                                </tr>
                                    <?php }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>      
    </div>   
</section>
<?php
	@include("footer.php");