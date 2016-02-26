<?
//데이터 베이스 연결하기
include "info_db.php";
$seq = $_GET[seq];
$passwd = $_POST[passwd];

$result=mysql_query("SELECT passwd FROM {$table_name_board} WHERE seq=$seq",
$conn);
$row=mysql_fetch_array($result);

if ($pass==$row[pass] )//비밀번호 맞는지 확인함.
{
    $query ="DELETE FROM {$table_name_board} WHERE seq=$seq"; //데이터 삭제하는 쿼리문
    $result=mysql_query($query, $conn);
	
}
else
{
    echo ("
    <script>
    alert('비밀번호가 틀립니다.');
    history.go(-1);
    </script>
    ");
    exit;
}
?>
<center>

<FONT size=2 >삭제되었습니다.</font>
<meta http-equiv='Refresh' content='0.5; URL=../list.php'>
</center>
