<?php
/**
 * @desc 微信支付api
 * @author huazai
 */

namespace Pay\Controller;

use Pay\Service\WeixinService;
use Plume\Util\ArrayUtils;

class WeixinController extends BaseController
{
	/**
	 * @var WeixinService
	 */
	private $weixin;
	public function beforeDispatch() {
		parent::beforeDispatch();
		// 获取微信配置信息
		$wxConfig = $this->getConfigValue('weixin');
		$this->weixin = new WeixinService($wxConfig);
	}

	public function transfersAction() {
		$this->api();
		$res = new \stdClass();
		$res->result = 0;
		if ($this->isPost() == false) {
			$res->result = 5008;
			return $this->result($res)->json()->response();
		}
		$base64Data = $this->getParamValue('data');
		if (empty($base64Data)) {
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$jsonData = base64_decode($base64Data);
		$data = json_decode($jsonData, true);
		$source = ArrayUtils::getValue($data, 'source');
		$content = ArrayUtils::getValue($data, 'content');
		$openid = ArrayUtils::getValue($content, 'openid');
		$amount = ArrayUtils::getValue($content, 'amount');
		$desc = ArrayUtils::getValue($content, 'desc');
		if (empty($openid) || empty($amount) || empty($desc)) {
			$this->provider('log')->exception('参数不合法', $data);
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$reUserName = ArrayUtils::getValue($content, 're_user_name');
		$checkName = ArrayUtils::getValue($content, 'check_name');
		// 新增请求数据
		$id = $this->id();
		$insertRet = $this->insertPayLog($id, $source, 10, $content);
		if ($insertRet == false) {
			$insertLog = array(
				'id' => $id,
				'source' => $source,
				'type' => 10,
				'content' => json_encode($content),
			);
			$this->app->provider('log')->log('pay_source_data.log', 'insert pay log failed', $insertLog);
		}
		$transfers = $this->weixin->transfers($openid, $amount, $desc, $checkName, $reUserName);
		// 更新请求结果
		$updateRet = $this->updatePayLog($id, $transfers);
		if ($updateRet == false) {
			$updateLog = array(
				'id' => $id,
				'result' => json_encode($transfers)
			);
			$this->app->provider('log')->log('pay_result_data.log', 'update pay log failed', $updateLog);
		}
		if ($transfers[0] == false) {
			$res->result = 5000;
			return $this->result($res)->json()->response();
		}
		$res->data = $transfers;
		return $this->result($res)->json()->response();
	}

	public function sendredpackAction() {
		$this->api();
		$res = new \stdClass();
		$res->result = 0;
		if ($this->isPost() == false) {
			$res->result = 5008;
			return $this->result($res)->json()->response();
		}
		$base64Data = $this->getParamValue('data');
		if (empty($base64Data)) {
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$jsonData = base64_decode($base64Data);
		$data = json_decode($jsonData, true);
		$source = ArrayUtils::getValue($data, 'source');
		$content = ArrayUtils::getValue($data, 'content');
		$sender = ArrayUtils::getValue($content, 'send_name');
		$openid = ArrayUtils::getValue($content, 're_openid');
		$amount = ArrayUtils::getValue($content, 'total_amount');
		$wishing = ArrayUtils::getValue($content, 'wishing');
		$actName = ArrayUtils::getValue($content, 'act_name');
		$remark = ArrayUtils::getValue($content, 'remark');
		if (empty($sender) || empty($openid) || empty($amount) ||
			empty($wishing) || empty($actName) || empty($remark))
		{
			$this->provider('log')->exception('参数不合法', $data);
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$sceneId = ArrayUtils::getValue($content, 'scene_id');
		// 新增请求数据
		$id = $this->id();
		$insertRet = $this->insertPayLog($id, $source, 10, $content);
		if ($insertRet == false) {
			$insertLog = array(
				'id' => $id,
				'source' => $source,
				'type' => 10,
				'content' => json_encode($content),
			);
			$this->app->provider('log')->log('pay_source_data.log', 'insert pay log failed', $insertLog);
		}
		$sendredpack = $this->weixin->sendredpack($sender, $openid, $amount, $wishing, $actName, $remark, $sceneId);
		// 更新请求结果
		$updateRet = $this->updatePayLog($id, $sendredpack);
		if ($updateRet == false) {
			$updateLog = array(
				'id' => $id,
				'result' => json_encode($sendredpack)
			);
			$this->app->provider('log')->log('pay_result_data.log', 'update pay log failed', $updateLog);
		}
		if ($sendredpack[0] == false) {
			$res->result = 5000;
			return $this->result($res)->json()->response();
		}
		$res->data = $sendredpack;
		return $this->result($res)->json()->response();
	}

	public function sendgroupredpackAction() {
		$this->api();
		$res = new \stdClass();
		$res->result = 0;
		if ($this->isPost() == false) {
			$res->result = 5008;
			return $this->result($res)->json()->response();
		}
		$base64Data = $this->getParamValue('data');
		if (empty($base64Data)) {
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$jsonData = base64_decode($base64Data);
		$data = json_decode($jsonData, true);
		$source = ArrayUtils::getValue($data, 'source');
		$content = ArrayUtils::getValue($data, 'content');
		$sender = ArrayUtils::getValue($content, 'send_name');
		$openid = ArrayUtils::getValue($content, 're_openid');
		$amount = ArrayUtils::getValue($content, 'total_amount');
		$wishing = ArrayUtils::getValue($content, 'wishing');
		$actName = ArrayUtils::getValue($content, 'act_name');
		$remark = ArrayUtils::getValue($content, 'remark');
		$totalAmount = ArrayUtils::getValue($content, 'total_amount');
		$totalNum = ArrayUtils::getValue($data, 'total_num');
		if (empty($sender) || empty($openid) || empty($amount) ||
			empty($wishing) || empty($actName) || empty($remark) ||
			empty($totalAmount) || empty($totalNum))
		{
			$this->provider('log')->exception('参数不合法', $data);
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$sceneId = ArrayUtils::getValue($content, 'scene_id');
		// 新增请求数据
		$id = $this->id();
		$insertRet = $this->insertPayLog($id, $source, 10, $content);
		if ($insertRet == false) {
			$insertLog = array(
				'id' => $id,
				'source' => $source,
				'type' => 10,
				'content' => json_encode($content),
			);
			$this->app->provider('log')->log('pay_source_data.log', 'insert pay log failed', $insertLog);
		}
		$sendgroupredpack = $this->weixin->sendgroupredpack($openid, $totalAmount, $totalNum, $sender, $wishing, $actName, $remark, $sceneId);
		// 更新请求结果
		$updateRet = $this->updatePayLog($id, $sendgroupredpack);
		if ($updateRet == false) {
			$updateLog = array(
				'id' => $id,
				'result' => json_encode($sendgroupredpack)
			);
			$this->app->provider('log')->log('pay_result_data.log', 'update pay log failed', $updateLog);
		}
		if ($sendgroupredpack[0] == false) {
			$res->result = 5000;
			return $this->result($res)->json()->response();
		}
		$res->data = $sendgroupredpack;
		return $this->result($res)->json()->response();
	}

	public function gettransferinfoAction() {
		$this->api();
		$res = new \stdClass();
		$res->result = 0;
		if ($this->isPost() == false) {
			$res->result = 5008;
			return $this->result($res)->json()->response();
		}
		$base64Data = $this->getParamValue('data');
		if (empty($base64Data)) {
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$jsonData = base64_decode($base64Data);
		$data = json_decode($jsonData, true);
		$source = ArrayUtils::getValue($data, 'source');
		$content = ArrayUtils::getValue($data, 'content');
		$partnerTradeNo = ArrayUtils::getValue($content, 'partner_trade_no');
		if (empty($partnerTradeNo)) {
			$this->provider('log')->exception('参数不合法', $data);
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		// 新增请求数据
		$id = $this->id();
		$insertRet = $this->insertPayLog($id, $source, 10, $content);
		if ($insertRet == false) {
			$insertLog = array(
				'id' => $id,
				'source' => $source,
				'type' => 10,
				'content' => json_encode($content),
			);
			$this->app->provider('log')->log('pay_source_data.log', 'insert pay log failed', $insertLog);
		}
		$gettransferinfo = $this->weixin->gettransferinfo($partnerTradeNo);
		// 更新请求结果
		$updateRet = $this->updatePayLog($id, $gettransferinfo);
		if ($updateRet == false) {
			$updateLog = array(
				'id' => $id,
				'result' => json_encode($gettransferinfo)
			);
			$this->app->provider('log')->log('pay_result_data.log', 'update pay log failed', $updateLog);
		}
		if ($gettransferinfo[0] == false) {
			$res->result = 5000;
			return $this->result($res)->json()->response();
		}
		$res->data = $gettransferinfo;
		return $this->result($res)->json()->response();
	}

	public function gethbinfoAction() {
		$this->api();
		$res = new \stdClass();
		$res->result = 0;
		if ($this->isPost() == false) {
			$res->result = 5008;
			return $this->result($res)->json()->response();
		}
		$base64Data = $this->getParamValue('data');
		if (empty($base64Data)) {
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		$jsonData = base64_decode($base64Data);
		$data = json_decode($jsonData, true);
		$source = ArrayUtils::getValue($data, 'source');
		$content = ArrayUtils::getValue($data, 'content');
		$mchBillno = ArrayUtils::getValue($content, 'mch_billno');
		if (empty($mchBillno)) {
			$this->provider('log')->exception('参数不合法', $data);
			$res->result = 5007;
			return $this->result($res)->json()->response();
		}
		// 新增请求数据
		$id = $this->id();
		$insertRet = $this->insertPayLog($id, $source, 10, $content);
		if ($insertRet == false) {
			$insertLog = array(
				'id' => $id,
				'source' => $source,
				'type' => 10,
				'content' => json_encode($content),
			);
			$this->app->provider('log')->log('pay_source_data.log', 'insert pay log failed', $insertLog);
		}
		$gethbinfo = $this->weixin->gethbinfo($mchBillno);
		// 更新请求结果
		$updateRet = $this->updatePayLog($id, $gethbinfo);
		if ($updateRet == false) {
			$updateLog = array(
				'id' => $id,
				'result' => json_encode($gethbinfo)
			);
			$this->app->provider('log')->log('pay_result_data.log', 'update pay log failed', $updateLog);
		}
		if ($gethbinfo[0] == false) {
			$res->result = 5000;
			return $this->result($res)->json()->response();
		}
		$res->data = $gethbinfo;
		return $this->result($res)->json()->response();
	}
}