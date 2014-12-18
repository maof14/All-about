<?php 

namespace Anax\Tags;

class Tag extends \Anax\MVC\CDatabaseModel {

	public function __construct() {

	}
	/**
	*
	* get tags from question
	*
	*
	*/
	public function getTags() {
		$sql = "SELECT tags FROM question";
		$this->db->execute($sql);
		return $this->db->fetchAll();
	}
	/**
	*
	* create string from question tag entries
	*
	*
	*/
	public function stringify($tags) {
		foreach($tags as $tag) {
			@$arr .= $tag->tags.',';
		}
		return @$arr;
	}
	/**
	*
	* create array from the string
	*
	*
	*/
	public function createArray($str) {
		$str = substr($str, 0, -1);
		return explode(',', $str);
	}
	/**
	*
	* count instances of the words. 
	*
	*
	*/
	public function getTagCount($arr) {
		$tags = array_count_values($arr);
		arsort($tags);
		return $tags;
	}
}