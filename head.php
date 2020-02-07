<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name='language' content='IT' />
<meta name='author' content='Tommaso Azzalin, azzalintommaso@gmail.com' />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php
	$info = Session::get("info");

	$linkIntero = substr(GlobalVar::SERVER("SCRIPT_NAME"),1);
	$pageName = substr($linkIntero,0,strrpos($linkIntero,"/"));
	$pageName = ($pageName == "") ? "home" : $pageName;
	switch($pageName) {
		case "home":
			$pageName = "Home";
			break;
		case "tutti-i-corsi":
			$pageName = "Tutti i corsi";
			break;
		case "licenza":
			$pageName = "Licenza";
			break;
		case "iscrizione":
			$pageName = "Iscrizione";
			break;
		case "i-miei-corsi":
			$pageName = "I miei corsi";
			break;
		case "registro-presenze":
			$pageName = "Registro presenze";
			break;
		case "autogest":
			$pageName = "Auto.Gest";
			break;
		case "amministrazione":
			$pageName = "Amministrazione";
			break;
		case "accesso":
			$pageName = "Registrazione e accesso";
			break;
		default:
			$pageName = "Auto.Gest";
			break;
	}

	echo "<title>" . $pageName . " - " . $info["titolo"] . "</title>";
?>
<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/css/jquery-confirm.min.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery-confirm.min.js"></script>
<script src="/js/jquery.validate.min.js"></script>
<script src="/js/script.js"></script>