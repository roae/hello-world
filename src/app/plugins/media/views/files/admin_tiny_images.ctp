<?php
    echo $this->Html->script("ext/jquery",array('inline'=>false));
    echo $this->Html->scriptBlock(
        "
        $(function(){
            $('.img').click(function(){
                url=$(this).attr('rel');
                if (url == '') return false;

                url = '".Helper::url('/media/uploads/')."' + url;

                field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
                field.value = url;
                if (field.onchange != null) field.onchange();
                window.top.close();
                window.top.opener.browserWin.focus();
            })
        });
        "
    ,array('inline'=>false));
    echo $this->Html->css("/media/css/tiny_images");

    echo $this->Form->create(
        "TinyImage",
        array(
            'type' => 'file',
            'url' => $this->Form->url(),
            'class'=>'clearfix'
        )
    );
    echo $this->Form->label(
        'Imagen.imagen',
        'Upload image'
    );
    echo $this->I18n->file(
        'imagen'
    );
    echo $this->Form->end('Upload');
?>

<?php if(isset($images[0])) {
    //pr($images);
    $tableCells = array();

    foreach($images As $the_image) {
        echo $this->Html->link(
            $this->Html->image("/media/uploads/".$the_image['basename'],array('class'=>'thumb')),"#",array('rel'=>$the_image['basename'],'escape'=>false,'class'=>'img')
        );
    }
} ?>
