<?php /* @var $this View */ ?>
<a name="comments"></a>
<?php
$url=Router::parse($this->Html->url());
unset($url['pass'],$url['named']);
$url=am($url,$this->params['named']);
$this->Paginator->options(array('url' => $url));
if(isset($this->params['named']['page'])){
	$page=$this->params['named']['page']+1;
}else{
	$page=2;
}
echo $this->Session->flash();
if(!empty($comments)){ ?>
	<?php
	echo $this->Html->tag("span",$this->params['paging']['Comment']['count']." [:comments:]",'h3');
	/*ob_start();
	echo $this->Html->link("[:sort_newest_$model:]",am($url,array('sort'=>'Comment.created','direction'=>'desc','#'=>'comments')),array('class'=>'btn','title'=>'[:sort_by_newest:]'));
	echo $this->Html->link("[:sort_oldest_$model:]",am($url,array('sort'=>'Comment.created','direction'=>'asc','#'=>'comments')),array('class'=>'btn','title'=>'[:sort_by_oldest:]'));
	echo $this->Html->div("sort","[:sort:]".ob_get_clean());*/

	echo $this->Ajax->div("Comments",array('class'=>'commentList'));
		$this->I18n->start();
		echo $this->element("comments/tree",array('recordset'=>$comments));
		$this->I18n->end();
	echo $this->Ajax->divEnd("Comments");

	echo $this->Ajax->div("commentNext");
		$this->I18n->start();
		echo $this->Paginator->prev("prev",array('class'=>'prev_comments','rel'=>'prev'));
		echo $this->Paginator->next("[:more_comments:]",array('id'=>'nextComments','class'=>'noHistory more_commments','rev'=>'#Comments:append|#commentNext','rel'=>'next'),null,array('class'=>'disabled'));
		$this->I18n->end();
	echo $this->Ajax->divEnd("commentNext");
	?>
<?php } ?>
<section id="CommentForm" class="form">
	<a name="comment"></a>
	<span class="h3">[:comments_<?= $model ?>_title:]</span>
	<span class="help">[:help_comments:]</span>
	<?php
	echo $this->Ajax->div("ajaxComment");$this->I18n->start();
	echo $this->Form->create("Comment",array('url'=>$this->Form->url()));
		echo $this->Form->hidden("foreign_id",array('value'=>$id));
		echo $this->Form->hidden("class",array('value'=>$model));
		echo $this->I18n->inputs(array(
				'nombre'=>array('before'=>$this->Html->tag("i","","icon name")),
				'email'=>array('before'=>$this->Html->tag("i","","icon email")),
				//'tel'=>array('before'=>$this->Html->tag("i","","icon phone")),
				'legend'=>false,
				'fieldset'=>false
		));
		echo $this->I18n->input("message",array('before'=>$this->Html->tag("i","","icon message")));
		echo $this->I18n->input("captcha",array(
				'before'=>$this->Html->image("/contacts/captcha/comment/".rand(0,  getrandmax()),array('alt'=>'captcha','id'=>'captchaComment')).$this->Html->tag("i","",'icon block'),
				'div'=>array('class'=>'input text captcha'),
				'after'=>$this->Html->link('[:change_code:]','#',array('escape'=>false,'onclick'=>"$('#captchaComment').attr('src','/contacts/captcha/comment/'+Math.random());return false;"))
		));
		echo $this->I18n->input('privacy',array('type'=>'checkbox','checked'=>'checked'));
		echo $this->Form->button("[:send_information:]",array('class'=>'btn-principal','type'=>'submit'));
		echo $this->Html->div("loading",$this->Html->tag("span","[:sending-information:]"));
		$this->I18n->addMissing("sending-information","Mensaje que aparece cuando se esta cargando el formulario de comentarios");
	echo $this->Form->end();
	echo $this->I18n->end();
	echo $this->Ajax->divEnd("ajaxComment");
	?>
</section>