<?php 
@include("sessioncheck.php");
/*
	Created By - Mohan M
	Page - backupreportsajax.php
*/


$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showclass" and $oper != " " )
{
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$distid = isset($method['distid']) ? $method['distid'] : '0';
	?>
	Class 
	<dl class='field row'>
            <div class="selectbox">
                <input type="hidden" name="classid" id="classid" value="">
                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                        <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                    <ul role="options" style="width:100%">
                        <?php 
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname FROM itc_class_master 
                                                                                WHERE fld_school_id='".$schoolid."' AND fld_district_id='".$distid."' AND fld_delstatus='0' 
                                                                                GROUP BY classid ORDER BY fld_class_name;");
                        if($qry->num_rows>0)
                        { 
                            ?>
                            <li><a tabindex="-1" href="#" data-option="0" onclick="$('#viewreportdiv').show();">All Classes</a></li>
                            <?php
                            while($row = $qry->fetch_assoc())
                            {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="$('#viewreportdiv').show();"><?php echo $classname;?></a></li>
                                <?php
                            }
                        }   ?>      
                    </ul>
                </div>
            </div> 
	</dl>
	<?php
}

@include("footer.php");