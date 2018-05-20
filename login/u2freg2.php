<?php
/*if($_POST['user']!="")
{
  $_SESSION["userid"] = $_POST['user'];
  $_SESSION["passwd"] = $_POST['pass'];
  if ($_POST['challenge']!="")
  {
    $_SESSION["challenge"] = $_POST['challenge'];
  };*/

  require_once('U2F.php');
  $scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
  $u2f = new u2flib_server\U2F($scheme . $_SERVER['HTTP_HOST']);
//}

?>
<html>
<head>
    <title>PHP U2F Demo</title>

    <script src="u2f-api.js"></script>
    <script>
        function addRegistration(reg) {
           /* var vID = '<?php echo $_SESSION["userid"]?>';
            var vPW = '<?php echo $_SESSION["passwd"]?>';
            var vChan = '<?php echo $_SESSION["challenge"]?>';*/
            var existing = localStorage.getItem('u2fregistration');
            var regobj = JSON.parse(reg);
            var data = null;
            if(existing) {
                data = JSON.parse(existing);
                if(Array.isArray(data)) {
                    for (var i = 0; i < data.length; i++) {
                        if(data[i].keyHandle === regobj.keyHandle) {
                            data.splice(i,1);
                            break;
                        }
                    }
                    data.push(regobj);
                } else {
                    data = null;
                }
            }
            if(data == null) {
                data = [regobj];
            }
            alert("Registration Successful!");
            //alert(vID);
            //alert(vPW);
            //location.replace("passsign.php?keyhandle="+JSON.stringify(data)+"&userid="+vID+"&passwd="+vPW+"&challenge="+vChan);
	    location.replace("passsign.php);
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
            return $ret;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['startRegister'])) {
                echo "alert('002');\n";
                $regs = json_decode($_POST['registrations']) ? : array();
                list($data, $reqs) = $u2f->getRegisterData($regs);
                echo "var request = " . json_encode($data) . ";\n";
                echo "var signs = " . json_encode($reqs) . ";\n";
        ?>
        setTimeout(function() {
            console.log("Register: ", request);
            u2f.register([request], signs, function(data) {
                var form = document.getElementById('form');
                var reg = document.getElementById('doRegister');
                var req = document.getElementById('request');
                console.log("Register callback", data);
                if(data.errorCode && data.errorCode != 0) {
                    alert("registration failed with errror: " + data.errorCode);
                    return;
                }
                reg.value=JSON.stringify(data);
                req.value=JSON.stringify(request);

                document.getElementById('challenge').value=JSON.stringify(request);
                //alert(JSON.stringify(request));
                alert('003');
                form.submit();
            });
        }, 1000);
        <?php
            } else if($_POST['doRegister']) {
                try {
                    echo "alert('004');\n";
                    $data = $u2f->doRegister(json_decode($_POST['request']), json_decode($_POST['doRegister']));
                    echo "var registration = '" . json_encode($data) . "';\n";

        ?>
        addRegistration(registration);
        alert("Registration End!");
        <?php
                } catch(u2flib_server\Error $e) {
                    echo "alert('error:" . $e->getMessage() . "');\n";
                }
            }/* else if(isset($_POST['startAuthenticate'])) {
                //echo "alert('005');\n";
                $regs = json_decode($_POST['registrations']);
                $data = $u2f->getAuthenticateData($regs);
                echo "var registrations = " . $_POST['registrations'] . ";\n";
                echo "var request = " . json_encode($data) . ";\n";
        ?>
        setTimeout(function() {
            console.log("sign: ", request);
            u2f.sign(request, function(data) {
                var form = document.getElementById('form');
                var reg = document.getElementById('doAuthenticate');
                var req = document.getElementById('request');
                var regs = document.getElementById('registrations');
                console.log("Authenticate callback", data);
                reg.value=JSON.stringify(data);
                req.value=JSON.stringify(request);
                regs.value=JSON.stringify(registrations);
                form.submit();
            });
        }, 1000);
        <?php
            } else if($_POST['doAuthenticate']) {
                //echo "alert('006');\n";
                $reqs = json_decode($_POST['request']);
                $regs = json_decode($_POST['registrations']);
                try {
                    $data = $u2f->doAuthenticate($reqs, $regs, json_decode($_POST['doAuthenticate']));
                    echo "var registration = '" . json_encode($data) . "';\n";
                    echo "addRegistration(registration);\n";
                    echo "alert('Authentication successful, counter:" . $data->counter . "');\n";
                } catch(u2flib_server\Error $e) {
                    echo "alert('error:" . $e->getMessage() . "');\n";
                }
            }*/
        }
        ?>
    </script>

</head>
<body>
<form method="POST"  id="form">
    <p>
    <font size=12 color=red>Please Touch U2F Key</font>

    <input type="hidden" name="doRegister" id="doRegister"/>
    <input type="hidden" name="doAuthenticate" id="doAuthenticate"/>
    <input type="hidden" name="request" id="request"/>
    <input type="hidden" name="registrations" id="registrations"/>

    <p>
    <input type="hidden" name="user" id="user" value='<?php echo $_SESSION["userid"]?>'>
    <p>
    <input type="hidden" name="pass" id="pass" value='<?php echo $_SESSION["passwd"]?>'>
    <p>
    <input type="hidden" name="challenge" id="challenge" value='<?php echo $_SESSION["challenge"]?>'>

</form>
</body>
</html>

