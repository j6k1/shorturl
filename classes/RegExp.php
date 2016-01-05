<?php
namespace Todays\Libs\ShortUrl;

class RegExp {
	const VALID_URL = 'https?:\/\/([\-._~@:a-zA-Z0-9!\$\(\)*+,;=\']||(%[0-9A-F]{2})|(([\xC0-\xDF][\x80-\xBF])|([\xE0-\xEF][\x80-\xBF]{2})|([\xF0-\xF7][\x80-\xBF]{3})))+([\/\?#]([\-._~@:a-zA-Z0-9!\$&\(\)*+,;=\/?\[\]#]|(([\xC0-\xDF][\x80-\xBF])|([\xE0-\xEF][\x80-\xBF]{2})|([\xF0-\xF7][\x80-\xBF]{3})))*)?';
	const MULTIBYTE_STRING = '(([\xC0-\xDF][\x80-\xBF])|([\xE0-\xEF][\x80-\xBF]{2})|([\xF0-\xF7][\x80-\xBF]{3}))+';
}
