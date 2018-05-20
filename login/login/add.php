<?php
$user = $_POST['user'];
$password = $_POST['pass'];
if ($user == ""){
  echo '<script type="text/javascript">location.replace("login2.php")</script>';
  echo '<script type="text/javascript">alert("NO")</script>';
}
else
{
	include '/var/www/html/DB/linktest.php';
	$user = $_POST['user'];
        $password = $_POST['pass'];
        if($_SESSION["connok"]=="N")
	{
          echo '<script type="text/javascript">alert("ConnectDB Error")</script>';
	  echo '<script type="text/javascript">location.replace("login2.php")</script>';
	}
	else
	{
	  $result=pg_query("select * from  client where userid='$user'");
          $row=pg_fetch_array($result);
	  if($row)
	  {
	    echo '<script type="text/javascript">alert("帳號已註冊")</script>';
            echo '<script type="text/javascript">location.replace("sign.php")</script>';
	  }

	  else 
	  {
	    $q="insert into client (userid,passwd) values ('$user','$password')";
	    $sql=pg_query($q);
            echo '<script type="text/javascript">alert("Register Successful")</script>';
            echo '<script type="text/javascript">location.replace("login2.php")</script>';
	  }
	}
}
?>




