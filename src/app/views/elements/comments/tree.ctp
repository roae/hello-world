<?php /* @var $this View */ ?>
<ul>
<?php foreach($recordset as $record):
	if(!preg_match('/<a[^>]*>|www\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|http:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|https:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}/ism',$record['Comment']['message'])){
	?>
	<li>
		<span class="nombre"></i><?= $record['Comment']['nombre'] ?></span>
		<span class="datetime"></i><?= $this->Time->format("j [:M:], Y g:i a",$record['Comment']['created']); ?></span>
		<?php
		$comment=preg_replace('/<\/p>|<br[^<]*?>/',"\n",$record['Comment']['message']);# se remplazan los <br> y </p> por \n para que los comentarios viejos sean compatibles
		$comment=preg_replace("/\\\|<[^<]+?>/","",$comment); #se quitan todos las etiquetas html y las \ que se usan para escapar las comillas
		$comment=preg_replace("/\n\s*\n/","</p><p>",$comment);#se remplazan los dos saltos de lineas por una P
		$comment=preg_replace('/\n/',"<br />",$comment);#se remplazan los saltos de linea que quedan solos por <br />
		$comment=preg_replace('/[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-\.]+/','[E-mail]',$comment);# se quitan los emails que se escriben en el mensaje
		$comment=preg_replace('/www\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|http:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}|https:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9]{2,4}/','[URL]',$comment);
		echo $this->Html->para("msg",$comment);
		if(!empty($record['children'])){
			echo $this->element("comments/tree",array('recordset'=>$record['children']));
		}
		?>
	</li>
<?php }
endforeach; ?>
</ul>