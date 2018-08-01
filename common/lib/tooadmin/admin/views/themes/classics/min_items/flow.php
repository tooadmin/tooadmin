<?php
$useUpdateOrder = false;
if($useUpdateOrder && isset($_POST['setOrder']) && $_POST['setOrder'] == 'storyOrder') {
	$storyId = intval($_POST["storyId"]);
	$newOrder = intval($_POST["newOrder"]);
	$oldOrder = intval($_POST["oldOrder"]);
	if($newOrder < $oldOrder) {
		Yii::app()->db2->createCommand("update zt_story set pri2=pri2+1 where pri2 >=".$newOrder
		." and pri2<=".$oldOrder." and `status` != 'closed' and stage in ('wait','planned','projected','developing') and openedDate >'2017-04-20 00:00:00'")->execute();
	} else {
		Yii::app()->db2->createCommand("update zt_story set pri2=pri2-1 where pri2 <=".$newOrder
		." and pri2>=".$oldOrder." and `status` != 'closed' and stage in ('wait','planned','projected','developing') and openedDate >'2017-04-20 00:00:00'")->execute();	
	}	
	Yii::app()->db2->createCommand("update zt_story set pri2=".$newOrder." where id='".$storyId."'")->execute();
	echo "需求排序成功";exit;
}
if($useUpdateOrder && isset($_POST['setOrder']) && $_POST['setOrder'] == 'taskOrder') {
	$taskId = intval($_POST["taskId"]);
	$assignedTo = $_POST["assignedTo"];
	$newOrder = intval($_POST["newOrder"]);
	$oldOrder = intval($_POST["oldOrder"]);
	if($newOrder < $oldOrder) {
		Yii::app()->db2->createCommand("update zt_task set pri2=pri2+1 where assignedTo='".$assignedTo."' and pri2 >=".$newOrder
		." and pri2<=".$oldOrder." and project>=40 and (`status`='wait' or `status`='doing' or `status`='pause') and deleted='0'")->execute();
	} else {
		Yii::app()->db2->createCommand("update zt_task set pri2=pri2-1 where assignedTo='".$assignedTo."' and pri2 <=".$newOrder
		." and pri2>=".$oldOrder." and project>=40 and (`status`='wait' or `status`='doing' or `status`='pause') and deleted='0'")->execute();
	}	
	Yii::app()->db2->createCommand("update zt_task set pri2=".$newOrder." where id='".$taskId."'")->execute();
	echo "任务排序成功";exit;
}

if($useUpdateOrder && isset($_POST['setOrder']) && $_POST['setOrder'] == 'bugOrder') {
	$bugId = intval($_POST["bugId"]);
	$assignedTo = $_POST["assignedTo"];
	$newOrder = intval($_POST["newOrder"]);
	$oldOrder = intval($_POST["oldOrder"]);
	if($newOrder < $oldOrder) {
		Yii::app()->db2->createCommand("update zt_bug set pri2=pri2+1 where assignedTo='".$assignedTo."' and pri2 >=".$newOrder." and pri2<=".$oldOrder." and `status`='active' and deleted='0'")->execute();
	} else {
		Yii::app()->db2->createCommand("update zt_bug set pri2=pri2-1 where assignedTo='".$assignedTo."' and pri2 <=".$newOrder." and pri2>=".$oldOrder." and `status`='active' and deleted='0'")->execute();
	}	
	Yii::app()->db2->createCommand("update zt_bug set pri2=".$newOrder." where id='".$bugId."'")->execute();
	echo "BUG排序成功";exit;
}

//默认待认领任务指派
$waitToTaskUid = 23;

$all = array(
    "u9" => array("id" => "9","st"=>"宇平", "name" => "王宇平", "avator" => "https://wx.qlogo.cn/mmopen/l1rLqiaDs7q9ticnscEWNtpSymiaibbO1sf4zjNkAvibF8X6FlC7CzLralj3NRqYMm2kAan6hbdL32hINNicG2dx2jmNVUHAIfMDeF/0"),
    "u5" => array("id" => "5","st"=>"秦辉", "name" => "秦&nbsp;辉", "avator" => "https://wx.qlogo.cn/mmopen/nkJEeDkH649P3rnS5oR8fGYx0KndNjFPtEuNCTVZfJIlf6RODEdIXTt1hDtoicX8rVyIFQRl7mrp0sLbPaP1lBibyCZYLshSdx/0"),
    "u38" => array("id" => "38","st"=>"仲政", "name" => "肖仲政", "avator" => "https://wx.qlogo.cn/mmopen/PiajxSqBRaEKO4QXY3c7zKPKibNN1WHaVxD9GTtoATctfpLaqol6libh2UhNniamy9Gw3uAicCvdDdmmXx1eWeFWjfw/0"),
    "u29" => array("id" => "47","st"=>"远龙", "name" => "陈远龙", "avator" => ""),
    "u16" => array("id" => "16","st"=>"日存", "name" => "罗日存", "avator" => "https://wx.qlogo.cn/mmopen/MIticZoxzAyuroVujVia8Cnbq7ia9pnT9M8NGibkY8IJ8QgKdHJ32Hcsaffv9npe9IsmvrcKcRfVnoVGBwpbYAcnOgKoN8ibAmgUk/0"),
    "u2" => array("id" => "2","st"=>"方毅", "name" => "方&nbsp;毅", "avator" => "https://wx.qlogo.cn/mmopen/Q3auHgzwzM7ozDulbOINibbiaUauZe9sib0vXrIcpTEePhScUyB1ld66yLVrCZUFHFt01M1SvVcVoWAWkkfpXeXSdpcKibAoeBPtryI77LghBIM/0"),
    "u21" => array("id" => "21", "st"=>"晓俊", "name" => "郑晓俊", "avator" => "https://wx.qlogo.cn/mmopen/osskgNfsdSWxYvJPDLGVxwQdTdGxF2MJZBlHAJlKVbL0utfcfcmNibtn7AhSbJJyykZRjo4xMrS7yYoeQibU4yEWz07nemcKibA/0"),
    "u15" => array("id" => "15","st"=>"少林", "name" => "陈少林", "avator" => "https://wx.qlogo.cn/mmopen/FZlGPCRPCQuZ51JITX5Tu1Ees6qZyOB0gJFgL3YnaCL0mzO5ksxhJbHaqjLdVpaKsia4yvibiaQDpyvnPhsGbSl2YoDNtyVDfib9/0"),
    "u1" => array("id" => "1","st"=>"井顺", "name" => "吴井顺", "avator" => "https://wx.qlogo.cn/mmopen/MIticZoxzAyso3NS63AUo2gKK77NJJus5ANGUJhiaMMDiaT4E57Y0rt6h2prK8g5ehYibdptFPPPMCHfebEyOSMVEgBKgibWT1Ux7/0"),
    "u3" => array("id" => "3","st"=>"玉亭", "name" => "张玉亭", "avator" => "https://wx.qlogo.cn/mmopen/d2fJZTzRTulXc3atRy1xnwpib0ghdNGmlzkdK49libiav9Kib4zMsFx2oIPBklO2GI3fDURR47hm5o8jCC74w9Nn8A/0"),
    "u13" => array("id" => "13","st"=>"国露", "name" => "吴国露", "avator" => "https://wx.qlogo.cn/mmopen/FZlGPCRPCQuJEibNBXCJGJgwVc0ib9n5sQWBlRGy9ibIy4q2Cv0zgmKJrkXfZ3iaQ628THt3AcWhiaeZrlRkw5AmI3LUoJOibV2NHv/0"),
    "u40" => array("id" => "40","st"=>"润林", "name" => "唐润林", "avator" => "https://wx.qlogo.cn/mmopen/3pMXUNKO1xqEsGYmsb6SRjm7o7lTFNCWCE3mwG5qO7oPqicuicvw2icaAvG7vUC9JnvpSXjs1Kw99Tn9MReptUgbD3oLqkSdkib0/0"),
    "u14" => array("id" => "14","st"=>"学金", "name" => "贺学金", "avator" => "https://wx.qlogo.cn/mmopen/AHibOvvPEricKyxDF9c8Stx1Bx1eRjzIUdGmxSGfYuSCPFU4EDTea4InCxRTPvjmbswUpo9iaBDZy0Z0OVWYTECIno6vfaUicQXC/0"),
    "u23" => array("id" => "23","st"=>"领取区", "name" => "领取区", "avator" => "https://wx.qlogo.cn/mmopen/MIticZoxzAyvH9h8GOKvq2ibcXTk9kUsqibYicRFpHOMw0ib0FPVlvKFLXSibpyeMuP2MPia8PI7dvmSrfiaFdGMoicGLWAPXUux8vuP1/0"),//欧芳"
    "u11" => array("id" => "11","st"=>"秦露", "name" => "秦&nbsp;露", "avator" => "https://wx.qlogo.cn/mmopen/79hogxr0cXNdicjfY26K3d8hzFNBzaGEwdICFokiaGC0btwsiaggD5HN6qxlex30u9zCJ5YCEUbIUnLOcvQbSibbzQ5eUaVAXAcb/0"),
    "u30" => array("id" => "30","st"=>"瑶瑶", "name" => "何瑶瑶", "avator" => ""),
    "u45" => array("id" => "45","st"=>"黑才", "name" => "李黑才", "avator" => ""),
);

/* 
$person = array(
    "product" => array( $all["u1"],$all["u5"], $all["u38"],$all["u29"]),
    "test" => array( $all["u23"],$all["u11"], $all["u9"],$all["u30"]),
    "backend" => array($all["u2"],$all["u16"],$all["u15"],$all["u21"]),
    "frontend" => array($all["u3"], $all["u13"],$all["u14"],$all["u40"]),
);
*/
$person = array(
    "groupA" => array($all['u23'],$all["u2"],$all["u16"],$all["u15"],$all["u21"],$all["u45"]),
    "groupB" => array($all["u3"], $all["u13"],$all["u14"],$all["u40"],$all['u29'],$all["u1"]),
);

$workItems = array();

//bug
$bugRows = Yii::app()->db2->createCommand("select id,title,openedBy,assignedTo,pri2 from zt_bug where `status`='active' and deleted='0' order by assignedTo,pri2")->queryAll(); //product in (6,7,11,12) and
foreach ($bugRows as $bugRow) {
	$tuid = empty($bugRow["assignedTo"]) ? $waitToTaskUid :  Yii::app()->db2->createCommand("select id from zt_user where account='" . $bugRow["assignedTo"] . "'")->queryScalar();
	if($tuid == $waitToTaskUid && !empty($bugRow["assignedTo"])) {
		continue;
	}
	if (!isset($workItems["u" . $tuid])) {
		$workItems["u" . $tuid] = array();
	}
	$buid = Yii::app()->db2->createCommand("select id from zt_user where account='" . $bugRow["openedBy"] . "'")->queryScalar();
	$fromUserAvator = isset($all["u" . $buid]) ? $all["u" . $buid]["avator"] : "";
	$workItems["u" . $tuid][] = array("type" => "<span style='color:red;'>B</span>","typemark"=>"bug","assignedTo"=>$bugRow["assignedTo"],"pri2"=>$bugRow["pri2"], "id" => $bugRow["id"],
	    "title" => $bugRow["title"], "fromUserAvator" => $fromUserAvator, "url" => "http://pms.xjuke.com/bug-view-" . $bugRow["id"].'.html');
}
//项目
$projectIdName = [];
$projects = Yii::app()->db2->createCommand("select id,`name` from zt_project where 1 order by id desc")->queryAll();
$viewFsProIds = "";
foreach ($projects as $proIndex => $pro) {
	$str = str_replace('星聚客','',$pro['name']);
	$strNew = '';
	for($i=0;$i<mb_strlen($str,'utf8'); $i++) {
		if(is_numeric(mb_substr($str,$i,1))) {
			$strNew .= mb_substr($str,$i,1);
		}
        }
	$projectIdName[$pro['id']] = $strNew; 
	if($proIndex < 3) {
		$viewFsProIds .= empty($viewFsProIds) ?  $pro["id"] : ",".$pro["id"];
	}
}	
$viewFsProIds = empty($viewFsProIds) ?  "-1" : $viewFsProIds;
//任务
$taskType = array( "study" => "研", "discuss" => "讨", "affair" => "事", "design" => "原", "ui" => "UI", "devel" => "后", "web" => "前", "test" => "测", "misc" => "其",);
$taskRows = Yii::app()->db2->createCommand("select id,`name`,project,`type`,`desc`,openedBy,assignedTo,pri2 from zt_task where project>=40 and "
."(`status`='wait' or `status`='doing' or `status`='pause') and deleted='0' order by assignedTo,pri2")->queryAll();
$taskRowsFs = Yii::app()->db2->createCommand("select id,`name`,project,`type`,`desc`,openedBy,finishedBy,assignedTo,pri2 from zt_task where project in (".$viewFsProIds.") and "
."(`status`='done' or `status`='closed') and deleted='0' order by project desc,id desc")->queryAll();
foreach ($taskRows as $taskRow) {
	$tuid = empty($taskRow["assignedTo"]) ? $waitToTaskUid : Yii::app()->db2->createCommand("select id from zt_user where account='" . $taskRow["assignedTo"] . "'")->queryScalar();
	if($tuid == $waitToTaskUid && !empty($taskRow["assignedTo"])) {
		continue;
	}
	if (!isset($workItems["u" . $tuid])) {
		$workItems["u" . $tuid] = array();
	}
	$fromUserAvator = "";
	//$buid = Yii::app()->db2->createCommand("select id from zt_user where account='" . $taskRow["openedBy"] . "'")->queryScalar();
	///$fromUserAvator = isset($all["u" . $buid]) ? $all["u" . $buid]["avator"] : "";
	$workItems["u" . $tuid][] = array("type" => !empty($taskRow["type"]) ? $taskType[$taskRow["type"]] : "","typemark"=>"task","assignedTo"=>$taskRow["assignedTo"],"pri2"=>$taskRow["pri2"], "id" => $taskRow["id"],
	    "title" => $taskRow["name"], "fromUserAvator" => $fromUserAvator,'project'=>(isset($projectIdName[$taskRow['project']]) ? '<span style="color:#1902f9">'.$projectIdName[$taskRow['project']].'</span> ' : ''), "url" => "http://pms.xjuke.com/task-view-" . $taskRow["id"].'.html');
}
foreach ($taskRowsFs as $taskRow) {
	$tuid = Yii::app()->db2->createCommand("select id from zt_user where account='" . $taskRow["finishedBy"] . "'")->queryScalar();
	if (!isset($workItems["u" . $tuid])) {
		$workItems["u" . $tuid] = array();
	}
	$fromUserAvator = "";
	//$buid = Yii::app()->db2->createCommand("select id from zt_user where account='" . $taskRow["finishedBy"] . "'")->queryScalar();
	//$fromUserAvator = isset($all["u" . $buid]) ? $all["u" . $buid]["avator"] : "";
	$workItems["u" . $tuid][] = array("type" => !empty($taskRow["type"]) ? $taskType[$taskRow["type"]] : "","typemark"=>"taskFs","assignedTo"=>$taskRow["assignedTo"],"pri2"=>$taskRow["pri2"], "id" => $taskRow["id"],
	    "title" => $taskRow["name"], "fromUserAvator" => $fromUserAvator,'project'=>(isset($projectIdName[$taskRow['project']]) ? '<span style="color:#1902f9">'.$projectIdName[$taskRow['project']].'</span> ' : ''), "url" => "http://pms.xjuke.com/task-view-" . $taskRow["id"].'.html');
}

function createHtml($useUpdateOrder,$createHtmlIndex,$bigSpanNum, $itemSpanNum, $groupName, $person, $workItems) {
	$html = '<div class="span' . $bigSpanNum . '">';

	$html .= '<div class="row-fluid">';
	foreach ($person[$groupName] as $item) {
		//<img src="' . $item["avator"] . '" title="' . $item["name"] . '" class="pserson">
		if($item['id'] == 23) {
			$html .= '<div class="span' . $itemSpanNum . '"><h3 style="color:#e95611 !important;">'.$item["st"].'</h3></div>';
		} else {
			$html .= '<div class="span' . $itemSpanNum . '"><h3>'.$item["st"].'</h3></div>';
		}
	}
	$html .= '</div>';

	$html .= '<div class="row-fluid">';

	
	foreach ($person[$groupName] as $item) {
		$works = isset($workItems["u" . $item["id"]]) ? $workItems["u" . $item["id"]] : array();
		$html .= '<div class="span' . $itemSpanNum . '">';
		$workASTo = 'df';
		$workASToId = 1;
		if($item['id'] == 23) {
			$taskHCount = 0;
			$taskQCount = 0;
			$bugCount = 0;
			$taskHHtml = '<table class="table tasktable">';
			$taskQHtml = '<table class="table tasktable">';
			$bugHtml = '<table class="table tasktable">';
		foreach ($works as $work) {
			$workASTo = $work['assignedTo'];
			$workASToId = $work['id']; 
			if($work["typemark"] == "bug") {
				$bugCount++;
				if($useUpdateOrder && $bugCount != $work["pri2"]) { Yii::app()->db2->createCommand("update zt_bug set pri2=".$bugCount." where id='".$work['id']."'")->execute(); }
				$bugHtml .= '<tr><td><input class="taskinput" type="text" value="'.$bugCount.'" onchange="reSetBugOrder('.$work['id'].','
				.$bugCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
				. '" target="_blank" style="padding-right:0px;">'.mb_substr($work["title"],0,18).'</a></td></tr>';

			} else if($work["typemark"] == "task") {
				if($work['type'] == '前') {
					$taskQCount++;
					if($useUpdateOrder && $taskQCount != $work["pri2"]) { Yii::app()->db2->createCommand("update zt_task set pri2=".$taskQCount." where id='".$work['id']."'")->execute(); }
					$taskQHtml .= '<tr><td><input class="taskinput" type="text" value="'.$taskQCount.'" onchange="reSetTaskOrder('.$work['id'].','
					.$taskQCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
					. '" target="_blank" style="padding-right:0px;">'.(isset($work['project']) ? $work['project'] : '').mb_substr($work["title"],0,14) . '</a></td></tr>';	
				} else if($work['type'] == '后') {
					$taskHCount++;
					if($useUpdateOrder && $taskHCount != $work["pri2"]) { Yii::app()->db2->createCommand("update zt_task set pri2=".$taskHCount." where id='".$work['id']."'")->execute(); }
					$taskHHtml .= '<tr><td><input class="taskinput" type="text" value="'.$taskHCount.'" onchange="reSetTaskOrder('.$work['id'].','
					.$taskHCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
					. '" target="_blank" style="padding-right:0px;">'.(isset($work['project']) ? $work['project'] : '').mb_substr($work["title"],0,14) . '</a></td></tr>';	
				}
			}
		}
		$taskHHtml .= '</table>';
		$taskQHtml .= '</table>';
		$bugHtml .= '</table>';
		$html .= '
			<div class="tabbable">
				<ul class="nav nav-tabs"> 
					<li class="active"><a href="#item_'.$createHtmlIndex.'_1_'.$workASTo.$workASToId.'"  data-toggle="tab">后端任务<span style="color:red;">'.$taskHCount.'</span>个</a></li>
					<li><a href="#item_'.$createHtmlIndex.'_3_'.$workASTo.$workASToId.'"  data-toggle="tab">前端任务<span style="color:red;">'.$taskQCount.'</span>个</a></li>
					'.($bugCount > 0 ? '<li><a href="#item_'.$createHtmlIndex.'_2_'.$workASTo.$workASToId.'" data-toggle="tab">BUG<span style="color:red;">'.$bugCount.'</span>个</a></li>' : '').'
				</ul>
			<div class="tab-content"> 
				<div class="tab-pane active" id="item_'.$createHtmlIndex.'_1_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$taskHHtml.'</div>
				<div class="tab-pane" id="item_'.$createHtmlIndex.'_3_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$taskQHtml.'</div>
				'.($bugCount > 0 ? '<div class="tab-pane" id="item_'.$createHtmlIndex.'_2_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$bugHtml.'</div>' : '').'
				</div>
			</div>
		';
		$html .= '</div>';
		continue;
		}
		$taskCount = 0;
		$taskFsCount = 0;
		$bugCount = 0;
		$taskHtml = '<table class="table tasktable">';
		$taskFsHtml = '<table class="table tasktable">';
		$bugHtml = '<table class="table tasktable">';
		foreach ($works as $work) {
			$workASTo = $work['assignedTo'];
			$workASToId = $work['id'];
			if($work["typemark"] == "bug") {
				$bugCount++;
				if($useUpdateOrder && $bugCount != $work["pri2"]) { Yii::app()->db2->createCommand("update zt_bug set pri2=".$bugCount." where id='".$work['id']."'")->execute(); }
				$bugHtml .= '<tr><td><input class="taskinput" type="text" value="'.$bugCount.'" onchange="reSetBugOrder('.$work['id'].','
				.$bugCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
				. '" target="_blank" style="padding-right:0px;">'.mb_substr($work["title"],0,18).'</a></td></tr>';

			} else if($work["typemark"] == "task") {
				$taskCount++;
				if($useUpdateOrder && $taskCount != $work["pri2"]) { Yii::app()->db2->createCommand("update zt_task set pri2=".$taskCount." where id='".$work['id']."'")->execute(); }
				$taskHtml .= '<tr><td><input class="taskinput" type="text" value="'.$taskCount.'" onchange="reSetTaskOrder('.$work['id'].','
				.$taskCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
				. '" target="_blank" style="padding-right:0px;">'.(isset($work['project']) ? $work['project'] : '').mb_substr($work["title"],0,14) . '</a></td></tr>';	
			} else if($work["typemark"] == "taskFs") {
				$taskFsCount++;
				$taskFsHtml .= '<tr><td><input class="taskinput" type="text" value="'.$taskFsCount.'" onchange="reSetTaskOrder('.$work['id'].','
				.$taskFsCount.',this.value,\''.$work['assignedTo'].'\')"></td><td><a href="' . $work["url"] . '" title="' . $work["title"] 
				. '" target="_blank" style="padding-right:0px;">'.(isset($work['project']) ? $work['project'] : '').mb_substr($work["title"],0,14) . '</a></td></tr>';	
			}

		}
		$taskHtml .= '</table>';
		$taskFsHtml .= '</table>';
		$bugHtml .= '</table>';
		$html .= '
			<div class="tabbable">
				<ul class="nav nav-tabs"> 
					<li class="active"><a href="#item_'.$createHtmlIndex.'_1_'.$workASTo.$workASToId.'"  data-toggle="tab">任务<span style="color:red;">'.$taskCount.'</span>个</a></li>
					<li><a href="#item_'.$createHtmlIndex.'_3_'.$workASTo.$workASToId.'"  data-toggle="tab">完成<span style="color:#1902f9;">'.$taskFsCount.'</span>个</a></li>
					'.($bugCount > 0 ? '<li><a href="#item_'.$createHtmlIndex.'_2_'.$workASTo.$workASToId.'" data-toggle="tab">BUG<span style="color:red;">'.$bugCount.'</span>个</a></li>' : '').'
				</ul>
			<div class="tab-content"> 
				<div class="tab-pane active" id="item_'.$createHtmlIndex.'_1_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$taskHtml.'</div>
				<div class="tab-pane" id="item_'.$createHtmlIndex.'_3_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$taskFsHtml.'</div>
				'.($bugCount > 0 ? '<div class="tab-pane" id="item_'.$createHtmlIndex.'_2_'.$workASTo.$workASToId.'" style="overflow-y: scroll;height: 290px;">'.$bugHtml.'</div>' : '').'
				</div>
			</div>
		';
		$html .= '</div>';
	}
	
	$html .= '</div>';

	

	$html .= '</div>';
	return $html;
}
?>
<script>
function changeTab(url,tbliIndex) {
	var width = 1800;
	var height = 1000;
	$("#tbli1").attr("class","");
	$("#tbli2").attr("class","");
	$("#tbli3").attr("class","");
	$("#tbli4").attr("class","");
	$("#tbli5").attr("class","");
	$("#tbli"+tbliIndex).attr("class","active");
	$("#tbconDIV").html('<iframe id="fram" frameborder="0"  src="'+url+'" style="width:'+width+'px;height:'+height+'px;overflow:auto;"></iframe>');
}
</script>
<div class="navbar">
   <div class="navbar-inner">
        <a class="brand" href="#">协助开发</a>
        <ul class="nav">
		<li id='tbli1' class="active"><a href="http://admindev.xjuke.com/index.php/tDSite/flow">工作任务</a></li>
		<li id='tbli2'><a target="_blank" href="http://admindev.xjuke.com/index.php/tDCommon/menuItems/mnInd/903/mitemId/0/topmnInd/894">小程序记录</a></li>
          <li id='tbli3'><a onclick="changeTab('http://pc.xjk.com/gii','3')">Gii生成</a></li>
          <li id='tbli4'><a onclick="changeTab('http://admindev.xjuke.com/index.php/tDCommon/menuItems/mnInd/900/mitemId/0/topmnInd/894/pageLayoutType/sigle','4')">迭代记录</a></li>
          <li id='tbli5'><a href="http://admindev.xjuke.com/index.php/tDCommon/menuItems/mnInd/902/mitemId/0/topmnInd/894" target="_blank">需求记录</a></li>
        </ul>
  </div>
</div>

<div id="tbconDIV">

<style>
	.pserson {width:50px;height:50px;padding-bottom: 10px;}
	.minpserson {width:20px;height:20px;} 
	.taskinput { width: 15px; height:12px; }
	.tasktable tr td { padding:0px; }
	.tasktable tr th { padding:0px; }
	select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], 
	input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], 
	input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
	height:12px; margin-bottom: 1px;}
</style>
<script>
function reSetStoryOrder(id,oldOrder,newOrder) {
if(window.confirm("是否确认修改需求优先顺序？")) {
$.ajax({ type: "post", url: "", data: "setOrder=storyOrder&storyId="+id+"&oldOrder="+oldOrder+"&newOrder="+newOrder, dataType: "html", success: function (data) { alert(data); window.location.reload(); } }); }
}
function reSetTaskOrder(id,oldOrder,newOrder,assignedTo) {
if(window.confirm("是否确认修改任务优先顺序？")) {
$.ajax({ type: "post", url: "", data: "setOrder=taskOrder&taskId="+id+"&oldOrder="+oldOrder+"&newOrder="+newOrder+'&assignedTo='+assignedTo, dataType: "html", success: function (data) { alert(data); window.location.reload(); } }); }
}
function reSetBugOrder(id,oldOrder,newOrder,assignedTo) {
if(window.confirm("是否确认修改BUG优先顺序？")) {
$.ajax({ type: "post", url: "", data: "setOrder=bugOrder&bugId="+id+"&oldOrder="+oldOrder+"&newOrder="+newOrder+'&assignedTo='+assignedTo, dataType: "html", success: function (data) { alert(data); window.location.reload(); } }); }
}
</script>

<div class="row-fluid sortable ui-sortable">
	<div class="box span12">
		<div class="box-header well" data-original-title="">
			<h2>
				<?php echo "星聚客研发中心工作流"; ?>
				<span class="color:red;margin-left:50px;">【建议使用:火狐浏览器】</span>
				<span style="color:#a4a4a4;margin-left:10px;font-size:12px;">【完成数只显示最近两个版本的任务】</span>
			</h2>
		</div>
		<div class="box-content">
			<div class="row-fluid sortable ui-sortable">
				<!-- A--><?php echo createHtml($useUpdateOrder,1,12,2,"groupA",$person,$workItems); ?>
			</div>
			<div class="row-fluid sortable ui-sortable">
				<!-- B --><?php echo createHtml($useUpdateOrder,2,12,2,"groupB",$person,$workItems); ?>
			</div>
		</div>
	</div><!--/span-->
</div>

</div>