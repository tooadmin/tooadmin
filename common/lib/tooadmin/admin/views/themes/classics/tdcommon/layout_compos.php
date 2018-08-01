<div class="container-fluid">
     <div class="row-fluid">
		   <?php 
		   $mitemId = intval($_GET["mitemId"]);
		   if(!empty($mitemId)) {
		   		$childItems = TDModelDAO::queryAll("too_menu_items","id=".$mitemId." or layout_menu_items_pid=".$mitemId." order by `order`","id,layout_compos");
				$spanCount = 0;
				foreach($childItems as $item) {
					$curSpan = 0;
					if($item["layout_compos"] == 0 || $item["layout_compos"] == 1) {
						$curSpan = 12;
					} else {
						$curSpan = intval(12/$item["layout_compos"]);
					}			
					if($spanCount + $curSpan > 12) {
						$spanCount = $curSpan;
						echo '</div><div class="row-fluid">';
					} else {
						$spanCount += $curSpan;
					}
					echo '<div class="span'.$curSpan.'" id="layoutCompos'.$item["id"].'"><script>setTimeout("loadMenuItemUrl(\'layoutCompos'.$item["id"].'\',\''.
					TDPathUrl::getMenuItemLink(TDRequestData::getGetData('mnInd'),TDRequestData::getGetData('topmnInd'),$item["id"],TDPathUrl::$menuForType_menulink).'\')",1000);</script></div>';
				}	
		   } else {
			   echo "暂无数据";
		   }
		   ?>
      </div>
</div>