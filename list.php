<?
session_start();
#set_include_path(get_include_path() . PATH_SEPARATOR . $home );
$home = $_SERVER[DOCUMENT_ROOT] . "/board/";
include $home . "path_config.php";
include $path_info_db;
############################변수 설정#################################
#상수, 1 페이지에 들어가는 게시물 수 #CONSTANT;
#how many docs in the page.
$page_size=10;
#상수, 페이지 갯수를 몇개나 나열할 것인지 #CONSTANT;
#how may pages be shown in the below;
#usually 10;
$page_arr_size=10;

#변수, 보여질 page의 번호 ;
#variable what page will be shown.
$page_no = $_GET[page_no];
$seq = $_GET[seq];

#게시물 검색 옵션과 키워드 변수
$keyword = $_POST[keyword];
$search_option = $_POST[search_option];

#페이지 번호에 음수값 예외 처리.
#exception handling for the wrong input (neg number) from somewhere else.
if(!$page_no || $page_no < 0) $page_no=0;

#총 글 갯수 counting.
#count the total docs
$row_count_query = "SELECT count(*) FROM {$table_name_board}";
#query that counts rows in the table.
$row_count_resource = mysql_query($row_count_query,$conn);
#a query and a connection information. give us resource.
$row_count = mysql_fetch_row($row_count_resource)[0];
#fetch the count value AND put the item in the [0] index into a variable named row_count;

#필요 페이지 갯수 계산 -> ceil( 전체 글수 / 페이지 크기 )
#count how many pages will we have by ceil($row_count/$page_size);
if($row_count < 0 ) $row_count = 0;
#divide number of the content by page_size.
$page_count = ceil($row_count/$page_size);
#페이지 숫자를 주소창으로 입력할때 등, 범위 초과시 가장 최후의 목록으로
if($page_no>=$page_count) $page_no = $page_count-1;

#seq 넘버를 계산해서 해당 게시물이 속하는 page로 page_no 설정.
#문제점 게시물이 page_size 이상 지워지면 page_no 설정이 -로 되버리는 문제.
#해결 방안 -> page_size를 seq 대신에 seq를 쿼리에 날려서
#전체 DB에서 몇번째 게시물인지를 계산해서 real_seq라는 변수에 저장후
#real_seq로 page_no 계산
#calculate the order of the previous visited document
#by sending query with 'seq'
#store calculated real sequence in the $real_seq and
#use $real_seq instead of $seq to calculated $page_no
if($seq!=NULL){
$seq_count_query = "SELECT count(*) FROM {$table_name_board} WHERE seq<=$seq";
$seq_count_resource = mysql_query($seq_count_query,$conn);
$real_seq = mysql_fetch_row($seq_count_resource)[0];
$page_no = floor(($row_count-($real_seq))/$page_size);
}

################################/변수 설정###################################
?>

<html>
<head>
<style type="text/css">
.table{
display:table;
width:100%;
}
.row{
display:table-row;
}

.cell{
display:table-cell;
text-align:center;
margin:auto;
}
#top_bar .cell{

text-align: center; border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; padding: 8px 0; background: #faf9fa;
background-color:grey;
border:1px solid black;
margin:30%;
}
#docs .cell{
border:1px solid grey;
margin:30%;
}
#docs.cell a:hover{
color:green;
}

#doc_no{
width:10%;
border-right:white, 0px;
}
#doc_title{
text-align:left;
padding-left:2%;
width:60%;
}
#index{
padding:30px;
}


.hidden{
display:hidden;
}
.inline-table;{
display:inline-table;
}
</style>

<script>
function confirm_submit(){
	if(document.getElementById('keyword').value==""||document.getElementById('keyword').value==undefined){
		alert("검색어를 입력하세요");
		return false;
	}
	return true;
};
</script>

<title>
테스트 게시판
</title>
</head>
<body>

<div id="entire_board">
	<div id="configure" class="hidden configure">
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
	</div>

	<div id="listview" class="table">
		<!--게시판 상단 줄-->
		<div id="top_bar" class="row">
			<a class="cell">
			no.(글번호)
			</a>
			<a class="cell">
			title(제목)
			</a>
			<a class="cell">
			name(작성자id)
			</a>
			<a class="cell">
			date(게시일)
			</a>
			<a class="cell">
			view(조회수)
			</a>
		</div>
		<!--/게시판 상단 줄-->

		<!--변수 및 쿼리 부분-->
		<div id="configure">
			<!--start of while; doc list view -->
			<?
			#######리스트 쿼리#############
			$limit_start = $page_no*$page_size;
			#검색어어와 옵션을 지정한 경우
			if($keyword!=NULL & $search_option!=NULL){
			#검색옵션이 2가지인 경우
			if($search_option=="name_n_title"){
			$search_query = "SELECT * FROM {$table_name_board} WHERE name like '%$keyword%' or title like '%$keyword%'";

			}
			#검색 옵션이 단일 옵션일때
			else{
			$search_query = "SELECT * FROM {$table_name_board} WHERE $search_option like '%$keyword%'";
			}
			$query_get_doc=$search_query ." ORDER BY seq DESC LIMIT $limit_start,$page_size";
			}
			#검색이 아닌 경우
			else{

			$query_get_doc = "SELECT * FROM {$table_name_board} ORDER BY seq DESC LIMIT $limit_start, $page_size";
			}
			#########리스트 쿼리
			$result = mysql_query($query_get_doc,$conn);
			#with this while function we put doc array to list view of html.
			?>
		</div>

		<!--게시물 뿌려주는 부분-->
		<?
		while($row=mysql_fetch_array($result))
		{
		?>
		<div id="docs" class="row">
			<!--FOR SEQ COUNT-->
			<a class="cell" id="doc_no" href="read.php?seq=<?=$row[seq]?>">
			<?=$row[seq]?>
			</a>

			<!--FOR TITLE-->
			<a class="cell" id="doc_title" href="read.php?seq=<?=$row[seq]?>">
			<?=htmlspecialchars($row[title])?>
			</a>

			<a class="cell" id="doc_name" >
			<?=htmlspecialchars($row[name])?> (<?=$row[id]?>)
			</a>

			<a class="cell" id="doc_date" >
			<?=$row[date]?>
			</a>

			<a class="cell" id="doc_viewcount">
			<?=$row[view]?>
			</a>
		</div>
		<?
		}
		//<!--/게시물 뿌려주는 부분-->
		//SQL 연결 종료
		mysql_close($conn);
		?>

		<!--to show the page numbers.-->

		<!--글쓰기버튼, 페이지 인덱스 어레이, 검색인터페이스!-->


	</div>
	<div id="bottom_menu" class="table">
		<div id="bottom_menu_tr" class="row">
			<form method="post">
			<!--글쓰기 버튼-->
			<div id="write_link" class="cell">
				<a href=write_doc.php>[글 쓰기]</a>
			</div>
			<!--글쓰기 버튼끝-->

			<!--[페이지 인덱싱]-->
			<div id="index" class="cell">
				<!--인덱싱 변수 초기화-->
				<div id ="configure" class="hidden configure">
					<?
					#페이지 인덱스의 시작 번호 ex) 페이지 인덱스 표시가 1~10, 11~20 이렇게 10개 단위인 경우
					#page_no이 2일때는 페이지 인덱스 표기가 1~10, 12일때는 11~20으로 되어야 함, 이렇게 하기 위해서는
					#내림으로 나눠주면 페이지의 시작 번호를 알 수 있음.
					$curr_page_start = floor($page_no/$page_arr_size)*$page_arr_size;
					#페이지의 끝번호는 = 시작 번호+크기-1
					$curr_page_end = $curr_page_start + $page_arr_size -1;

					#예외 처리;
					#페이지 총 갯수가 설정된 페이지 어레이 갯수보다 작을때;
					#페이지의 끝번호를 갯수로.
					#page count는 1부터, page_end는 0부터 센다.
					if($page_count-1 < $curr_page_end) $curr_page_end = $page_count-1;
					?>
				</div>
				<!--/인덱싱 변수 초기화-->
				<!--페이지 인덱스 좌향 화살표 출력부분-->
				<div id="rarrow" class="cell">
					<?
					#앞 페이지 인덱스 어레이로 이동하는 버튼.
					#현재 페이지 인덱스 시작 숫자가, 페이지 인덱스 어레이의 크기보다 크다면
					#$prev_list 링크를 생성한다.
					if($curr_page_start >= $page_arr_size){
					$prev_list = $page_no-10;
					$prev_page = $page_no-1;
					echo "<a href=$_SERVER[PHP_SELF]?page_no=$prev_list>≪</a> ";
					echo "<a href=$_SERVER[PHP_SELF]?page_no=$prev_page>＜</a>";
					}
					?>
				</div>
				<!--/페이지 인덱스 좌향 화살표 출력부분-->

				<!--페이지 인덱스 번호 출력부분-->
				<div id="number" class="cell">
					<?
					#페이지 인덱스 어레이를 하이퍼링크 텍스트로 출력한다. 시작~끝까지.
					#그리고 현재 인덱스와 일치하는 것은 일반 텍스트로 출력
					for ($i=$curr_page_start; $i <= $curr_page_end; $i++){
					$page = ($i);
					if($page_no!=$page){
					echo " <a href=$_SERVER[PHP_SELF]?page_no=$page>";
					echo "$i"+1;
					echo "</a> ";
					}
					else{
					echo "<b>";
					echo "$i"+1;
					echo "</b>";
					}
					}
					?>
				</div>
				<!--/페이지 인덱스 번호 출력부분-->

				<!--페이지 인덱스 우향 화살표 출력부분-->
				<div id="rarrow" class="cell">
					<?
					#뒷 페이지 인덱스 어레이로 이동하는 버튼 생성
					#현재 페이지 인덱스 끝 숫자가, 페이지 인덱스 어레이의 크기보다 작으면
					#$next_list 링크를 생성한다.
					if($curr_page_end < $page_count-1){
					$next_page = $page_no +1;
					if($page_no+10 <=$page_count){
					$next_list = $page_no +10;
					}
					else $next_list=$page_count-1;
					echo "<a href=$_SERVER[PHP_SELF]?page_no=$next_page>＞</a> ";
					echo "<a href=$_SERVER[PHP_SELF]?page_no=$next_list>≫</a>";

					}

					?>
				</div>
				<!--/페이지 인덱스 우향 화살표 출력부분-->
			</div>
			<!--/[페이지 인덱싱]-->

			<!--검색 이후에 목록 새로고침용 새로고침 버튼-->
			<div id="to_list" class="cell">
				<?
				if($keyword!=NULL & $search_option!=NULL){
				echo "<a href='list.php'>[목록으로]</a>";
				}
				?>
			</div>
			<!--/검색 이후에 목록 새로고침용 새로고침 버튼-->

			<!-- 검색 드랍다운 메뉴와 입력박스 시작 -->
			<div id="search" class="cell">
				<select name="search_option" id="search_option">
				<option value="name">이름</option>
				<option value="title">제목</option>
				<option value="name_n_title">제목&이름</option>
				</select>
				<input type="text" name="keyword" id="keyword" size=10 required  value="<?=htmlspecialchars($keyword)?>"/>
				<input type="submit" value="검색" onclick="return confirm_submit('submit');"/>
			</div>
			<!-- 검색 드랍다운 메뉴와 입력박스 끝 -->
			</form>
		</div>
	</div>

</div>

</body>



</html>
