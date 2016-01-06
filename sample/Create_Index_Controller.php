<?php

namespace Todays\Sample\ShortUrl;

use Todays\Libs\ShortUrl\ShortUrl;
use \Todays\Libs\ShortUrl\Exception\InvalidTokenException;
use \Todays\Libs\ShortUrl\Exception\InvalidUrlException;
use \Todays\Libs\ShortUrl\Exception\OriginalUrlNotFoundException;
use Todays\Sample\ShortUrl\Environment;
use Todays\Sample\ShortUrl\DataStore;
use Todays\Sample\ShortUrl\Config;

class Create_Index_Controller extends Abstract_Controller {
	public function run()
	{
		if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["original_url"]))
		{
			$shorturl = new ShortUrl(new Environment(), new DataStore($this->dbconfig()));
			
			$vars = [];
			
			try {
				$vars["shorturl"] = $shorturl->getShortUrl($_POST["original_url"]);
			} catch (InvalidUrlException $e) {
				$vars["message"] = $e->getMessage();
			} catch (UrlInsertFailException $e) {
				$vars["message"] = $e->getMessage();
			} catch (\Exception $e) {
				$vars["message"] = "エラーが発生しました。";
			}
			
			$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","create_shorturl.php"]), $vars);
		}
		else
		{
			$this->send_body(implode(DIRECTORY_SEPARATOR, ["..","..","view","create_shorturl.php"]));
		}
	}
}
