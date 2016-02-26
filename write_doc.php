<?
session_start();
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
############### login session 정보 확인 ###########
if (!empty($_SESSION['login_ok']))#------로그인 되었는지를 비교-------------#
{
 #--------로그인된 회원인 경우에 실행-------#
  $login_ok = $_SESSION['login_ok'];
  echo "$login_ok[1]님 반갑습니다";
  echo " <a href=db_access/logout.php>[로그아웃] </a>";
 //exit;
}
else
{
 echo ("<script>alert('글을 쓰려면 먼저 로그인하세요');window.location.href='login_page.php';</script>");
}
#######################login####################
?>
<html>
<head>
<title>practice of board</title>

</head>

<script>

	function confirm_submit(){
		var is_ok_title=document.forms["write"]["title"].value;
		var is_ok_content=document.forms["write"]["content"].value;
		var warning_message = "";
		//alert("제목:"+is_ok_title);
		//alert("내용:"+is_ok_content);
		if(is_ok_title && is_ok_content){
			if(confirm("등록하시겠습니까?")){
				return true;
			}
			else
				return false;
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
	function confirm_return(){ // 내용이 없으면 바로 돌아가고 있으면 confirm
		var is_ok_title=document.forms["write"]["title"].value;
		var is_ok_content=document.forms["write"]["content"].value;
		if(!is_ok_title & !is_ok_content) {history.back();}
		else if(confirm("작성중인 내용을 취소하고 되돌아가시겠습니까?")){
			history.back();
		}
	};
</script>
<body>

<form id="write" name="write" action="<?=$relative_path_db_related?>insert_db.php" method="post">
<table>
  <tr>
    <td width=50 algin=left >제목</td>
    <td>
      <input type="text" id="title" required  name="title" size=40 maxlength=40>
    </td>
  </tr>
  <tr>
    <td width=50 algin=left >본문</td>
    <td align=left height=500 >
     <textarea name="content" id="content" required cols=75 rows=30  maxlength=5000></textarea>
    </td>
  </tr>



  <tr>
   <td></td>
   <td>
	<input type=submit value="save(저장하기)" onclick="return confirm_submit()">
	<input type=button value="back(뒤로가기)" onclick="return confirm_return()">
   </td>

  </tr>
</table>


</form>

</body>




</html>
