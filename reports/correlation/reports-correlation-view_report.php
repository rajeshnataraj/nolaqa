<?php 
ini_set('memory_limit', '-1');
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '0';
$oper = isset($method['oper']) ? $method['oper'] : '';
$filename = isset($method['filename']) ? $method['filename'] : '';

if($oper=='')
{
    $oper="correlationreport";
    $filename="correlationreport_".date('mdyhis');
}

$content = ob_get_clean();


?>
	<script language="javascript" type="text/javascript">
		$('#cbasicinfo').removeClass("active-first");
		$('#cselectstandard').removeClass("active-mid");
		$('#cselectproduct').removeClass("active-mid");
		$('#cviewreport').addClass("active-last").parent().removeClass("dim");
	</script>

                            
                <?php
                        	
            	$html = file_get_contents(REPORT_SERVER_URL.'/index.php?id='.$id.'&oper='.$oper.'&filename='.$filename.'&hostname='.$_SERVER['SERVER_NAME'].'&uid='.$uid.'&sessmasterprfid='.$sessmasterprfid.'&sessionid='.$sessionid.'&schoolid='.$schoolid.'');
                        	
                echo $html;
                ?>
           
    
<?php
	@include("footer.php");