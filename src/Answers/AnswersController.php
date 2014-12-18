<?php 

namespace Anax\Answers;

class AnswersController implements \Anax\DI\IInjectionAware {
	use \Anax\DI\TInjectable;
	/**
	*
	* Constructor for controllers
	*
	*
	*/
	public function initialize() {
		$this->answers = new \Anax\Answers\Answer();
		$this->answers->setDI($this->di);

		if($this->session->has('user')) {
			$this->user = $this->session->get('user');
		}
	}
	/**
	*
	* list all answers. To be called by dispatcher. 
	* questionid = question that the answers belongs to
	* myquestion = is the question mine?
	* correctAns = ans marked as correct?
	*
	*
	*/
	public function listAction($questionId, $myQuestion, $correctAns) {
		$all = $this->answers->findAllAnswers($questionId);
		$this->views->add('project/answers', [
				'answers' => $all,
				'myQuestion' => $myQuestion,
				'correctAns' => $correctAns,
				'isLoggedIn' => isset($this->user)
			]);
	}
	/**
	*
	*
	* Create new answer. To be called by dispatcher
	*
	*/
	public function newAction($questionId) {
		if(!$this->session->has('user')) exit('Du är inte inloggad. Då får man inte svara på frågor!');
		$form = $this->cf->getCreateAnswerForm();
		$this->views->add('project/new-comment', [
				'content' => $form
			]);

		$isPosted = $this->request->getPost('submit');
		$isCommented = $this->request->getPost('submitcomment');

		if($isPosted) {
			$user = $this->session->get('user');
			$answer = [
				'userid' => $user['id'],
				'answerto' => $questionId,
				'text' => $this->request->getPost('text'),
				'created' => date(DATE_RFC2822),
				'score' => 0
			];
			$this->answers->save($answer);
			$sql = "UPDATE user SET score = score + 2 WHERE id = ?";
			$this->db->execute($sql, [$user['id']]);
			
			$url = $this->request->getCurrentUrl();
			$this->response->redirect($url);
		} elseif($isCommented) {
			$user = $this->session->get('user');
			$comment = [
				'userid' => $user['id'],
				'answerto' => $questionId,
				'text' => $this->request->getPost('text'),
				'created' => date(DATE_RFC2822),
				'iscommentto' => $this->request->getPost('iscommentto'),
			];
			$this->answers->save($comment);
			$url = $this->request->getCurrentUrl();
			$this->response->redirect($url);
		}
	}

	/**
	* Function to vote on Answers. 
	*
	* @param $id = id of answer, $direction = vote up or down from thumbs. 
	*
	*/
	public function voteAction($id, $direction) {
		$answer = $this->answers->find($id);
		
		$score = $answer->score;
		switch ($direction) {
			case 'up':
				$newScore = $score + 1;
				break;
			case 'down':
				$newScore = $score - 1;
				break;
		}
		$answer = [
			'score' => $newScore
		];
		$this->answers->save($answer);

		// get question for redirect to self.
		$sql = "SELECT slug FROM question as q JOIN answer AS a ON q.id = a.answerto WHERE a.id = ?";
		$this->db->execute($sql, [$id]);
		$question = $this->db->fetchOne();
		
		$url = $this->url->create('questions/view/'.$question->slug);
		$this->response->redirect($url);
	}
	/**
	*
	*
	* mark answer as correct.
	*
	*/
	public function correctAction($id = null) {
		if(is_null($id)) exit('Du har inte angett en fråga');

		$answer = $this->answers->find($id);
		// see who owns question.. 
		$sql = "SELECT userid, slug FROM question WHERE id = ?";
		$this->db->execute($sql, [$answer->answerto]);
		$question = $this->db->fetchOne(); // always only one row?
		if(isset($this->user) && $answer->correct == 0 && $question->userid == $this->user['id'] && $answer->userid != $this->user['id']) { // question is not yet chosen as correct and it is your own question.. 
			$sql = "UPDATE answer SET correct = ? WHERE id = ?"; // sätt ens användarnamn på frågan vars id är denna fråga.
			// Svårt. Frågan ska alltså inte vara ens egen, inte svaret. 
			$this->db->execute($sql, [$question->userid, $id]);
			$sql = "UPDATE user SET score = score + 4 WHERE id = ?";
			$this->db->execute($sql, [$answer->userid]);
			// en redirect tillabak till frågan nu då. 
			$this->response->redirect($this->url->create('questions/view/'.$question->slug));
		} else {
			exit('Frågan är redan markerad som korrekt eller så har du inte behörighet att markera den som korrekt.');
		}
	}
}