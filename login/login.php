<?php
include '/var/www/html/login/pubfunc.js';
?>

<html>
<head>
<title>This is test</title>
</head>
<FORM METHOD=POST ACTION="auth.php" onsubmit="javascript:return LoginDataChk('account','password');">
username: <INPUT TYPE="text" NAME="account" ID="account"><BR>
<p>
password: <INPUT TYPE="text" NAME="password" ID="password"><BR>
<p>
<INPUT TYPE="submit" value="LOG IN">


<button type="button" onclick="location.href='sign.php'">
SIGN UP
</button>
</FORM>
</html>
