<?php 

@include("sessioncheck.php");
$oper = isset($method['oper']) ? $method['oper'] : '';
$date=date("Y-m-d H:i:s");
error_reporting(E_ALL);
ini_set("display_errors","1");

if($oper == "category" and $oper != '')
{		
    $cid = isset($method['cid']) ? $method['cid'] : '0'; 
    $catname = isset($method['catname']) ? $method['catname'] : '';
    if($cid!='0' && $cid!='undefined')
    {
            $ObjDB->NonQuery("UPDATE itc_nondigicontent_category
                                                     SET fld_category_name='".$catname."', fld_updated_by='".$uid."', fld_updated_date='".$date."'
                                                     WHERE fld_id='".$cid."'");

    }
    else
    {
            $maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_nondigicontent_category(fld_category_name, fld_created_by, fld_created_date) VALUES ('".$catname."','".$uid."','".$date."')");
    }

}
/*--- Delete the Category ---*/
if($oper=="deletecategory" and $oper != " " )
{
        try
        {
                $catid = isset($method['catid']) ? $method['catid'] : ''; 

                $count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_nondigicontent_category 
                                                                                          WHERE fld_id='".$catid."' 
                                                                                          AND fld_delstatus='0'");

                if($count==1)
                {
                        $ObjDB->NonQuery("UPDATE itc_nondigicontent_category 
                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
                                                         WHERE fld_id='".$catid."'");
                        echo "success";
                }
                else
                {
                        echo "exists";
                }
        }
        catch(Exception $e)
        {
                echo "fail";
        }
}


?>