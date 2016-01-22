<?php
$pag_val = '';
$where = 1;
$get_st ='';
$num = 10;
$page =1;
$status = array();
$form = array();
$uid=$modx->getLoginUserID();// получаем ID текущего пользователя


$color['1']='default';
$color['2']='primary';
$color['3']='success';
$color['4']='info';
$color['5']='warning';
$color['6']='danger';


//var_dump($_POST);
//var_dump($_GET);

//удалнгие значений
if (isset($_GET["del_value"])){
    $modx->db->query('DELETE FROM '.$modx->getFullTableName('mform_value').' WHERE `id` = "'.$_GET['del_value'].'"');
    $info .= $lang["info_value_del"];
}

include_once("function_trim.php");
foreach($_POST as $k=>$v){
    $v=func_trim($v);
    if($v!=""){
        $post[$k]=$v;
    }
}
foreach($_GET as $k=>$v){
    $v=func_trim($v);
    if($v!=""){
        $get[$k]=$v;
    }
}

//сохранение комментариев
if (isset($post["save"])){
    if($get["edit_value"]!=""){
        $modx->db->query("
UPDATE ".$modx->getFullTableName('mform_value')."
SET `comment` = '".$post["comment"]."',
`id_status` = '".$post["status"]."',
`date_com` = '".date("Y-m-d H:i:s")."',
`id_admin`= '".$uid."'
 WHERE `id` =".$get["edit_value"].";");
    }
}


//Формирование в запрос where и get_st для постраничного вывода
if (isset($get['form'])){
    $where ='id_form='.$get['form'];
    $get_st .='&form='.$get['form'].'';
}
if (isset($get['status'])){
    $get_st .='&status='.$get['status'].'';
    if($where!=1){
        $where .=' and id_status='.$get['status'];
    }
    else{
        $where='id_status='.$get['status'];
    }
}

//создание массива форм
$result2 =$modx->db->query('SELECT `id`, `name`, `email`,`message` FROM '.$modx->getFullTableName('mform_forms').' ORDER BY `id` ASC');
while($row2 = $modx->db->getRow($result2)){
    $form[$row2["id"]]=array("name"=>$row2["name"],"email"=>$row2["email"]);
}


//========== пагинация =================
if (isset($get['page'])) {$page = $get['page']; }
// Определяем общее число сообщений в базе данных
$result1 = $modx->db->query('SELECT COUNT(*)as count FROM '.$modx->getFullTableName('mform_value').' WHERE '.$where);
while($row1 = $modx->db->getRow($result1))
{$posts=$row1['count'];}
$total = intval(($posts - 1) / $num) + 1;
$page = intval($page);
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
$start = $page * $num - $num;

// Проверяем нужны ли стрелки назад
if ($page != 1) {
    $pervpage = '<li><a href= "'.$mod_page.'&page=1'.$get_st.'">&laquo;</a></li>
         <li><a href= "'.$mod_page.'&page='. ($page - 1) .''.$get_st.'"><</a></li>';
}
else{
    $pervpage='<li class="disabled"><a>&laquo;</a></li> <li class="disabled"><a><</a></li>';
}
// Проверяем нужны ли стрелки вперед
if ($page != $total){
    $nextpage=' <li><a href= "' . $mod_page . '&page=' . ($page + 1) . '' . $get_st . '">></a></li>
<li><a href= "' . $mod_page . '&page=' . $total . ' ' . $get_st . '">&raquo;</a></li>';
}
else {
    $nextpage = '<li class="disabled"><a>></a></li> <li class="disabled"><a>&raquo;</a></li>';
}

// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 2 > 0) {$page2left = '<a href= "'.$mod_page.'&page='. ($page - 2) .''.$get_st.'">'. ($page - 2) .'</a>'; }
else {$page2left = '';}
if($page - 1 > 0) $page1left = '<a href= "'.$mod_page.'&page='. ($page - 1) .''.$get_st.'">'. ($page - 1) .'</a>';
else {$page1left = '';}
if($page + 2 <= $total) $page2right = '<a href= "'.$mod_page.'&page='. ($page + 2) .''.$get_st.'">'. ($page + 2) .'</a>';
else {$page2right = '';}
if($page + 1 <= $total) $page1right = '<a href= "'.$mod_page.'&page='. ($page + 1) .''.$get_st.'">'. ($page + 1) .'</a>';
else {$page1right = '';}

$pagination ='
          <ul class="pagination">
            '.$pervpage.'
            <li>'.$page2left.'</li>
            <li>'.$page1left.'</li>
            <li><a href=""><b>'.$page.'</b></a></li>
            <li>'.$page1right.'</li>
            <li>'.$page2right.'</li>
            '.$nextpage.'
          </ul>';
//===========================

$result4 =$modx->db->query('SELECT `id`,`name`,`color` FROM '.$modx->getFullTableName('mform_status'));
//вытягиваються статусы
while($row4 = $modx->db->getRow($result4)){//перебираються все статусы и заносяться в массив
    $status[$row4["id"]]["color"]=$row4["color"];
    $status[$row4["id"]]["name"]=$row4["name"];
}

//var_dump($status);


$s ='';
$t=0;//Проверяет, или  использовался блок while, чтобы не выводить пагинацию
$result =$modx->db->query('
SELECT `id`, `id_status`, `id_form`, `id_page`, `id_user`, `value` , `date`, `comment`, `date_com`,`id_admin`
FROM '.$modx->getFullTableName('mform_value').'
WHERE '.$where.'
ORDER BY `id` DESC
LIMIT '.$start.', '.$num);
while($row = $modx->db->getRow($result)){
    $t++;
    if($row["value"]!=""){
        $value=unserialize($row["value"]);
    }
    $userinfo = $modx->getUserInfo($row["id_admin"]);//достаем информацию о пользователе
    $out1= $userinfo["username"];
    if($userinfo["fullname"]!=""){
        $out2= "(".$userinfo["fullname"].")";
    }
    $document=$modx->getDocument($row['id_page']);
    $tovar_info= '<small><a target="_blank" title="перейти на страницу" href="'.$modx->makeUrl(intval($row['id_page'])).'">'.$document['pagetitle'].'</a>('.$row['id_page'].')</small> ';//ccылка

    $mes="";//сюда будут присаться value
    foreach($value as $k=>$v){
        if($k!=""){//проверка на пустоту записаную в базу у значения
            $mes .="<b>".$k."</b>:".$v."</p>";
        }
    }
//собирание блока вывода комментариев
    if($row["comment"]!=""){
        $comment="<div class='alert alert-success'>
    <div class='row'>
        <div class='col-xs-12'><h4>".$row["date_com"]."</h4></div>
    </div>
    <div class='row'>
        <div class='col-xs-12'>".$out1." ".$out2."</div>
    </div>
    ".$row["comment"]."
</div>";
    }
    else{
        $comment="";
    }
    $option2 ="";

    foreach($status as $k=>$v){//формирование option и li для статусов
        if($row["id_status"]==$k){
            $sel2='selected="selected"';
        }
        else{
            $sel2="";
        }
        $option2 .= '<option '.$sel2.' value="'.$k.'">'.$status[$k]["name"].'</option>';
    }
    $status_out ='';
//echo "id_status=".$row["id_status"];
    if (isset($row["id_status"])){
        if (($row["id_status"]!=0 and $row["id_status"]!="") and count($status)>=1 and array_key_exists($row["id_status"],$status)){
            $status_out ="<span class='label label-".$status[$row["id_status"]]["color"]."'>".$status[$row["id_status"]]["name"]."</span>";
        }
    }
    /*$option2 ="";

    foreach($status as $k=>$v){//формирование option и li для статусов
        if($row["id_status"]==$k){
            $sel2='selected="selected"';
        }
        else{
            $sel2="";
        }
        $option2 .= '<option '.$sel2.' value="'.$k.'">'.$status[$k]["name"].'</option>';
    }*/
    if(isset($form[$row["id_form"]]["name"])){
        $form_name=$form[$row["id_form"]]["name"];
    }else{
        $form_name="id=".$row["id_form"];
    }
    $s .="<div class='panel panel-primary'>
<div class='panel-heading'>
    <div class='row'>
        <div class='col-xs-5'> <h3 class='panel-title'>".$lang["date"].": ".$row["date"]." №: ".$row["id"]."</h3></div>
        <div class='col-xs-3'>
            <h3 class='panel-title'>".$form_name."</h3>
        </div>
        <div class='col-xs-4'>
            <button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#Modal_edit_com".$row["id"]."'><span class='glyphicon glyphicon-pencil'></span> ".$lang["edit"]."</button>
            <a href='".$mod_page."&action=main&del_value=".$row["id"]."' class='btn btn-danger btn-xs'  onclick='return confirm(\"".$lang["form_delete"]."\")'><span class='glyphicon glyphicon-trash'></span> ".$lang["delete"]."</a>
        </div>
    </div>
</div>
<div class='panel-body'>
    <div class='col-xs-4'>
        ".$lang["user"].": ".$row["id_user"]."<br>
        ".$lang["page"].": ".$tovar_info."<br>
        ".$mes."
    </div>
    <div class='col-xs-3'>
        ".$status_out."
    </div>
    <div class='col-xs-5'>
        ".$comment."
    </div>
</div>
</div>


<div class='modal fade' id='Modal_edit_com".$row["id"]."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class='modal-header'>
<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
<h4 class='modal-title' id='myModalLabel'>".$lang["edit_value"]."</h4>
</div>
<div class='modal-body'>
    <form action='".$mod_page."&action=main&edit_value=".$row["id"]."' method='post'>
        <div class='form-group'>
            <label for='comment'>".$lang['comment']."</label><span class='text-danger'>*</span>
            <textarea class='form-control' id='comment' name='comment' rows='3'>".$row["comment"]."</textarea>
        </div>
        <label for='status'>".$lang['select_status'].":</label>
        <select class='form-control' name='status' id='status'>
                ".$option2."
        </select>
        <div class='modal-footer'>
            <button type='cancel' class='btn btn-default' data-dismiss='modal'>".$lang["cancel"]."</button>
            <button type='submit' name='save' class='btn btn-primary' value='1'>".$lang["save"]."</button>
        </div>
  </form>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->";

}

if($t==0){
    $info .= $lang["no_value"];
}else
{ $pag_val=$pagination.$s.$pagination;}


// Формирование выпадающего списка для выбора формы
$li="";
if(count($form)!=0){
    foreach ($form as $k => $v) {
        if(isset($get['status'])){
            $stat_l ='&status='.$get["status"];
        }
        else{
            $stat_l ='';
        }
        $li .='<li><a href="'.$mod_page.'&form='.$k.$stat_l.'">'.$form[$k]["name"].'</a></li>';//собираються поля списка форм без учета статусов
    }
}
$form_text ='';
//Если была выбрана форма для фильтрации-то в выпадающем списке меняеться имя
if(isset($get['form'])){
    if($get['form']!="" and $get['form']!="0"){
        //echo 'form="'.$get['form'].'"';
        $form_text=$form[$get['form']]["name"];
    }
    else
    {
        $form_text=$lang["choose_form"];
    }
}
else
{
    $form_text=$lang["choose_form"];
}
//Если был выбран статус то для фильтрации в выпадающем списке меняеться имя
$status_text ='';
if(isset($get['status'])){
    $status_text=$status[$get['status']]["name"];
}
else{
    $status_text=$lang["choose_status"];
}
//формирование li для статусов
$li2 ="";
if(count($status)!=0){
    foreach($status as $k=>$v){
        if(isset($get['status'])){
            if($get['status']==$k){
                $active2='class="active"';
            }
            else{
                $active2="";
            }
        }
        else{
            $active2="";
        }

        if(isset($get["form"])){
            if($get["form"]!=""){
                $form_l='&form='.$get["form"];
            }
            else{
                $form_l="";
            }
        }
        else{
            $form_l="";
        }
        $li2 .='<li '.$active2.'><a href="'.$mod_page.'&status='.$k.$form_l.'">'.$status[$k]["name"].'</a></li>';
    }
}
if ($info!='') {
    $info = '<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     '.$info.'
</div>';
}

//Полный вывод страници
echo ''.$info.'
<div class="btn-group">
<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
'.$form_text.'<span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
'.$li.'
</ul>
</div>
<div class="btn-group">
<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
'.$status_text.' <span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
'.$li2.'
</ul>
</div>
<br />'.$pag_val;

?>