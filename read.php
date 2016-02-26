<?
session_start();
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
include $path_info_db;
$seq = $_GET[seq];
$page_no = $_GET[page_no];

// 조회수 업데이트
$result=mysql_query("update {$table_name_board} set view=view+1 where seq=$seq", $conn);

// 글 정보 가져오기
$result=mysql_query("select * from {$table_name_board} where seq=$seq", $conn);
$row=mysql_fetch_array($result);
if(!$row){ // seq 값이 잘못입력되어 쿼리가 없을땐 -> 가장 최신의 글 표시.
	$result=mysql_query("select * from {$table_name_board} order by seq desc limit 0,1", $conn);
	$row = mysql_fetch_array($result);
	//또는 스크립트로 뒤로가기도 가능하지만 스크립트 막아놓는 브라우저 대비
}

?>

<html>
<head>
<title>테스트</title>
<style>
</style>


</head>

<body>
<center>
<?
############### login session 정보 확인 ###########
if (!empty($_SESSION['login_ok']))#------로그인 되었는지를 비교-------------#
{
 #--------로그인된 회원인 경우에 실행-------#
  $login_ok = $_SESSION['login_ok'];
  $login_ok[4]  = 1;
  echo "$login_ok[1]님 반갑습니다";
  echo " <a href=db_access/logout.php>[로그아웃] </a>";
 //exit;
}
else
{
 echo ("로그인하세요! <a href=login_page.php>[로그인]</a>");
 //echo ("<script>window.location.href='login_page.php';</script>");
}
#######################login####################
?>
<table width=100% border=0 cellpadding=2 cellspacing=1
bgcolor=#777777>

	<!--글제목-->
	<tr>
		<td height=20 colspan=6 align=center bgcolor=#999999>
			<font color=white><B><?=htmlspecialchars($row[title])?></B></font>
		</td>
	</tr>
	<!--/글제목-->

	<!--글정보-->
	<tr>
		<td width=50 height=20 align=center bgcolor=#EEEEEE>글쓴이</td>
		<td width=240 bgcolor=white><?=$row[name]?></td>
		<td width=50 height=20 align=center bgcolor=#EEEEEE>
			날짜
		</td>
		<td width=240
			bgcolor=white><?=$row[date]?>
		</td>
		<td width=50 height=20 align=center bgcolor=#EEEEEE>조회수</td>
		<td width=240 bgcolor=white><?=$row[view]?></td>
	</tr>
	<!--/글정보-->

	<!--본문-->
	<tr height=100px>
		<td bgcolor=white colspan=6>
			<font color=black>
				<pre><?=htmlspecialchars($row[content])?></pre>
			</font>
		</td>
	</tr>
	<!--/본문-->

	<!-- 기타 버튼 + 이전 다음 -->
	<tr>
		
		<td colspan=6 bgcolor=#999999>
			<table width=100%>
			<tr>
				<!-- 기타 버튼 -->
				<td align=left height=20>

					<a href=list.php?seq=<?=$seq?>><font color=white>
					[목록보기]</font></a>
					<a href=write_doc.php><font color=white>
					[글쓰기]</font></a>
					<a href=edit_doc.php?seq=<?=$seq?>><font color=white>
					[수정]</font></a>
					<a href=db_access/predel.php?seq=<?=$seq?>><font color=white>
					[삭제]</font></a>
					
				</td>
				<!-- /기타 버튼-->

				<!-- 이전 다음 -->
				<td align=right>
					<?
					// 현재 글보다 id 값이 큰 글 중 가장 작은 것을 가져온다. 삭제됬을때를 생각해서 이렇게 구현함
					// 즉 바로 이전 글 ORDER BY id ASC가 함축됨 즉 오름차순으로 정렬되있음
					$query=mysql_query("SELECT seq FROM {$table_name_board} WHERE seq>$seq LIMIT 1",
					$conn);
					$prev_id=mysql_fetch_array($query);

					if ($prev_id[seq]) // 이전 글이 있을 경우
					{
					echo "<a href=read.php?seq=$prev_id[seq]>
					<font color=white>[이전]</font></a>";
					}
					else
					{
					echo "[이전]";
					}

					//내림차순으로 정렬하고 작은 것 한개 가져옴
					$query=mysql_query("SELECT seq FROM {$table_name_board} WHERE seq <$seq
					ORDER BY seq DESC LIMIT 1", $conn);
					$next_id=mysql_fetch_array($query);

					if ($next_id[seq])
					{
					echo "<a href=read.php?seq=$next_id[seq]>
					<font color=white>[다음]</font></a>";
					}
					else
					{
					echo "[다음]";
					}
					?>
				</td>
				<!-- /이전 다음 -->
			</tr>
			</table>
			</b></font>
		</td>
	</tr>
	<!-- /기타 버튼 + 이전 다음 -->

</table>
</center>
</body>
</html>

