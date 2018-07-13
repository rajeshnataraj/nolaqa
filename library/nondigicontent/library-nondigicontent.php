<?php
/*------
	Page - sim
	Description:
		Showing the menus related with sim / accounts
		new page created by chandru 
	History:	
		
------*/

@include("sessioncheck.php");
$sid= isset($method['sid']) ? $method['sid'] : '';

$date=date("Y-m-d H:i:s");

?>
<section data-type='2home' id='library-nondigicontent'>
	<div class='container'>
		<div class='row'>
			<div class='twelve columns'>
				<p class="dialogTitle">Nondigital</p>
				<p class="dialogSubTitleLight">&nbsp;</p>
			</div>
		</div>

		<div class='row buttons' id="nondigicontentcategory">
			<?php if($sessmasterprfid == 2) 
			{ ?>
				<div id="large_icon_studentlist" style="float:left;">
					<a class='skip btn mainBtn' href='#library-nondigicontent-newcategory' id='btnlibrary-nondigicontent-newcategory' name='0'>
						<div class="icon-synergy-add-dark"></div>
						<div class='onBtn'>New Category</div>
					</a>
				</div><?php
			} ?>
			<?php 

				$category=$ObjDB->NonQuery("SELECT fld_id as id, fld_category_name as categorys FROM itc_nondigicontent_category WHERE fld_delstatus='0' ".$sqry." ");

				if($category->num_rows>0) 
				{	
					while($rowcat=$category->fetch_assoc())
					{
						extract($rowcat);
					?>
					<div id="large_icon_studentlist" style="float:left;">
						<a class='skip btn mainBtn' href='#library-nondigicontent-product' id='btnlibrary-nondigicontent-product' name='<?php echo $id.",".$categorys;?>'>
							<div class="icon-synergy-tests"></div>
							<div class='onBtn'><?php echo $categorys; ?></div>
						</a>
					</div>
					
					<?php
						
					}
				}
				
			?>
		</div>
		
	</div>
</section>
<?php
	@include("footer.php");
