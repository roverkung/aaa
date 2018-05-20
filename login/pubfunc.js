<script type="text/javascript">
    function LoginDataChk(var1,var2)
    { 
       var vaccount=document.getElementById(var1).value;
       var vid=document.getElementById(var2).value;
       if (vaccount==""){
        alert("Please Input username");
        return false;
       }
       else    
       {
          if (vid==""){
             alert("Please Input password");
             return false;
          }
          else
             return true; 
        }
     }
</script>

