<?php if(!$this->MobileDetect->detect("isMobile") || $this->MobileDetect->detect("isTablet")){ ?>
	<section class="mobile-apps <?= !$home ? "small" : ""?>">
		<div class="col-container">
			<h2>¡Descarga la App de Citicinemas Gratis!</h2>

			<p>
				Consulta la cartelera en tu ciudad, compra tus entradas y disfruta de las promociones exclusivas.
			</p>

			<ul class="actions">
				<li class="google-play">
					<a href="https://play.google.com/store/apps/details?id=com.citicinemas.citicinemas" rel="nofollow">
						<?= $this->Html->image("google-play-button.png",array('alt'=>'[:google-play-alt:]')) ?>
					</a>
				</li>

				<li class="appstore">
					<a href="https://appsto.re/mx/gCy59.i" rel="nofollow">
						<?= $this->Html->image("appstore-button.png",array('alt'=>'[:appstore-alt:]')) ?>
					</a>
				</li>
			</ul>
		</div>
	</section>
<?php }else{
?>
	<section class="mobile-apps-phone">
		<?= $this->Html->image("icon-app.png",array('alt'=>'Icono App Citicinemas','class'=>'icon'));?>
		<div class="buttons">
			<?php 
			if($this->MobileDetect->detect("isiOs")){
				echo $this->Html->link("Descargar","https://appsto.re/mx/gCy59.i",array('class'=>'download','rel'=>'nofollow'));
			}else if($this->MobileDetect->detect("isAndroidOS")){
				echo $this->Html->link("Descargar","https://play.google.com/store/apps/details?id=com.citicinemas.citicinemas",array('class'=>'download','rel'=>'nofollow')); 	
			}
			?>
			<span class="close">Cerrar</span>
		</div>
		<div class="texto">
			¡Descarga la App de Citicinemas Gratis!
		</div>
	</section>

<?php
}
?>