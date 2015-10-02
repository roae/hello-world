<?php/* @var $this View */if(isset($requestError)){	$this->requestAction('/i18n/interpreter/start');}$this->element('i18n_missingkeys');header("Content-type: text/html; charset: utf-8");header("Content-Language: es");$class = "";$class .= ($home) ? "home" : "";$class .= isset($this->params['url']['mobile'])? " mobile" : "";?><!doctype html><!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]--><!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]--><!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]--><!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->	<head>		<?php echo $this->Html->charset(); ?>		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">		<title><?= $pageTitle; ?></title>		<?php			echo $this->Html->meta('favicon.ico', '/favicons/favicon.ico', array('type' => 'icon'));			echo $this->Html->meta("keywords", $pageKeywords);			echo $this->Html->meta("description", $pageDescription);			echo $this->getVar("canonical") ? $this->getVar("canonical") : $this->Html->meta('canonical', $this->Html->url(), array('rel'=>'canonical', 'type'=>null, 'title'=>null));			echo $this->getVar("next") ? $this->getVar("next") : "";			echo $this->getVar("prev") ? $this->getVar("prev") : "";			echo $this->Html->css(array('style.min.css', 'litebox.css'));			echo $this->Html->css(array('owl.carousel.css','owl.theme.css', 'magnific-popup.css'));			if (Configure::read('debug') > 0) {				echo $this->Html->css('cake.generic');			}			echo $this->Html->script("modernizr-2.0.6.min.js");		?>		<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">		<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">		<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">		<link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-icon-76x76.png">		<link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-icon-114x114.png">		<link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-icon-120x120.png">		<link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-icon-144x144.png">		<link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-icon-152x152.png">		<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-icon-180x180.png">		<link rel="icon" type="image/png" sizes="192x192"  href="/favicons/android-icon-192x192.png">		<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">		<link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-96x96.png">		<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">		<link rel="manifest" href="/favicons/manifest.json">		<meta name="msapplication-TileColor" content="#ffffff">		<meta name="msapplication-TileImage" content="/favicons/ms-icon-144x144.png">		<meta name="theme-color" content="#ffffff">	</head>	<body class="<?= $class ?>">		<div id="fb-root"></div>		<script>(function(d, s, id) {		  var js, fjs = d.getElementsByTagName(s)[0];		  if (d.getElementById(id)) return;		  js = d.createElement(s); js.id = id;		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=512084482226018&version=v2.0";		  fjs.parentNode.insertBefore(js, fjs);		}(document, 'script', 'facebook-jssdk'));</script>		<?php if(!isset($this->params['url']['mobile'])){ ?>		<!-- Google Tag Manager -->		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-5Z9J8S"		                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);			})(window,document,'script','dataLayer','GTM-5Z9J8S');</script>		<!-- End Google Tag Manager -->		<header class="block" id="main-header">			<div class="fndHeader">				<div class="col-container">					<?= $this->Html->link($this->Html->image("logo.png",array('alt'=>'[:logo_alt:]')), "/", array('escape'=>false,'title'=>'[:title_logo:]','id'=>'logo'));?>					<?= $this->element('cities/selector') ?>					<?php //echo $this->element("users/account");?>				</div>			</div>		</header>		<?php } ?>		<div class="the-content">			<?= $content_for_layout ?>		</div>		<?php if(!isset($this->params['url']['mobile'])){ ?>		<footer id="main-footer">			<section class="mobile-apps <?= !$home ? "small" : ""?>">				<div class="col-container">					<h2>Descarga nuestra App</h2>					<p>						Consulta la cartelera en tu ciudad, compra tus entradas y disfruta de las promociones exclusivas.					</p>					<ul class="actions">						<li class="google-play">							<a href="https://play.google.com/store/apps/details?id=com.citicinemas.citicinemas" rel="nofollow">								<?= $this->Html->image("google-play-button.svg",array('alt'=>'[:logo_alt:]')) ?>							</a>						</li>						<!--<li class="appstore">							<a href="">								<?= $this->Html->image("appstore-button.svg",array('alt'=>'[:logo_alt:]')) ?>							</a>						</li>-->					</ul>				</div>			</section>			<section class="footer-menu">				<div class="col-container">					<section class="footer-menu-options">						<h2>[:menu:]</h2>						<?= $this->Navigation->menu($defaultMenu['menu']) ?>					</section>					<section class="news">						<h2>[:ultimas-noticias:]</h2>						<?= $this->element("articles/footer_articles"); ?>					</section>				</div>				<div class="col-container">					<section class="social-networks">						<h2>[:follow-us:]</h2>						<ul>							<li><a class="fb" href="https://www.facebook.com/Citicinemas" target="_blank">Facebook</a></li>							<li><a class="tw" href="https://twitter.com/Citicinemas" target="_blank">Twitter</a></li>							<li><a class="gp" href="https://plus.google.com/+citicinemas/" target="_blank">Google+</a></li>							<li><a class="insta" href="https://instagram.com/citicinemasmx/" target="_blank">Instragram</a></li>							<li><a class="yt" href="https://www.youtube.com/user/CiticinemasTV" target="_blank">Youtube</a></li>						</ul>					</section>					<section class="subscription">						<h2>[:suscribete-a-nuestro-boletin:]</h2>						<form action="//citicinemas.createsend.com/t/j/s/qllkh/" method="post">							<div class="input">								<input type="text" name="cm-qllkh-qllkh" placeholder="correo@ejemplo.com">								<input type="submit" name="submit" value="[:suscribirme:]">							</div>						</form>					</section>				</div>			</section>			<section class="copyright">				<div class="col-container">					<div class="certificates">						<table class="ssl" width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose Symantec SSL for secure e-commerce and confidential communications.">							<tr>								<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.websecurity.norton.com/getseal?host_name=www.citicinemas.com&amp;size=XS&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=es"></script><br />									<a href="https://www.symantec.com/es/es/ssl-certificates" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>							</tr>						</table>						<?= $this->Html->link($this->Html->image("logo-adhoc.jpg",array('alt'=>'[:logo-adhoc:]')),'http://www.grupoadhoc.mx/',array('title'=>'Sitio desarrollado por Grupo Adhoc','escape'=>false,'target'=>'_blank'));?>					</div>					<strong class="copy">						[:copyright:]					</strong>					<a href="/privacidad.pdf" target="_blank">[:politicas-de-privacidad:]</a>				</div>			</section>		</footer>		<?= $this->element("users/login");?>		<?php } ?>		<!--I18nScripts-->		<script>window.jQuery || document.write('<script src="/js/ext/jquery.js"><\/script>')</script>		<!--<script>window.jQuery.ui || document.write('<script src="/js/ext/jquery.ui.js"><\/script>')</script>-->		<?php			echo $this->Html->script(array(				#'ext/underscore-min.js',				#'ext/jquery.floating.js',				'//maps.google.com/maps/api/js?sensor=true',				'gmaps.js',				'jquery.waypoints.min.js',				'inview.min.js',				'cycle2.min.js',				'cycle2.carousel.js',				'owl.carousel.min.js',				'ext/jquery.carouFredSel-6.2.1-min.js',				'ext/jquery.select.min.js',				'jquery.magnific-popup.min.js',				'social-connect.js',			));			echo $scripts_for_layout;			echo $this->Html->script(array('script.js'));		?>		<?php if(!isset($this->params['url']['mobile'])){ ?>			<script>				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');				ga('create', 'UA-15403889-7', 'auto');				ga('send', 'pageview');				ga('set', 'contentGroup1', 'Grupo Citicinemas');			</script>		<?php } ?>		<!--[if lt IE 7 ]>			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>		<![endif]-->		<?php echo $this->Js->writeBuffer(); ?>	</body></html><?php	if(isset($requestError)){		echo $this->requestAction('/i18n/interpreter/end');	}?>