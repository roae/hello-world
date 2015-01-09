<?php /* @var $this View */  ?>
<ul class="comments">
<?php
	foreach((array)$recordset as $count=>$record){
		ob_start();
		echo $this->Html->tag("div","","datos");
			echo $this->Form->checkbox("Xpagin.record][",array('class'=>'check','id'=>'','value'=>$record['Comment']['id']));
			echo $this->Html->tag("span",$record['Comment']['nombre'],'nombre')." > ";
			if($this->params['named']['class']=="Hotel"){
				echo "Hotel: ".$this->Html->link($record['Hotel']['titulo'],array('controller'=>'hotels','action'=>'view','admin'=>false,'id'=>$record['Hotel']['id'],'slug'=>$record['Hotel']['slug']),array('class'=>'model','target'=>'_blanck'));
			}else{
				echo "Article: ".$this->Html->link($record['Article']['titulo'],array('controller'=>'articles','action'=>'view','admin'=>false,$record['Article']['id']),array('class'=>'model','target'=>'_blanck'));
			}
			echo $this->Html->tag("span",$this->Time->format('d/m/Y h:m a',$record['Comment']['created']),'date');
			echo $this->Html->tag("span","ID: ".$record['Comment']['id'],'id');
			echo $this->Html->tag("span","E-mail: ".h($record['Comment']['email']),'email');
			echo $this->Html->tag("span","Tel: ".h($record['Comment']['tel']),'tel');
			$comment=preg_replace('/<\/p>|<br[^<]*?>/',"\n",$record['Comment']['message']);# se remplazan los <br> y </p> por \n para que los comentarios viejos sean compatibles
			$comment=preg_replace("/\\\|<[^<]+?>/","",$comment); #se quitan todos las etiquetas html y las \ que se usan para escapar las comillas
			$comment=preg_replace("/\n\s*\n/","</p><p>",$comment);#se remplazan los dos saltos de lineas por una P
			$comment=preg_replace('/\n/',"<br />",$comment);#se remplazan los saltos de linea que quedan solos por <br />
			echo $this->Html->tag("div",$this->Html->para("msg",$comment),"message");
			echo $this->Html->div('row-actions',
                  (($record['Comment']['status']<1)? $this->Paginator->link("[:aprobar_comment:]",array('action'=>'status',1,$record['Comment']['id']),array('class'=>'action btn btn_success aprobar','rel'=>'[:aprobar_comentario_preg:]')) : $this->Paginator->link("[:rechazar_comment:]",array('action'=>'status',0,$record['Comment']['id']),array('class'=>'action btn rechazar','rel'=>'[:rechazar_comentario_preg:]'))).
                  $this->Paginator->link("[:editar_comment:]",array('action'=>'edit',$record['Comment']['id']),array('rev'=>'','class'=>'edit_comment btn btn_primary')).
                  $this->Paginator->link("[:spam_comment:]",array('action'=>'status',2,$record['Comment']['id']),array('class'=>'action btn_warning spam','rel'=>'[:spam_commemt_question:]: '.h($record['Comment']['nombre']).'?')).
                  $this->Paginator->link("[:delete_comment:]",array('action'=>'status',3,$record['Comment']['id']),array('class'=>'action btn btn_danger delete','rel'=>'[:delete_commemt_question:]: '.h($record['Comment']['nombre']).'?'))
			);
			echo $this->Html->div("loading");
		echo $this->Html->tag("/div");
		echo $this->Html->div("edit");
		if(!empty($record['children'])){
			echo $this->element("comments/admin_tree",array('recordset'=>$record['children']));
		}
		echo $this->Html->tag("li",  ob_get_clean(),"comment");
	}
?>
</ul>