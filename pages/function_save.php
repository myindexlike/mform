<?php
/**
 * @param $post
 */
function save_text($post){
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
        $post["field_l_min"] = (int)$post["field_l_min"];
        $post["field_l_max"] = (int)$post["field_l_max"];
        if($post["field_type"]==""){
            $post["field_type"]=1;
        }
        if((($post["field_l_max"]-$post["field_l_min"]<=0 and $post["field_l_max"]==0) or ($post["field_l_max"]-$post["field_l_min"]>=0)) and ($post["field_l_max"]>=0) and ($post["field_l_min"]>=0)){
            $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_fields')."
            (`id`,`id_form`, `name`, `type`,`type_main`, `obligat`, `posit`, `default`, `l_min`, `l_max`, `options`)
            VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."','".$post["field_type"]."',1,'".$post["field_obligation"]."','".$post["field_position"]."', '".$post["field_holder_text"]."','".$post["field_l_min"]."','".$post["field_l_max"]."','".$post["field_option"]."');");
        }
        else{
            $error .= $lang["err_field_save"];
        }
    }
    else{
        $error .= $lang["err_field_name"];
    }
}

/**
 * @param $post
 */
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
                VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."',2,'".$post["field_obligation"]."','".$post["field_position"]."','".$post["field_holder_text_area"]."','".$post["field_l_max_area"]."');");
        }else{
            $error .= $lang["err_field_save"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}

/**
 * @param $post
 */
function save_checkbox($post){
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
        $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_fields')."
            (`id`,`id_form`, `name`,`type_main`, `obligat`, `posit`, `default`)
            VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."',3,'".$post["field_obligation"]."','".$post["field_position"]."','".$post["check_checkbox"]."');");
    }else{
        $error .= $lang["err_field_name"];
    }
}

/**
 * @param $post
 */
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
            $error .= $lang["err_radio_names"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}

/**
 * @param $post
 */
function save_list($post){
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
        if(isset($post["list_names"]) and $post["list_names"]!=""){
            $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_fields')."
            (`id`,`id_form`, `name`,`type_main`, `obligat`, `posit`, `default`)
            VALUES (NULL,'".$post["form_id"]."', '".$post["field_name"]."',5,'".$post["field_obligation"]."','".$post["field_position"]."','".$post["list_names"]."');");
        }else{
            $error .= $lang["err_list_names"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}
?>