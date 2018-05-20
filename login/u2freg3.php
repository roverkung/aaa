<?php
$cmtagain = "N";
$userid = $_POST['account'];
$chal = $_POST['challenge'];
$keystr = $_POST['keyhandle'];


require_once('U2F.php');
$scheme = isset($_SERVER['HTTPS']) ? "https://" : "http://";
$u2f = new u2flib_server\U2F($scheme . $_SERVER['HTTP_HOST']);
?>
<html>
<head>
    <title>PHP U2F Register</title>

    <script src="u2f-api.js"></script>

    <script>
        function addRegistration(reg) {
            var existing = localStorage.getItem('u2fregistration');
            var regobj = JSON.parse(reg);
            var data = null;

            if(existing) {
		 alert("Exist co");
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
          //return JSON.stringify(data);
    	  document.getElementById('challenge').value=JSON.stringify(data);
	  alert(JSON.stringify(data));
        }
        <?php
        function fixupArray($data) {
            $ret = array();
            $decoded = json_decode($data);
            foreach ($decoded as $d) {
                $ret[] = json_encode($d);
            }
            return $ret;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['startRegister'])) {
                $regs = json_decode($_POST['registrations']) ? : array();
                list($data, $reqs) = $u2f->getRegisterData($regs);
                echo "var request = " . json_encode($data) . ";\n";
                echo "var signs = " . json_encode($reqs) . ";\n";
        ?>        setTimeout(function() {
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
		form.submit();
            });
        }, 1000);
        <?php
            } else if($_POST['doRegister']) {
                try {
                    $data = $u2f->doRegister(json_decode($_POST['request']), json_decode($_POST['doRegister']));
                    echo "var registration = '" . json_encode($data) . "';\n";
        ?>
        addRegistration(registration);
        alert("registration successful!");

        <?php
		$cmtagain = "Y";
                } catch(u2flib_server\Error $e) {
                    echo "alert('error:" . $e->getMessage() . "');\n";
                }
            }
        }
        ?>
    </script>

</head>
<body>

<form method="POST" id="form" >
    <font size=12 color=red>Please Touch U2F Key</font>
<!--<p><button name="startRegister" type="submit">Register</button>-->
    <input type="hidden" name="doRegister" id="doRegister"/>
    <input type="hidden" name="request" id="request"/>\
    <input type="hidden" name="registrations" id="registrations"/>

    <input type="hidden" name="account" ID="account" value='<?php echo $userid?>'>
    <input type="hidden" name="challenge" ID="challenge" value='<?php echo $chal?>'/>
    <input type="hidden" name="keyhandle" ID="keyhandle" value='<?php echo $keystr?>'/>
</form>
<?php
	if($cmtagain=="Y")
	{
	   echo '<script type="text/javascript">form.action="addu2f.php";</script>';
	   echo '<script type="text/javascript">form.submit();</script>';
	}
?>
</body>
</html>
