<article id="article-detail">
	<div class="article-banner <?= ( isset( $record['Foto']['id'] ) ) ? 'with-image' : '' ?>"
	     style="<?= ( isset( $record['Foto']['id'] ) ) ? 'background-image: url('.$record['Foto']['big'].')' : '' ?>">
		<div class="col-container">
			<div class="info">
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
				<h1><?= $record['Article']['titulo'] ?></h1>
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
					<a class="tw red" href="http://twitter.com/home?status=<?= rawurlencode( $record['Article']['titulo']."+".$this->Html->url() ) ?>">Twitter</a>
				</li>
				<li>
					<a class="fb red" href="http://www.facebook.com/share.php?u=<?= rawurlencode( $record['Article']['titulo'] )."&title=".rawurlencode( $this->Html->url() ) ?>">Facebook</a>
				</li>
				<li>
					<a class="gp red" href="https://plus.google.com/share?url=<?= rawurlencode($this->Html->url())?>">G+</a>
				</li>
			</ul>
		</div>

		<div class="related-posts">

			<strong>Artículos relacionados</strong>

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

		<div class="comments">
			<div class="fb-comments" data-href="<?= $this->Html->url(null,true)?>" data-width="100%"
			     data-numposts="5" data-colorscheme="light"></div>
		</div>
	</div>
</article>