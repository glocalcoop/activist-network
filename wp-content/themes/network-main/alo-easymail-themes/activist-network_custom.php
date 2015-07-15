<html>
<head>

	<title>[SITE-NAME]</title>

	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

</head>
<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">

<table cellspacing="0" border="0" style="background: #f5f3f0;" cellpadding="0" width="100%">

	<tr>
		<td>
			<table cellspacing="0" bgcolor="#484845" width="100%" cellpadding="0">
				<tr>
					<td height="50" valign="top">
						<table cellspacing="0" align="center" width="600" cellpadding="0">
							<tr>
								<td class="header-text" align="center" style="color: #fff; font-family:	'Lato',Helvetica,Verdana,sans-serif; font-size: 10px; text-transform: uppercase; padding: 0 20px;">
									<br />[READ-ONLINE]
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table cellspacing="0" width="100%" cellpadding="0">
				<tr>
					<td valign="top" style="site-banner">
						<table cellspacing="0" align="center" width="600" cellpadding="0"  style="border: 5px solid #fff;">
							<tr>
								<td class="main-title site-name" style="color: #333231; padding: 10px 20px; font-size: 36px; font-weight: 	300; text-align: center; font-family: 'Lato',Helvetica,Verdana,sans-serif; text-transform: uppercase; background: #fff; ">
									[SITE-NAME]
                                    
								</td>
							</tr>
                            <?php if(function_exists('header_image')) { ?>
                            <tr>
								<td class="site-header" align="center">
									<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?>"  style="width: 100%; height: auto;" />
								</td>
							</tr>
                            <?php } ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td valign="top">
			<table cellspacing="0" border="0" align="center" style="" cellpadding="0" width="600">

				<tr>
					<td valign="top" align="center" style="background: #4d4c4a; padding: 10px; font-size: 16px; color: #fff; font-family: 'Lato',Helvetica,Verdana,sans-serif; text-transform: uppercase;">
                        [DATE]
					</td>
				</tr>
				<tr>
					<td>
						<!-- content -->
						<table cellspacing="0" border="0" height="370" cellpadding="0" width="600" style="background: #fff;">
							<tr>
								<td class="article-title" height="45" valign="top" style="padding: 0 20px; font-family: 'Lato',Helvetica,Verdana,sans-serif; font-size: 28px; color: #333231;" width="600" colspan="2">
									<br />[TITLE]
								</td>
							</tr>
							<tr>
								<td class="content-copy" valign="top" style="padding-left: 20px; font-family: 'Lato',Helvetica,Verdana,sans-serif; color: #333231;" width="1">
									[THUMB]
								</td>
								<td class="content-copy" valign="top" style="padding: 20px; font-size: 14px; font-family: 'Lato',Helvetica,Verdana,sans-serif; line-height: 20px; color: #333231;">
									[CONTENT]
								</td>
							</tr>

							<tr>

								<td class="gallery" height="45" valign="top" style="padding: 0 20px;font-family: 'Lato',Helvetica,Verdana,sans-serif; color: #333231;" width="600" colspan="2">
									[GALLERY]
								</td>
							</tr>

						</table>
						<!--  / content -->
					</td>
				</tr>

				<tr>
					<td valign="top">
					</td>
				</tr>
			</table>

		</td>

	</tr>
	<tr>
		<td valign="top">
			<table cellspacing="0" width="100%" cellpadding="0">
				<tr>
					<td valign="top">
						<!-- footer -->
						<table cellspacing="0" border="0" align="center" cellpadding="0" width="600">
							<tr>
								<td valign="top">
									<table cellspacing="0" border="0" width="600" cellpadding="0">
										<tr>
											<td class="unsubscribe" valign="top" style="padding: 20px; color: #999; font-size: 14px; font-family: 'Lato',Helvetica,Verdana,sans-serif; color: #333231; line-height: 20px;" width="305" colspan="2">
												[USER-UNSUBSCRIBE]
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- / end footer -->
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table cellspacing="0" width="100%" cellpadding="0">
							<tr>
								<td class="copyright" align="center" height="80" valign="top" style="color: #FFF; font-family: 'Lato',Helvetica,Verdana,sans-serif; font-size: 10px; text-transform: uppercase; text-align: center; line-height: 20px;" bgcolor="#484845">
									<br />
									[SITE-LINK]<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>

</body>
</html>
