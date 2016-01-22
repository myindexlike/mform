<?php
function save_area($post){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    if (!empty($post["field_name"])){
        if (isset($post['field_obligation'])){
            if($post["field_obligation"]!=0 and $post["field_obligation"]!=''){
                $post["field_obligation"]=1;
            }
            else{
                $post["field_obligation"]=0;
            }
        }
        else{
            $post["field_obligation"]=0;
        }
        $post["field_l_max"] = (int)$post["field_l_max"];
        if($post["field_l_max"]>=0){
            $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_fields')."
                (`id`,`id_form`, `name`,`type_main`, `obligat`, `posit`, `default`, `l_max`)
                VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."',2,'".$post["field_obligation"]."','".$post["field_position"]."','".$post["field_holder"]."','".$post["field_l_max"]."');");
        }else{
            $error .= $lang["err_invalid_length"];
        }
    }
    else{
        $error .= $lang["err_field_name"];
    }
}
?>