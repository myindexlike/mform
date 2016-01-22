<?php
function getFT($type_m=1){
    global $modx, $info, $error, $log, $mod_page, $lang, $type;
    $type = array(
        1 => "".$lang["type_field_1"]."",
        2 => "".$lang["type_field_2"]."",
        3 => "".$lang["type_field_3"]."",
        4 => "".$lang["type_field_4"]."",
        5 => "".$lang["type_field_5"]."",
        6 => "".$lang["type_field_6"]."",
        7 => "".$lang["type_field_7"].""
    );
    $select = "<label for='field_type'>".$lang["field_type"]."</label>
					<select class='form-control' name='field_type' id='field_type'>";
    foreach ($type as $k => $v) {
        if ($type_m==$k){
            $sel='selected="selected"';
        }
        else {$sel=''; }
        $select .= '<option '.$sel.' value="'.$k.'">'.$v.'</option>';
    }
    $select .= "</select>";
    return $select;
}
?>