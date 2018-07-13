<?php
//Should only be included after the database is available

//Returns true if a teacher has permission to view information about a class
function teacher_has_permission_to_view_class($teacher_id, $class_id){
    global $ObjDB;
    $teacher_id = intval($teacher_id);

    //The list of classes on has permmision to view:
    $viewable_classes_for_current_teacher = [];
    $get_viewable_classes_query = "select fld_class_id from itc_class_teacher_mapping
        where fld_teacher_id = $teacher_id";

    $viewable_classes_query_object = $ObjDB->QueryObject($get_viewable_classes_query);

    while ($row = $viewable_classes_query_object->fetch_assoc()){
        $viewable_classes_for_current_teacher[] = $row['fld_class_id'];
    }
    if (in_array($class_id, $viewable_classes_for_current_teacher)){
        return true;
    }else{
        return false;
    }
}

