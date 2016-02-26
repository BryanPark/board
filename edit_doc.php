<?
session_start();
############### login session 정보 확인 ###########
//데이터 베이스 연결하기
#include "./db_access/info_db.php";
$home = __DIR__;
set_include_path(get_include_path() . PATH_SEPARATOR . $home );
include "path_config.php";
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

	//비밀번호 일치
	//입력된 값과 비교한다.
	if (!empty($_SESSION['login_ok'])&($row[id] == $login_ok[0] )) {
	//로그인 되어 있고 해당 사용자인경우
	echo "$login_ok[1]님 반갑습니다";
	echo " <a href=db_access/logout.php>[로그아웃] </a>";
	//그리고 기존 본문 내용 가져오기 위한 쿼리
	$result=mysql_query("SELECT {$table_name_board}.seq, {$table_name_board}.name,{$table_name_board}.title, {$table_name_board}.content FROM {$table_name_board} where seq=$seq", $conn);
	$row=mysql_fetch_array($result);

	}
	else {
	// 해당 사용자가 아닌 경우
	echo ("
	<script>
	alert('해당 사용자가 아닙니다.');
	</script>
	");
	exit;//반드시 exit를 써줘야됨. 안그러면 아래의 코드가 실행이됨.
	}

}
else
{
echo ("<script>alert('글을 수정하려면 먼저 로그인하세요');</script>");
echo ("<script>window.location.href='login_page.php';</script>");
}



?>


<html>
<head>
<title>테스트 게시판</title>
<style>
</style>
<script>
	function confirm_submit(){
		var is_ok_title=document.forms["update"]["title"].value;
		var is_ok_content=document.forms["update"]["content"].value;
		var warning_message = "";
		//alert("제목:"+is_ok_title);
		//alert("내용:"+is_ok_content);
		if(is_ok_title && is_ok_content){
			if((is_ok_title=="<?=$row[title]?>" & is_ok_content=="<?=$row[content]?>")){
				if(confirm("수정된 내역이 없습니다. 목록으로 나가려면 확인을, 계속 수정하시려면 취소를 눌러주세요"))
					history.back();
				else
					return false;
			}
			else{
				if(confirm("등록하시겠습니까?")){
				return true;
				}
				else
					return false;
			}
		}else{
			//타이틀, 내용 둘다 널일경우
			if(!is_ok_title && !is_ok_content){ warning_message = "내용과 제목";}
			//그렇지 않으면 내용만 널일때 
			else if(!is_ok_content){ warning_message = "내용";}
			//그렇지 않을때 -> 제목만 널일때 
			else {/*#if(!is_ok_title)*/ 
				warning_message = "제목";}
			alert(warning_message+"을 입력하세요");
			return false;
		}
	};
	/*function confirm_return(){ 
		// 내용이 기존과 동일하면 바로 돌아가고 변경 있으면 confirm
		var is_ok_title=document.forms["write"]["title"].value;
		var is_ok_content=document.forms["write"]["content"].value;
		alert("<?=$row[title]?>");
		alert("<?=$row[content]?>");

		if(confirm("작성중인 내용을 취소하고 되돌아가시겠습니까?")){
			history.back();
		}
	};*/
	function confirm_return(){ // 내용이 없으면 바로 돌아가고 있으면 confirm
		var is_ok_title=document.forms["update"]["title"].value;
		var is_ok_content=document.forms["update"]["content"].value;

		if((is_ok_title=="<?=$row[title]?>" & is_ok_content=="<?=$row[content]?>")){
			history.back();
		//내용이 기존과 동일하면 confirm없이 바로 뒤로가기
		}
		else if(confirm("작성중인 내용을 취소하고 되돌아가시겠습니까?")){
			history.back();
		}
	};
</script>
</head>

<body>


<!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->

<form name=update id=update action=<?=$relative_path_update?>?seq=<?=$seq?> method=post>

<table width=580 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
<tr>
<td height=20 align=center bgcolor=#999999>
	<font color=white><B>글 수 정 하 기</B></font>
</td>
</tr>
<!-- 입력 부분 -->
<tr>
<td bgcolor=white>
	&nbsp;
	<table>
	<tr>
	</tr>
	<tr>
		<td width=60 align=left >제 목</td>
		<td align=left >
			<INPUT type="text" id="title" name="title" size=60 maxlength=35 value="<?=htmlspecialchars($row[title])?>">
		</td>
	</tr>
	<tr>
		<td width=60 align=left >내용</td>
		<td align=left >
			<TEXTAREA id=content name=content cols=65 rows=15><?=htmlspecialchars($row[content])?></TEXTAREA>
		</td>
	</tr>
	<tr>
		<td colspan=10 align=center>
			<INPUT type=submit value="글 저장하기" onclick="return confirm_submit()">
			&nbsp;&nbsp;
			<input type=button onclick="return confirm_return()" value="돌아가기" />
		</td>
	</tr>
	</TABLE>
</td>
</tr>
<!-- 입력 부분 끝 -->
</table>
</form>
</center>
</body>
</html>
