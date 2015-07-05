<?php /* @var $this View */ ?>
<?php
if(isset($requestError)){
	$this->requestAction('/i18n/interpreter/start');
}
echo $this->element('i18n_missingkeys');
?>
<?php header("Content-type: text/html; charset: utf-8"); ?>
<?php echo $this->Html->docType('xhtml-trans'); ?>
	<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<?php
		echo $this->Html->charset();
		echo $this->Html->script(array(
			'ext/jquery',
		));
		echo $this->Html->css("reseter");
		echo $scripts_for_layout;
		?>
		<style type="text/css">
			html,
			body{
				height: 100%;
				padding:0;
				margin:0;
			}
			.view,
			.canvas{
				height:100%;
				width:100%;
				/*float:left*/

			}
			form{display: none;}
		</style>
	</head>
	<body>
	<?php echo $content_for_layout; ?>
	</body>
	</html>
<?php
if(isset($requestError)){
	echo $this->requestAction('/i18n/interpreter/end');
}
?>