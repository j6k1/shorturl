<?php

namespace Todays\Sample\ShortUrl;

use Todays\Sample\ShortUrl\Config;

class DataStore extends \Todays\Libs\ShortUrl\DataStore {
	protected $_dbhost;
	protected $_database;
	protected $_dbuser;
	protected $_dbpass;
	protected $_dbconnection;
	
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
		
		$this->connect();
	}
	
	public function __destruct()
	{
		$this->_dbconnection = null;
	}
	
	protected function connect()
	{
		$this->_dbconnection = new \PDO(
			"mysql:host=" . $this->dbhost . ";" .
			"dbname=" . $this->database,
			$this->dbuser,
			$this->dbpass
		);
	}
	
	public function getMaxShortUrlLength()
	{
		return 4096;
	}
	
	public function findUrl($id)
	{
		$sql = <<<EOM
select
	original_url 
		from shorturl_original
			where
				id = :id
EOM;
		$stmt = $this->_dbconnection->prepare($sql);
		
		$stmt->bindValue(":id", $id, \PDO::PARAM_INT);
		
		if($stmt->execute() === false)
		{
			return null;
		}
		
		$result = $stmt->fetchAll();
		
		if(count($result) == 0) return null;
		
		return $result[0];
	}
	
	public function insertUrl($url)
	{
		$sql = <<<EOM
insert into 
	shorturl_original
		(original_url, created_at, updated_at) 
		values 
		(:original_url, now(), now());
EOM;
		$stmt = $this->_dbconnection->prepare($sql);

		$stmt->bindValue(":original_url", $url, \PDO::PARAM_STR);

		if($stmt->execute() === false)
		{
			return null;
		}
		
		return $this->_dbconnection->lastInsertId();
	}
}
