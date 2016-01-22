<?php
global $modx, $info,  $error, $log, $mod_page, $lang, $type, $lang_val, $chank_field;
$id_form = (isset($id_form)) ? $id_form : "";
$add_mail = (isset($add_mail)) ? $add_mail : "1";  // запускать ли сохранение и отправку почты
// нужно для того, чтобы если несколько вызовов на странице, не было несколько сохранений за раз

$valid_js='';
$info ='';
$id=$modx->documentIdentifier;
$url=$modx->makeUrl(intval($id));
$user_id =$modx->getLoginUserID();
include_once("function_sn.php");
if($user_id==""){
    $user_id=0;
}
$result =$modx->db->query('
SELECT `id`,`name`, `email`, `ch_form`,`ch_field`,`lang`,`captcha`,`settings`
FROM '.$modx->getFullTableName('mform_forms').'
WHERE `id`='.$id_form.'
');
//var_dump($_POST);


$error="";
$out ='0';
while($row = $modx->db->getRow($result)){//прохождение по массиву row
    $lang_val=$row["lang"];
    switch ($lang_val) {
        case 0:
            include_once($base_path.'assets/modules/mform/language/lang_en.php'); break;
        case 1:
            include_once($base_path.'assets/modules/mform/language/lang_ru.php'); break;
		case 2:
            include_once($base_path.'assets/modules/mform/language/lang_es.php'); break;
		case 3:
            include_once($base_path.'assets/modules/mform/language/lang_fr.php'); break;
    }

    $settings=unserialize($row["settings"]);
    if ($add_mail==1){
        //проверка на существование POST'name_save', проверка на наличие id формы и их равенство
        if (isset($_POST['name_save']) and $id_form!="" and $_POST['name_save']==$id_form){
            //проверка на наличие в БД капчи в форме, необходимы для того, чтобы увидеть надо ли работать с капчей
            if ($row["captcha"]=="1"){
                //проверяет существует ли запись в сессии и проверяет равна ли сессия значению POST
                if (isset($_SESSION['veriword']) && ($_SESSION['veriword'] == $_POST['vericode'])){
                    //include_once("function.php");подключаем функцию для записи файла и отправки емейла
                    $out=add_mail($id_form,$user_id,$id);//запускаем эту функцию
                }
                else{
                    $error .= $lang["invalid_captcha"];
                }
            }
            else{
                $out=add_mail($id_form,$user_id,$id);
            }
        }
    }
    if ($error!='') {
        $error='<div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 '.$error.'
             </div>';
    }
    $site_url=$modx->config['site_url'];
    if($row["captcha"]==1){
        $link=''.$site_url.'manager/includes/veriword.php?rand='.rand().'';
        $captcha='
        <div class="form-group vericode">
            <label for="field_3">'.$lang["enter_code"].' <span class="text-danger">*</span></label><br />
            <a href="javascript:;" onclick="document.getElementById(\'capcha'.$id_form.'\').src=\'manager/includes/veriword.php?rand=\'+Math.random();"><img class="vericode_img" src="'.$link.'" id="capcha'.$id_form.'" name="captcha"
            alt="'. $lang["security_code"].'" border="1"/></a>
            <a class="vericode_text" href="javascript:;" onclick="document.getElementById(\'capcha'.$id_form.'\').src=\'manager/includes/veriword.php?rand=\'+Math.random();">'.$lang["refresh_image"].' </a>
            <input type="text" class="vericodeform form-control" name="vericode" validate="required"/>

            </p>
        </div>
        ';
    }
    else{
        $captcha="";
    }
    $chank_form =$row["ch_form"];
    $chank_field =$row["ch_field"];
    $base_path=$modx->config['base_path'];
    /* ======= поля =========== */
    $fields ='';//создаеться пустая строка, чтобы могли делать операцию добавления к строке .=
    $result2 =	$modx->db->query('
    SELECT `id`, `id_form`, `name`, `type`, `type_main`, `obligat`, `posit`, `l_min`, `l_max`, `default`, `options`
    FROM '.$modx->getFullTableName('mform_fields').'
    WHERE `id_form`='.$id_form.'
    ORDER BY `posit`');
    while($row2 = $modx->db->getRow($result2)){
        switch ($row2["type_main"]){
            case 1:
                $fields .= show_text($row2);
                break;
            case 2:
                $fields .= show_area($row2);
                break;
            case 3:
                $fields .= show_checkbox($row2);
                break;
            case 4:
                $fields .= show_radio($row2);
                break;
            case 5:
                $fields .= show_list($row2);
                break;
        }
    }
}
/* ========  /поля ========== */
/* ========  форма ========== */
$params['captcha']=$captcha;
$params['name']=$row["name"];//значению  массива присваивается значение name формы
$params['button']=$lang["site_add_name"];//значению  массива присваивается значение из массиива lang
$params['field_chunk']=$fields;//плейс холдеру присваевается значение из переменной fields, которое было сформировано раньше
$params['id']=$id_form;
$site_url=$modx->config['site_url'];

//$params['action']="".$url."&action=forms&form_id=".$row[id]."";
//$params['action']="".$url."&action=forms";

if($_SERVER["REQUEST_URI"]=="/"){
    $u=$_SERVER["REQUEST_URI"];
}else{
    $u=$url;
}
$params['action']="".$u."";
foreach($params as $k=>$v){
    $chank_form = str_replace("[+".$k."+]", $v, $chank_form);
}
$form=$chank_form;
//		echo "+++".$form."---";
//$form=$modx->parseChunk("form_chunk", $params, '[+', '+]');// в переменную добавляется чанк, с замененными плейс холдерами на содержимое переменных	$params
//echo 'info='.$info;

if($out!='1' and $fields!=""){//проверка на то, что поля формы не пустые
    echo $error.$info.$form;
}
else {
    echo $info;
}
/* ========  /форма ========== */
//var_dump($settings);

if(!empty($settings)){
    if($settings["bootstrap"]==1 and isset($settings["bootstrap"])){
        $myCSS1 = '<link href="assets/modules/mform/css/bootstrap.css" rel="stylesheet">';
        $modx->regClientCSS( $myCSS1 );
    }
    if($settings["bootstrap-theme"]==1 and isset($settings["bootstrap-theme"])){
        $myCSS2 = '<link href="assets/modules/mform/css/bootstrap-theme.min.css" rel="stylesheet">';
        $modx->regClientCSS( $myCSS2 );
    }
    if($settings["theme"] and isset($settings["theme"])){
        $myCSS3 = '<link href="assets/modules/mform/css/theme.css" rel="stylesheet">';
        $modx->regClientCSS( $myCSS3 );
    }
    if (array_key_exists('datepicker', $settings)) {
        if($settings["datepicker"] and isset($settings["datepicker"])){
            $myCSS4 = '<link href="assets/modules/mform/css/datepicker.css" rel="stylesheet">';
            $modx->regClientCSS( $myCSS4 );
        }
    }
    if (array_key_exists('jquerymin', $settings)) {
        if($settings["jquerymin"] and isset($settings["jquerymin"])){
            $myJS1 ='<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>';
            $modx->regClientScript($myJS1);
        }
    }
    if (array_key_exists('bootstrapmin', $settings)) {
        if($settings["bootstrapmin"] and isset($settings["bootstrapmin"])){
            $myJS7 ='<script type="text/javascript" src="assets/modules/mform/js/bootstrap.min.js"></script>';
            $modx->regClientScript($myJS7);
        }
    }
    if (array_key_exists('bootstrap-datepicker', $settings)) {
        if($settings["bootstrap-datepicker"] and isset($settings["bootstrap-datepicker"])){
            $myJS ='<script type="text/javascript" src="assets/modules/mform/js/bootstrap-datepicker.js"></script>';
            $modx->regClientScript($myJS);
        }
    }
    if (array_key_exists('bootstrapvalidate', $settings)) {
        if($settings["bootstrapvalidate"] and isset($settings["bootstrapvalidate"])){
            $myJS8 ='<script type="text/javascript" src="assets/modules/mform/js/bootstrap.validate.js"></script>';
            $modx->regClientScript($myJS8);
            switch ($lang_val){
                case 0:
                    $myJS9 ='<script type="text/javascript" src="assets/modules/mform/js/bootstrap.validate.en.js"></script>';
                    $modx->regClientScript($myJS9);
                    break;
                case 1:
                    $myJS10 ='<script type="text/javascript" src="assets/modules/mform/js/bootstrap.validate.ru.js"></script>';
                    $modx->regClientScript($myJS10);
                    break;
            }
            $myJS6 ='<script type="text/javascript">
                    $(".form_'.$id_form.'").bt_validate();
                </script>';
            $modx->regClientScript($myJS6);
        }
    }
    if (array_key_exists('myJS', $settings)) {
        if($settings["myJS"] and isset($settings["myJS"])){
            $myJS3 ='<script type="text/javascript" src="assets/modules/mform/js/myJS.js"></script>';
            $modx->regClientScript($myJS3);
        }
    }
}

?>