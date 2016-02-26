<?
session_start();
############### login session 정보 확인 ###########
//데이터 베이스 연결하기
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
include $path_info_db;

$seq = $_GET[seq];
$page_no = $_GET[page_no];
// 회원정보와 글의 회원정보를 가져와서 비교할 준비
$query = "SELECT id FROM {$table_name_board} WHERE seq=$seq";
$result=mysql_query($query, $conn);
$row=mysql_fetch_array($result);

if (!empty($_SESSION['login_ok']))#------로그인 되었는지를 비교-------------#
{
#--------로그인된 회원인 경우에 실행-------#
$login_ok = $_SESSION['login_ok'];
$login_ok[4]  = 1;

//exit;

	//세션 비교로 해당 사용자인지 확인
	if (!empty($_SESSION['login_ok'])&($row[id] == $login_ok[0] )) {

	//그리고 기존 본문 내용 가져오기 위한 쿼리
	$result=mysql_query("SELECT {$table_name_board}.seq, {$table_name_board}.name,{$table_name_board}.title, {$table_name_board}.content FROM {$table_name_board} where seq=$seq", $conn);
	$row=mysql_fetch_array($result);
	echo ("<script>if(confirm('정말 삭제하시겠습니까? 되돌릴 수 없습니다.')){window.location.href='delete.php?seq=$_GET[seq]'}</script>");
	echo ("<script>history.back();</script>");
	}
	else {
	// 해당 사용자가 아닌 경우
	echo ("
	<script>
	alert('작성자만 삭제할 수 있습니다');
	history.go(-1);
	</script>
	");
	exit;//반드시 exit를 써줘야됨. 안그러면 아래의 코드가 실행이됨.
	}

}
else
{
echo ("<script>alert('글을 삭제하려면 먼저 로그인하세요');</script>");
echo ("<script>window.location.href='../login_page.php';</script>");
}

?>