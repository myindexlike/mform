<?php
//var_dump($_POST);
//var_dump($_GET);
include_once('type/type.php');

include_once("function_trim.php");
include_once("function_save.php");
include_once("function_edit.php");

$_POST["file"]= (isset($_POST["file"])) ? $_POST["file"] : "";

foreach($_POST as $k=>$v){
    $v=func_trim($v);
    $post[$k]=$v;
}
foreach($_GET as $k=>$v){
    $v=func_trim($v);
    $get[$k]=$v;
}
$prefix = $modx->db->config['table_prefix'];//вытягивание префикса таблици


if (isset($post['save'])){

    if(empty($post["form_name"])){
        $error .= $lang["err_form_name"];
    }
    if(empty($post["form_email"])){
        $error .= $lang["err_email_text"];
    }
    if(empty($post["form_message"])){
        $error .= $lang["err_mess_text"];
    }
    if(empty($post["ch_form"])){
        $error .=$lang["err_form_ch"];
    }
    if(empty($post["ch_field"])){
        $error .= $lang["err_field_ch"];
    }
    if(empty($post["captcha"])){
        $post["captcha"]='';
    }
    $lize=serialize($_POST["file"]);
    if($error==""){
        $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_forms')."
		(`id`,`name`,`email`,`message`,`ch_form`,`ch_field`,`lang`,`captcha`,`settings`) 
		VALUES (NULL, '".$post["form_name"]."','".$post["form_email"]."','".$post["form_message"]."','".$post["ch_form"]."','".$post["ch_field"]."','".$post["lang"]."','".$post["captcha"]."','".$lize."');");
    }
}

if (isset($get['del_form'])){
    $modx->db->query("DELETE FROM ".$modx->getFullTableName('mform_forms')."
		WHERE `".$prefix."mform_forms`.`id` = '".$get['del_form']."'");
    $info .= $lang["info_form_del"];
}

if (isset($post['update_form'])){
    if (empty($post["form_name"])){
        $error .= $lang["err_form_name"];
    }
    if(empty($post["form_email"])){
        $error .= $lang["err_email_text"];
    }
    if(empty($post["form_message"])){
        $error .= $lang["err_mess_text"];
    }
    if(empty($post["name_ch_form"])){
        $error .=$lang["err_form_ch"];
    }
    if(empty($post["name_ch_field"])){
        $error .= $lang["err_field_ch"];
    }
    if(empty($post["captcha"])){
        $post["captcha"]='';
    }
    if($error==""){
        $lize=serialize($_POST["file"]);
        $modx->db->query("UPDATE ".$modx->getFullTableName('mform_forms')."
		SET `name` = '".$post["form_name"]."', `email` = '".$post["form_email"]."',`message` = '".$post["form_message"]."',`ch_form`='".$post["name_ch_form"]."',`ch_field`='".$post["name_ch_field"]."',`lang`='".$post["lang"]."',`captcha`='".$post["captcha"]."',`settings`='".$lize."'  WHERE `id` =".$post['form_id']."");
        $info .=$lang["info_form_upd1"]." '".$post['form_name']."' ".$lang['info_form_upd2'];
    }

}

if (isset ($get['del_fields'])){
    $modx->db->query("DELETE FROM ".$modx->getFullTableName('mform_fields')."
		WHERE `".$prefix."mform_fields`.`id` = '".$get['del_fields']."'");
    $info .= $lang["info_field_del"];
}

if (isset($post['field_save'])){
    if(isset($post["text"])){
        save_text($post);
    }
    elseif(isset($post["area"])){
        save_area($post);
    }
    elseif(isset($post["checkbox"])){
        save_checkbox($post);
    }
    elseif(isset($post["radio"])){
        save_radio($post);
    }
    else{
        save_list($post);
    }
}


if (isset($post['field_update'])){
    if(isset($post["text"])){
        edit_text($post);
    }
    elseif(isset($post["area"])){
        edit_area($post);
    }
    elseif(isset($post["checkbox"])){
        edit_checkbox($post);
    }
    elseif(isset($post["radio"])){
        edit_radio($post);
    }
    else{
        edit_list($post);
    }
}

/**
 * @param $id
 *
 * @return string
 */
function getFildsForm($id){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $li="";

    if($id!=""){
        $result2 =$modx->db->query('
	SELECT `id`,`id_form`,`name`,`type`,`type_main`,`obligat`,`posit`,`l_min`,`l_max`,`default`,`options`
	FROM '.$modx->getFullTableName('mform_fields').'	
	WHERE `id_form`='.$id.'
	ORDER BY `posit`
	');
    }
    while($row2 = $modx->db->getRow($result2))
    {
        $active1="";
        $active2="";
        $active3="";
        $active4="";
        $active5="";

        $type="";
        if($row2["type_main"]==1){
            $active1="active";
            $type=$lang["text_f"].'('.$lang["type_field_".$row2["type"].""].')';
        }
        elseif($row2["type_main"]==2){
            $active2="active";
            $type=$lang["text_a"];
        }
        elseif($row2["type_main"]==3){
            $active3="active";
            $check="";
            $type=$lang["name_c"];
            if($row2["default"]==1){$check="checked";}
        }
        elseif($row2["type_main"]==4){
            $active4="active";
            $type=$lang["name_r"];
        }
        elseif($row2["type_main"]==5){
            $active5="active";
            $type=$lang["name_l"];
        }

        if ($row2["obligat"]==1){$row2["obligat"]="checked";}
        $li .='
    <tr>
        <td>'.$row2["posit"].'</td>
        <td>'.$row2["name"].'</td>
        <td>'.$type.'</td>
        <td>
            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#Modal_edit_fields'.$row2["id"].'"><span class="glyphicon glyphicon-pencil"></span> '.$lang["edit"].'</button>
            <a href="'.$mod_page.'&action=forms&del_fields='.$row2["id"].'" class="btn btn-danger btn-xs"  onclick="return confirm(\''.$lang["field_delete"].'\')"><span class="glyphicon glyphicon-trash"></span> '.$lang["delete"].'</a>
            <div class="modal fade" id="Modal_edit_fields'.$row2["id"].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">'.$lang["field_upd"].'</h4>
                      </div>
                      <div class="modal-body">
                            <form action="'.$mod_page.'&action=forms" method="post">
                                <div class="form-group">
                                    <label for="field_name">'.$lang["field_name"].'</label><span class="text-danger">*</span>
                                    <input type="text" name="field_name" class="form-control" id="field_name" placeholder="" required value="'.$row2["name"].'">
                                </div>
                                <div class="checkbox">
                                    <label>
                                    <input type="checkbox" name="field_obligation" value="1"  '.$row2["obligat"].'>
                                        '.$lang["field_checkbox"].'
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="field_position">'.$lang["field_position"].'</label>
                                    <input type="text" name="field_position" class="form-control" id="field_position" placeholder="" value="'.$row2["posit"].'">
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="'.$active1.' text-center"><a href="#home_'.$row2["id"].'" data-toggle="tab"><small>'.$lang["text_field"].'</small></a></li>
                                    <li class="'.$active2.' text-center"><a href="#text_area_'.$row2["id"].'" data-toggle="tab"><small>'.$lang["text_area"].'</small></a></li>
                                    <li class="'.$active3.' text-center"><a href="#checkbox_'.$row2["id"].'" data-toggle="tab"><small>'.$lang["name_check"].'</small></a></li>
                                    <li class="'.$active4.' text-center"><a href="#radiobutton_'.$row2["id"].'" data-toggle="tab"><small>'.$lang["name_radio"].'</small></a></li>
                                    <li class="'.$active5.' text-center"><a href="#list_'.$row2["id"].'" data-toggle="tab"><small>'.$lang["name_list"].'</small></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane '.$active1.'" id="home_'.$row2["id"].'">
                                        <div class="form-group">
                                            <label for="field_holder_text">'.$lang["field_holder"].'</label>
                                            <input type="text" name="field_holder_text" class="form-control" id="field_holder_text" placeholder="" value="'.$row2["default"].'">
                                        </div>
                                        '.getFT($row2["type"]).'
                                        <div class="form-group">
                                            <label for="field_l_min">'.$lang["field_l_min"].'</label>
                                            <input type="text" name="field_l_min" class="form-control" id="field_l_min" placeholder="" value="'.$row2["l_min"].'">
                                        </div>

                                        <div class="form-group">
                                            <label for="field_l_max">'.$lang["field_l_max"].'</label>
                                            <input type="text" name="field_l_max" class="form-control" id="field_l_max " placeholder="" value="'.$row2["l_max"].'">
                                        </div>
                                        <div class="form-group">
                                            <label for="field_option">'.$lang["field_option"].'</label>
                                            <input type="text" name="field_option" class="form-control" id="field_option" placeholder="" value="'.$row2["options"].'">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                            <button type="submit" name="text" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                                       </div>
                                    </div>
                                    <div class="tab-pane '.$active2.'" id="text_area_'.$row2["id"].'">
                                        <div class="form-group">
                                            <label for="field_holder_text_area">'.$lang["field_holder"].'</label>
                                            <input type="text" name="field_holder_text_area" class="form-control" id="field_holder_text_area" placeholder="" value="'.$row2["default"].'">
                                        </div>
                                        <div class="form-group">
                                            <label for="field_l_max_area">'.$lang["field_l_max"].'</label>
                                            <input type="text" name="field_l_max_area" class="form-control" id="field_l_max_area" placeholder="" value="'.$row2["l_max"].'">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                            <button type="submit" name="area" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                                        </div>
                                    </div>
                                    <div class="tab-pane '.$active3.'" id="checkbox_'.$row2["id"].'">
                                        <div class="checkbox">
                                            <label>
                                            <input type="checkbox" name="check_checkbox" value="1" '.$check.'>
                                            '.$lang["check"].'
                                            </label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                            <button type="submit" name="checkbox" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                                        </div>
                                    </div>
                                    <div class="tab-pane '.$active4.'" id="radiobutton_'.$row2["id"].'">
                                        <div class="form-group">
                                            <label for="radio_names">'.$lang["radio"].'</label><span class="text-danger">*</span>
                                            <input type="text" name="radio_names" class="form-control" id="radio_names" placeholder="" value="'.$row2["default"].'" >
                                        </div>
                                        <div class="modal-footer">
                                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                            <button type="submit" name="radio" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                                        </div>
                                    </div>
                                    <div class="tab-pane '.$active5.'" id="list_'.$row2["id"].'">
                                        <div class="form-group">
                                            <label for="list_names">'.$lang["list"].'</label><span class="text-danger">*</span>
                                            <input type="text" name="list_names" class="form-control" id="list_names" placeholder="" value="'.$row2["default"].'">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                            <button type="submit" name="list" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="field_update" value="1">
                                <input type="hidden" name="field_id" value="'.$row2["id"].'">
                            </form>
                      </div>
                    </div>
                </div>
            </div>

        </td>
    </tr>';
    }
$table = '<table class="table">
        <tr>
            <th>№</th>
            <th>'.$lang["name"].'</th>
            <th>'.$lang["type"].'</th>
            <th><button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_add_field'.$id.'"><span class="glyphicon glyphicon-plus-sign"></span> '.$lang["field_add"].'</button>

            <div class="modal fade" id="Modal_add_field'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

            <h4 class="modal-title" >'.$lang["field_add"].'</h4>
          </div>
          <div class="modal-body">
                <form action="'.$mod_page.'&action=forms" method="post">
                    <div class="form-group">
                        <label for="field_name">'.$lang["field_name"].'</label><span class="text-danger">*</span>
                        <input type="text" name="field_name" class="form-control" id="field_name" placeholder="" required>
                    </div>
                    <div class="checkbox">
                        <label>
                        <input type="checkbox" name="field_obligation" value="1">
                            '.$lang["field_checkbox"].'
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="field_position">'.$lang["field_position"].'</label>
                        <input type="text" name="field_position" class="form-control" id="field_position" placeholder="" value="0">
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active text-center"><a href="#text_'.$id.'" data-toggle="tab"><small style="font-weight: normal;">'.$lang["text_field"].'</small></a></li>
                        <li class="text-center"><a href="#text_area_'.$id.'" data-toggle="tab"><small style="font-weight: normal;">'.$lang["text_area"].'</small></a></li>
                        <li class="text-center"><a href="#checkbox_'.$id.'" data-toggle="tab"><small style="font-weight: normal;">'.$lang["name_check"].'</small></a></li>
                        <li class="text-center"><a href="#radiobutton_'.$id.'" data-toggle="tab"><small style="font-weight: normal;">'.$lang["name_radio"].'</small></a></li>
                        <li class="text-center"><a href="#list_'.$id.'" data-toggle="tab"><small style="font-weight: normal;">'.$lang["name_list"].'</small></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="text_'.$id.'">
                            <div class="form-group">
                                <label for="field_holder_text">'.$lang["field_holder"].'</label>
                                <input type="text" name="field_holder_text" class="form-control" id="field_holder_text" placeholder="">
                            </div>
                            '.getFT().'
                            <div class="form-group">
                                <label for="field_l_min">'.$lang["field_l_min"].'</label>
                                <input type="text" name="field_l_min" class="form-control" id="field_l_min" placeholder="" value="0">
                            </div>

                            <div class="form-group">
                                <label for="field_l_max">'.$lang["field_l_max"].'</label>
                                <input type="text" name="field_l_max" class="form-control" id="field_l_max " placeholder="" value="0">
                            </div>

                            <div class="form-group">
                                <label for="field_option">'.$lang["field_option"].'</label>
                                <input type="text" name="field_option" class="form-control" id="field_option" placeholder="">
                            </div>

                            <div class="modal-footer">
                                <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                <button type="submit" name="text" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                           </div>
                        </div>
                        <div class="tab-pane" id="text_area_'.$id.'">
                            <div class="form-group">
                                <label for="field_holder_text_area">'.$lang["field_holder"].'</label>
                                <input type="text" name="field_holder_text_area" class="form-control" id="field_holder_text_area" placeholder="" value="'.$row2["default"].'">
                            </div>
                            <div class="form-group">
                                <label for="field_l_max">'.$lang["field_l_max"].'</label>
                                <input type="text" name="field_l_max" class="form-control" id="field_l_max " placeholder="" value="'.$row2["l_max"].'">
                            </div>
                            <div class="modal-footer">
                                <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                <button type="submit" name="area" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="checkbox_'.$id.'">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" name="check_checkbox" value="1">
                                    '.$lang["check"].'
                                </label>
                            </div>
                            <div class="modal-footer">
                                <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                <button type="submit" name="checkbox" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="radiobutton_'.$id.'">
                            <div class="form-group">
                                <label for="radio_names">'.$lang["radio"].'</label><span class="text-danger">*</span>
                                <input type="text" name="radio_names" class="form-control" id="radio_names" placeholder="" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                <button type="submit" name="radio" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="list_'.$id.'">
                            <div class="form-group">
                                <label for="list_names">'.$lang["list"].'</label><span class="text-danger">*</span>
                                <input type="text" name="list_names" class="form-control" id="list_names" placeholder="" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                                <button type="submit" name="list" class="btn btn-primary"  id="field_update" value="1">'.$lang["save"].'</button>
                            </div>
                        </div>
                    </div>
                  <input type="hidden" name="field_save" value="1">
                  <input type="hidden" name="form_id" value="'.$id.'">
                </form>
          </div>
        </div>
      </div>
</div>
</th>
</tr>'.$li.'</table>';

return $table;
}


/**
 * @param $str
 *
 * @return mixed
 *
 * функцыя для редактирования шаблонов у форм и полей
 */
function repl($str){
    global $modx, $info, $error, $log, $mod_page, $lang, $type, $error;
    $str = str_replace('\"', "“", $str);
    $str = str_replace('\[', "[", $str);
    $str = str_replace('\+', "+", $str);
    $str = str_replace('\]', "]", $str);
    $str = str_replace("\'", "“", $str);
    $str = str_replace("\\\\" , "", $str);
    $str = str_replace('\\\\', "", $str);
    return $str;
}

/**
 * @return string
 */
function get_children_list() {
    global $modx, $info, $error, $log, $mod_page, $lang, $type;
    $li='';
    $result1 = $modx->db->query('
	SELECT `id` , `name` , `email`,`message`,`ch_form`,`ch_field`,`lang`,`captcha`,`settings`
	FROM '.$modx->getFullTableName('mform_forms').'	
	WHERE 1
	ORDER BY `id` DESC 
		');
    while($row = $modx->db->getRow($result1))
    {
        $settings=unserialize($row["settings"]);
        //echo "+".$c_mass."+";
        $checked1="";
        $checked2="";
        $checked3="";
        $checked4="";
        $checked5="";
        $checked6="";
        $checked7="";
        $checked8="";
        $checked9="";
        $checked10="";
        if(!empty($settings)){
            foreach($settings as $k=>$v){
                if($k=="bootstrap"){
                    $checked1="checked";
                }
                if($k=="bootstrap-theme"){
                    $checked2="checked";
                }
                if($k=="theme"){
                    $checked3="checked";
                }
                if($k=="datepicker"){
                    $checked4="checked";
                }
                if($k=="datepickerless"){
                    $checked5="checked";
                }
                if($k=="jquerymin"){
                    $checked6="checked";
                }
                if($k=="bootstrapmin"){
                    $checked7="checked";
                }
                if($k=="bootstrap-datepicker"){
                    $checked8="checked";
                }
                if($k=="bootstrapvalidate"){
                    $checked9="checked";
                }
                if($k=="myJS"){
                    $checked10="checked";
                }
            }
        }
        $input ='<pre>'.$lang["call_form"].': [!showFields? &id_form=`'.$row["id"].'`!]</pre>';//формирование вызова формы

        $row["ch_form"]=repl($row["ch_form"]);
        $row["ch_field"]=repl($row["ch_field"]);


        if($row["lang"]==0){
            $sel0='selected="selected"';
            $sel1='';
			$sel2='';
			$sel3='';
        }elseif($row["lang"]==2){
            $sel0='';
            $sel1='';
			$sel2='selected="selected"';
			$sel3='';
        }
		elseif($row["lang"]==3){
            $sel0='';
            $sel1='';
			$sel2='';
			$sel3='selected="selected"';
        }else {
			$sel0='';
            $sel1='selected="selected"';
			$sel2='';
			$sel3='';
		}
		
		
		
        if($row["captcha"]==1){
            $checked="checked";
        }
$li .= '<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="panel-title">'.$row["name"].' ('.$row["id"].')</h3>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-warning butt_edit_form" data-toggle="modal" data-target="#Modal_edit_form'.$row["id"].'"><span class="glyphicon glyphicon-pencil"></span> '.$lang["edit"].'</button>
                    <a href="'.$mod_page.'&action=forms&del_form='.$row["id"].'" class="btn btn-danger butt_edit_form"  onclick="return confirm(\''.$lang["form_delete"].'\')"><span class="glyphicon glyphicon-trash"></span> '.$lang["delete"].'</a>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </div>
    <div class="panel-body">
        '.getFildsForm($row["id"]).'
        '.$input.'


    </div>
 </div>
 <div class="modal fade" id="Modal_edit_form'.$row["id"].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home-'.$row["id"].'" data-toggle="tab">'.$lang["editing_form"].'</a></li>
                <li><a href="#settings-'.$row["id"].'" data-toggle="tab">'.$lang["settings_form"].'</a></li>
            </ul>
          </div>
          <div class="modal-body">

            <form action="'.$mod_page.'&action=forms" method="post">
            <div class="tab-content">
                <div class="tab-pane active" id="home-'.$row["id"].'">
                        <div class="form-group">
                            <label for="form_name">'.$lang["form_name"].'</label>
                            <input type="text" name="form_name" class="form-control" id="form_name" placeholder="" required value="'.$row["name"].'">
                        </div>
                        <div class="form-group">
                            <label for="form_email">'.$lang["form_mail"].'</label>
                            <input type="text" name="form_email" class="form-control" id="form_email" placeholder="" required value="'.$row["email"].'">
                        </div>
                        <div class="form-group">
                            <label for="form_message">'.$lang["form_message"].'</label>
                            <textarea class="form-control" id="form_message" name="form_message" rows="5" required>'.$row["message"].'</textarea>
                        </div>
                        <div class="form-group">
                            <label for="ch_form">'.$lang["ch_form"].'</label><span class="text-danger">*</span>
                            <textarea class="form-control" id="ch_form" name="name_ch_form" rows="5" required>'.$row["ch_form"].'</textarea>
                        </div>
<pre>'.$lang["pre_form"].'
</pre>
                        <div class="form-group">
                            <label for="ch_field">'.$lang["ch_field"].'</label><span class="text-danger">*</span>
                            <textarea class="form-control" id="ch_field" name="name_ch_field" rows="5" required>'.$row["ch_field"].'</textarea>
                        </div>
<pre>'.$lang["pre_field"].'
</pre>
                        <label for="lang">'.$lang["lang"].'</label>
                        <select class="form-control" name="lang" id="lang">

                            <option '.$sel1.' value="1">'.$lang["russian"].'</option>
                            <option '.$sel0.' value="0">'.$lang["english"].'</option>
                            <option '.$sel2.' value="2">'.$lang["spanish"].'</option>
                            <option '.$sel3.' value="3">'.$lang["francais"].'</option>
                        </select>
                        <div class="checkbox">
                            <label>
                            <input type="checkbox" name="captcha" value="1" '.$checked.'>
                            '.$lang["captcha"].'
                            </label>
                        </div>
                  </div>
                <div class="tab-pane" id="settings-'.$row["id"].'">
                        CSS:
                        <label class="checkbox">
                        <input type="checkbox" name="file[bootstrap]" value="1" '.$checked1.'>
                            bootstrap.css
                        </label>
                        <label class="checkbox">
                        <input type="checkbox" name="file[bootstrap-theme]" value="1" '.$checked2.'>
                            bootstrap-theme.min.css
                        </label>
                        <label class="checkbox">
                        <input type="checkbox" name="file[theme]" value="1" '.$checked3.'>
                            theme.css
                        </label>
                        <label class="checkbox">
                        <input type="checkbox" name="file[datepicker]" value="1" '.$checked4.'>
                            datepicker.css
                        </label>
                        JS:
                        <label class="checkbox">
                        <input type="checkbox" name="file[jquerymin]" value="1" '.$checked6.'>
                            jquery.min.js
                        </label>
                        <label class="checkbox">
                        <input type="checkbox" name="file[bootstrapmin]" value="1" '.$checked7.'>
                            bootstrap.min.js
                        </label>
                        <label class="checkbox">
                        <input type="checkbox" name="file[bootstrap-datepicker]" value="1" '.$checked8.'>
                            bootstrap-datepicker.js
                        </label>
                         '.$lang["myjs_info"].'
                        <label class="checkbox">
                        <input type="checkbox" name="file[myJS]" value="1" '.$checked10.'>
                            myJS.js
                        </label>

                        '.$lang["validate_info"].'
                        <label class="checkbox">
                        <input type="checkbox" name="file[bootstrapvalidate]" value="1" '.$checked9.'>
                            bootstrap.validate.js
                        </label>

                </div>
                </form>
                <div class="modal-footer">
                            <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                            <button type="submit" name="update_form" class="btn btn-danger">'.$lang["update"].'</button>
                </div>
                      <input type="hidden" name="form_id" value="'.$row["id"].'">
            </div>
          </div>
        </div>
      </div>
</div>';
    }
    return $li;
}



if ($error!='') {
echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
         '.$error.'
     </div>';
}

if ($info!='') {
echo '<div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     '.$info.'
 </div>';
}
?>
<div class="row">
    <button type="button" class="btn btn-success butt_add_forms" data-toggle="modal" data-target="#Modal_add_form"><span class="glyphicon glyphicon-plus-sign"></span> <?php echo $lang["button1"];?></button>
</div>
<div class="row">
    <div class="col-md-12">
        <?php echo get_children_list(); ?>
    </div>
</div>

<div class="modal fade" id="Modal_add_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab"><?php echo $lang["form_add"];?></a></li>
                    <li><a href="#settings" data-toggle="tab"><?php echo $lang["settings_form"];?></a></li>
                </ul>
            </div>
            <div class="modal-body">
                <!--<div class="tab-content">-->
                <form action="<?php echo $mod_page.'&action=forms'; ?>" method="post">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="form-group">
                                <label for="form_name"><?php echo $lang["form_name"];?></label><span class="text-danger">*</span>
                                <input type="text" name="form_name" class="form-control" id="form_name" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="form_email"><?php echo $lang["form_mail"];?></label><span class="text-danger">*</span>
                                <input type="text" name="form_email" class="form-control" id="form_email" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="form_message"><?php echo $lang["form_message"];?></label><span class="text-danger">*</span>
                                <textarea class="form-control" id="form_message" name="form_message" rows="5" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name_ch_form"><?php echo $lang["ch_form"];?></label><span class="text-danger">*</span>
                                <textarea class="form-control" id="ch_form" name="ch_form" rows="5" required>
                                    <form action="[+action+]" method="post" class="form_[+id+]" >
                                        <h3>[+name+]</h3>
                                        [+field_chunk+]
                                        [+captcha+]
                                        <button type="submit" name="name_save" class="btn btn-primary"  id="name_save" value="[+id+]">[+button+]</button>
                                    </form>
                                </textarea>
                            </div>
                            <pre><?php echo $lang["pre_form"]; ?></pre>
                            <div class="form-group">
                                <label for="ch_field"><?php echo $lang["ch_field"];?></label><span class="text-danger">*</span>
                                <textarea class="form-control" id="ch_field" name="ch_field" rows="5" required>
                                    <div class="form-group">
                                        <label for="field_[+id+]">[+field_name+]<span class="text-danger">[+obligat+]</span></label>
                                        <input type="text" [+validate+] id="field_[+id+]" name="fields[[+id+]]" class="form-control" id="field_name[+id+]" placeholder="[+def+]" value="[+value+]" type_val="[+type+]"/>
                                    </div>
                                </textarea>
                            </div>
                            <pre><?php echo $lang["pre_field"]; ?></pre>
                            <label for="field_type"><?php echo $lang["lang"];?></label>
                            <select class="form-control" name="lang" id="lang">
                                <option value="1"><?php echo $lang["russian"];?></option>
                                <option value="0"><?php echo $lang["english"];?></option>
                                <option value="2"><?php echo $lang["spanish"];?></option>
                                <option value="3"><?php echo $lang["francais"];?></option>
                            </select>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="captcha" value="1">
                                    <?php echo $lang["captcha"]; ?>
                                </label>
                            </div>
                        </div><!-- tab-pane active-->
                        <div class="tab-pane" id="settings">
                            CSS:
                            <label class="checkbox">
                                <input type="checkbox" name="file[bootstrap]" value="1">
                                bootstrap.css
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[bootstrap-theme]" value="1">
                                bootstrap-theme.min.css
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[theme]" value="1">
                                theme.css
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[datepicker]" value="1">
                                datepicker.css
                            </label>
                            JS:
                            <label class="checkbox">
                                <input type="checkbox" name="file[jquerymin]" value="1">
                                jquery.min.js
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[bootstrapmin]" value="1">
                                bootstrap.min.js
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[bootstrap-datepicker]" value="1">
                                bootstrap-datepicker.js
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[bootstrapvalidate]" value="1">
                                bootstrap.validate.js
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="file[myJS]" value="1">
                                myJS.js
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="cancel" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel"];?></button>
                            <button type="submit" name="save" class="btn btn-primary"><?php echo $lang["save"];?></button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>