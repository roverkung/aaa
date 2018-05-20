<?php
	include '/var/www/html/DB/linktest.php';
	$use = $_POST['user'];
	$pas = $_POST['pass'];
        $authtype = $_POST['btnid'];
        $actionwww = "";
        $auth = "N";
        if($_SESSION["connok"]=="N")
        {
          echo '<script type="text/javascript">alert("ConnectDB Error")</script>';
          echo '<script type="text/javascript">location.replace("login2.php")</script>';
        }
        else {
          $result=pg_query("select * from  client where userid='$use'");
          $row=pg_fetch_array($result);
          if ($row)
  	  {
            if($pas==$row["passwd"])
	    {
              if ($authtype=="1"){  //1.LOGIN
                echo '<script type="text/javascript">alert("Login Successful, You can Register your U2F key!")</script>';
              }
              else {                //2.SIGN UP
                 $result=pg_query("select * from  devices where userid='$use'");
                 //$row=pg_fetch_array($result);
                 if ($result) {
                    echo '<script type="text/javascript">alert("Sign up Successful,Please Insert Your U2F Key")</script>';
                    $regtype = "auth";
                    $actionwww = "doAuthenticate";
                 }
                 else {
                   echo '<script type="text/javascript">alert("Please Register you U2F Key First!")</script>';
                 }
              }
	    }
	    else
	    {
               if ($authtype=="1"){  //1.LOGIN            account as registered
                  echo '<script type="text/javascript">alert("帳號已註冊")</script>';
                  echo '<script type="text/javascript">location.replace("login2.php")</script>';
               } else {    //password error
                  echo '<script type="text/javascript">alert("密碼錯誤")</script>';
 	          echo '<script type="text/javascript">history.back()</script>';
               }
	    }
          }
	  else
	  {
             if ($authtype=="1"){  //1.LOGIN            account as registered
   	        $q="insert into client (userid,passwd) values ('$use','$pas')";
	        $sql=pg_query($q);
                echo '<script type="text/javascript">alert("Register ID Successful! You Can Register your U2F KEY!")</script>';
             } else {
                echo '<script type="text/javascript">alert("無此帳號")</script>';
    	        echo '<script type="text/javascript">location.replace("login2.php")</script>';
             }
	  }
        }
/*	else if($_POST[account] == $use && $_POST[password] == $pas){
  		header("Location:suc");
	}
	else{
 		header("Location:fail");
		}

*/
?>
<html>
 <body>
  <FORM id="form" METHOD=POST ACTION="suc.php">
       <INPUT TYPE="hidden" NAME="user" ID="user" value='<?php echo $use?>'>
       <INPUT TYPE="hidden" NAME="pass" ID="pass" value='<?php echo $pas?>'>
       <INPUT TYPE="hidden" NAME="keyhandle" ID="keyhandle" value='<?php echo $keyhandle?>'>
       <INPUT TYPE="hidden" NAME="regtype" ID="regtype" value='<?php echo $regtype?>'>
       <INPUT TYPE="hidden" name="doAuthenticate" id="doAuthenticate"/>

       <?php
          if ($actionwww != "") {
            echo '<script type="text/javascript">form.action="u2freg.php";</script>';
          }
          echo '<script type="text/javascript">form.submit();</script>';
       ?>
  </FORM>
 </body>
</html>




