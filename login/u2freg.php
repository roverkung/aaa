<?php
  if($_POST['user']!="")
  {
    $use = $_POST['user'];
    $pas = $_POST['pass'];
    //$keyhand = $_POST['keyhandle'];
    $regtype = $_POST['regtype'];
    $challenge = $_POST['challenge'];

    require_once('U2F.php');
    $scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
    $u2f = new u2flib_server\U2F($scheme . $_SERVER['HTTP_HOST']);
  } else {
    echo '<script type="text/javascript">location.replace("login2.php")</script>';
  }
?>

<html>
<head>
    <title>PHP U2F Demo</title>

    <script src="u2f-api.js"></script>
    <script>
        function addRegistration(reg) {
            var vID = '<?php echo $use?>';
            var vPW = '<?php echo $pas?>';
            var vChan = '<?php echo $challenge?>';
            var autoclick = '<?php echo $regtype?>';

            var regobj = JSON.parse(reg);
            var data = null;
            //alert("3--" + reg);

            if(data == null) {
                data = [regobj];
            }
            Keystr=JSON.stringify(data);
            if (autoclick == "reg") {
              alert("Registration Successful!");
            } else {
              alert("Authentication successful!");
            };

            location.replace("passsign.php?keyhandle="+Keystr+"&userid="+vID+"&passwd="+vPW+"&challenge="+vChan+"&regtype="+autoclick);
            //location.replace("passsign.php?keyhandle="+JSON.stringify(data)+"&userid="+vID+"&passwd="+vPW+"&challenge="+vChan);

            //location.replace("add.php?challenge="+JSON.stringify(data));
            //alert(JSON.stringify(data));
            //localStorage.setItem('u2fregistration', JSON.stringify(data));
        }
        <?php
        function fixupArray($data) {
            $ret = array();
            $decoded = json_decode($data);
            foreach ($decoded as $d) {
                $ret[] = json_encode($d);
            }
             //echo "alert('001');\n";
            //echo "alert(5--'".$decoded ."');\n";
            return $ret;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

	    include '/var/www/html/DB/linktest.php';
	    $result=pg_query("select * from  devices where userid='$use'");
	    if ($result) {
    	       $keyhand ="";
	       while($row=pg_fetch_array($result)) {
	          if ($keyhand == "") {
	             $keyhand = $row["bind_data"];
	          } else {
	             $keyhand = $keyhand .','.$row["bind_data"];
	          }
	          $keyhand = '['.$keyhand.']';
	       }
	    }

            //echo "alert('444');\n";

            if(isset($_POST['startRegister'])) {
                //echo "alert('002');\n";
                //echo "alert('regstration= ".$keyhand."');\n";
                $regs = json_decode($keyhand) ? : array();
                //$regs = json_decode($_POST['registrations']) ? : array();
                list($data, $reqs) = $u2f->getRegisterData($regs);
                echo "var request = " . json_encode($data) . ";\n";
                echo "var signs = " . json_encode($reqs) . ";\n";
        ?>


            setTimeout(function() {
              //alert("9--" + request);
              //alert("10--" + signs);
              console.log("Register: ", request);

	      u2f.register([request], signs, function(data) {
                var form = document.getElementById('form');
                var reg = document.getElementById('doRegister');
                var req = document.getElementById('request');
                console.log("Register callback", data);

                document.getElementById('keyhandle').value='<?php echo $keyhand?>';
                document.getElementById('registrations').value='<?php echo $keyhand?>';

                if(data.errorCode && data.errorCode != 0) {
                    alert("registration failed with errror: " + data.errorCode);

                    form.action="suc.php";
                    form.submit();
                    return;
                }
                reg.value=JSON.stringify(data);
                req.value=JSON.stringify(request);

                document.getElementById('challenge').value=JSON.stringify(request);
                //document.getElementById('challenge').value=data;
                //alert('003');
                form.submit();
            });
        }, 1000);
        <?php
            } else if($_POST['doRegister']) {
                try {
                    //echo "alert('004');\n";
                    $data = $u2f->doRegister(json_decode($_POST['request']), json_decode($_POST['doRegister']));
                    echo "var registration = '" . json_encode($data) . "';\n";

        ?>
        addRegistration(registration);
        //alert("Registration End!");


        <?php
                } catch(u2flib_server\Error $e) {
                    echo "alert('error:" . $e->getMessage() . "');\n";
                }
            } else if(isset($_POST['startAuthenticate'])) {
                //echo "alert('005');\n";
                $regs = json_decode($_POST['registrations']);
                $data = $u2f->getAuthenticateData($regs);
                echo "var registrations = " . $_POST['registrations'] . ";\n";
                echo "var request = " . json_encode($data) . ";\n";
        ?>
        setTimeout(function() {
            console.log("sign: ", request);
            u2f.sign(request, function(data) {
                document.getElementById('registrations').value='<?php echo $keyhand?>';

                var form = document.getElementById('form');
                var reg = document.getElementById('doAuthenticate');
                var req = document.getElementById('request');
                var regs = document.getElementById('registrations');
                console.log("Authenticate callback", data);
                reg.value=JSON.stringify(data);
                req.value=JSON.stringify(request);
                regs.value=JSON.stringify(registrations);
                document.getElementById('regtype').value="auth";
                form.submit();
            });
        }, 1000);
        <?php
            } else if($_POST['doAuthenticate']) {
               //echo "alert('006');\n";
               //echo "alert('666==".$keyhand."');\n";

                $reqs = json_decode($_POST['request']);
                $regs = json_decode($_POST['registrations']);
                //$regs = json_decode($keyhand);

                try {
                    $data = $u2f->doAuthenticate($reqs, $regs, json_decode($_POST['doAuthenticate']));
                    echo "var registration = '" . json_encode($data) . "';\n";
                    echo "addRegistration(registration);\n";
                    echo "alert('Authentication successful, counter:" . $data->counter . "');\n";
                } catch(u2flib_server\Error $e) {
                    echo "alert('error:" . $e->getMessage() . "');\n";
                }
            }
        }
        ?>
    </script>

</head>
<body>
<form method="POST"  id="form">
    <p>
    <font size=12 color=red>Please Touch U2F Key</font>

    <input type="hidden" name="doRegister" id="doRegister"/>
    <P>
    <button name="startAuthenticate" type="submit" id="startAuthenticate">Authenticate</button>
    <P>
    doAutienticate<input type="text" size="300" name="doAuthenticate" id="doAuthenticate"/>
    <P>
    request <input type="text" size="300" name="request" id="request"/>
    <p>
    registration: <input type="text" size="300" name="registrations" id="registrations" value='<?php echo $keyhand?>'>

    <p>
    <input type="hidden" name="user" id="user" value='<?php echo $use?>'>
    <p>
    <input type="hidden" name="pass" id="pass" value='<?php echo $pas?>'>
    <p>
    <input type="hidden" name="challenge" id="challenge" value='<?php echo $challenge?>'>
    <p>
    <INPUT TYPE="hidden" NAME="keyhandle" ID="keyhandle">
    <p>
    <INPUT TYPE="hidden" NAME="regtype" ID="regtype" value='<?php echo $regtype?>'>



<p>
    <forn size=8><span id="registered">0</span> Authenticators currently registered.</font>
</p>


    <script>
        var reg = '<?php echo $keyhand?>';
        //var reg = document.getElementById('registrations').value;
        var autoclick = '<?php echo $regtype?>';
        var auth = document.getElementById('startAuthenticate');
        //alert("1==>reg= "+ reg);
        //alert("2==>reg= "+ auth);

        if(reg == null) {
          alert("reg empty");
        } else {
            var regs = reg;
            decoded = JSON.parse(reg);
            if(!Array.isArray(decoded)) {
              alert("Array EMPTY");
            } else {
                regs.value = reg;
                console.log("set the registrations to : ", reg);
                var regged = document.getElementById('registered');
                regged.innerHTML = decoded.length;
                //if (autoclick=="Y")
                //{
                //  alert("dddddddddddd");
                //  auth.click();
                //}
            }
        }
    </script>


</form>
</body>
</html>

