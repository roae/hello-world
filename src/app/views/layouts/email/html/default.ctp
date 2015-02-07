<?php $this->requestAction('/i18n/interpreter/start') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<base href="<? echo FULL_BASE_URL ?>" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Te recomiendo QuieroMiComida.com</title>
		<style type="text/css">
			#outlook a {padding:0;}
			body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
			.ExternalClass {width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
			#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}

			img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
			a img {border:none;}
			.image_fix {display:block;}
			p {margin: 1em 0;color:#616161 !important;font-family: Helvetica,Arial,sans-serif !important;font-size:12px;line-height:18px !important;}
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
			/*h2{font-size:16px;font-weight:normal; font-style: normal;margin:0;}*/
		</style>
	</head>
	<body bgcolor="#EEEEEE">
		<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#EEEEEE" width="100%">
			<tr>
				<td>
					<table cellpadding="" cellspacing="0" border="0" align="center">
						<tr>
							<td colspan="3" height="50px"></td>
						</tr>
						<tr>
							<td width="1px"></td>
							<td width="603px">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="1px" bgcolor="#bdd9d5"></td>
										<td colspan="3" height="1px" bgcolor="#cce4dd"></td>
										<td width="1px" bgcolor="#bdd9d5"></td>
									</tr>
									<tr>
										<td width="1px" bgcolor="#bdd9d5"></td>
										<td colspan="3" height="30px" bgcolor="#ffffff"></td>
										<td width="1px" bgcolor="#bdd9d5"></td>
									</tr>
									<tr>
										<td width="1px" bgcolor="#bdd9d5"></td>
										<td width="50px" bgcolor="#ffffff"></td>
										<td bgcolor="#ffffff">
											<?php echo $content_for_layout; ?>
										</td>
										<td width="50px" bgcolor="#ffffff"></td>
										<td width="1px" bgcolor="#bdd9d5"></td>
									</tr>
									<tr>
										<td width="1px" bgcolor="#bdd9d5"></td>
										<td colspan="3" height="30px" bgcolor="#ffffff"></td>
										<td width="1px" bgcolor="#bdd9d5"></td>
									</tr>
								</table>
							</td>
							<td width="1px"></td>
						</tr>
						<tr>
							<td colspan="3" height="50px"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php echo $this->requestAction('/i18n/interpreter/end') ?>
