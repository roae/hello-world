<?php echo $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $html->charset('utf-8');
		echo $this->Html->css('admin');
		echo $scripts_for_layout;
		?>
	</head>
	<body>
		<?php echo $content_for_layout; ?>
		<noscript>[:your-browser-doesnt-support-this-page:]</noscript>
	</body>
</html>