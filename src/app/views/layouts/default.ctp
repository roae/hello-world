<?php/* @var $this View */if(isset($requestError)){	$this->requestAction('/i18n/interpreter/start');}$this->element('i18n_missingkeys');?><?php header("Content-type: text/html; charset: utf-8"); ?><?php header("Content-Language: es"); ?><?php $class = ($home) ? "class='home'" : null ?><!doctype html><!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]--><!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]--><!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]--><!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->	<head>		<?php echo $this->Html->charset(); ?>		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">		<title><?= $pageTitle; ?></title>		<?php			echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));			echo $this->Html->meta("keywords", $pageKeywords);			echo $this->Html->meta("description", $pageDescription);			echo $this->getVar("canonical") ? $this->getVar("canonical") : $this->Html->meta('canonical', $this->Html->url(), array('rel'=>'canonical', 'type'=>null, 'title'=>null));			echo $this->getVar("next") ? $this->getVar("next") : "";			echo $this->getVar("prev") ? $this->getVar("prev") : "";			echo $this->Html->css(array('style.min.css', 'litebox.css'));			echo $this->Html->css(array('owl.carousel.css','owl.theme.css', 'magnific-popup.css'));			if (Configure::read('debug') > 0) {				echo $this->Html->css('cake.generic');			}		?>	</head>	<body <?php echo $class ?>>		<div id="fb-root"></div>		<script>(function(d, s, id) {		  var js, fjs = d.getElementsByTagName(s)[0];		  if (d.getElementById(id)) return;		  js = d.createElement(s); js.id = id;		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=512084482226018&version=v2.0";		  fjs.parentNode.insertBefore(js, fjs);		}(document, 'script', 'facebook-jssdk'));</script>		<header class="block" id="main-header">			<div class="col-container">				<?= $this->Html->link($this->Html->image("logo.png",array('alt'=>'[:logo_alt:]')), "/", array('escape'=>false,'title'=>'[:title_logo:]','id'=>'logo'));?>				<?= $this->element('cities/selector') ?>				<?= $this->element("users/account");?>			</div>		</header>		<div class="the-content">			<?= $content_for_layout ?>		</div>		<footer id="main-footer">			<?php /*			<section class="mobile-apps <?= !$home ? "small" : ""?>">				<div class="col-container">					<h2>Descarga CiticinemasApp</h2>					<p>						Consulta la cartelera en tu ciudad, compra tus entradas y disfruta de las promociones exclusivas.					</p>					<ul class="actions">						<li class="google-play">							<a href="">								<?= $this->Html->image("google-play-button.svg",array('alt'=>'[:logo_alt:]')) ?>							</a>						</li>						<li class="appstore">							<a href="">								<?= $this->Html->image("appstore-button.svg",array('alt'=>'[:logo_alt:]')) ?>							</a>						</li>					</ul>				</div>			</section>            */ ?>			<section class="footer-menu">				<div class="col-container">					<section class="footer-menu-options">						<h2>[:menu:]</h2>						<?= $this->Navigation->menu($defaultMenu['menu']) ?>					</section>					<section class="news">						<h2>[:ultimas-noticias:]</h2>						<?= $this->element("articles/footer_articles"); ?>					</section>				</div>				<div class="col-container">					<section class="social-networks">						<h2>[:follow-us:]</h2>						<ul>							<li><a class="fb" href="">Facebook</a></li>							<li><a class="tw" href="">Twitter</a></li>							<li><a class="gp" href="">Google+</a></li>							<li><a class="vim" href="">Vimeo</a></li>							<li><a class="yt" href="">Youtube</a></li>						</ul>					</section>					<section class="subscription">						<h2>[:suscribete-a-nuestro-boletin:]</h2>						<form action="" method="post">							<div class="input">								<input type="text" placeholder="correo@ejemplo.com">								<input type="submit" value="Suscribir">							</div>						</form>					</section>				</div>			</section>			<section class="copyright">				<div class="col-container">					<div class="certificates">						<?= $this->Html->image("footer-certificates.png",array('alt'=>'[:logo_alt:]')) ?>					</div>					<strong class="copyright">						[:copyright:]					</strong>					<a href="">[:terminos-legales:]</a>					<a href="">[:politicas-de-privacidad:]</a>				</div>			</section>		</footer>		<?= $this->element("users/login");?>		<!--I18nScripts-->		<script>window.jQuery || document.write('<script src="/js/ext/jquery.js"><\/script>')</script>		<!--<script>window.jQuery.ui || document.write('<script src="/js/ext/jquery.ui.js"><\/script>')</script>-->		<?php			echo $this->Html->script(array(				#'ext/underscore-min.js',				#'ext/jquery.floating.js',				'//maps.google.com/maps/api/js?sensor=true',				'gmaps.js',				'jquery.waypoints.min.js',				'inview.min.js',				'cycle2.min.js',				'cycle2.carousel.js',				'owl.carousel.min.js',				'ext/jquery.carouFredSel-6.2.1-min.js',				'ext/jquery.select.min.js',				'jquery.magnific-popup.min.js',				'social-connect.js'			));			echo $scripts_for_layout;			echo $this->Html->script(array('script.js'));		?>		<script>			window._gaq = [['_setAccount','<?= Configure::read("Analitycs.".Configure::read("I18n.Locale"));?>'],['_trackPageview'],['_trackPageLoadTime']];			/*Modernizr.load({				load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'			});*/		</script>		<!--[if lt IE 7 ]>			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>		<![endif]-->		<?php echo $this->Js->writeBuffer(); ?>	</body></html><?php	if(isset($requestError)){		echo $this->requestAction('/i18n/interpreter/end');	}?>