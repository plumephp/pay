<?php

return array(
	//=======【数据库配置信息】=====================================
	'db' => array(
		'driver' => 'mysql',
		'host' => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'database' => 'al_pay',
		'port' => '3306',
		'charset' => 'utf8'
	),
	//=======【微信配置信息】=======================================
	'weixin' => array(
		/**
		 * 微信公众号信息配置
		 *
		 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
		 *
		 * MCHID：商户号（必须配置，开户邮件中可查看）
		 *
		 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
		 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
		 *
		 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
		 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
		 * @var string
		 */
		'app_id' => 'wxbbc98ca9189c97f5',
		'mch_id' => '1278024301',
		'key' => 'infobird0949ba59abbe56e057f20f88',
		'app_secret' => 'f251a4053d630a0d2c9126257f79c69c',
		/**
		 * 商户证书路径配置
		 *
		 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
		 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
		 * @var path
		 */
		'ssl_cert_path' => '/cert/apiclient_cert.pem',
		'ssl_key_path' => '/cert/apiclient_key.pem',
		'ca_info_path' => '/cert/rootca.pem',
		/**
		 * 接口访问配置
		 *
		 * 接口访问token，可自定义
		 *
		 * 调用微信红包接口的机器Ip地址
		 * @var string
		 */
		'wechat_token' => 'weixin',
		'client_ip' => '120.76.113.249'
	),
);