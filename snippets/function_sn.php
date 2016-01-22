<?php

/**
 * @param $row2
 *
 * @return string
 *
 * функция формирования выпадающего списка
 */
function show_list($row2){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $input_text=explode("||", $row2["default"]);
    $option ="";
    foreach ($input_text as $key => $value ) {
        if(isset($_POST["fields"][$row2["id"]]) and $_POST["fields"][$row2["id"]]==$value){
            $selected='selected="selected"';
        }else{
            $selected="";
        }
        $option .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
    }

  $chank_field0='<div class="form-group">
                        <label for="field_[+id+]">[+field_name+]
                        <select class="form-control" name="fields[[+id+]]">
                            '.$option.'
                        </select>
                        </label>
                    </div>';

    $params_field['id']=$row2["id"];//значению  массива присваивается значение id полей
    $params_field['field_name']=$row2["name"];//значению  массива присваивается значение name полей
    $chank_field1 = $chank_field0;
    foreach($params_field as $k=>$v){
        $chank_field1 = str_replace("[+".$k."+]", $v, $chank_field1);
    }
    return $chank_field1;
}


/**
 * @param $row2
 *
 * @return string
 *
 * функция формирования radiobutton
 */
function show_radio($row2){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $input_text=explode("||", $row2["default"]);
    $input = "";
    foreach ($input_text as $key => $value) {
        if(isset($_POST["fields"][$row2["id"]]) and $_POST["fields"][$row2["id"]]==$value){
            $check="checked";
        }else{
            $check="";
        }
        $input .= '<br><input type="radio" name="fields[[+id+]]" id="field_name[+id+]-'.$key.'" value="'.$value.'" '.$check.'>
<label for="field_name[+id+]-'.$key.'">'.$value.'</label>';
    }
    $chank_field0='<div class="radio">
                      <b>[+field_name+]</b>
                      '.$input.'
                    </div>';
    $params_field['id']=$row2["id"];//значению  массива присваивается значение id полей
    $params_field['field_name']=$row2["name"];//значению  массива присваивается значение name полей
    /*$params_field['value']=$_POST["fields"][$row2["id"]];*/
    $chank_field1 = $chank_field0;
    foreach($params_field as $k=>$v){
        $chank_field1 = str_replace("[+".$k."+]", $v, $chank_field1);
    }
    return $chank_field1;
}

/**
 * @param $row2
 *
 * @return string
 *
 * функция формирования checkbox
 */
function show_checkbox($row2){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    //var_dump($_POST);
    if($row2["default"] or (isset($_POST["fields"][$row2["id"]]) and $_POST["fields"][$row2["id"]]==1)){
        $check="checked";
    }else{
        $check="";
    }
    if($row2["obligat"]==1){
        $params_field['obligat']='*';
        $required="required";
    }else{
        $required="";
    }
    $chank_field0='
                <div class="checkbox">
                    <label>
                    <input type="checkbox" value="1" name="fields[[+id+]]" [+validate+] id="field_name[+id+]" '.$check.' '.$required.'[+validate+]>
                    <b>[+field_name+]</b><span class="text-danger">[+obligat+]</span>
                    </label>
                </div>';
    $params_field['id']=$row2["id"];//значению  массива присваивается значение id полей
    $params_field['field_name']=$row2["name"];//значению  массива присваивается значение name полей
    $params_field['def']=$row2["default"];
    $chank_field1 = $chank_field0;
    foreach($params_field as $k=>$v){
        $chank_field1 = str_replace("[+".$k."+]", $v, $chank_field1);
    }
    return $chank_field1;
}


/**
 * @param $row2
 *
 * @return str
 *
 * функция формирования текстового поля
 */
function show_text($row2){
    global $modx,$mod_page,$lang,$type,$info, $error, $log, $chank_field;
    $validate='';
    $params_field['obligat']='';
    if($row2["obligat"]==1){
        $params_field['obligat']='*';
        if($validate!=''){$validate.='|';}
        $validate.='required';
    }
    if($row2["l_min"]!=0){
        if($validate==''){
        }else{
            $validate.='|';
        }
        $validate.='length_min,'.$row2["l_min"].'';
    }
    if($row2["l_max"]!=0){
        if($validate!=''){$validate.='|';}
        $validate.='length_max,'.$row2["l_max"].'';
    }
    if($row2["type"]==6){
        if($validate!=''){$validate.='|';}
        $validate.='number';
    }
    if($row2["type"]==7){
        if($validate!=''){$validate.='|';}
        $validate.='email';
    }
    if ($validate!=''){
        $params_field['validate']='validate="'.$validate.'"';
    }else{
        $params_field['validate']='';
    }
    if($row2["type"]==""){
        $params_field['type']=1;
    }else{
        $params_field['type']=$row2["type"];
    }
    $params_field['id']=$row2["id"];//значению  массива присваивается значение id полей
    $params_field['field_name']=$row2["name"];//значению  массива присваивается значение name полей
    $params_field['def']=$row2["default"];
    if(isset($_POST["fields"][$row2["id"]])){
        $params_field['value']=$_POST["fields"][$row2["id"]];
    }else{
        $params_field['value']="";
    }
    $chank_field1 = $chank_field;
    foreach($params_field as $k=>$v){
        $chank_field1 = str_replace("[+".$k."+]", $v, $chank_field1);
    }
    return $chank_field1;
}

/**
 * @param $row2
 *
 * @return string
 */
function show_area($row2){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    if(isset($_POST["fields"][$row2["id"]])){
        $value=$_POST["fields"][$row2["id"]];
    }else{
        $value="[+value+]";
    }
    $chank_field0='<div class="form-group">
                        <label for="field_[+id+]">[+field_name+]<span class="text-danger">[+obligat+]</span></label>
                        <textarea rows="4" name="fields[[+id+]]" [+validate+] id="field_name[+id+]" class="form-control">'.$value.'</textarea>
                      </div>';
    $validate='';
    $params_field['obligat']='';
    if($row2["obligat"]==1){
        $params_field['obligat']='*';
        if($validate!=''){$validate.='|';}
        $validate.='required';
    }

    if($row2["l_max"]!=0){
        if($validate!=''){$validate.='|';}
        $validate.='length_max,'.$row2["l_max"].'';
    }
    if ($validate!=''){
        $params_field['validate']='validate="'.$validate.'"';
    }else{
        $params_field['validate']='';
    }

    $params_field['id']=$row2["id"];//значению  массива присваивается значение id полей
    $params_field['field_name']=$row2["name"];//значению  массива присваивается значение name полей
    $params_field['def']=$row2["default"];
    /*$params_field['value']=$_POST["fields"][$row2["id"]];*/

    foreach($params_field as $k=>$v){
        $chank_field0 = str_replace("[+".$k."+]", $v, $chank_field0);
    }
    return $chank_field0;
}

/**
 * @param $id_form
 * @param $user_id
 * @param $id
 *
 * @return int
 */
function add_mail($id_form,$user_id,$id){
    global $modx, $info, $error, $log, $mod_page, $lang, $type, $error;
    //var_dump($_POST);
    $post = array();
    $field = array();
    $base_path=$modx->config['base_path'];
    include_once($base_path.'assets/modules/mform/pages/function_trim.php');
    /* ======= 1) =========== */
    foreach ($_POST["fields"] as $k => $v){
        $v=func_trim_snip($v);
        $post[$k]=$v;
    }
    /* ======= 2) =========== */
    $result =$modx->db->query('
		SELECT `id`, `id_form`, `name`, `type`, `type_main`,  `obligat`, `posit`, `l_min`, `l_max`, `default`, `options`
		FROM '.$modx->getFullTableName('mform_fields').'	
		WHERE `id_form`='.$id_form.'
		ORDER BY `posit`');
    while($row = $modx->db->getRow($result)){
        $field[$row["id"]]=array("name"=> $row["name"],"type"=> $row["type"],"type_main"=> $row["type_main"],"obligat"=> $row["obligat"],"posit"=> $row["posit"],"l_min"=>$row["l_min"],"l_max"=>$row["l_max"],"default"=>$row["default"]);
    }

    /* ======= 2.5) =========== */
    $result2 =$modx->db->query('
		SELECT `id`, `name`, `email`,`message`
		FROM '.$modx->getFullTableName('mform_forms').'
		WHERE `id`='.$id_form.'
		ORDER BY `id`
		');
    while($row2 = $modx->db->getRow($result2)){
        $form=array("name"=>$row2["name"],"email"=>$row2["email"],"message"=>$row2["message"]);
    }

    /* ======= 3) =========== */
    $check=0;
    foreach($post as $k => $v) {
        if($v==""){
            if($field[$k]["obligat"]==1){
                $check=1; $error.=$lang["fill_field"].''.$field[$k]["name"].'. ';
            }
        }else{
            if($field[$k]["l_min"]!=0){
                if($field[$k]["l_min"]>mb_strlen($v,'UTF-8')){
                    $check=1; $error.=$lang["short_value"].' '.$field[$k]["name"].'. '.$lang["minimum_value"].' '.$field[$k]["l_min"].'. ';
                }
            }
            if($field[$k]["l_max"]!=0){
                if($field[$k]["l_max"]<mb_strlen($v,'UTF-8')){
                    $check=1; $error.=$lang["long_value"].' '.$field[$k]["name"].'. '.$lang["maximum_value"].' '.$field[$k]["l_max"].'. ';
                }
            }
            if($field[$k]["type"]==6){
                if((int)$v>0){
                }else{
                    $check=1;
                    $error.=$lang["wrong_field_filled"].$field[$k]["name"].' ';
                }
            }
        }
    }
    if($check==0){
        foreach($field as $k=>$val){
            if($field[$k]["type_main"]==3){
                if(isset($post[$k]) and $post[$k]==1){
                    $post1[$field[$k]["name"]]=$lang["joined"];
                }else{
                    $post1[$field[$k]["name"]]=$lang["unspecified"];
                }
            }else{$post1[$field[$k]["name"]]=$post[$k];}
        }
        //var_dump($post1);
        $lize=serialize($post1);

        $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_value')."
			(`id`, `id_form`, `id_page`, `id_user`, `value`,`date`) 
			VALUES (NULL,".$id_form.",".$id.",".$user_id.",'".$lize."','".date("Y-m-d H:i:s")."');");

        $info .= '<div class="mf_message">'.$form["message"].'</div>';
        $email=explode(",",$form["email"]);
        foreach ($email as $key => $value ) {
            $value=trim($value);
            $email[$key]=$value;
            $to  = $value;
            $mes="";
            foreach($field as $k=>$val){
                $mes .="<p><b>".$field[$k]["name"]."</b>: ".$post1[$field[$k]["name"]]."</p>";
            }
            $subject = $lang["message_from_site"].' '.$modx->config['site_name'];
            $message = '
				<html> 
				  <head> 
				   <title>'.$lang["message_from_site"].'</title>
				  </head> 
				  <body> 
				   '.$mes.' 
				   <p><b>'.$lang["date_message"].'</b>: '.date("Y-m-d H:i:s").'</p>
				   <p><b>'.$lang["id_page"].'</b>: '.$modx->documentIdentifier.'</p>
				   <p></p> 
				   <p>'.$lang["more_module"].'</p>
				  </body> 
				</html>';
            $headers  = "Content-type: text/html; charset=utf-8 \r\n";
            $headers .= "From: \" From \" <".$modx->config['emailsender'].">\r\n";
            $headers .= "MIME-Version: 1.0 \r\n";
            $headers .= "Content-Transfer-Encoding: utf-8 \r\n";
            mail($to, $subject, $message, $headers);
            return 1;
        }
    }
}

?>