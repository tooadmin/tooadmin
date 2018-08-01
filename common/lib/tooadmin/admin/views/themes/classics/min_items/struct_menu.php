<?php
if (isset($_GET['optType']) && $_GET['optType'] == 'structMenus') {
	$struct = TDSessionData::getCache("structMenu");
	if ($struct === false) {
		$struct = ['name' => '菜单结构'];
		$menu1 = TDModelDAO::queryAll(TDTable::$too_menu, "`pid`=0 and is_show=1 order by `order`");
		$it1 = [];
		foreach ($menu1 as $m1) {
			$it1_v = [];
			$it1_v['name'] = $m1['name'];
			$menu2 = TDModelDAO::queryAll(TDTable::$too_menu, "`pid`=" . $m1['id'] . " and is_show=1 order by `order`");
			$it2 = [];
			foreach ($menu2 as $m2) {
				$it2_v = [];
				$it2_v['name'] = $m2['name'];
				$menu3 = TDModelDAO::queryAll(TDTable::$too_menu, "`pid`=" . $m2['id'] . " and is_show=1 order by `order`");
				$it3 = [];
				foreach ($menu3 as $m3) {
					$it3_v = [];
					$it3_v['name'] = $m3['name'];
					$menu4 = TDModelDAO::queryAll(TDTable::$too_menu_items, "`menu_id`=" . $m3['id'] . " and is_show=1 order by `order`");
					$it4 = [];
					foreach ($menu4 as $m4) {
						$mmodelRemark = TDModelDAO::queryScalarByPk(TDTable::$too_module, intval($m4['module_id']), 'remark');
						$mmodelRemark = !empty($mmodelRemark) ? " 【" . $mmodelRemark . "】" : "";
						$it4[] = ['name' => $m4['name'] . $mmodelRemark, 'value' => ''];
					}
					if (count($menu4) > 0)
						$it3_v['children'] = $it4;
					$it3[] = $it3_v;
				}
				if (count($menu3) > 0)
					$it2_v['children'] = $it3;
				$it2[] = $it2_v;
			}
			if (count($menu2) > 0)
				$it1_v['children'] = $it2;
			$it1[] = $it1_v;
		}
		if (count($menu1) > 0)
			$struct['children'] = $it1;
		TDSessionData::setCache("structMenu", $struct);
	}
	echo json_encode($struct);
	exit;
}

$baseUrl = Yii::app()->baseUrl . '/common/lib/tooadmin/admin/views/themes/' . TDCommon::getThemeName() . '/www/too_admin/';
?>
<script src="<?php echo $baseUrl ?>js/echarts.min.js"></script>

<div id="main" style="width: 1600px;height: <?php echo TDModelDAO::queryScalar(TDTable::$too_menu_items, "is_show=1 and menu_id in (select id from " .
	TDTable::$too_menu_items . " where is_show=1)", "count(1)") * 12;
?>px;"></div>

<script>
	var myChart = echarts.init(document.getElementById('main'));
	myChart.showLoading();
	$.getJSON('?optType=structMenus', function(data) {
		myChart.hideLoading();
		echarts.util.each(data.children, function(datum, index) {
			//datum.collapsed = false;
			//index % 2 === 0 && (datum.collapsed = true);
		});
		myChart.setOption(option = {
			tooltip: {
				trigger: 'item',
				triggerOn: 'mousemove'
			},
			series: [
				{
					type: 'tree',
					data: [data],
					top: '1%',
					left: '7%',
					bottom: '1%',
					right: '20%',
					symbolSize: 12,
					label: {
						normal: {
							position: 'left',
							verticalAlign: 'middle',
							align: 'right',
							fontSize: 12
						}
					},
					leaves: {
						label: {
							normal: {
								position: 'right',
								verticalAlign: 'middle',
								align: 'left'
							}
						}
					},
					expandAndCollapse: false,
					animationDuration: 550,
					animationDurationUpdate: 750
				}
			]
		});
	});
</script>