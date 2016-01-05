<?php

namespace Todays\Sample\ShortUrl;

use Todays\Sample\ShortUrl\Config;

class DataStore extends \Todays\Libs\ShortUrl\DataStore {
	protected $_dbhost;
	protected $_database;
	protected $_dbuser;
	protected $_dbpass;
	
	public function __construct(array $config = [])
	{
		if(empty($config))
		{
			$this->dbhost = Config::DBHOST;
			$this->database = Config::DATABASE;
			$this->dbuser = Config::DBUSER;
			$this->dbpass = Config::DBPASS;
		}
		else
		{
			$this->dbhost = $config["dbhost"];
			$this->database = $config["database"];
			$this->dbuser = $config["dbuser"];
			$this->dbpass = $config["dbpass"];
		}
	}
	
	public function getMaxShortUrlLength()
	{
		return 768;
	}
}
