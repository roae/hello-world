<?php
$url=isset($url) ? $url : $this->Html->url();
?>
<div class="socialNetwork">
	<div class="share facebook_button">
		<div class="fb-like" data-href="<?= $url ?>" data-send="true" data-width="80" data-show-faces="false" data-font="tahoma" data-layout="button_count" data-share="false"></div>
	</div>
	<div class="share google_button">
		<div class="g-plusone" data-size="medium" data-href="<?= $url ?>"></div>
	</div>
	<div class="share twitter_button">
		<a href="https://twitter.com/share" class="twitter-share-button"  data-width="150" data-url="<?= $url ?>" data-count="horizontal">Tweet</a>
	</div>
</div>
<?php
$this->Html->scriptBlock("
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');
	(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
",array('inline'=>false))
?>
