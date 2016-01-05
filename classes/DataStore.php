<?php

namespace Todays\Libs\ShortUrl;

abstract class DataStore {
	abstract protected function connect();
	abstract public function getMaxShortUrlLength();
	abstract public function findUrl($id);
	abstract public function insertUrl($url);
}
