<?php
function save_radio($post){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    if (!empty($post["field_name"])){
        if (isset($post['field_obligation'])){
            if($post["field_obligation"]!=0 and $post["field_obligation"]!=''){
                $post["field_obligation"]=1;
            }else{
                $post["field_obligation"]=0;
            }
        }else{
            $post["field_obligation"]=0;
        }
        if(!empty($post["radio_names"])){
            $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_fields')."
                    (`id`,`id_form`, `name`,`type_main`, `obligat`, `posit`, `default`)
                    VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."',4,'".$post["field_obligation"]."','".$post["field_position"]."','".$post["radio_names"]."')");
        }else{
            $error .= $lang["err_field_name"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}
?>