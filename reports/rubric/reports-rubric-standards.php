<?php
@include('sessioncheck.php');
$method = $_REQUEST;


$rubid = isset($method['rubid']) ? $method['rubid'] : '';
$schedtype = isset($method['schedtype']) ? $method['schedtype'] : '';
        $tname=array();
$tid=array();
$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
                                                                      FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
                                                                                                  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='34' 
                                                                                                        AND b.fld_access='1' 
                                                                                                                AND a.fld_delstatus='0' AND b.fld_item_id='".$rubid."' GROUP BY tagid");
if($qrytag->num_rows > 0){
    //echo '<span style="font-size:16px; line-height: 18px; font-weight: bold; text-shadow:0 0 4px rgba(150,200,250,0.3); text-transform: uppercase;">'.$category[0]["fld_category"].' Academic Standards</span>';
    echo '<span class="standardslist">';
    while($row=$qrytag->fetch_assoc()){
        extract($row);
        $tname[]=$tagname;
        $tid[]=$tagid;
    }
    for($t=0;$t<sizeof($tid);$t++) {
        echo $tname[$t]." <br>";
    }
    echo '</span>';
}else{
    echo "There are no academic standards for this product category.";

}