<?php
//--Если передана капча
if ( isset($_POST['captcha']) ){
    $code = $_POST['captcha']; //--Получаем введенную пользователем капчу
    session_start();
    //--Сравниваем
    if ( isset($_SESSION['captcha']) && strtoupper($_SESSION['captcha']) == strtoupper($code) )
        echo 'Правильно!';
    else
        echo 'Неправильно!';
    //--Удаляем из сессии код капчи
    unset($_SESSION['captcha']);
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Тестовая страница</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <img src="captcha.php?sid=<?php echo rand(10000, 99999); ?>" width="120" height="20" alt="" /><br />
    <input type="text" name="captcha" /><br />
    <input type="submit" value="Проверить" />
</form>
</body>
</html>