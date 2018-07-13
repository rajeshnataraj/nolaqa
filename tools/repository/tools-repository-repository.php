<?php 
    @include("sessioncheck.php");
?>

<script type='text/javascript'>
	$.getScript("tools/repository/tools-repository-newrepository.js");
</script>
<section data-type='2home' id='tools-repository-repository'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Repository</p>
                <!--<p class="dialogSubTitleLight">Upload repository for offline student portfolio</p>-->
            </div>
        </div>        	
        <div class='row buttons rowspacer' id="repository">
        	<?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#tools-repository' id='btntools-repository-newrepository'>
                <div class='icon-synergy-add-dark'></div>
                <div class='onBtn'>New<br /> Repository</div>
            </a> 
                <?php } ?>
           <?php 
					$qry = $ObjDB->QueryObject("SELECT fld_id as repositoryid, fld_repository_name as repositoryname, 
					                                   fn_shortname(fld_repository_name,1) as shortrepositoryname, fld_file_name as filename, 
					                                   fld_file_type as filetype, fld_share, fld_created_by FROM itc_repository_master 
											   WHERE  fld_delstatus='0'");//fld_created_by='".$uid."' AND 
					if($qry->num_rows>0){
						while($res=$qry->fetch_assoc()){
							extract($res);
						?>
                 <a class='skip btn mainBtn' href='#tools-repository' id='btntools-repository-actions' name="<?php echo $repositoryid;?>">
                    <div class='icon-synergy-repository'></div>
                    <div class='onBtn tooltip' title="<?php echo $repositoryname; ?>"><?php echo $shortrepositoryname; ?></div>
				</a>
			<?php 				
				}
				
			}
                        else
                        {
                                    echo "No Records Found";
                        }
			
			?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
