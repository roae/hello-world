<!doctype html>
<?php
if(isset($requestError)){
	$this->requestAction('/i18n/interpreter/start');
}
$this->element('i18n_missingkeys');?>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Login | [:admin_project_title:]</title>
	<?php
	echo $this->Html->meta('favicon.ico','/favicon.ico',array('type'=>'icon'));
	echo $this->Html->css(array(
			'cake.generic',
			'admin',
		)
	);
	?>
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
	<!--I18nScripts-->
</head>
<body id="LoginPage">
	<?php
	echo $this->Session->flash();
	echo $content_for_layout;
	?>
	<script>window.jQuery || document.write(unescape(decodeURIComponent('<?= rawurlencode($this->Html->script("ext/jquery"))?>')))</script>
	<?php
	echo $scripts_for_layout;
	echo $this->Js->writeBuffer();
	?>
</body>
</html>
<?php
if(isset($requestError)){
	echo $this->requestAction('/i18n/interpreter/end');
}
?>
