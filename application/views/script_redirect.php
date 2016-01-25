<html>
   <head>
      <script type="text/javascript">
            function Redirect() {
               window.location="<? if (isset($url) && $url != '') { echo $url;}else{echo '/index.php';} ?> ";
            }

            <? if (isset($timeout) && intval($timeout) >= 0)
            {
                echo "setTimeout('Redirect()', ". $timeout*1000 .");";
            }
            else
            {
                echo "setTimeout('Redirect()', 3000);";
            }
            ?>

      </script>
   </head>
   <body>
   <?php if (isset($msg) && $msg != '') {echo '<span>'.$msg.'</span>';} ?>
   </body>
</html>