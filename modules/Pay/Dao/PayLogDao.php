<?php
/**
 *  @author huazai
 */

namespace Pay\Dao;

use Plume\Core\Dao;

class PayLogDao extends Dao
{
	public function __construct($app)
	{
		parent::__construct($app, 'pay_log', 'id');
	}
}