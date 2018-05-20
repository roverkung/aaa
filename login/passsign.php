<?php
  $vUser=$_GET['userid'];
  $vPasswd=$_GET['passwd'];
  $vchallenge=$_GET['challenge'];
  $vkeyhandle=$_GET['keyhandle'];
  $vregtype=$_GET['regtype'];


?>
<html>
  <head>
    <meta charset="utf-8">
    <title>註冊參數</title>
  </head>
  <body onload="document.form.submit()">
    <FORM id="form" name="form" METHOD=POST ACTION="addu2f.php">
        username: <input type="text" name="user" ID="user" value='<?php echo $vUser ?>'>
        <p>
        password: <input type="text" name="pass" ID="pass" value='<?php echo $vPasswd ?>'>
        <p>
        challenge: <input type="text" name="challenge" ID="challenge" value='<?php echo $vchallenge ?>'>
        <p>
        keyhandle: <input type="text" name="keyhandle" ID="keyhandle" value='<?php echo $vkeyhandle ?>'>
        <p>
        keyhandle: <input type="text" name="regtype" ID="regtype" value='<?php echo $vkeyhandle ?>'>


    </FORM>
  </body>
</html>


