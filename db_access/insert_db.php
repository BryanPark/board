<?
session_start();
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
echo $home;
include $path_info_db;

$login_ok = $_SESSION['login_ok'];
$id = $login_ok[0];
$name = $login_ok[1];

$seq = $_GET[seq];
$title = $_POST[title];
$content = $_POST[content];
$REMOTE_ADDR = $_SERVER[REMOTE_ADDR];

#Single Quotation 및 특문 처리
$title = mysql_real_escape_string($title);
$content=  mysql_real_escape_string($content);

$query = "insert into {$table_name_board} 
(seq, name, id, title, content, date, view, ip)
values
('', '$name', '$id','$title', '$content',now(), 0, '$REMOTE_ADDR')";

$result=mysql_query($query, $conn)or die(mysql_error());

mysql_close($conn);

echo ("<script>window.location.href='../list.php';</script>");
?>
