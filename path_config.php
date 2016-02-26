<?
#root 기준으로 작성
$path_root = $_SERVER['DOCUMENT_ROOT'] . "/board/";
$path_db_related = $path_root . "db_access/";
$path_info_db = $path_db_related . "info_db.php";

$path_logout =$path_db_related . "logout.php";
$path_login_ok =$path_db_related ."login_ok.php";
$path_login_page=$path_root . "login_page.php";

$path_update =$path_db_related . "update.php";
$path_insert =$path_db_related . "insert_db.php";
$path_delete =$path_db_related . "delete.php";
$path_predel =$path_db_related . "predel.php";

$path_read =$path_root . "read.php";
$path_edit = $path_root . "edit_doc.php";
$path_write =$path_root .  "write_doc.php";
$path_search = $path_root . "search.php";

$relative_path_db_related = "/board/db_access/";
$relative_path_update = $relative_path_db_related . "update.php";
$relative_path_login_ok = $relative_path_db_related . "login_ok.php";
?>