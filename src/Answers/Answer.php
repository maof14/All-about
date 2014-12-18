<?php 

namespace Anax\Answers;

class Answer extends \Anax\MVC\CDatabaseModel {
	/**
	*
	* Find all answers, and some other stuff from other tables. 
	*
	*/
	public function findAllAnswers($questionId) {
		$orderby = (isset($_GET['sort'])) && (in_array($_GET['sort'], ['created', 'score'])) ? $_GET['sort'] : 'score';
		$order = (isset($_GET['order'])) && (in_array($_GET['order'], ['asc', 'desc'])) ? $_GET['order'] : 'desc';
		$sql = "SELECT a.*, u.acronym FROM answer AS a JOIN user AS u ON a.userid = u.id WHERE answerto = ? ORDER BY COALESCE(a.iscommentto, a.id), a.iscommentto IS NOT NULL, a.correct DESC, a.$orderby $order";
	    $this->db->execute($sql, [$questionId]);
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}
}

// "SELECT commentid, m.userid, c.username, commenttext, commentdate, isreplyto FROM comments AS c JOIN members as m ON c.username = m.username WHERE postid = $varpostid ORDER BY COALESCE(isreplyto, commentid), isreplyto IS NOT NULL, commentid, commentdate DESC";