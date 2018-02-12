<?php 
include_once '../init.php';
checkLogin();
?>
<!-- Curent Page<div id="page_num"></div>
Total Pages<div id="page_count"></div> -->
<div class="text-center">
	<ul class="pagination pagination-lg">
		<li>
			<a href="javascript:onPrevPage();"><i class="fa fa-chevron-left"></i></a>
		</li>
		
		<?php 
		if(!empty($_REQUEST['total']) && $_REQUEST['total'] > 0){
			for ($i=1; $i <= $_REQUEST['total'] ; $i++) { 
				if($i == $_REQUEST['currentPage']){
					?>
					<li class="active">
						<a href="javascript:renderPage(<?=$i;?>);"><?=$i;?></a>
					</li>
					<?php 
				}else{
					?>
					<li>
						<a href="javascript:renderPage(<?=$i;?>);"><?=$i;?></a>
					</li>
					<?php 
				}
			}
		}
		?>
		<li>
			<a href="javascript:onNextPage();"><i class="fa fa-chevron-right"></i></a>
		</li>
	</ul>
</div>
<div class="text-center">
	Current Page: <?=$_REQUEST['currentPage'];?>  Total Pages:<?=$_REQUEST['total'];?>
</div>
