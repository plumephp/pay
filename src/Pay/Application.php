<?php
/**
 * @author huazai
 */

namespace Plume\Pay;

use Plume\Core\ApplicationTrait;
use Plume\Application as PlumeApplication;

class Application
{
	use ApplicationTrait;

	public function __construct()
	{
		$this->app = new PlumeApplication('config');
		/**
		 * 设置根路径
		 */
		$this->plume('plume.root.path', __DIR__.'/../../');
		/**
		 * 开启debug日志
		 */
		$this->plume('plume.log.debug', false);
	}
}