<?php

namespace Anax\Users;

/** 
* User Db-model and class.
*/

class User extends \Anax\MVC\CDatabaseModel {

	public function __construct() {
		// vad i helvete.
	}
	// 
	// This class had all its methods here. When task was completed, methods were moved to CDatabaseModel instead. 
	// Inheritance is nice. 
	public function login($username, $password) {
		$this->db->select('password')
				->from('user')
				->where('acronym = ?');
		$this->db->execute([$username]); // skicka in användarnamnet till queryn
		$res = $this->db->fetchAll(); // hämta res (lösenordet)
		if(isset($res[0]) && password_verify($password, $res[0]->password)) { // verifiera
			// hämta en gång till, hela usern (inte bara lösenord. 
			$this->db->select()
						->from('user')
						->where('acronym = ?');
			$this->db->execute([$username]);
			$this->db->fetchInto($this); // hämta resultatet... men det är ju bara lösenordet lol
			$this->session->clear('user'); // rensa usern innan sätta ny
			$this->session->set('user', [
				'id' => $this->id,
				'acronym' => $this->acronym,
				'email' => $this->email,
				'name' => $this->name
			]);
		} else {
			exit('Den här användaren finns inte, eller så är lösenordet fel. <a href="">Gå tillbaka</a>.');
		}
	}

	public function logout() {
		$this->session->clear('user');
	}

	public function findUser($id) {
		if(is_numeric($id)) {
			$this->db->select()
		        ->from($this->getSource())
		        ->where("id = ?");
		} else {
			$this->db->select()
				->from($this->getSource())
				->where('acronym = ?');
		}

	    $this->db->execute([$id]);
	    return $this->db->fetchInto($this);
	}

	public static function getGravatarStatic($email, $size = 80) {
		$url = "http://www.gravatar.com/avatar/";
		$default = "http://www.gravatar.com/avatar/00000000000000000000000000000000";

		return  $url . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
	}
	public function getGravatar($size = 80) {
		$email = $this->email;
		$url = "http://www.gravatar.com/avatar/";
		$default = "http://www.gravatar.com/avatar/00000000000000000000000000000000";

		return  $url . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
	}

	public function getActivity($id) {
		$sql = "SELECT u.acronym, q.slug, q.title, q.created, q.id, ' ställde frågan ' AS action
			    FROM user AS u 
			        INNER JOIN question AS q ON q.userid = u.id
				WHERE u.id = ?
				UNION ALL 
				SELECT u.acronym, (SELECT slug FROM question WHERE question.id = a.answerto), (SELECT title FROM question WHERE question.id = a.answerto), a.created, a.id, a.iscommentto AS action
				    FROM user AS u 
				        INNER JOIN answer AS a ON a.userid = u.id
				WHERE u.id = ?";
		$this->db->execute($sql, [$id, $id]);
		return $this->db->fetchAll();
	}
}