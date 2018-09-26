<?php 
$userID = isset($_GET['userId']) ? $_GET['userId'] : 0;

if($userID == 'all') {
	$userName = "所有账户统计";
} else {
	$userName =  TDModelDAO::queryScalarByPk("crhusers",$userID,"trueName");
	if(empty($userName)) {
		$userName =  TDModelDAO::queryScalarByPk("crhusers",$userID,"userName");
	}
}

$userCond = $userID == 'all' ?  "" : " AND userId=".$userID;

//所有未微信对账的订单发起微信对账
$wxPayCount = 0;
$otPayCount = 0;
$rows = TDModelDAO::queryAll("crhspecial_order", "isPay=1 AND check_status=0".$userCond,"payFrom,orderNo");
foreach($rows as $row) { 
	if($row['payFrom']==1) { 
		echo CrhToo::checkorder($row['orderNo']).'<br/>';
		$wxPayCount++;
	} else {
		$otPayCount++;
	}
}

$rows = TDModelDAO::queryAll("crhorders", "isPay=1 AND check_status=0".$userCond,"payFrom,orderNo");
foreach($rows as $row) { 
	if($row['payFrom']==1) { 
		echo CrhToo::checkorder($row['orderNo']).'<br/>';
		$wxPayCount++;
	} else {
		echo CrhToo::checkorder($row['orderNo']).'<br/>';
		$otPayCount++;
	}
}
//echo '微信支付检测核对 '.$wxPayCount.'   其它检查核对 '.$otPayCount
$totalThreePay = 0;
?>
<div class="row-fluid sortable ui-sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i><?php echo '('.$userID.') '.$userName; ?>对账情况：</h2>
        </div>
        <div class="box-content">
           <div class="sortable row-fluid ui-sortable">
			<table class="table">
				<?php
				//商城订单统计核对
				$data = [];
				$data['a'] = array( 'title' => '商城订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=1 AND isPay=1".$userCond, "sum(totalMoney)"),2),);
				$orderAmountStore = $data['a']['amount'];
				$data['b'] = array( 'title' => '余额支付商城订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=1 AND isPay=1".$userCond, "sum(balancePay)"),2),);
				$data['c'] = array( 'title' => '第三方支付商城订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=1 AND isPay=1".$userCond, "sum(threePay)"),2),);
				$totalThreePay += $data['c']['amount'];
				$data['d'] = array( 'title' => '(新手)减免商城订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=1 AND isPay=1".$userCond, "sum(reduceMonye)"),2),);
				$data['e'] = array( 'title' => '商城订单礼包使用总额', 'amount' => round(TDModelDAO::queryScalar("crhgiftbag", "giftId in (select useGift from crhorders where "
				." saleType=1 AND isPay=1 ".$userCond." )".$userCond, "sum(money)"),2),);
				?>
				<tr>
					<td><?php echo $data['a']['title'].'  '.$data['a']['amount']; ?></td>
					<td>核对</td>
					<td><?php echo $data['b']['title'].'  '.$data['b']['amount']; ?>
					<?php echo ' + '.$data['c']['title'].'  '.$data['c']['amount']; ?>
					<?php echo ' + '.$data['d']['title'].'  '.$data['d']['amount']; ?>
					<?php echo ' + '.$data['e']['title'].'  '.$data['e']['amount']; ?>
					<?php $res = $data['b']['amount']+ $data['c']['amount']+$data['d']['amount']+$data['e']['amount']; echo ' = '.$res; ?></td>
					<td>核对结果：<?php $checkres = $data['a']['amount'] - $res; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">少支付</span>'.$checkres : '<span style="color:orange;">多支付</span>'.$checkres);  ?></td>
				</tr>
				<?php
				//团购订单统计核对   
				$data = [];
				$data['a'] = array( 'title' => '团购订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=2 AND isPay=1".$userCond, "sum(totalMoney)"),2),);
				$orderAmountTeam= $data['a']['amount'];
				$data['b'] = array( 'title' => '余额支付团购订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=2 AND isPay=1".$userCond, "sum(balancePay)"),2),);
				$data['c'] = array( 'title' => '第三方支付团购订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=2 AND isPay=1".$userCond, "sum(threePay)"),2),);
				$totalThreePay += $data['c']['amount'];
				$data['d'] = array( 'title' => '(新手)减免团购订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=2 AND isPay=1".$userCond, "sum(reduceMonye)"),2),);
				$data['e'] = array( 'title' => '团购订单礼包使用总额', 'amount' => round(TDModelDAO::queryScalar("crhgiftbag", "giftId in (select useGift from crhorders where "
				." saleType=2 AND isPay=1 ".$userCond." )".$userCond, "sum(money)"),2),);
				?>
				<tr>
					<td><?php echo $data['a']['title'].'  '.$data['a']['amount']; ?></td>
					<td>核对</td>
					<td><?php echo $data['b']['title'].'  '.$data['b']['amount']; ?>
					<?php echo ' + '.$data['c']['title'].'  '.$data['c']['amount']; ?>
					<?php echo ' + '.$data['d']['title'].'  '.$data['d']['amount']; ?>
					<?php echo ' + '.$data['e']['title'].'  '.$data['e']['amount']; ?>
					<?php $res = $data['b']['amount']+ $data['c']['amount']+$data['d']['amount']+$data['e']['amount']; echo ' = '.$res; ?></td>
					<td>核对结果：<?php $checkres = $data['a']['amount'] - $res; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">少支付</span>'.$checkres : '<span style="color:orange;">多支付</span>'.$checkres);  ?></td>
				</tr>
				<?php
				//拼单订单统计核对
				$data = [];
				$data['a'] = array( 'title' => '拼单订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=3 AND isPay=1".$userCond, "sum(totalMoney)"),2),);
				$orderAmountPin= $data['a']['amount'];
				$data['b'] = array( 'title' => '余额支付拼单订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=3 AND isPay=1".$userCond, "sum(balancePay)"),2),);
				$data['c'] = array( 'title' => '第三方支付拼单订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=3 AND isPay=1".$userCond, "sum(threePay)"),2),);
				$totalThreePay += $data['c']['amount']; 
				$data['d'] = array( 'title' => '(新手)减免拼单订单总额', 'amount' => round(TDModelDAO::queryScalar("crhorders", "saleType=3 AND isPay=1".$userCond, "sum(reduceMonye)"),2),);
				$data['e'] = array( 'title' => '拼单订单礼包使用总额', 'amount' => round(TDModelDAO::queryScalar("crhgiftbag", "giftId in (select useGift from crhorders where "
				." saleType=3 AND isPay=1 ".$userCond." )".$userCond, "sum(money)"),2),);
				?>
				<tr>
					<td><?php echo $data['a']['title'].'  '.$data['a']['amount']; ?></td>
					<td>核对</td>
					<td><?php echo $data['b']['title'].'  '.$data['b']['amount']; ?>
					<?php echo ' + '.$data['c']['title'].'  '.$data['c']['amount']; ?>
					<?php echo ' + '.$data['d']['title'].'  '.$data['d']['amount']; ?>
					<?php echo ' + '.$data['e']['title'].'  '.$data['e']['amount']; ?>
					<?php $res = $data['b']['amount']+ $data['c']['amount']+$data['d']['amount']+$data['e']['amount']; echo ' = '.$res; ?></td>
					<td>核对结果：<?php $checkres = $data['a']['amount'] - $res; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">少支付</span>'.$checkres : '<span style="color:orange;">多支付</span>'.$checkres);  ?></td>
				</tr>
				<?php
				//体验订单统计核对
				$data = [];
				$data['a'] = array( 'title' => '体验订单总额', 'amount' => round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(amount)"),2),);
				$data['b'] = array( 'title' => '余额支付体验订单总额', 'amount' => round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(balancePay)"),2),);
				$totalBalancePay = $data['b']['amount']; 
				$data['c'] = array( 'title' => '第三方支付体验订单总额', 'amount' => round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(threePay)"),2),);
				$totalThreePay += $data['c']['amount']; 
				$data['d'] = array( 'title' => '(新手)减免体验订单总额', 'amount' => round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(reduceMonye)"),2),);
				$newUserReduce = $data['d']['amount']; 
				$data['e'] = array( 'title' => '体验订单礼包使用总额', 'amount' => round(TDModelDAO::queryScalar("crhgiftbag", "giftId in (select useGift from crhspecial_order where "
				."isPay=1 ".$userCond." AND orderStatus in (1,4,5,6,7))".$userCond, "sum(money)"),2),);
				?>
				<tr>
					<td><?php echo $data['a']['title'].'  '.$data['a']['amount']; ?></td>
					<td>核对</td>
					<td><?php echo $data['b']['title'].'  '.$data['b']['amount']; ?>
					<?php echo ' + '.$data['c']['title'].'  '.$data['c']['amount']; ?>
					<?php echo ' + '.$data['d']['title'].'  '.$data['d']['amount']; ?>
					<?php echo ' + '.$data['e']['title'].'  '.$data['e']['amount']; ?>
					<?php $res = $data['b']['amount']+ $data['c']['amount']+$data['d']['amount']+$data['e']['amount']; echo ' = '.$res; ?></td>
					<td>核对结果：<?php $checkres = $data['a']['amount'] - $res; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">少支付</span>'.$checkres : '<span style="color:orange;">多支付</span>'.abs($checkres));  ?></td>
				</tr>
				<tr>
					<td>第三方支付总额：<?php echo $totalThreePay;?></td>
					<td>核对</td>
					<td>(商城)已对账第三方支付：<?php $three1 = round(TDModelDAO::queryScalar("crhorders","saleType=1 AND isPay=1 AND check_status>0".$userCond, "sum(threePay)"),2); echo $three1; ?>
					+ (团购)已对账第三方支付：<?php $three2 = round(TDModelDAO::queryScalar("crhorders","saleType=2 AND isPay=1 AND check_status>0".$userCond, "sum(threePay)"),2); echo $three2; ?>
					+ (拼单)已对账第三方支付：<?php $three3 = round(TDModelDAO::queryScalar("crhorders","saleType=3 AND isPay=1 AND check_status>0".$userCond, "sum(threePay)"),2); echo $three3; ?>
					+ (体验)已对账第三方支付：<?php $three4 = round(TDModelDAO::queryScalar("crhspecial_order","isPay=1 AND check_status>0".$userCond, "sum(threePay)"),2); echo $three4;
					$res = $three1+$three2+$three3+$three4; echo ' = '.$res; ?>  </td>
					<td>核对结果：<?php $checkres = $totalThreePay - $res; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">少支付</span>'.$checkres : '<span style="color:orange;">多支付</span>'.abs($checkres));  ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>
					应获得收益总额：<?php $needRecTotal = round(TDModelDAO::queryScalar("crhspecial_order","isPay=1 AND orderStatus>0".$userCond, "sum(redBag)"),2); echo $needRecTotal; ?>	
					 -   月返息待获得收益总额: <?php $laodRed = round(TDModelDAO::queryScalar("crhredbag","isEffective=1 AND isRelease=0".$userCond, "sum(redamount)"),2); echo $laodRed; ?>
					=  已获得收益总额 <?php $recIncome = $needRecTotal - $laodRed; echo $recIncome; ?>
					【 月返息已获得收益总额: <?php $recRed = round(TDModelDAO::queryScalar("crhredbag","isEffective=1 AND isRelease=1".$userCond, "sum(redamount)"),2); echo $recRed; ?>
					+ 立即到账获得收益总额 <?php $inTimeRec = $recIncome - $recRed; echo $inTimeRec; ?> 】 
					</td>
					<td></td>
				</tr>
		</table>
            </div>
	    <div class="sortable row-fluid ui-sortable">
		    <table class="table">
			<?php
			//现金券总额
			$totalCash = round(TDModelDAO::queryScalar("crhgiftbag","useType=1".$userCond,"sum(money)"),2);	
			//已领取现金券
			$recCash = round(TDModelDAO::queryScalar("crhgiftbag","isUse=1 AND useType=1".$userCond,"sum(money)"),2);	
			//待领取现金券
			$loadRecCash = round(TDModelDAO::queryScalar("crhgiftbag","isUse=0 AND useType=1 AND endDate>='".date("Y-m-d")."'".$userCond,"sum(money)"),2);	
			//过期现金券
			$expCash = round(TDModelDAO::queryScalar("crhgiftbag","isUse=0 AND useType=1 AND endDate <'".date("Y-m-d")."'".$userCond,"sum(money)"),2);	
				
			//优惠券总额
			$totalCoup = round(TDModelDAO::queryScalar("crhgiftbag","useType=0".$userCond,"sum(money)"),2);	
			//已使用优惠券
			$usedCoup = round(TDModelDAO::queryScalar("crhgiftbag","isUse=1 AND useType=0".$userCond,"sum(money)"),2);	
			//待使用优惠券
			$loadUseCoup = round(TDModelDAO::queryScalar("crhgiftbag","isUse=0 AND useType=0 AND endDate>='".date("Y-m-d")."'".$userCond,"sum(money)"),2);	
			//已过期优惠券
			$expCoup = round(TDModelDAO::queryScalar("crhgiftbag","isUse=0 AND useType=0 AND endDate <'".date("Y-m-d")."'".$userCond,"sum(money)"),2);	
			?>
			    <tr>
				    <td>现金券总额<?php echo $totalCash ?></td>	
				    <td>已领取现金券<?php echo $recCash; ?></td>
				    <td>待领取现金券<?php echo $loadRecCash; ?></td>
				    <td>过期现金券 <?php echo $expCash; ?></td>

				    <td>优惠券总额<?php echo $totalCoup; ?></td>
				    <td>已使用优惠券<?php echo $usedCoup;  ?></td>
				    <td>待使用优惠券<?php echo $loadUseCoup; ?></td>
				    <td>已过期优惠券<?php echo $expCoup; ?></td>
			    </tr>   
		    </table>
	    </div>
		<?php
		$amountItems = [];
		$cancelStore = round(TDModelDAO::queryScalar("crhorders", "saleType=1 AND isPay=1 AND orderStatus in (2,3)".$userCond, "sum(balancePay+threePay)"),2);
		$amountItems[] = array( 'type'=>'a','name' => '已付款取消商城订单总额', 'amount' => $cancelStore,);
		$cancelTeam= round(TDModelDAO::queryScalar("crhorders", "saleType=2 AND isPay=1 AND orderStatus in (2,3)".$userCond, "sum(balancePay+threePay)"),2);
		$amountItems[] = array( 'type'=>'a','name' => '已付款取消团购订单总额', 'amount' => $cancelTeam,);
		$cancelPin = round(TDModelDAO::queryScalar("crhorders", "saleType=3 AND isPay=1 AND orderStatus in (2,3)".$userCond, "sum(balancePay+threePay)"),2);
		$amountItems[] = array( 'type'=>'a','name' => '已付款取消拼单订单总额', 'amount' => $cancelPin,);
		$cancelSpecial = round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1 AND orderStatus in (2,3)".$userCond, "sum(amount)"),2);
		$amountItems[] = array( 'type'=>'a','name' => '已付款取消体验订单总额', 'amount' => $cancelSpecial,);
		$amountItems[] = array( 'type'=>'a','name' => '领取现金券', 'amount' => $recCash,);
		$amountItems[] = array( 'type'=>'a','name' => '体验使用优惠券', 'amount' => $usedCoup,);
		$amountItems[] = array( 'type'=>'a','name' => '体验新手减免', 'amount' => $newUserReduce,);
		?>
		<div class="sortable row-fluid ui-sortable">
	    		<table class="table">
				<tr>
					<td>其它收入金额 = 
					<?php $otherTotal = 0; $name=''; foreach($amountItems as $keystr => $item) { $otherTotal += $item['amount']; $name .= empty($name) ? '' : '+'; $name .= $item['name'];  } echo $name; ?>
					 = <?php echo $otherTotal; ?></td>
				</tr>
	    		</table>
		</div>
		<?php
		$amountItems = [];
		$amountItems['a1'] = array( 'type'=>'a','name' => '体验订单第三方支付总额', 'amount' =>round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(threePay)"),2) );
		$amountItems['c1'] = array( 'type'=>'d','name' => '体验订单总额', 'amount' =>  round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(amount)"),2));
		$specialOrderTotal = $amountItems['c1']['amount'];
		
		$amountItems['d1'] = array( 'type'=>'a','name' => '累计已获得收益', 'amount' => $recIncome,);
		$recBenJing =  round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1 AND orderStatus in (4,5,6,7)".$userCond,"sum(amount)"),2);
		$amountItems['d2'] = array( 'type'=>'a','name' => '累计返还押金', 'amount' => $recBenJing,);
		$rewardTal =  round(TDModelDAO::queryScalar("crhlog_moneys", "targetType=2 ".$userCond,"sum(money)"),2);
		$amountItems['d3'] = array( 'type'=>'a','name' => '累计提成奖励', 'amount' => $rewardTal,);

		//$amountItems['a2'] = array( 'type'=>'a','name' => '余额购买体验总额', 'amount' => $totalBalancePay,);
		
		$amountItems['e1'] = array( 'type'=>'c','name' => '待到期收益', 'amount' => $laodRed,);
		$loadBenJing =  round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1 AND orderStatus=1".$userCond,"sum(amount)"),2);
		$amountItems['e2'] = array( 'type'=>'c','name' => '体验中的押金', 'amount' => $loadBenJing,);

		$drawFinish =  round(TDModelDAO::queryScalar("crhcash_draws", "cashSatus=3 ".$userCond,"sum(money+poundage)"),2);
		$amountItems['f1'] = array( 'type'=>'d','name' => '已提现总额', 'amount' => $drawFinish,);
		$drawAuditing=  round(TDModelDAO::queryScalar("crhcash_draws", "cashSatus=0".$userCond,"sum(money)"),2);
		$amountItems['f2'] = array( 'type'=>'d','name' => '待审提现总额', 'amount' => $drawAuditing,);
		$drawLoad=  round(TDModelDAO::queryScalar("crhcash_draws", "cashSatus=1".$userCond,"sum(money)"),2);
		$amountItems['f3'] = array( 'type'=>'d','name' => '待转账提现总额', 'amount' => $drawLoad,);

		?>
		<div class="sortable row-fluid ui-sortable">
	    		<table class="table">
				<tr>
					<td>总入</td>
					<td>体验中</td>
					<td>已到期</td>
					<td>已支</td>
					<td>待支</td>
				</tr>
				<tr>
					<td>
						<?php $threeSpeTotal = round(TDModelDAO::queryScalar("crhspecial_order", "isPay=1".$userCond." AND orderStatus in (1,4,5,6,7)", "sum(threePay)"),2);
						$zongRu = $threeSpeTotal + $otherTotal;  
						?>
						其它收入金额<?php echo $otherTotal; ?> + 体验订单第三方支付总额<?php echo $threeSpeTotal; ?> = <?php echo $zongRu; ?> 
					</td>
					<td>
						<?php $zongTYZ = $laodRed + $loadBenJing; ?>
						待到期收益<?php echo $laodRed; ?> + 待到期押金<?php echo $loadBenJing; ?> = <?php echo $zongTYZ; ?>
					</td>
					<td>
						<?php $yidaoQiBenXi = $recIncome + $recBenJing + $rewardTal;  ?>
						已获得收益 <?php echo $recIncome; ?> + 已返还押金 <?php echo $recBenJing; ?> + 已获得提成奖励 <?php echo $rewardTal; ?> = <?php echo $yidaoQiBenXi; ?> 
					</td>
					<td>
						已提现总额 <?php echo $drawFinish; ?>
					</td>
					<td>
						<?php 
							$balance = round(TDModelDAO::queryScalar("crhusers","1 ".$userCond, "sum(userMoney)"),2);
							$daiZhiZongYe = $drawAuditing + $drawLoad + $balance; 
						?>
						待审提现总额 <?php echo $drawAuditing; ?> + 待转账提现总额 <?php echo $drawLoad; ?> + 当前余额 <?php echo $balance; ?> = <?php echo $daiZhiZongYe; ?> 	 
					</td>
				</tr>
				<tr>
					<td colspan="5"> 分析： </td>
				</tr>
				<tr>
					<td colspan="5"> 
						总入 是否大于或等于 (体验订单总额 - 体验余额支付总额）  : <?php  $checkres = $zongRu - $specialOrderTotal + $totalBalancePay; echo $checkres >= 0 ? '<span style="color:green;">正常</span>' : 
						'<span style="color:red;">当前缺少</span>'.$checkres;  ?>  
					</td>
				</tr>
				<tr>
					<td colspan="5"> 
						<?php $curShouYi = round($zongTYZ + $yidaoQiBenXi - $specialOrderTotal - $rewardTal,2);//-$totalBalancePay 余额支付体验订单总额  ?>
						( 体验中 + 已到期) - 体验订单总额 - 已获得提成奖励 = 当前总收益 <?php echo $curShouYi; ?>  
						<br/>	
						应获得收益(从体验订单中分析应该可获得收益总额) = 待到期收益 + 已获得收益 = <?php $yingShouYi = $laodRed + $recIncome; echo $yingShouYi;  ?> 
						 <?php $checkres = $yingShouYi - $curShouYi; echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">当前实际少</span>'.$checkres : '<span style="color:orange;">当前实际多</span>'.abs($checkres));  ?>
					</td>
				</tr>
				<tr>
					<td colspan="5"> 
						(总入 + 应获得收益 + 已获得提成奖励) - 体验中 - 已支 = 应可提现金额 <?php $yingKeTiXian = $zongRu + $yingShouYi + $rewardTal - $zongTYZ - $drawFinish; echo $yingKeTiXian;   ?>
						  与当前实际待支 <?php echo $daiZhiZongYe; ?>  <?php $checkres = round($yingKeTiXian - $daiZhiZongYe,2); 
						  if($checkres != 0) {
 							TDModelDAO::addRow("crhsys_configs", array("fieldName"=>"检测账户".$userID,"fieldCode"=>"userId=".$userID,"fieldValue"=>$checkres));
						  }
						  echo $checkres == 0 ? '<span style="color:green;">正常</span>' : 
					($checkres > 0 ? '<span style="color:red;">当前实际少</span>'.$checkres : '<span style="color:orange;">当前实际多</span>'.abs($checkres));  ?>
					</td>
				</tr>
	    		</table>
		</div>
		
        </div>
    </div>
</div>
<?php 
//异常情况处理
$optType = isset($_GET['optType']) ? $_GET["optType"] : "";
//资金记录时间为零的
if($optType == "logMoneyReInit") {
}
?>