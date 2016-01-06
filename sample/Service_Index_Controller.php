<?php

namespace Todays\Sample\ShortUrl;

use Todays\Libs\ShortUrl\ShortUrl;
use \Todays\Libs\ShortUrl\Exception\InvalidTokenException;
use \Todays\Libs\ShortUrl\Exception\InvalidUrlException;
use \Todays\Libs\ShortUrl\Exception\OriginalUrlNotFoundException;
use Todays\Sample\ShortUrl\Environment;
use Todays\Sample\ShortUrl\DataStore;
use Todays\Sample\ShortUrl\Config;

class Service_Index_Controller extends Abstract_Controller {
	public function run()
	{
		$shorturl = new ShortUrl(new Environment(), new DataStore($this->dbconfig()));

		$hash = isset($_GET["hash"]) ? $_GET["hash"] : null;

		if(empty($hash))
		{
			$this->send_status(400);
			$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","error.php"]), [
				"header_message" => "不正なアクセス",
				"message" => "URLが不正です。"
			]);
		}
		else
		{
			$vars = [];
			
			try {
				$url = $shorturl->getOriginalUrl($hash);
				$this->send_header("Location", $url);
			} catch (InvalidTokenException $e) {
				$this->send_status(400);
				$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","error.php"]), [
					"header_message" => "不正なアクセス",
					"message" => "URLが不正です。"
				]);
			} catch (OriginalUrlNotFoundException $e) {
				$this->send_status(404);
				$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","404.php"]), [
					"message" => "このURLは無効です。"
				]);
			} catch (\Exception $e) {
				$this->send_status(500);
				$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","500.php"]), [
					"message" => "エラーが発生しました。"
				]);
			}
		}
	}
}
