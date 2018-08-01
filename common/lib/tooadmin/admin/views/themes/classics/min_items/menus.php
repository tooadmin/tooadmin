<?php
function createLiUrl($row,$topMinId) {
	$menuItem = TDModelDAO::queryRowByCondtion(TDTable::$too_menu_items,"menu_id=".$row["id"]." and `is_show`=1 order by `order`");
	$url = '';
	if ($menuItem['link_page_type'] == 1) { $url .= '/tDCommon/custome'; } 
	else if($menuItem['link_page_type'] == 2) { $url .= '/tDCommon/query';	}
	else if (!empty($menuItem['module_id'])) {
		$url .= '/tDCommon/admin/moduleId/' . $menuItem['module_id'];
		if (!empty($menuItem["action_url"])) {
			if (substr($menuItem["action_url"], 0, 1) !== "/") {
				$url .= "/";
			}
			$url .= $menuItem["action_url"];
		}
	}
	if (empty($url)) {
		$url = "#";
	} else {
		$url = TDPathUrl::createUrl($url . '/mnInd/'.$row['id'].'/mitemId/'.$menuItem['id'].'/topmnInd/'.$topMinId);
		//if (!isset($_GET['mnInd'])) { header("Location:" . $url); exit; }
	}
	return $url;
}

$mainSpanNum = 10;
$topMnInd = isset($_GET["id"]) ? $_GET["id"] : -1;
$menuChilds = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=" . $topMnInd . " and `is_show`=1 " . Yii::app()->session['menu_permission_str'] . " order by `order`");
?>

			<div class="box-content">
				<ul class="nav nav-tabs" id="myTab">
					<?php foreach ($menuChilds as $second) {  
						$threeChilds = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=" . $second['id']. " and `is_show`=1 " 
						.Yii::app()->session['menu_permission_str'] . " order by `order`"); if (count($threeChilds) > 0) { ?>
						<li class=""><a href="#menutab<?php echo $second['id']; ?>"><?php echo $second['name']; ?></a></li>
					<?php } } ?>
				</ul>

				<div id="myTabContent" class="tab-content">
				<?php foreach ($menuChilds as $second) {  
						$threeChilds = TDModelDAO::queryAll(TDTable::$too_menu,"`pid`=".$second['id']. " and `is_show`=1 " 
						.Yii::app()->session['menu_permission_str'] . " order by `order`"); if (count($threeChilds) > 0) { ?>
					<div class="tab-pane" id="menutab<?php echo $second['id']; ?>">
						<div class="sortable row-fluid ui-sortable">
								<?php $index=0; foreach ($threeChilds as $row) { ?>
								<a data-original-title="" data-rel="tooltip" class="well span3 top-block" href="<?php echo createLiUrl($row,$topMnInd); ?>">
								<span class="icon32 <?php echo $row['iclass']; ?>"></span>
								<div><?php echo $row['name']; ?></div>
								</a>
								<?php $index++; 
								if($index%4 == 0 || $index == count($threeChilds)) {?>
						</div>  
						<?php } if($index%4 == 0 && $index < count($threeChilds)) { ?>
						<div class="sortable row-fluid ui-sortable">
						<?php } } ?>
					</div>	
				<?php } } ?>
			</div>
		</div>