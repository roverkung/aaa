<?php
    include '/var/www/html/login/pubfunc.js';
?>


<html>
  <head>
    <meta charset="utf-8">
    <title>註冊頁面tt</title>
  </head>
    <FORM METHOD=POST ACTION="u2freg.php" onsubmit="javascript:return LoginDataChk('user','pass');">
        username: <input type="text" name="user" ID="user" value=""panel placeholder="輸入帳號">
        <p>
        password: <input type="text" name="pass" ID="pass" value=""panel placeholder="輸入密碼">
        <p>
        <input type="submit" name="startRegister" value="註冊">

    </FORM>
</html>


