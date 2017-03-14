<?php
/**
 * @author huazai
 */

namespace Plume\Pay\Weixin\Core;

use Plume\Pay\Application;

class Weixin extends Application
{
	/**
	 * 微信支付配置信息
	 * @var array|null
	 */
	private $config;
	public function __construct($config)
	{
		parent::__construct();
		if (empty($config)) {
			throw new \Exception('weixin config is empty!!!');
		}
		$this->config = $config;
	}

	/**
	 * 企业付款接口
	 * @param $openid string  商户appid下，某用户的openid
	 * @param $amount int  企业付款金额，单位为分
	 * @param $desc  string   企业付款操作说明信息
	 * @param $checkName string    校验用户姓名选项(NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名 OPTION_CHECK：针对已实名认证的用户才校验真实姓名)
	 * @param $reUserName string  收款用户真实姓名，如果check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名
	 * @return bool|mixed
	 */
	public function transfers($openid, $amount, $desc, $checkName='NO_CHECK', $reUserName='') {
		$obj = array();
		$obj['mch_appid']        = $this->config['app_id'];  //微信分配的公众账号ID（企业号corpid即为此appId）
		$obj['mchid']            = $this->config['mch_id'];  //微信支付分配的商户号
		$obj['partner_trade_no'] = $this->config['mch_id'] . date('YmdHis') . rand(1000, 9999);  //商户订单号（每个订单号必须唯一）
		$obj['openid']           = $openid;
		$obj['amount']           = $amount;
		$obj['desc']             = $desc;
		$obj['spbill_create_ip'] = $this->config['client_ip']; //调用接口的机器Ip地址
		$obj['check_name']       = $checkName;
		$obj['re_user_name']     = $reUserName;
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";

		$this->debug(__FUNCTION__, $obj);

		$result = $this->handle($url, $obj);

		$this->debug(__FUNCTION__, $result);

		return (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 * 现金红包接口
	 * @param $openid string  接受红包的用户用户在公众号下的openid
	 * @param $amount int  付款金额，单位分
	 * @param $sender string  红包发送者名称
	 * @param $wishing string 红包祝福语
	 * @param $actName string  活动名称
	 * @param $remark string  备注信息
	 * @param $sceneId string 场景id,发放红包使用场景，红包金额大于200时必传
	 * @return bool|mixed
	 */
	public function sendredpack($sender, $openid, $amount, $wishing, $actName, $remark, $sceneId='') {
		$obj = array();
		/**
		 * 商户订单号（每个订单号必须唯一）组成：
		 * mch_id+yyyymmdd+10位一天内不能重复的数字。
		 * 接口根据商户订单号支持重入，如出现超时可再调用。
		 */
		$obj['mch_billno']   = $this->config['mch_id'] . date('YmdHis') . rand(1000, 9999);
		// 微信支付分配的商户号
		$obj['mch_id']       = $this->config['mch_id'];
		/**
		 * 微信分配的公众账号ID（企业号corpid即为此appId）。
		 * 接口传入的所有appid应该为公众号的appid（在mp.weixin.qq.com申请的），
		 * 不能为APP的appid（在open.weixin.qq.com申请的）。
		 */
		$obj['wxappid']      = $this->config['app_id'];
		$obj['send_name']    = $sender;
		$obj['re_openid']    = $openid;
		$obj['total_amount'] = $amount;
		// 红包发放总人数,目前只能是1
		$obj['total_num']    = 1;
		$obj['wishing']      = $wishing;
		// 调用接口的机器Ip地址
		$obj['client_ip']    = $this->config['client_ip'];
		$obj['act_name']     = $actName;
		$obj['remark']       = $remark;
		$obj['scene_id']     = $sceneId;
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";

		$this->debug(__FUNCTION__, $obj);

		$result = $this->handle($url, $obj);

		$this->debug(__FUNCTION__, $result);

		return (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 * 裂变红包接口
	 * @param $openid   string     接受红包的用户用户在公众号下的openid
	 * @param $totalAmount int  红包发放总金额，即一组红包金额总和，包括分享者的红包和裂变的红包，单位分
	 * @param $totalNum   int   红包发放总人数，即总共有多少人可以领到该组红包（包括分享者）
	 * @param $sender    string    红包发送者名称
	 * @param $wishing   string    红包祝福语
	 * @param $actName   string    活动名称
	 * @param $remark    string    备注信息
	 * @param $sceneId   string    场景id,发放红包使用场景，红包金额大于200时必传
	 * @return bool|mixed
	 */
	public function sendgroupredpack($openid, $totalAmount, $totalNum, $sender, $wishing, $actName, $remark, $sceneId='') {
		$obj = array();
		$obj['mch_billno']   = $this->config['mch_id'] . date('YmdHis') . rand(1000, 9999);
		$obj['mch_id']       = $this->config['mch_id'];
		$obj['wxappid']      = $this->config['app_id'];
		$obj['send_name']    = $sender;
		$obj['re_openid']    = $openid;
		$obj['total_amount'] = $totalAmount;
		$obj['total_num']    = $totalNum;
		//红包金额设置方式ALL_RAND—全部随机,商户指定总金额和红包发放总人数，由微信支付随机计算出各红包金额
		$obj['amt_type']     = "ALL_RAND";
		$obj['wishing']      = $wishing;
		$obj['act_name']     = $actName;
		$obj['remark']       = $remark;
		$obj['scene_id']     = $sceneId;
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";

		$this->debug(__FUNCTION__, $obj);

		$result = $this->handle($url, $obj);

		$this->debug(__FUNCTION__, $result);

		return (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 * 红包查询接口
	 * @param $mchBillno  string  商户发放红包的商户订单号
	 * @return bool|mixed
	 */
	public function gethbinfo($mchBillno) {
		$obj = array();
		$obj['mch_billno']   = $mchBillno;
		// 微信支付分配的商户号
		$obj['mch_id']       = $this->config['mch_id'];
		/**
		 * 微信支付分配的公众帐号ID，接口传入的所有appid应该为公众号的appid（在mp.weixin.qq.com申请的），
		 * 不能为APP的appid（在open.weixin.qq.com申请的）。
		 */
		$obj['appid']        = $this->config['app_id'];
		// MCHT:通过商户订单号获取红包信息
		$obj['bill_type']    = "MCHT";
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";

		$this->debug(__FUNCTION__, $obj);

		$result = $this->handle($url, $obj);

		$this->debug(__FUNCTION__, $result);

		return (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 * 企业付款查询接口
	 * @param $partnerTradeNo string  商户调用企业付款API时使用的商户订单号
	 * @return bool|mixed
	 */
	public function gettransferinfo($partnerTradeNo) {
		$obj = array();
		$obj['appid']              = $this->config['app_id'];
		$obj['mch_id']             = $this->config['mch_id'];
		$obj['partner_trade_no']   = $partnerTradeNo;
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";

		$this->debug(__FUNCTION__, $obj);

		$result = $this->handle($url, $obj);

		$this->debug(__FUNCTION__, $result);

		return (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 * 微信现金红包入口函数
	 * @param string $url  微信现金红包接口
	 * @param array $obj   接口的请求参数
	 * @return bool|mixed  结果
	 */
	private function handle($url, $obj) {
		$obj['nonce_str'] = $this->create_noncestr();
		$stringA = $this->formatQueryParaMap($obj, false);
		$stringSignTemp = $stringA . "&key=". $this->config['key'];
		$sign = strtoupper(md5($stringSignTemp));
		$obj['sign'] = $sign;

		$postXml = $this->arrayToXml($obj);
		$responseXml = $this->curl_post_ssl($url, $postXml);
		return $responseXml;
	}

	/**
	 * curl方式访问微信现金红包接口
	 * @param string $url   微信现金红包接口地址
	 * @param string $vars  XML格式的请求参数
	 * @param int $second   请求超时时间
	 * @return bool|mixed   成功并且请求结果不为空返回请求结果，否则返回false
	 */
	private function curl_post_ssl($url, $vars, $second = 30) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		curl_setopt($ch, CURLOPT_SSLCERT, __DIR__ . $this->config['ssl_cert_path']);
		curl_setopt($ch, CURLOPT_SSLKEY, __DIR__ . $this->config['ssl_key_path']);
		curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . $this->config['ca_info_path']);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);

		$data = curl_exec($ch);
		if ($data) {
			curl_close($ch);
			return $data;
		} else {
			curl_close($ch);
			return false;
		}
	}

	/**
	 * 生成随机字符串
	 * @param int $len  随机字符串长度
	 * @return string   随机生成的字符串
	 */
	private function create_noncestr($len = 32) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$str = "";
		for ($i = 0; $i < $len; $i ++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	/**
	 * 格式化参数成url参数
	 * @param array $paraMap    请求参数数组
	 * @param bool $urlencode   是否进行url编码
	 * @return string           url格式的参数
	 */
	private function formatQueryParaMap($paraMap, $urlencode) {
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $key => $val) {
			if ($val != null && $val != "null" && $key != "sign") {
				if ($urlencode) {
					$val = urlencode($val);
				}
				$buff .= $key . "=" . $val ."&";
			}
		}
		$reqPar = "";
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff) - 1);
		}
		return $reqPar;
	}

	/**
	 * 数组转XML
	 * @param array $arr    需要转XML格式的数组
	 * @return string       XML数据
	 */
	private function arrayToXml($arr) {
		$xml = "<xml>";
		foreach ($arr as $key => $val) {
			if (is_numeric($val)) {
				$xml .= "<" .$key. ">" .$val . "</" . $key . ">";
			} else {
				$xml .= "<" .$key. "><![CDATA[" .$val . "]]></" . $key . ">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}
}