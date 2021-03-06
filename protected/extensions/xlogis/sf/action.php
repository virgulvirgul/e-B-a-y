<?php
/**
 * 顺丰API接口
 * @author gk
 * @since 2014/11/04
 */
require_once Yii::app()->basePath.'/extensions/xlogis/sf/config.php';
class SfServiceAction extends SfService{
	public function __construct($action){
		parent::__construct($action);
	}
	
	/**
	 * 新建快件信息
	 * @param string $packageId
	 * @param tinyInt $expressType
	 * @return object
	 */
	
	public function createPackage($data){
		//print_r($data);
		//var_dump($data);exit;
		$return = array();
		try
		{
			$result = parent::_call($data);//执行上传
			//var_dump($result);
			if($result->Head == 'ERR'){//失败
				$errorAttr = $result->ERROR->attributes();
				$return = array('uploadflag'=>false,'uploadmsg'=>'ErrorCode: '.$this->getErrorCodeMsg(trim($errorAttr['code'])).' Msg: '.$result->ERROR);
			}else if( $result->Head == 'OK' ){//成功
				$succAttr = $result->Body->OrderResponse->attributes();
				$succAttr = (array)$succAttr;
				$return = array('uploadflag'=>true,'uploadmsg'=>array('trackNum' =>$succAttr['@attributes']['return_tracking_no'],'mailno'=>$succAttr['@attributes']['mailno'],'agentMailno'=>$succAttr['@attributes']['agent_mailno'] ));
			}
			return $return;
		}
		catch (Exception $e)
		{
			$return = array('uploadflag'=>false,'uploadmsg'=>'CatchException:'.$e->getMessage());
			return $return;
		}
	}
// 	public function confirm($packageId){
// 		//获取包裹信息
// 		$packageInfo = getModel('order_package')->getRowBySimple('packageid = "'.$packageId.'"');
// 		$transData['Order'] = array(
// 				'orderid' 	=> $packageId,
// 				'mailno'	=> $packageInfo['track_num'],
// 				'dealtype'	=> 1,
// 		);
// 		$packageDetail = getModel('order_package_detail')->getCollectionBySimple('packageid = "'.$packageId.'"');
// 		foreach( $packageDetail as $detail ){
// 			$prodInfo = getModel('product')->getProdInfo($detail['product_code']);
// 			$weight += round(floatval($prodInfo['weight']) * intval($detail['quantity']) / 1000, 3);
// 		}
// 		$transData['Option'] = array(
// 				'weight' => $weight,
// 		);
// 		$result = parent::_call($transData, true);//执行上传
// 		if( $result->Head=='ERR' ){
// 			$this->_error = $result->ERROR;
// 			return false;
// 		}elseif( $result->Head=='OK' ){//成功
// 			return true;
// 		}
// 	}

	public function getExpressType(){
		return array(
				'1' => '标准快递',
				'2' => '顺丰特惠',
				'9' => '顺E宝平邮',
				'10'=> '顺E宝挂号',
		);
	}
	
	/**
	 * 获取错误信息
	 * @return string
	 */
	public function getErrorMsg(){
		return $this->_error;
	}
	
	public function getErrorCodeMsg( $code ){
		$codeArr = array(
				'6101' => '请求数据缺少必选项',
				'6102' => '寄件方公司名称为空',
				'6103' => '寄方联系人为空',
				'6106' => '寄件方详细地址为空',
				'6107' => '到件方公司名称为空',
				'6108' => '到件方联系人为空',
				'6111' => '到件方地址为空',
				'6112' => '到件方国家不能为空',
				'6114' => '必须提供客户订单号',
				'6115' => '到件方所属城市名称不能为空',
				'6116' => '到件方所在县/区不能为空',
				'6117' => '到件方详细地址不能为空',
				'6118' => '订单号不能为空',
				'6119' => '到件方联系电话不能为空',
				'6120' => '快递类型不能为空',
				'6121' => '寄件方联系电话不能为空',
				'6122' => '筛单类别不合法',
				'6123' => '运单号不能为空',
				'6124' => '付款方式不能为空',
				'6125' => '需生成电子运单,货物名称等不能为空',
				'6126' => '月结卡号不合法',
				'6127' => '增值服务名不能为空',
				'6128' => '增值服务名不合法',
				'6129' => '付款方式不正确',
				'6130' => '体积参数不合法',
				'6131' => '订单操作标识不合法',
				'6132' => '路由查询方式不合法',
				'6133' => '路由查询类别不合法',
				'6134' => '未传入筛单数据',
				'6135' => '未传入订单信息',
				'6136' => '未传入订单确认信息',
				'6137' => '未传入请求路由信息',
				'6138' => '代收货款金额传入错误',
				'6139' => '代收货款金额小于0错误',
				'6140' => '代收月结卡号不能为空',
				'6141' => '无效月结卡号,未配置代收货款上限',
				'6142' => '超过代收货款费用限制',
				'6143' => '是否自取件只能为1或2',
				'6144' => '是否转寄件只能为1或2',
				'6145' => '是否上门收款只能为1或2',
				'6146' => '回单类型错误',
				'6150' => '订单不存在',
				'8000' => '报文参数不合法',
				'8001' => 'IP未授权',
				'8002' => '服务（功能）未授权',
				'8003' => '查询单号超过最大限制',
				'8004' => '路由查询条数超限制',
				'8005' => '查询次数超限制',
				'8006' => '已下单，无法接收订单确认请求',
				'8007' => '此订单已经确认，无法接收订单确认请求',
				'8008' => '此订单人工筛单还未确认，无法接收订单确认请求',
				'8009' => '此订单不可收派,无法接收订单确认请求。',
				'8010' => '此订单未筛单,无法接收订单确认请求。',
				'8011' => '不存在该接入编码与运单号绑定关系',
				'8012' => '不存在该接入编码与订单号绑定关系',
				'8013' => '未传入查询单号',
				'8014' => '校验码错误',
				'8015' => '未传入运单号信息',
				'8016' => '重复下单',
				'8017' => '订单号与运单号不匹配',
				'8018' => '未获取到订单信息',
				'8019' => '订单已确认',
				'8020' => '不存在该订单跟运单绑定关系',
				'8021' => '接入编码为空',
				'8022' => '校验码为空',
				'8023' => '服务名为空',
				'8024' => '未下单',
				'8025' => '未传入服务或不提供该服务',
				'8026' => '不存在的客户',
				'8027' => '不存在的业务模板',
				'8028' => '客户未配置此业务',
				'8029' => '客户未配置默认模板',
				'8030' => '未找到这个时间的合法模板',
				'8031' => '数据错误，未找到模板',
				'8032' => '数据错误，未找到业务配置',
				'8033' => '数据错误，未找到业务属性',
				'8034' => '重复注册人工筛单结果推送',
				'8035' => '生成电子运单，必须存在运单号',
				'8036' => '注册路由推送必须存在运单号',
				'8037' => '已消单',
				'8038' => '业务类型错误',
				'8039' => '寄方地址错误',
				'8040' => '到方地址错误',
				'8041' => '寄件时间格式错误',
				'8066' => '包裹数量无效',
				'8067' => '超过最大能申请子单号数量',
				'8068' => '已经生成清单',
				'8069' => '不可收派不能生成子单号',
				'4001' => '系统发生数据错误或运行时异常',
				'4002' => '报文解析错误'
		);
	
		return $codeArr[$code];
	}
}