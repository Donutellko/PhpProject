<?php

if (!isset($_SESSION["login"]))
{
	preg_match("/^(.*?)\./", pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME), $rgMatches);
	if ($rgMatches[1]=="index")
	{
		?>
		<a href="pages/login.php">Вход</a>
		<?php
	}
	else
	{
		?>
		<a href="login.php">Вход</a>
		<?php
	}
}
else
{
}

?>

				