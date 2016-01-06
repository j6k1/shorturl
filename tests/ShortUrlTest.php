<?php

use Todays\Libs\ShortUrl\ShortUrl;
use \Todays\Libs\ShortUrl\Exception\InvalidTokenException;
use \Todays\Libs\ShortUrl\Exception\InvalidUrlException;
use \Todays\Libs\ShortUrl\Exception\OriginalUrlNotFoundException;
use Todays\Sample\ShortUrl\Environment;
use Todays\Sample\ShortUrl\DataStore;
use Todays\Sample\ShortUrl\Config;

class Tests_ShortUrl extends PHPUnit_Framework_TestCase {
	private static $dbconfig = [
		"dbhost" => "localhost",
		"database" => "shorturltest",
		"dbuser" => "shorturltest",
		"dbpass" => "shorturltestpass"
	];
	
	public function init_table()
	{
		$connection = new \PDO(
			"mysql:host=" . static::$dbconfig["dbhost"] . ";" .
			"dbname=" . static::$dbconfig["database"],
			static::$dbconfig["dbuser"],
			static::$dbconfig["dbpass"]
		);
		
		$connection->query("truncate table shorturl_original;");
	}
	
	public function test_getMinLength()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		$this->assertEquals(strlen(sprintf("http://%s/%s", Config::HOST_NAME, "aaaaaa")), $shorturl->getMinLength());
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_int()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		$token = $shorturl->encodeToBase62(100);
		
		$this->assertEquals(100, $shorturl->decodeFromBase62($token));
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_intString()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		$token = $shorturl->encodeToBase62("10000");
		
		$this->assertEquals(10000, $shorturl->decodeFromBase62($token));
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_1_to_100()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=0; $i < 100; $i++)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_1000_to_10000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=1000; $i <= 10000; $i+=100)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_10000_to_10000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=10000; $i <= 10000000; $i+=10000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_1000000_to_100000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=1000000; $i <= 100000000; $i+=1000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_100000000_to_10000000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=100000000; $i <= 10000000000; $i+=100000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_10000000000_to_1000000000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		for($i=10000000000; $i <= 1000000000000; $i+=10000000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}
	
	public function test_ValidateBase62Token_success()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
		
		$this->assertTrue($shorturl->validateBase62Token('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
	}

	public function test_ValidateBase62Token_fail()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateBase62Token('AcSXr7_');
			$this->fail();
		} catch (InvalidTokenException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateBase62Token_fail_multibyte()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateBase62Token('AcSXr7あ');
			$this->fail();
		} catch (InvalidTokenException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_success()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$this->assertTrue($shorturl->validateUrl("http://yahoo.co.jp"));
	}
	
	public function test_ValidateUrl_success_query()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$this->assertTrue($shorturl->validateUrl("http://yahoo.co.jp?q=あああ%E3%81%82aaaああ"));
	}
	
	public function test_ValidateUrl_success_fragmenthash()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$this->assertTrue($shorturl->validateUrl("http://yahoo.co.jp#/あああ%E3%81%82/aaaああ"));
	}
	
	public function test_ValidateUrl_success_query_and_fragmenthash()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$this->assertTrue($shorturl->validateUrl("http://yahoo.co.jp?q=あああ%E3%81%82aaaああ#/あああ%E3%81%82/aaaああ"));
	}
	
	public function test_ValidateUrl_success_slash_and_query_and_fragmenthash()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$this->assertTrue($shorturl->validateUrl("http://yahoo.co.jp/あああ%E3%81%82aaa?q=あああ%E3%81%82aaaああ#/あああ%E3%81%82/aaaああ"));
	}
	public function test_ValidateUrl_fail_character_0x00()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x00aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x01()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x01aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x02()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x02aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x03()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x03aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x04()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x04aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x05()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x05aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x06()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x06aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x07()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x07aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x08()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x08aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}


	public function test_ValidateUrl_fail_character_0x09()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x09aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x10()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x10aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x11()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x11aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x12()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x12aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x13()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x13aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x14()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x14aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x15()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x15aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x16()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x16aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x17()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x17aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x18()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x18aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x19()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x19aaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1A()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Aaaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1B()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Baaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1C()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Caaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1D()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Daaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1E()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Eaaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x1F()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x1Faaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}

	public function test_ValidateUrl_fail_character_0x7F()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		try {
			$shorturl->validateUrl("http://yahoo.co.jp/\x7Faaaa");
			$this->fail();
		} catch (InvalidUrlException $e) {
			$this->assertTrue(true);
		}
	}
	
	public function test_filterUrl_nochanged()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp";
		
		$this->assertEquals("http://yahoo.co.jp", $shorturl->filterUrl($url));
	}

	public function test_filterUrl_truncate_url_maxlength()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp/".str_repeat("a", 6000);
		
		$this->assertEquals(substr("http://yahoo.co.jp/".str_repeat("a", 6000), 0, 4096), $shorturl->filterUrl($url));
	}
	
	public function test_filterUrl_encode_multibyte()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp/あああ/aaa";
		
		$this->assertEquals("http://yahoo.co.jp/%E3%81%82%E3%81%82%E3%81%82/aaa", $shorturl->filterUrl($url));
	}

	public function test_filterUrl_encode_multibyte_and_truncate_maxlength()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp/あああ/aaa/".str_repeat("あ", 1024);
		
		$this->assertEquals(
			substr("http://yahoo.co.jp/%E3%81%82%E3%81%82%E3%81%82/aaa/".str_repeat("%E3%81%82", 1024), 0, 4096), 
			$shorturl->filterUrl($url));
	}
	
	public function test_getShortUrl_and_getOriginalUrl_success()
	{
		$this->init_table();
		
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp";
		
		$shortedurl = $shorturl->getShortUrl($url);
		
		list(,,, $token) = explode("/", $shortedurl);
		
		$this->assertEquals("http://yahoo.co.jp", $shorturl->getOriginalUrl($token));
	}

	public function test_getShortUrl_fail_notfound()
	{
		$this->init_table();
		
		$shorturl = new ShortUrl(new Environment(), new DataStore(static::$dbconfig));
	
		$url = "http://yahoo.co.jp";
		
		$shortedurl = $shorturl->getShortUrl($url);
		
		list(,,, $token) = explode("/", $shortedurl);
		
		try {
			$shorturl->getOriginalUrl($token."a");
			$this->fail();
		} catch (OriginalUrlNotFoundException $e) {
			$this->assertTrue(true);
		}
	}
}
