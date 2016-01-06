<?php
namespace Todays\Sample\ShortUrl;

require_once(implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), "..", "..", "..", "bootstrap.php"]));
(new Create_Index_Controller)->run();
