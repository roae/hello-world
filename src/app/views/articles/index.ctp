<div id="articles-container">
	<div class="top-message">
		<div class="col-container">
			<?php if(isset($category['Term'])){?>
				<h1><?= $category['Term']['nombre'] ?></h1>
				<?= $category['Term']['descripcion'] ?>
				<div class="crumb">
					<?= $this->Html->link("[:home_crumb:]","/");?>
					/
					<?= $this->Html->link("[:blog_crumb:]",array('action'=>'index'));?>
					/
					<span>[:category:]: <?= h($category['Term']['nombre'])?></span>
				</div>
			<?php }else if(isset($tag['Term'])){?>
				<h1><?= $tag['Term']['nombre'] ?></h1>
				<?= $tag['Term']['descripcion'] ?>
				<div class="crumb">
					<?= $this->Html->link("[:home_crumb:]","/");?>
					/
					<?= $this->Html->link("[:blog_crumb:]",array('action'=>'index'));?>
					/
					<span>[:tag:]: <?= h($tag['Term']['nombre'])?></span>
				</div>
			<?php }else{ ?>
				<h1>[:Blog-principal-title:]</h1>
				[:welcome-to-our-blog:]
				<div class="crumb">
					<?= $this->Html->link("[:home_crumb:]","/");?>
					/
					<span>[:blog_crumb:]</span>
				</div>
			<?php } ?>
		</div>
	</div>

	<ul class="the-articles">

		<?php foreach( $recordset as $record ): ?>

			<?php
			$article = $record['Article'];
			$tags = $record['Tag'];
			$categories = $record['Category'];
			$photo = $record['Foto'];
			?>

			<li class="<?= ( isset( $photo['id'] ) ) ? 'with-image' : '' ?>"
			    style="<?= ( isset( $photo['id'] ) ) ? 'background-image: url('.$photo['big'].')' : '' ?>">
				<div class="col-container">
					<article>
						<small class="date">
							Creado: 22 Enero, 2015 | en

							<?php
							$total_categories = count( $categories );
							$_categories = array();

							foreach( $categories as $i => $category ):
								$_categories[] = $this->Html->link( $category['nombre'], array( 'controller' => 'articles',
									'action' => 'index',
									'category_slug' => $category['slug']
								) );
							endforeach;

							echo implode( ', ', $_categories );
							?>
						</small>

						<header>
							<h2><?= $article['titulo'] ?></h2>
						</header>

						<?= $this->Text->truncate( $article['contenido'], 300, array( 'html' => true,
							'exact' => false
						) ) ?>

						<div class="bottom-article">
							<div class="tags">
								<ul>

									<?php foreach( $tags as $tag ): ?>

										<li>
											<?= $this->Html->link( $tag['nombre'], array( 'controller' => 'articles',
												'action' => 'index',
												'tag_slug' => $tag['slug']
											), array( 'class' => 'tag' ) ) ?>
										</li>

									<?php endforeach; ?>

								</ul>
							</div>

							<?= $this->Html->link( '[:seguir_leyendo:]', array( 'controller' => 'articles',
								'action' => 'view',
								'id' => $article['id'],
								'slug' => $article['slug']
							), array( 'class' => 'see-more' ) ); ?>
						</div>
					</article>
				</div>
			</li>

		<?php endforeach; ?>

	</ul>
	<?php if(isset($this->params['paging']['Article']['pageCount']) && $this->params['paging']['Article']['pageCount'] > 1){ ?>
		<div class="articles-pagination">
			<?= $this->Paginator->numbers( array( 'separator' => null ) ) ?>
		</div>
	<?php } ?>

</div>
