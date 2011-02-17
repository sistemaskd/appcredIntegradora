<?php

if(md5(@$_GET['access']) == '479784b54768fa4d92d54166cfa73134'){
	# -
} else {
	die('No tienes permiso para hacer eso.');
}

?>
<form ENCTYPE="multipart/form-data" action="/kondinero/home.php/administracion/import" method="POST">
	<input type='file' name='archivo'/>
	<input type='hidden' name='importType' value='info963' />
	<br/><br/>
	Tipo de archivo:
	<select name='importType'>
  		<option value="info963">Info 963</option>
  		<option value="bnp">BNP</option>
	</select>
	<br/><br/>
	<input type='submit'/>
</form>