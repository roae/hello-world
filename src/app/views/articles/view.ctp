<?php /* @var $this View */ ?>
<article id="article-detail">
	<div class="article-banner <?= ( isset( $record['Foto']['id'] ) ) ? 'with-image' : '' ?>"
	     style="<?= ( isset( $record['Foto']['id'] ) ) ? 'background-image: url('.$record['Foto']['url'].')' : '' ?>">
		<?php if(!isset($this->params['url']['mobile'])){ ?>
		<div class="crumb ">
			<div class="col-container">
				<span class="crumbTitle">[:estas-aqui:]</span>
				<?= $this->Html->link("[:home_crumb:]","/");?>
				/
				<?= $this->Html->link("[:blog_crumb:]",array('action'=>'index'));?>
				/
				<span><?= h($record['Article']['titulo']) ?></span>
			</div>
		</div>
		<?php } ?>
		<div class="title">
			<div class="col-container">
				<div class="info">
					<h1><?= h($record['Article']['titulo']) ?></h1>
					<small class="date">
						<?php
						$categories = array();
						foreach( $record['Category'] as $category ):
							$categories[] = $this->Html->link( $category['nombre'], array( 'controller' => 'articles',
								'action' => 'index',
								'tag_slug' => $category['slug']
							), array( 'class' => 'tag' ) );
						endforeach; ?>
						Creado: <?= $this->Time->format("d [:F:], Y", $record['Article']['created']);?> | en <?= implode(", ",$categories)?>
					</small>
				</div>
			</div>
		</div>

	</div>

	<div class="col-container">
		<div class="article-content">
			<?= $record['Article']['contenido'] ?>
		</div>

		<div class="tags">
			<ul>

				<?php foreach( $record['Tag'] as $tag ): ?>

					<li>
						<?= $this->Html->link( $tag['nombre'], array( 'controller' => 'articles',
							'action' => 'index',
							'tag_slug' => $tag['slug']
						), array( 'class' => 'tag' ) ) ?>
					</li>

				<?php endforeach; ?>

			</ul>
		</div>

		<div class="shares redes">
			<strong>¡Compártelo con tus amigos!</strong>

			<ul>
				<li>
					<a class="tw red" href="http://twitter.com/home?status=<?= rawurlencode( $record['Article']['titulo']."+".$this->Html->url(null,true) ) ?>">Twitter</a>
				</li>
				<li>
					<a class="fb red" href="http://www.facebook.com/share.php?u=<?= rawurlencode( $this->Html->url(null,true)."&title=".rawurlencode( $record['Article']['titulo'] ) ) ?>">Facebook</a>
				</li>
				<li>
					<a class="gp red" href="https://plus.google.com/share?url=<?= rawurlencode($this->Html->url(null,true))?>">G+</a>
				</li>
			</ul>
		</div>
		<?php if(!empty($record['Related'])){ ?>
		<div class="related-posts">
			<span class="title">[:related-articles-title:]</span>
			<ul>
				<?php foreach( $record['Related'] as $related ): ?>
					<li>
						<h2>
							<?= $this->Html->link( $related['titulo'], array( 'controller' => 'articles',
								'action' => 'view',
								'id' => $related['id'],
								'slug' => $related['slug']
							) ) ?>
						</h2>
						<span class="date"><?= $this->Time->format("d [:F:], Y", $related['created']);?></span>
					</li>

				<?php endforeach; ?>
			</ul>
		</div>
		<?php } ?>
		<div class="comments">
			<div class="fb-comments" data-href="<?= $this->Html->url(null,true)?>" data-width="100%"
			     data-numposts="5" data-colorscheme="light"></div>
		</div>
	</div>
</article>
<?= $this->element("movies/commingsoon",array('cache' => array('key' => isset($CitySelected['name']) ? $CitySelected['name'] : "", 'time' => '+1 hour'))); ?>