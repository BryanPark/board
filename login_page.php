<?
session_start();
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
//echo "경로 " . $path_login_ok;
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title></title>
</head>
<body>
<form name='login' method='post' action='<?=$relative_path_login_ok?>'>
	<table border='0' width='200' cellpadding='1' cellspacing='1'>
		<tr>
			<td width='100' valign='middle' align='left'>
				&nbsp;아이디
			</td>
			<td width='100'>
				<input type='text' name='user_id' id='user_id' size='8'>
			</td>
		</tr>
		<tr>
			<td width='100' valign='middle' align='left'>
				&nbsp;비밀번호&nbsp;
			</td>
			<td width='100'>
				<input type='password' name='user_passwd' id='user_passwd' size='8'>
			</td>
		</tr>
		<tr>
			<td width='200' colspan='2' align='right'>
				<input type='submit' value='로그인' style='font-family: 굴림; font-size: 9pt; width:70px; height:23px'>&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
</form>
</body>
</html>
