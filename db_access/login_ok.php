<?
session_start();
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
//echo "경로 " . $path_login_ok;
include $path_info_db;
include "password.php";

$user_id = $_POST[user_id];
$user_passwd = $_POST[user_passwd];



$login_query = "select user_no, user_id, user_passwd, user_name, user_email, user_regdate from {$table_name_member} where user_id='$user_id'";
$result = mysql_query($login_query, $conn);
$row = mysql_fetch_row($result);

if($row[0]==NULL)
{
	echo("<script>alert('아이디를 확인해주세요');
	history.back();</script>
	");
	//echo "rw" . $path_info_db;
}
else{##아이디는 있음
	if(password_verify($user_passwd, $row[2])){
		if (empty($_SESSION['login_ok'])) {
			$_SESSION['login_ok'] = array($row[1], $row[3], $row[4], $row[5]);
			echo ("<script>window.location.href='../list.php';</script>");
		}
		//echo ("<script>window.location.href='../list.php';</script>");
	}
	else {
		echo("<script>alert('비밀번호를 확인해주세요');
		history.back();</script>
		");
	}
}
?>

