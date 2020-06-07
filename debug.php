<?php

session_start();

$APP_NAME = "Эпсилон-Биржа";
$CONTEXT_ROOT = $_SERVER['CONTEXT_PREFIX'];

include("php/connect.php");
include("php/queries.php");
include("php/utils.php");

?>

<b>_SERVER: </b> <pre> <?php print_r($_SERVER) ?> </pre><br><br>
<b>_SESSION: </b> <pre> <?php print_r($_SESSION) ?> </pre><br><br>
<b>_GET: </b> <pre> <?php print_r($_GET) ?> </pre><br><br>
<b>_POST: </b> <pre> <?php print_r($_POST) ?> </pre><br><br>

<?php
//$resend_result = send_confirmation($_SESSION['email'], $_SESSION['confirm_code']);
//print_r($resend_result);
?>