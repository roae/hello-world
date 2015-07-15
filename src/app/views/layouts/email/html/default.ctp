
<?php $this->requestAction('/i18n/interpreter/start') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="padding:0 !important;margin:0 !important;">
<head>
	<base href="<? echo FULL_BASE_URL ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Confirmacion de compra</title>
	<style type="text/css">
		#outlook a {padding:0;}
		body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
		.ExternalClass {width:100%;}
		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
		#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}

		img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
		a img {border:none;}
		.image_fix {display:block;}
		p {margin: 1em 0;font-family: Helvetica,Arial,sans-serif !important;line-height:18px !important;}
		h1{
			color:#555;
		}
		h1, h2, h3, h4, h5, h6 {color: #444; !important; font-family: Helvetica,Arial,sans-serif !important;margin: 0 0 5px !important; line-height: 1.5em;}
		h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
		h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {color: red !important;}
		h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {color: purple !important;}
		table td {border-collapse: collapse;}
		a {color: #005f71;}
		li{
			margin-bottom: 10px;
		}
		h2.error{
			color: #f00;
		}
		<?php
		 if(isset($this->params['named']['print'])){
		 ?>
		@page {
			size: 21.59cm 27.49 cm;
			margin: 0.5cm 2cm;
		}
		<?php
		 }
		?>
			/*h2{font-size:16px;font-weight:normal; font-style: normal;margin:0;}*/
	</style>
</head>
<body <?php echo !isset($this->params['named']['print']) ? 'bgcolor="#f4f4f4"' : "" ?> style="padding:0 !important;margin:0 !important;">
	<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" <?php echo !isset($this->params['named']['print']) ? 'bgcolor="#f4f4f4"' : "" ?> width="100%">
		<tr>
			<td>
				<table cellpadding="" cellspacing="0" border="0" align="center" style="font-family: Helvetica,Arial,Sans-serif;">
					<tr>
						<td colspan="3" height="20px" ></td>
					</tr>
					<tr>
						<td colspan="3" align="center" style="padding-bottom:20px;">
							<?= $this->Html->image("logo-mail.png",array('class'=>'img_fix'));?>
						</td>
					</tr>
					<tr>
						<td width="1px"></td>
						<td width="550px"  style="padding-bottom:30px;">
							<?= $content_for_layout; ?>
						</td>
						<td width="1px"></td>
					</tr>


				</table>
			</td>
		</tr>
	</table>
</body>
</html>
<?php echo $this->requestAction('/i18n/interpreter/end') ?>
