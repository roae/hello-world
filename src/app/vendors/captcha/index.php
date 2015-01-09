<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Captcha con PHP</title>
<style type="text/css">
body { font-family: Arial; font-size: 12px; padding: 20px; }
#result { border: 1px solid green; width: 250px; margin: 0 0 5px 0; padding: 2px 20px; font-weight: bold; }
#change-image { font-size: 0.8em; }
#form{border: 1px solid rgb(148, 163, 196);margin: 0px 0px 15px;width:290px;}
form h3{background: rgb(236, 239, 245);display:block;margin: 0px 0px 0px;padding: 5px 10px;}
</style>
</head>
<body>
<div style="margin:auto;width:250px">
<h1>Captcha con PHP</h1>
<?php
/** Validate captcha */
if (!empty($_REQUEST['captcha'])) {

    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
        $captcha_message = "Captcha incorrecto"; $style = "background-color: #FF606C";
    } else {
        $captcha_message = "Captcha correcto!"; $style = "background-color: #CCFF99";
    }
    $request_captcha = htmlspecialchars($_REQUEST['captcha']);

    echo <<<HTML
        <div id="result" style="$style">
        <h3>$captcha_message</h3>
        </div>
HTML;
		
    unset($_SESSION['captcha']);
}
?>

<form id="form" method="GET">
<h3>Escriba el texto de la imagen</h3>
<div style="padding:20px">
<img src="captcha.php" id="captcha" /><br/>
<!-- CHANGE TEXT LINK -->
<a href="#" onclick="document.getElementById('captcha').src='captcha.php?'+Math.random();" id="change-image">Recargar Captcha.</a><br/><br/>
<input type="text" name="captcha" id="captcha-form" />
<input type="submit" value="Enviar" />
</div>
</form>

</div>
</body>
</html>
