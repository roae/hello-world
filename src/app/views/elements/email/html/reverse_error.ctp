<h1 class="error">Intento de Reverso Fallido</h1>
<small>Fecha: <?= date("d [:F:] Y H:i:s");?></small>
<?php
echo $this->Html->para("intentos","No. de intentos: ".$attempts);
echo $this->Html->para("tiempo","Fecha de transaccion: ".date("Y-m-d H:i:s",$time));