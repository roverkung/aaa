<?php
  $user = $_POST['user'];
  $pass = $_POST['pass'];
  $challenge = $_POST['challenge'];
  $keyhandle = $_POST['keyhandle'];
  $regtype = $_POST['regtype'];

  require_once('U2F.php');

  if ($user == ""){
    echo '<script type="text/javascript">location.replace("login2.php")</script>';
  }
  else
  {
        include '/var/www/html/DB/linktest.php';
        if($_SESSION["connok"]=="N")
        {
          echo '<script type="text/javascript">alert("ConnectDB Error")</script>';
          echo '<script type="text/javascript">location.replace("login2.php")</script>';
        }
        else
        {
            $result=pg_query("select * from  client where userid='$user'");
            $row=pg_fetch_array($result);
            if($row){
              //do not insert userid
            }
            else
            {
              //echo '<script type="text/javascript">alert("Here")</script>';
              $q="insert into client (userid,passwd) values ('$user','$pass')";
              $sql=pg_query($q);
            }

            $challenge = str_replace(";","",$challenge);
            $keyhandle = str_replace("[","",$keyhandle);
            $keyhandle = str_replace("]","",$keyhandle);
            $keyhandle = str_replace(";","",$keyhandle);

            if ($regtype != "reg") {
              $result=pg_query("select count(userid)cnt from devices where userid='$user'");
              $row=pg_fetch_array($result);

	      $num_rows = $row['cnt']+1;

              $q="insert into devices select '$user','$challenge','$keyhandle','$num_rows'";
              $sql=pg_query($q);
            }

            echo "<font size=4>userid=" . $user . ";\n";
            echo "<p>";
            echo "<font size=4>passwd=" . $pass . ";\n";
            echo "<p>";

            echo "<font size=4>Challenge: " . $challenge . ";\n";
            echo "<p>";
            echo "<font size=4>KeyHandle: " . $keyhandle . ";\n";
            echo '<script type="text/javascript">alert("Register Successful")</script>';
            //echo '<script type="text/javascript">location.replace("login2.php")</script>';
          //}

          //else
          //{
            //echo '<script type="text/javascript">alert("帳號已註冊")</script>';
            //echo '<script type="text/javascript">location.replace("login2.php")</script>';
          //  echo "userid = " .  $user;
          //}
        }
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>U2F Server Register Data</title>
  </head>
  <body>
    <P>
    <font size=8 color=red><?php echo $userid ?>U2F Server Register Data </font>
    <P>
    <form  method="POST" id="form" action="login2.php">
    <button name="homepage" type="submit" >HOME</button>

   </form>
 </body>
</html>


