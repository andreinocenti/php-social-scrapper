<?php
namespace AndreInocenti\PhpSocialScrapper\Handler\Helper;

class DataCrawler {

	private $dataList = [];
	private $tmp = [];

	function __construct($dataList = []) {
		$this->dataList = $dataList;
	}

	function addData($data){
		$this->dataList[] = $data;
		return $this;
	}

	static private function isArray($data){
		if(is_array($data)) return true;
		return $data instanceof \ArrayObject;
	}

	private function findKeysRecursive($filter, $path, $data, $key){
		$path[] = $key;
		if(!self::isArray($data)) {
			if($filter($data, $key, $path)) $this->tmp[] = $path;
			return;
		}
		foreach($data as $k => $val){
			$this->findKeysRecursive($filter, $path, $val, $k);
		}
	}

	function findKeys($filter){
		$this->tmp = [];
		foreach($this->dataList as $key => $val){
			$this->findKeysRecursive($filter, [], $val, $key);
		}
		$list = $this->tmp;
		$this->tmp = [];
		return $list;
	}

	function getValueKeys($value){
		return $this->findKeys(function($data) use ($value){
			return $data === $value;
		});
	}

	private function queryPathRecursive($selectors, $data, $key, $path = []){
		$path[] = $key;
		if($key === $selectors[0]) array_shift($selectors);
		if(empty($selectors)) return $path;

		if(!self::isArray($data)) return null;

		foreach($data as $k => $val){
			if($found = $this->queryPathRecursive($selectors, $val, $k, $path)){
				return $found;
			}
		}
		return null;
	}

	private function queryPath($selectors){
		foreach($this->dataList as $key => $val){
			if($path = $this->queryPathRecursive($selectors, $val, $key)){
				return $path;
			}
		}
		return null;
	}

	function query($selectors){
		$path = $this->queryPath($selectors);
		if($path === null) return null;
		$data = $this->dataList;
		foreach($path as $key) $data = $data[$key];
		return $data;
	}

	private function queryAllRecursive($selectors, $data, $key, $path = []){
		$path[] = $key;
		if($key === $selectors[0]) array_shift($selectors);

		if(empty($selectors)) {
			$this->tmp[implode('->', $path)] = $data;
			return;
		}

		if(self::isArray($data))
			foreach($data as $k => $val)
				$this->queryAllRecursive($selectors, $val, $k, $path);
	}

	function queryAll($selectors){
		$this->tmp = [];
		foreach($this->dataList as $key => $val) {
			$this->queryAllRecursive($selectors, $val, $key);
		}
		$list = $this->tmp;
		$this->tmp = [];
		return $list;
	}

}