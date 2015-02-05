<?php
/* @var $this View */

if(isset($requestError)){
	$this->requestAction('/i18n/interpreter/start');
}

$this->element('i18n_missingkeys');
?>
<?php header("Content-type: text/html; charset: utf-8"); ?>
<?php header("Content-Language: es"); ?>
<?php $class = ($home) ? "class='home'" : null ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

		<title><?php echo $pageTitle; ?></title>
		<?php
		echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
		echo $this->Html->meta("keywords", $pageKeywords);
		echo $this->Html->meta("description", $pageDescription);

		echo $this->getVar("canonical") ? $this->getVar("canonical") : $this->Html->meta('canonical', $this->Html->url(), array('rel'=>'canonical', 'type'=>null, 'title'=>null));
		echo $this->getVar("next") ? $this->getVar("next") : "";
		echo $this->getVar("prev") ? $this->getVar("prev") : "";

		if (Configure::read('debug') > 0) {
			echo $this->Html->css('cake.generic');
		}
		echo $this->Html->css(array('style.min.css'));
		?>
	</head>
	<body <?php echo $class ?>>
		<header>
			<!--<div class="Wrapper">
				<?= $this->Html->link($this->Html->image("logo.jpg",array('alt'=>'[:logo_alt:]')),"/",array('escape'=>false,'title'=>'[:title_logo:]','id'=>'Logo'));?>
				<nav>
					<?= $this->Navigation->menu($defaultMenu['menu'],array('id'=>'menu'))?>
				</nav>
			</div>-->
		</header>
		<?= $content_for_layout ?>
		<!--I18nScripts-->
		<script>window.jQuery || document.write('<script src="/js/ext/jquery.js"><\/script>')</script>
		<!--<script>window.jQuery.ui || document.write('<script src="/js/ext/jquery.ui.js"><\/script>')</script>-->
		<?php
		echo $this->Html->script(array(
			'script.min.js',
		));
		?>
		<?= $scripts_for_layout ?>
		<script>
			window._gaq = [['_setAccount','<?= Configure::read("Analitycs.".Configure::read("I18n.Locale"));?>'],['_trackPageview'],['_trackPageLoadTime']];
			Modernizr.load({
				load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
			});
		</script>

		<!--[if lt IE 7 ]>
			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->

		<?php echo $this->Js->writeBuffer(); ?>
	</body>
</html>
<?php
	if(isset($requestError)){
		echo $this->requestAction('/i18n/interpreter/end');
	}
?>
