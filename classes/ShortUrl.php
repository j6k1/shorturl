<?php
namespace Todays\Libs\ShortUrl;

use \Todays\Libs\ShortUrl\Environment;
use \Todays\Libs\ShortUrl\DataStore;

class ShortUrl {
	protected $_env;
	protected $_datastore;
	
	protected static $asciiTable = [65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,48,49,50,51,52,53,54,55,56,57];
	protected static $shuffleTable = [20,1,3,58,22,33,52,34,43,15,48,6,54,23,9,39,19,11,40,53,42,61,32,25,35,7,27,10,47,24,56,37,2,17,0,59,57,31,50,38,21,51,16,12,26,18,8,49,28,46,14,41,60,30,4,29,45,5,55,44,36,13];
	protected static $min_length;
	
	protected static $reverse_asciiTable = [];
	protected static $reverse_shuffleTable = [];
	
	public static function _init()
	{
		foreach(static::$asciiTable as $i => $v)
		{
			static::$reverse_asciiTable[$v] = $i;
		}

		foreach(static::$shuffleTable as $i => $v)
		{
			static::$reverse_shuffleTable[$v] = $i;
		}
	}
	
	public function __construct(Environment $env, DataStore $datastore)
	{
		$this->_env = $env;
		$this->_datastore = $datastore;
	}
	
	public function getMinLength()
	{
		return strlen(sprintf("https://%s/%s", $this->_env->hostname(), str_repeat("a", 6)));
	}
	
	public function validateBase62Token($token)
	{
		if(preg_match('/^[a-zA-Z0-9]+\z/', $token))
		{
			return true;
		}
		else
		{
			throw new \RuntimeException("トークンの形式が不正です。");
		}
	}
	
	public function encodeToBase62($id)
	{
		$source = (int)$id;
		
		$low4bits = $source & 0xF;
		$high4bits = $source & 0x780000000;
		
		$source = $source & ~(0xF);
		$source = $source & ~(0x780000000);
		
		$source = ($source | ($low4bits << 31) | ($high4bits >> 31));
		
		$i = 0;
		
		$token = [];
		
		do {
			$token[$i] = chr(static::$asciiTable[static::$shuffleTable[(int)(floor($source / pow(62, $i))) % 62]]);
			$i++;
		} while((static::$min_length > $i) || (pow(62, $i) <= $source));
		
		return implode("", $token);
	}
	
	public function decodeFromBase62($token)
	{
		$id = 0;
		
		for($i=strlen($token)-1; $i >= 0; $i--)
		{
			$id = $id * 62 + static::$reverse_shuffleTable[static::$reverse_asciiTable[ord($token[$i])]];
		}
		
		$low4bits = $id & 0xF;
		$high4bits = $id & 0x780000000;
		
		$id = $id & ~(0xF);
		$id = $id & ~(0x780000000);
		
		$id = ($id | ($low4bits << 31) | ($high4bits >> 31));
		
		return $id;
	}
}
