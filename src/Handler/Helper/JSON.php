<?php
namespace AndreInocenti\PhpSocialScrapper\Handler\Helper;

class JSON {

	static function parseFromHTML($html){
		$chunks = self::chunkify($html);
		$list = [];
		foreach ($chunks as $chunk){
			if(preg_match('#<script[^>]+application/(ld\+)?json.+#', $chunk, $matches)){
				$list[] = JSON::parseFromScript($matches[0]);
			}
		}
		return $list;
	}

	private static function chunkify($html){
		$chunks = explode("</script>", $html);
		return array_map(function($chunk){
			return preg_replace('#\s+#', ' ', $chunk);
		}, $chunks);
	}

	static function parseFromScript($script){
		$script = trim($script);
		$script = preg_replace('#^<script[^>]+application/(ld\+)?json[^>]*>#', '', $script);
		$script = preg_replace('#</script>$#', '', $script);
		$script = trim($script);
		$data = json_decode($script, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
		return $data;
	}

}