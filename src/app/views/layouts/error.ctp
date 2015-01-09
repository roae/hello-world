<?php /* @var $this View */
if(isset($requestError)){
	$this->requestAction('/i18n/interpreter/start');
	pr("rochin");
}
?>
<?php
header("Content-type: text/html; charset: utf-8");
header("Content-Language: en, es");
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>[:error404_title:] | [:project_title:]</title>
		<?php
		echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
		?>
		<meta name="robots" content="index,follow,noodp,noydir" />
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
		<?php
		if (Configure::read('debug') > 0) {
			echo $this->Html->css('cake.generic');
		}
		echo $this->Html->css(array('error'));
		?>
		<?= $this->Html->script("modernizr-2.0.6.min.js"); ?>
	</head>
	<body>
		<div id="container">
			<header>
			</header>
			<div id="main" role="main">
				<?= $content_for_layout ?>
			</div>
			<footer>

			</footer>
		</div>
		<?php
		echo $this->Html->script(array(
			'ext/jquery',
			'ext/jquery.label',
			'ext/jquery.ui',
			'script'
		));
		?>
		<?= $scripts_for_layout ?>
		<!--[if lt IE 7 ]>
			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
		<?php echo $this->Js->writeBuffer(); ?>
	</body>
</html>
<?php
if(isset($requestError)) {
	echo $this->requestAction('/i18n/interpreter/end');
}
?>