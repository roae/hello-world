<!DOCTYPE html>
<?php
if(isset($requestError)){
$this->requestAction('/i18n/interpreter/start');
}
$this->element('i18n_missingkeys');?>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $pageTitle; ?> | [:admin_project_title:]</title>
	<?php
	echo $this->Html->meta('favicon.ico','/favicon.ico',array('type'=>'icon'));echo $this->Html->meta("keywords","[:admin_default_keyword:]");echo $this->Html->meta("description","[:admin_default_description:]");
	if($loggedUser['User']['group_id'] != Configure::read('Group.Anonymous')){
		echo $this->Html->css("cake.generic");
	}
	echo $this->Html->css(array('admin','jquery-ui/mactheme/mactheme'));

	echo $this->Html->script("modernizr-2.0.6.min.js");
	?>
</head>
<body ng-app="Citicinemas">
	<div id="cmswrap">
		<header>
			<?= $this->Html->link("Dashboard",array('controller'=>'pages','action'=>'display','admin_home','admin'=>true,'plugin'=>false),array('id'=>'logo'))?>
			<div id="config">
				<?= $this->Navigation->menu($adminMenu['config']);?>
			</div>
			<div class="user">
				<?= $loggedUser['User']['nombre'] ?>
				<span class="username"><?= $loggedUser['User']['username']?></span>
			</div>
			<div id="Crumbs">
				<span class="separator">/</span>
				<span><?php echo $html->getCrumbs('<span class="separator">/</span>'); ?></span>
			</div>
		</header>
		<div id="fndNav"></div>
		<nav><?php echo $this->Navigation->menu($adminMenu['menu'],array('id'=>'menu'));?></nav>
		<div id="content" class="clearfix">
			<div class="row-fluid">
				<?php
				echo $this->Session->flash();
				echo $content_for_layout;
				?>
			</div>
		</div>
		<footer>
			<?= $this->Html->image("admin/h1web-logo.png", array('class'=>'logo'));?>CMS 2.0 H1WebStudio
			<span class="dateTime">
				<?=  date("[:l:], j [:F:] Y") ?>
			</span>
		</footer>
	</div>
	<div id="Loading"></div>
	<!--I18nScripts-->
	<script>window.jQuery || document.write('<script src="/js/ext/jquery.js"><\/script>')</script>
	<script>window.jQuery.ui || document.write('<script src="/js/ext/jquery.ui.js"><\/script>')</script>
	<?php
	echo $this->Html->script(array(
			'ext/bootstrap.min',
			'ext/jquery.flydom',
			'ext/jquery.history',
			'ext/jquery.xupdater.min',
			'ext/jquery.floating.min',
			'ext/jquery.slug',
			'ext/angular.min',
			'ext/angular-animate.min',
			#'ext/ui-bootstrap-custom-0.12.0.min',
			#'ext/ui-bootstrap-custom-tpls-0.12.0.min',
		    'ext/underscore-min',
			'admin.min'
		)
	);
	echo $scripts_for_layout;
?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>

</html>
<?php
if(isset($requestError)){
echo $this->requestAction('/i18n/interpreter/end');
}
?>
