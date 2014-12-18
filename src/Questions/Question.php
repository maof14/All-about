<?php 

namespace Anax\Questions;

class Question extends \Anax\MVC\CDatabaseModel {
	
	/**
	*
	*
	* Format tags before inserting into DB
	*
	*
	*/
	public function setTags($str) {
		$str = strtolower($str);
		return str_replace(', ', ',', $str);
	}

	/**
	*
	*
	* format tags and get them in a div.
	*
	*
	*/
	public function getTags(){
		$tags = explode(',', $this->tags);
		$html = "<div class='tags'>";
		foreach($tags as $tag) {
			$html .= "<a href='".$this->url->create('questions/tag/'.urlencode($tag).'')."'>$tag</a> ";
		}
		$html .= "</div>";
		return $html;
	}

	/**
	*
	*
	* Helper method, exactly as getTags but to be used when nothing is instanced. 
	* param tags = the tags instead of $question->tags, url because url cannot be created from static method. 
	*
	*/
	public static function getTagsStatic($tags, $url = null) {
		$tags = explode(',', $tags);
		$html = "<div class='tags'>";
		foreach($tags as $tag) {
			$html .= "<a href='".$url.'/'.urlencode($tag)."'>$tag</a> ";
		}
		$html .= "</div>";
		return $html;
	}
	/**
	*
	* Find question and also some stuff from other tables.
	*
	*
	*
	*/
	public function findQuestion($id) {
		if(is_numeric($id)) {
			$sql = "SELECT q.*, u.acronym FROM question AS q JOIN user AS u ON q.userid = u.id WHERE q.id = ?";
		} else {
			$sql = "SELECT q.*, u.acronym FROM question AS q JOIN user AS u ON q.userid = u.id WHERE q.slug = ?";
		}
	    $this->db->execute($sql, [$id]);
	    return $this->db->fetchInto($this);
	}
	/**
	*
	* Find all questions and some stuff from other tables. 
	*
	*
	*
	*/
	public function findAllQuestions() {
		$sql = "SELECT q.*, u.acronym, (
SELECT COUNT(*) FROM answer AS a
WHERE a.answerto = q.id
) AS c
FROM question AS q
JOIN user AS u ON q.userid = u.id
ORDER BY q.created DESC";

		$this->db->execute($sql);
		return $this->db->fetchAll();
	}
	/**
	*
	*
	* create slug from $str (title of question)
	* Method concatenates a random number to make the slug unique if other question has same title. 
	*
	*/
    public function slugify($str) { 
        $str = mb_strtolower(trim($str)); 
        $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str); 
        $str = preg_replace('/[^a-z0-9-]/', '-', $str); 
        $str = trim(preg_replace('/-+/', '-', $str), '-'); 
        return $str . '-' . rand(10000, 99999); 
    } 
	/**
	*
	*
	* helper method for front page, last 3 questions. 
	*
	*
	*/
    public function findLastQuestions() {
    	$sql = "SELECT q.*, u.acronym FROM question AS q JOIN user AS u ON q.userid = u.id ORDER BY q.created DESC LIMIT 0, 3";
    	$this->db->execute($sql);
  		return $this->db->fetchAll();
    }
}