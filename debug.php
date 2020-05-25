<?php include('php/init.php') ?>

<b>_SERVER: </b> <?php print_r($_SERVER) ?> <br><br>
<b>_SESSION: </b> <?php print_r($_SESSION) ?> <br><br>
<b>_GET: </b> <?php print_r($_GET) ?> <br><br>
<b>_POST: </b> <?php print_r($_POST) ?> <br><br>


<b>encode: </b> <?php 
    $str = 'a@b.c       ///   +79217584508 \'0; drop table bargains; --';
    echo '<pre> raw: ' . $str . '</pre> <br>'; 
    echo '<pre> enc: ' . urlencode($str) . '</pre> <br>'; 
    echo '<pre> dec: ' . urldecode(urlencode($str)) . '</pre> <br>'; 
?> <br><br>

<b>encode: </b> <?php 
    $str = 'a@b.c       ///  \' +79217584508 \'; drop table bargains; --';
    echo '<pre> raw: ' . $str . '</pre> <br>'; 
    echo '<pre> enc: ' . escape ($str) . '</pre> <br>'; 
?> <br><br>
