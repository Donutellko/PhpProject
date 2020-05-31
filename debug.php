<?php include('php/init.php') ?>

<b>_SERVER: </b> <pre> <?php print_r($_SERVER) ?> </pre><br><br>
<b>_SESSION: </b> <pre> <?php print_r($_SESSION) ?> </pre><br><br>
<b>_GET: </b> <pre> <?php print_r($_GET) ?> </pre><br><br>
<b>_POST: </b> <pre> <?php print_r($_POST) ?> </pre><br><br>


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
