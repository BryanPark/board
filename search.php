<?
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
include $path_info_db;
$keyword =$_GET[keyword];
$search_option = $_GET[search_option];

#검색 옵션에 따라서 쿼리를 다르게 지정
#쿼리는 검색 옵션에 맞는 키워드 스트링을 검색하게끔.
if($search_option=="name_n_title"){
#$search_query = "SELECT * FROM {$table_name_board} WHERE name like '%$keyword%' or title like '%$keyword%'";
$search_query = "SELECT * FROM {$table_name_board} WHERE match(name,title) against ('$keyword')";

}
# 2개의 키워드를 or로 검색하는 것은 매우 느리다.
# full text search의 경우에는 match ~ against 쿼리문을 이용하는 것이 좋다.
else{
$search_query = "SELECT * FROM {$table_name_board} WHERE $search_option like '%$keyword%'";
}
#생성된 쿼리를 list.php에서 get 하게끔.


echo "세션값: $_SESSION[search_query]";
header("refresh:1;url=list.php?search_query=$search");
?>