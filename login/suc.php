<?php
  $userid = $_POST['user'];
  $pas = $_POST['pass'];
  $keyhandle = $_POST['keyhandle'];

?>
<html>
  <head>
    <meta charset="utf-8">
    <title>登入成功</title>
  </head>
  <body>
    <font size=12 color=red>Hello , <?php echo $userid ?></font>

    <form  method="POST" id="form" action="u2freg.php">
    <button name="startRegister" type="submit" >Register</button>
    <input type="hidden" name="user" ID="user" value='<?php echo $userid?>'>
    <INPUT TYPE="hidden" NAME="pass" ID="pass" value='<?php echo $pas?>'>
    <INPUT TYPE="hidden" NAME="keyhandle" ID="keyhandle" value='<?php echo $keyhandle?>'>

   </form> 
 </body>
</html>


