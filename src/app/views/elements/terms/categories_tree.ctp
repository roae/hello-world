<ul>
<?php
/* @var $this View */
/*foreach($recordset as $category){
	echo $this->Form->checkbox("Term.Term.[",)
}*/
echo $this->I18n->input("Term",array('options'=>$categories,'multiple'=>'checkbox'));
?>
</ul>