<?php
/**
 * @author huazai
 */

namespace Pay\Controller;

use Pay\Service\PayLogService;
use Plume\Core\Controller;

class BaseController extends Controller
{
	/**
	 * 将请求源数据插入表
	 * @param $id
	 * @param $source
	 * @param $type
	 * @param $content
	 * @return bool
	 */
	protected function insertPayLog($id, $source, $type, $content) {
		$this->api();
		try {
			$insertData = array(
				'id' => $id,
				'source' => $source,
				'type' => $type,
				'content' => json_encode($content),
				'create_time' => date('Y-m-d H:i:s')
			);
			$payLogService = new PayLogService($this->app);
			$insertRet = $payLogService->insertPayLog($insertData);
			if ($insertRet > 0) {
				return true;
			} else {
				return false;
			}
		} catch (\Exception $e) {
			$this->app->provider('log')->log('pay_log_exception.log', 'insert pay log exception', $e->getMessage(), 'exception');
			return false;
		}
	}

	/**
	 * 将请求结果更新入表
	 * @param $id
	 * @param $result
	 * @return bool
	 */
	protected function updatePayLog($id, $result) {
		try {
			$setData = array(
				'result' => json_encode($result),
				'update_time' => date('Y-m-d H:i:s')
			);
			$where = array(
				'id' => $id
			);
			$payLogService = new PayLogService($this->app);
			$updateRet = $payLogService->updatePayLog($setData, $where);
			if ($updateRet > 0) {
				return true;
			} else {
				return false;
			}
		} catch (\Exception $e) {
			$this->app->provider('log')->log('pay_log_exception.log', 'update pay log exception', $e->getMessage(), 'exception');
			return false;
		}
	}
}