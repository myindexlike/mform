<?php
global $modx, $info, $error, $log, $mod_page, $lang, $type;

ini_set('display_errors', 1);
include_once("function_m.php");
include_once("pages/function_trim.php");
foreach($_POST as $k=>$v){
    $v=func_trim($v);
    $post[$k]=$v;
}
foreach($_GET as $k=>$v){
    $v=func_trim($v);
    $get[$k]=$v;
}
$mod_name = '';
$mod_page = "index.php?a=112&id=".$get['id'];
define("MODULE_PATH","../assets/modules/mform/");
define("START_PATH","../");
$tab_select =1; // вкладка по уполчанию
$prefix = $modx->db->config['table_prefix'];//вытягивание префикса таблици




if(isset($get["cr_table"])){//проверка на то, надо ли создать таблицы
    create_table($prefix);//вызов функции создания таблиц
}
$manager_language = $modx->config['manager_language'];

switch($manager_language) {
    case 'russian-UTF8': include_once('language/lang_ru.php'); break;
    case 'english': include_once('language/lang_en.php'); break;
	case 'spanish-utf8': include_once('language/lang_es.php'); break;
	case 'francais-utf8': include_once('language/lang_fr.php'); break;
    default:include_once('language/lang_ru.php');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $mod_name; ?></title>
    <link href="<?php echo MODULE_PATH; ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo MODULE_PATH; ?>css/theme.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php
    if(get_tables_count($prefix)==true){//вызов функции и проверка выведеного ней значения

        if(isset($post['action'])) {
            $action=$post['action'];
        }
        elseif(isset($get['action'])){
            $action=$get['action'];
        }
        else{
            $action='main';
        }
        require_once 'tpl/menu.php';

        switch($action) {
            case 'main': include_once('pages/main.php'); break;
            case 'forms': include_once('pages/forms.php'); break;
            case 'settings': include_once('pages/settings.php'); break;
            default: include_once('pages/main.php');
        }
    }
    else{
        echo "
		<div class='row'>
			<div class='col-md-4'></div>
			<div class='col-md-4'>
				 ".$lang["have_not_created_a_table"]."
			</div>
			<div class='col-md-4'></div>
		</div>
		<div class='row'>
			<div class='col-md-4'></div>
			<div class='col-md-4'>
				<a href=".$mod_page."&action=mform&cr_table=all class='btn btn-success btn-lg'><span class='glyphicon glyphicon-plus-sign'></span>".$lang["create_table"]." </a>
			</div>
			<div class='col-md-4'></div>
		</div>";
    }
    require_once 'tpl/footer.php';/**/
    ?>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="<?php echo MODULE_PATH; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo MODULE_PATH; ?>/js/holder.js"></script>
    <script src="<?php echo MODULE_PATH; ?>/js/jqBootstrapValidation.js"></script>
    <script>
        $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
    </script>
</div>
</body>
</html>