<?php
include '/var/www/html/login/pubfunc.js';
?>

<html>
<head>
<title>This is test</title>
</head>
<FORM if="form"  METHOD=POST ACTION="auth.php" onsubmit="javascript:return LoginDataChk('user','pass');">
Username: <INPUT TYPE="text" NAME="user" ID="user"><BR>
<p>
Password: <INPUT TYPE="text" NAME="pass" ID="pass"><BR>

<INPUT TYPE="hidden" NAME="btnid" ID="btnid"><BR>


<p>
<INPUT TYPE="submit" value="Register ID & U2F" onclick="document.getElementById('btnid').value='1';">

<INPUT TYPE="submit" value="Login With U2F" onclick="document.getElementById('btnid').value='2';">

</button>
</FORM>
</html>
