<?php
/* @var $this View */
//$this->Html->script(array('ext/jquery.history.js','ext/jquery.history.html45.js','ext/jquery.flydom.js',"ext/jquery.xupdater.js"),array('inline'=>false));
$this->set("pageDescription","[:location-page-description:]");
$this->set("pageKeywords",$record['Location']['name']);
?>
	<div class="location-detail-container">

		<?php
		$bg_url = '';
		$class = '';

		if( isset( $record['Gallery'][0]['url'] ) ){
			$bg_url = $record['Gallery'][0]['url'];
		} else{
			$class = 'image-not-founded';
		}
		?>

		<div class="big-cover <?= $class ?>" style="background-image: url(<?= $bg_url ?>)">
			<div class="mask"></div>
			<div class="col-container">
				<div class="text">
					<h1>
						<?= Inflector::humanize( low( $record['Location']['name'] ) ) ?>
					</h1>
					<small class="address">
						<span class="icon"></span>
						<?= $record['Location']['street']." #".$record['Location']['outside'].(!empty($record['Location']['interior'])? " - ".$record['Location']['interior'] : "")." ".$record['Location']['neighborhood']." C.P. ".$record['Location']['zip'].", ".$record['City']['name'].", ".$record['Location']['state'] ?>
					</small>
					<?= $this->Html->link("<span class='icon'></span>[:location-billboard:]",array('controller'=>'shows','action'=>'index','slug'=>$record['City']['slug'],'#'=>Inflector::slug($record['Location']['name'],"-")),array('class'=>'btn-primary','escape'=>false));?>
				</div>
			</div>
		</div>
		<div class="col-container">
			<div class="services">
				<h2>[:location-services:]</h2>
				<ul>
					<?php
					foreach($record['Service'] as $service){
						echo $this->Html->tag("li",$this->Html->image($service['Icon']['url'],array('alt'=>$service['name']))." ".h($service['name']));
					}
					?>
				</ul>
			</div>
			<div class="gallery">
				<h2>[:location-gallery:]</h2>
				<ul>
					<?php
					foreach($record['Gallery'] as $pic){
						echo $this->Html->tag("li",$this->Html->link($this->Html->image($pic['mini']),$pic['url'],array('escape'=>false)));
					}
					?>
				</ul>
			</div>
		</div>
		<div class="col-container">
			<div class="contact">
				<h2>[:location-contact:]</h2>
				<?php $phones  = explode(",",$record['Location']['phone_numbers']); ?>
				<ul>
					<?php
					foreach($phones as $phone){
						echo $this->Html->tag("li",$phone);
					}
					?>
				</ul>
			</div>
			<div class="social">
				<?php
				echo $this->element("share");
				echo $this->Html->link("<span class='icon'></span>[:contact-location-manager:]",array('controller'=>'contacts','action'=>'add'),array('class'=>'btn','escape'=>false));
				?>
			</div>
		</div>
		<div id="map">
			<div class="col-container">
				<ul class="tabs">
					<li><a href="#" data-id="mapCanvas" class="selected">[:map:]</a></li>
					<?php if($record['Location']['street_view']){ ?>
					<li><a href="#" data-id="streetCanvas">[:street-view:]</a></li>
					<?php } ?>
				</ul>
			</div>
			<?php
			$url = $this->Html->url(array(
				'controller'=>'locations','action'=>'map',0,
				'mark_lat'=>$record['Location']['mark_lat'],
				'mark_lng'=>$record['Location']['mark_lng'],
				'map_lat'=>$record['Location']['map_lat'],
				'map_lng'=>$record['Location']['map_lng'],
				'map_zoom'=>$record['Location']['map_zoom'],
			));
			echo $this->Html->tag("div","",array('id'=>'mapCanvas','data-url'=>$url,'class'=>'current'));

			if($record['Location']['street_view']){
				$url = $this->Html->url(array(
					'controller'=>'locations','action'=>'map',1,
					'sv_lat'=>$record['Location']['sv_lat'],
					'sv_lng'=>$record['Location']['sv_lng'],
					'sv_heading'=>$record['Location']['sv_heading'],
					'sv_pitch'=>$record['Location']['sv_pitch'],
					'sv_zoom'=>$record['Location']['sv_zoom'],
				));
				echo $this->Html->tag("div","",array('id'=>'streetCanvas','data-url'=>$url));
			}
			?>
		</div>
	</div>
<?php

$this->Html->scriptBlock("
var Complex = ".$this->Javascript->object($record['Location']).";
",array('inline'=>false));

$this->Html->script( array(
	'ext/images-loaded.min.js',
	'ext/litebox.min.js',
), false );
?>