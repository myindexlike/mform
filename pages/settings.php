<?php
$color['1']='default';
$color['2']='primary';
$color['3']='success';
$color['4']='info';
$color['5']='warning';
$color['6']='danger';
$prefix = $modx->db->config['table_prefix'];//вытягивание префикса таблицы
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
//var_dump($post); 

if(isset($get["del_status"])){
    $modx->db->query("DELETE FROM ".$modx->getFullTableName('mform_status')."
    WHERE `".$prefix."mform_status`.`id` = '".$get['del_status']."'");
    $info .= $lang["info_status_del"];

}
if(isset($post["save"]) and isset($post["status_name"])){
    $modx->db->query("INSERT INTO ".$modx->getFullTableName('mform_status')."
    (`id`,`name`,`color`)
    VALUES (NULL,'".$post["status_name"]."','".$post["color"]."');");
    $info .=$lang["info_status_upd1"]." <b>".$post["status_name"]."</b> ".$lang["info_status_add"]." ";

}
if(isset($post["edit"])){
    if (!empty($post["status_name"])){
        $modx->db->query("
        UPDATE ".$modx->getFullTableName('mform_status')."
        SET `name` = '".$post["status_name"]."',
        `color` = '".$post["color"]."'
         WHERE `id` =".$get["id_status"].";");

        $info .=$lang["info_status_upd1"]." <b>".$post["status_name"]."</b> ".$lang['info_status_upd2'];
    }else{
        $error .= $lang["info_status_no_name"];
    }

}
if ($info!='') {
    $info = '<div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             '.$info.'
     </div>';
}
if ($error!='') {
    $error = '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             '.$error.'
     </div>';
}

$tr="";
$result =$modx->db->query('
SELECT `id`,`name`,`color`
FROM '.$modx->getFullTableName('mform_status').'
ORDER BY `id` DESC
');

while($row = $modx->db->getRow($result)){
    $option="";
    foreach ($color as $k=>$v){
        if($color[$k]==$row["color"]){
            $sel='selected="selected"';
        }
        else{
            $sel='';
        }
        $option.='<option '.$sel.' value='.$color[$k].'>'.$lang["color_".$k.""].'</option>';
    }



    $modal_edit_status='<div class="modal fade" id="Modal_edit_status'.$row["id"].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"Редактирование статуса</h4>
  </div>
  <div class="modal-body">
                <form action="'.$mod_page.'&action=settings&id_status='.$row["id"].'" method="post">
                    <div class="form-group">
                        <label for="status_name">'.$lang["status_name"].'</label><span class="text-danger">*</span>
                        <input type="text" name="status_name" class="form-control" id="status_name" placeholder="" value="'.$row["name"].'" required>
                    </div>
                        <label for="color">'.$lang["color"].'</label>
                        <select class="form-control" name="color" id="color">
                            '.$option.'
                        </select>
                    <div class="modal-footer">
                        <button type="cancel" class="btn btn-default" data-dismiss="modal">'.$lang["cancel"].'</button>
                        <button type="submit" name="edit" class="btn btn-primary">'.$lang["save"].'</button>
                    </div>
                </form>
        </div>
  </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->';

    $tr.='
    <tr>
        <td><span class="label label-'.$row["color"].'">'.$row["name"].'</span></td>
        <td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#Modal_edit_status'.$row["id"].'"> <span class="glyphicon glyphicon-pencil"></span> '.$lang["edit"].'</button>
        <a href="'.$mod_page.'&action=settings&del_status='.$row["id"].'"" class="btn btn-danger btn-xs" onclick="return confirm(\''.$lang["form_delete"].'\')"><span class="glyphicon glyphicon-trash"></span> '.$lang["delete"].'</a>
        </td>
        '.$modal_edit_status.'
    </tr>';



}

$panel1='
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8"><h3 class="panel-title">'.$lang["statuses"].'</h3></div>
            <div class="col-xs-4"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_add_status"><span class="glyphicon glyphicon-plus-sign"></span> '.$lang["status_add"].'</button></div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table">
        '.$tr.'
        </table>
    </div>
</div>';
$out='
<div class="row">
<div class="col-md-6">'.$panel1.'</div>
<div class="col-md-6"></div>
</div>';
echo $info.$error.$out;
?>
<div class="modal fade" id="Modal_add_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $lang["status_add_modal"];?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $mod_page.'&action=settings';?>" method="post">
                    <div class="form-group">
                        <label for="status_name"><?php echo $lang["status_name"];?></label><span class="text-danger">*</span>
                        <input type="text" name="status_name" class="form-control" id="status_name" placeholder="" required>
                    </div>
                    <label for="color"><?php echo $lang["color"];?></label>
                    <select class="form-control" name="color" id="color">
                        <option value="default"><?php echo $lang["color_1"];?></option>
                        <option value="primary"><?php echo $lang["color_2"];?></option>
                        <option value="success"><?php echo $lang["color_3"];?></option>
                        <option value="info"><?php echo $lang["color_4"];?></option>
                        <option value="warning"><?php echo $lang["color_5"];?></option>
                        <option value="danger"><?php echo $lang["color_6"];?></option>
                    </select>
                    <div class="modal-footer">
                        <button type="cancel" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel"];?></button>
                        <button type="submit" name="save" class="btn btn-primary" value="1"><?php echo $lang["save"];?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

