<?php

function Slashes ($s) {
	if (is_array($s)) {
		reset ($s);
		while (list ($key, $val) = each ($s)) {
			$s[$key] = addslashes($val);
		}
		return $s;
	} else {
		$s = addslashes($s);
		return $s;
	}	
}

// Anti-Injection
if (!get_magic_quotes_gpc()) {
	if (isset($_POST)) $_POST = Slashes($_POST);
	if (isset($_GET)) $_GET = Slashes($_GET);
}

?>