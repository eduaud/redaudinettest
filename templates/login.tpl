<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Audicel - Sistema</title>
<link href="{$rooturl}css/login.css" rel="stylesheet" type="text/css" />
<script src="{$rooturl}js/codifica/md5.js" type="text/javascript"></script>
</head>

<body>
	<form name="f1" action="{$rooturl}index.php" method="POST" target="_top" onSubmit="document.f1.encrypted_pwd.value=document.f1.password.value;document.f1.password.value='';">
		<input type="hidden" name="{$SESSIONNAME}" value="{$SESSIONID}">
		<input type="hidden" name="encrypted_pwd" value="" />
		
		<div class="marco_login" id="marco_login">
			<div class="system_name" id="system_name">Sistema  Audicel v1.0</div>
			<div class="login_icon" id="login_icon">
				<table width="144" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="39"><img src="{$rooturl}imagenes/login_icon.png" width="27" height="27" alt="Login" /></td>
						<td width="105"> Login</td>
					</tr>
				</table>
			</div>
			
			<div class="campos_login" id="campos_login">
				<table  width="380" border="0" cellpadding="0" cellspacing="0" id="login">
					<tr>
						<td width="179"><p>Usuario:</p></td>
						<td width="201"><p>Contrase&ntilde;a:</p></td>
					</tr>
					<tr>
						<td>
							<label for="textfield"></label><input name="login" type="text" id="textfield" size="20" />
						</td>
						<td>
							<input name="password" type="password" id="textfield2" size="20" />
						</td>
					</tr>
				</table>
			</div>
			
			<div class="login_btn" id="login_btn">
				<table width="500" border="0" cellpadding="0" cellspacing="0" id="btn_login">
					<tr>
						<td width="182">
							<p>
								
							</p>
						</td>
						<td width="196"><p></p></td>
						<td width="122" align="right">
							<input name="button" type="submit" id="button" class="button_entrar" value="Entrar" onClick="valida(this);" />
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="login_footer" id="login_footer">
			<table width="600" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="21" align="right"><p>&nbsp;</p></td>
					<td width="320"><p>Copyright ©2015  Audicel. Todos los Derechos Reservados.</p></td>
					<td width="247" align="right">
						<p>Desarrollo: <a href="http://www.sysandweb.com/" target="_blank">Sys&amp;Web</a></p>
					</td>
					<td width="12" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
	</form>
</body>
</html>
