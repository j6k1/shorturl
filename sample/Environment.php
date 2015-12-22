<?php

namespace Todays\Sample\ShortUrl;

use Todays\Sample\ShortUrl\Config;

class Environment extends \Todays\Libs\ShortUrl\Environment {
	public function hostname()
	{
		return Config::HOST_NAME;
	}
}
