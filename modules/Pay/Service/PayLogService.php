<?php
/**
 *  @author huazai
 */

namespace Pay\Service;

use Pay\Dao\PayLogDao;
use Plume\Core\Service;

class PayLogService extends Service
{
	public function __construct($app)
	{
		parent::__construct($app, new PayLogDao($app));
	}

	/**
	 * @param $insertData
	 * @return int
	 * @throws \Exception
	 */
	public function insertPayLog($insertData) {
		try {
			return $this->dao->insert($insertData);
		} catch (\Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param $setDta
	 * @param $where
	 * @return int
	 * @throws \Exception
	 */
	public function updatePayLog($setDta, $where) {
		try {
			return $this->dao->update($setDta, $where);
		} catch (\Exception $e) {
			throw $e;
		}
	}
}