<?
#세션이용시 필요
session_start();
#세션파기
session_destroy();
#게시판으로 되돌아감#return to the board
echo ("<script>window.location.href='../list.php';</script>");
?>