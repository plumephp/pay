<?php
/**
 * @author huazai
 */
namespace Pay\Tests;

use Plume\Pay\Weixin\Core\Weixin;

class WeixinTest extends \PHPUnit_Framework_TestCase
{
	public function testTransfers() {
		$openid = '123456';
		$amount = '100';
		$desc = 'test';
		$config = include_once __DIR__ . '/../../../config/config.php';
		$wx = new Weixin($config['weixin']);
		$ret = $wx->transfers($openid, $amount, $desc);
		$this->assertEquals('SUCCESS', $ret['return_code']);
		$this->assertEquals('SUCCESS', $ret['result_code']);
		$this->assertEquals(false, empty($ret['partner_trade_no']));
		$this->assertEquals(false, empty($ret['payment_no']));
	}
}