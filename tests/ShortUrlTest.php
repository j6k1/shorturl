<?php

use Todays\Libs\ShortUrl\ShortUrl;
use Todays\Sample\ShortUrl\Environment;
use Todays\Sample\ShortUrl\DataStore;
use Todays\Sample\ShortUrl\Config;

class Tests_ShortUrl extends PHPUnit_Framework_TestCase {
	public function test_getMinLength()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		$this->assertEquals(strlen(sprintf("https://%s/%s", Config::HOST_NAME, "aaaaaa")), $shorturl->getMinLength());
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_int()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		$token = $shorturl->encodeToBase62(100);
		
		$this->assertEquals(100, $shorturl->decodeFromBase62($token));
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_intString()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		$token = $shorturl->encodeToBase62("10000");
		
		$this->assertEquals(10000, $shorturl->decodeFromBase62($token));
	}
	
	public function test_EncodeToBase62AndDecodeFromBase62_1_to_100()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=0; $i < 100; $i++)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_1000_to_10000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=1000; $i <= 10000; $i+=100)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_10000_to_10000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=10000; $i <= 10000000; $i+=10000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_1000000_to_100000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=1000000; $i <= 100000000; $i+=1000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_100000000_to_10000000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=100000000; $i <= 10000000000; $i+=100000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}

	public function test_EncodeToBase62AndDecodeFromBase62_10000000000_to_1000000000000()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore());
		
		for($i=10000000000; $i <= 1000000000000; $i+=10000000000)
		{
			$token = $shorturl->encodeToBase62($i);
			
			if($i !== $shorturl->decodeFromBase62($token)) $this->fail();
		}
		
		$this->assertTrue(true);
	}
}
