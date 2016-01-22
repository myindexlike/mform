<?php
/**
 * @param $post
 */
function edit_text($post){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
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
    if((($post["field_l_max"]-$post["field_l_min"]<=0 and $post["field_l_max"]==0) or ($post["field_l_max"]-$post["field_l_min"]>=0)) and ($post["field_l_max"]>=0) and ($post["field_l_min"]>=0)){

        if (!empty($post["field_name"])){
            $modx->db->query("
                UPDATE ".$modx->getFullTableName('mform_fields')."
                SET `name` = '".$post["field_name"]."',
                `type` = '".$post["field_type"]."',
                `type_main` = 1,
                `obligat` = '".$post["field_obligation"]."',
                `posit` = '".$post["field_position"]."',
                `l_min` = '".$post["field_l_min"]."',
                `l_max` = '".$post["field_l_max"]."',
                `default` = '".$post["field_holder_text"]."',
                `options` = '".$post["field_option"]."' WHERE `id` =".$post["field_id"].";");

            $info .=$lang["info_field_upd1"]." <b>".$post["field_name"]."</b> ".$lang['info_field_upd2'];
        }else{
            $error .= $lang["err_field_name"];
        }
    }else{
        $error .= $lang["err_field_save"];
    }
}

/**
 * @param $post
 */
function edit_area($post){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
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
        $modx->db->query("
        UPDATE ".$modx->getFullTableName('mform_fields')."
        SET `name` = '".$post["field_name"]."',
        `type` = '',
        `type_main` = 2,
        `obligat` = '".$post["field_obligation"]."',
        `posit` = '".$post["field_position"]."',
        `l_min` = '',
        `l_max` = '".$post["field_l_max_area"]."',
        `default` = '".$post["field_holder_text_area"]."',
        `options` = '' WHERE `id` =".$post["field_id"].";");

        $info .=$lang["info_field_upd1"]." <b>".$post["field_name"]."</b> ".$lang['info_field_upd2'];
    }else{
        $error .= $lang["err_field_save"];
    }
}

/**
 * @param $post
 */
function edit_checkbox($post){
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
        $modx->db->query("
        UPDATE ".$modx->getFullTableName('mform_fields')."
        SET `name` = '".$post["field_name"]."',
        `type` = '',
        `type_main` = 3,
        `obligat` = '".$post["field_obligation"]."',
        `posit` = '".$post["field_position"]."',
        `l_min` = '',
        `l_max` = '',
        `default` = '".$post["check_checkbox"]."',
        `options` = '' WHERE `id` =".$post["field_id"].";");
    }else{
        $error .= $lang["err_field_name"];
    }
}

function edit_radio($post){
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
            $modx->db->query("
            UPDATE ".$modx->getFullTableName('mform_fields')."
            SET `name` = '".$post["field_name"]."',
            `type` = '',
            `type_main` = 4,
            `obligat` = '".$post["field_obligation"]."',
            `posit` = '".$post["field_position"]."',
            `l_min` = '',
            `l_max` = '',
            `default` = '".$post["radio_names"]."',
            `options` = '' WHERE `id` =".$post["field_id"].";");
        }else{
            $error .= $lang["err_field_name"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}

/**
 * @param $post
 */
function edit_list($post){
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
        if(!empty($post["list_names"])){
            $modx->db->query("
            UPDATE ".$modx->getFullTableName('mform_fields')."
            SET `name` = '".$post["field_name"]."',
            `type` = '',
            `type_main` = 5,
            `obligat` = '".$post["field_obligation"]."',
            `posit` = '".$post["field_position"]."',
            `l_min` = '',
            `l_max` = '',
            `default` = '".$post["list_names"]."',
            `options` = '' WHERE `id` =".$post["field_id"].";");
        }else{
            $error .= $lang["err_radio_names"];
        }
    }else{
        $error .= $lang["err_field_name"];
    }
}
?>