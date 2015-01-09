<?php
/* @var $this View */
if(!isset($class)){
	$class = "";
}
$_format='[:grid_page_number:] %page% [:grid_of_pages:] %pages%';
echo $this->Html->div('paginator '.$class,
	$this->Html->div("control",
		#$this->Paginator->first("<i class='icon-double-angle-left'></i>",array('escape' => false,'title' => '[:grid_first_page:]'),"<span class='disabled'><i class='icon-double-angle-left'></i></span>").
		$this->Paginator->prev("<i class='icon-angle-left'></i>",array('escape' => false,'title' => '[:grid_prev_page:]'),"<span class='disabled'><i class='icon-angle-left'></i></span>").
		$this->Paginator->numbers(array('separator' => null)).
		$this->Paginator->next("<i class='icon-angle-right'></i>",array('escape' => false,'title' => '[:grid_next_page:]'),"<span class='disabled'><i class='icon-angle-right'></i></span>")
		#$this->Paginator->last("<i class='icon-double-angle-right'></i>",array('escape' => false,'title' => '[:grid_last_page:]'),"<span class='disabled'><i class='icon-double-angle-right'></i></span>")
	).
	$this->Html->para('counter',$this->Paginator->counter(array('format'=>$_format)))
);
?>