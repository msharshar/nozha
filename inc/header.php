<!DOCTYPE html>
<html dir='<?php
	if($account["lang"] == "AR"){
		echo 'rtl';
	}else{
		echo 'ltr';
	}
 
?>'>
	<head>
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="img/ico/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/ico/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="img/ico/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="img/ico/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="img/ico/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="img/ico/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="img/ico/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="img/ico/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="img/ico/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="img/ico/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="img/ico/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />



		<meta name="theme-color" content="#339194" />
		<title>Nozha</title>
		<?php
		if(isset($_SESSION["id"])){
			if($account["lang"] == "AR"){
				echo '<link href="https://fonts.googleapis.com/css?family=Rakkas&amp;subset=arabic,latin-ext" rel="stylesheet">';
				echo '<link href="https://fonts.googleapis.com/css?family=Cairo&amp;subset=arabic,latin-ext" rel="stylesheet">';
			}
		}
		if(isset($_GET["lang"]) && $_GET["lang"] == "ar"){
			echo '<link href="https://fonts.googleapis.com/css?family=Rakkas&amp;subset=arabic,latin-ext" rel="stylesheet">';
			echo '<link href="https://fonts.googleapis.com/css?family=Cairo&amp;subset=arabic,latin-ext" rel="stylesheet">';
		}
		?>
		<!-- EN font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="layout/css/fontawesome-all.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="layout/css/style.css">
		<?php
		if(isset($_SESSION["id"])){
			if($account["lang"] == "AR"){
				echo '<link rel="stylesheet" href="layout/css/rtl.css">';
			}
		}
		if(isset($_GET["lang"]) && $_GET["lang"] == "ar"){
			echo '<link rel="stylesheet" href="layout/css/rtl.css">';
		}
		?>
	</head>
	<body>