<?php $this->I18n->start(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title><?php echo $title_for_layout; ?></title>
		<style type="text/css">
			#outlook a {padding:0;}
			body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;color:#454545;}
			.ExternalClass {width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
			#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}

			img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
			a img {border:none;}
			.image_fix {display:block;}
			p {margin: 1em 0;color:#4f4f4f !important;font-family: "Helvetica Nueue",Helvetica,Arial,sans-serif !important;font-size:14px;line-height:22px !important;}
			h1, h2, h3, h4, h5, h6 {color: ##2C2C2C !important; font-family: "Helvetica Nueue",Helvetica,Arial,sans-serif !important;font-weight: bold;margin: 0 0 10px !important;}
			h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: #2C2C2C !important;}
			h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {color: #2C2C2C !important;text-decoration: underline;}
			h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {color: #2C2C2C !important;}
			table td {border-collapse: collapse;}
			a {color: #1f78cb; text-decoration: none;}
			h1{font-size:25px;margin-bottom:25px;}
			h2{font-size:18px;margin-bottom:15px;}
			h3{font-size:16px;margin-bottom:10px;}

			#MTSCopyRights p{color:#8f8f8f !important;font-size: 10px;}
			#MTSViewBrowser{text-align: center;}
			#MTSViewBrowser p{font-size: 10px;}

			@media only screen and (max-device-width: 480px) {
				a[href^="tel"], a[href^="sms"] {text-decoration: none;color: black; pointer-events: none;cursor: default;}
				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration: default;color: orange !important;pointer-events: auto;cursor: default;}
			}

			@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
				/* You guessed it, ipad (tablets, smaller screens, etc) */
				a[href^="tel"], a[href^="sms"] {text-decoration: none;color: blue; pointer-events: none;cursor: default;}
				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration: default;color: orange !important;pointer-events: auto;cursor: default;}
			}

			@media only screen and (-webkit-min-device-pixel-ratio: 2) {
				/* Put your iPhone 4g styles in here */
			}

			@media only screen and (-webkit-min-device-pixel-ratio: .75) {
				/* Put CSS for low density (ldpi) Android layouts in here */
			}

			@media only screen and (-webkit-min-device-pixel-ratio: 1) {
				/* Put CSS for medium density (mdpi) Android layouts in here */
			}

			@media only screen and (-webkit-min-device-pixel-ratio: 1.5) {
				/* Put CSS for high density (hdpi) Android layouts in here */
			}

		</style>
		<!--[if IEMobile 7]>
			<style type="text/css">
			</style>
		<![endif]-->

		<!--[if gte mso 9]>
			<style type="text/css">
			</style>
		<![endif]-->

	</head>
	<body bgcolor="#DFDFDF">
		<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#DFDFDF">
			<tr>
				<td>
					<table cellpadding="" cellspacing="0" border="0" align="center">
						<tr>
							<td colspan="3" height="50px">
								<div id="MTSViewBrowser">
									[:subscription-comfirm-mail-view-browser:]
								</div>
							</td>
						</tr>
						<tr>
							<td width="1px"></td>
							<td width="650px">
								<table cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td colspan="3" valign="center" align="left" height="124px"><a href="<?php echo FULL_BASE_URL ?>"><img class="image_fix" src="<?php echo FULL_BASE_URL ?>/img/email/letter/logo.png" alt=""  width="421px" height="44px" /></a></td>
										<td width="50px" bgcolor="#ffffff"></td>
									</tr>
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td colspan="3" height="1px" bgcolor="#EDEDED"></td>
										<td width="50px" bgcolor="#ffffff"></td>
									</tr>
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td colspan="3" height="30px" bgcolor="#ffffff"></td>
										<td width="50px" bgcolor="#ffffff"></td>
									</tr>
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td width="10px" bgcolor="#ffffff"></td>
										<td bgcolor="#ffffff">

											<?php echo $content_for_layout; ?>

											<table cellpadding="" cellspacing="0" border="0" align="center" width="100%">
												<tr>
													<td valign="center" height="78px">
														<a href="http://www.facebook.com/pages/Mexican-Timeshare-Solutions-Timeshare-Cancellation/148325441949169"><img class="image_fix" src="<?php echo FULL_BASE_URL ?>/img/email/letter/facebook.png" alt=""  width="257px" height="48px" /></a>
													</td>
													<td ></td>
													<td valign="center" height="78px">
														<a href="https://twitter.com/MxTSolutions"><img class="image_fix" src="<?php echo FULL_BASE_URL ?>/img/email/letter/twitter.png" alt=""  width="257px" height="48px" /></a>
													</td>
												</tr>
											</table>
										</td>
										<td width="10px" bgcolor="#ffffff"></td>
										<td width="50px" bgcolor="#ffffff"></td>
									</tr>
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td colspan="3" height="1px" bgcolor="#EDEDED"></td>
										<td width="50px" bgcolor="#ffffff"></td>
									</tr>
									<tr>
										<td width="50px" bgcolor="#ffffff"></td>
										<td width="10px" bgcolor="#ffffff"></td>
										<td bgcolor="#ffffff">
											<div id="MTSCopyRights">
												[:subscription-comfirm-mail-copy-rights:]
											</div>
										</td>
										<td width="10px" bgcolor="#ffffff"></td>
										<td width="50px" bgcolor="#ffffff"></td>
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
			<tr>
				<td height="50px"></td>
			</tr>
		</table>
		<?php
		if($browser_version){
			echo $this->Html->script(array(
			#'plugins',
			'ext/jquery',
			'ext/jquery.ui',
		));
			echo $scripts_for_layout;
			echo $this->Js->writeBuffer();
		}
		?>
	</body>
</html>
<?php echo $this->Template->make($this->I18n->end(),$templatedata); ?>