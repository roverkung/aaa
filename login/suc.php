<?php
  $userid = $_POST['user'];
  $pas = $_POST['pass'];
  if ($userid == ""){
    echo '<script type="text/javascript">location.replace("login2.php")</script>';
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>登入成功</title>
  </head>
  <body>
    <font size=12 color=red>Hello , <?php echo $userid ?></font>

    <form  method="POST" id="form" action="u2freg.php">
    <button name="startRegister" type="submit" >Register U2F KEY</button>
    <button type="button" onclick="location.href='login2.php'">HOME
    <input type="hidden" name="user" ID="user" value='<?php echo $userid?>'>
    <INPUT TYPE="hidden" NAME="pass" ID="pass" value='<?php echo $pas?>'>
    <INPUT TYPE="hidden" NAME="regtype" ID="regtype" value="reg">

   </form> 
 </body>
</html>


