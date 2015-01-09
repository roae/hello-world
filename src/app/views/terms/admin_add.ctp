<?php
$this->Html->script('ext/jquery.slug',array('inline'=>false));
$this->Html->addCrumb('[:admin_terms:]',array('action' => 'index'));
$this->Html->addCrumb('[:admin_terms_add:]');
echo $this->Ajax->div("ajaxForm",array('class'=>'span4'));
echo $this->I18n->process($this->element("terms/form"));
echo $this->Ajax->divEnd("ajaxForm");
?>