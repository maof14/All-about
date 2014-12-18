<?php 

namespace Anax\Questions;

class QuestionsController implements \Anax\DI\IInjectionAware {
	// denna måste man ha på controllers. Annars går det åt pipis!! 
	use \Anax\DI\TInjectable;
	/**
	*
	* Constructor for Controllers
	*
	*
	*/
	public function initialize() {
		$this->questions = new \Anax\Questions\Question(); // create question model
		$this->questions->setDI($this->di);
		if($this->session->has('user')) {
			$this->user = $this->session->get('user');
		}
	}

	/**
	*
	* Start page for controller
	* Picks up all questions. 
	*
	*/
	public function indexAction() {
		$this->theme->setTitle('Frågor');

		$questions = $this->questions->findAllQuestions();
		$this->views->add('project/questions-index', [
			'title' => 'Senaste frågor',
			'questions' => $questions
		]);
	}

	/**
	*
	* Helper method to be used on the front page of the site
	*
	*
	*/
	public function frontpageAction() {
		$questions = $this->questions->findLastQuestions();
		$this->views->add('project/lastquestions', [
				'questions' => $questions
			]);
	}

	/**
	*
	* Create a new method.
	*
	*
	*/
	public function newAction() {
		if(!isset($this->user)) exit('Du måste logga in för att skapa en fråga!');
		$this->theme->setTitle('Skapa ny fråga');
		$form = $this->cf->getCreateQuestionForm();
		$this->views->add('project/page', [
				'title' => 'Skapa ny fråga',
				'content' => $form
			]);

		$isPosted = $this->request->getPost('submit');

		if($isPosted) {
			$user = $this->session->get('user');
			$question = [
					// det gick inte att lagra en user i session nej.. :) De koul. 
					'userid' => $user['id'], // userid from session
					'title' => trim($this->request->getPost('title')),
					'slug' => $this->questions->slugify($this->request->getPost('title')),
					'text' => $this->request->getPost('text'),
					'tags' => $this->questions->setTags($this->request->getPost('tags')),
					'created' => date(DATE_RFC2822),
					'score' => 0
				];
			$this->questions->save($question);

			// create question = 1pts.
			$sql = "UPDATE user SET score = score + 1 WHERE id = ?";
			$this->db->execute($sql, [$user['id']]);
			// rediredt till lista  med frågor
			$this->response->redirect($this->url->create('questions'));
		}
	}
	/**
	*
	* View a question. 
	*
	*
	*/
	public function viewAction($id = null) {
		if(is_null($id)) exit('Du angav ingen fråga!');
		$question = $this->questions->findQuestion($id);
		$this->theme->setTitle($question->title);
		$this->views->add('project/question', [
				'question' => $question
			]);
			if(isset($this->user) && $this->user['id'] == $question->userid) {
				$this->views->add('project/controls', [
						'controller' => 'questions',
						'text' => 'frågan',
						'id' => $question->id
					]);
			} 
		$myQuestion = isset($this->user) && $this->user['id'] == $question->userid ? true : false;
		$sql = "SELECT * FROM answer WHERE answerto = ? AND correct > 0";
		$correctAns = $this->db->fetchOne($this->db->execute($sql, [$question->id]));
		$correctAns = !empty($correctAns) ? $correctAns->id : false;

		$this->dispatcher->forward([
			'controller' => 'answers',
			'action' => 'list',
			'params' => [
				'questionId' => $question->id,
				'myQuestion' => $myQuestion,
				'correctAns' => $correctAns
			]
		]);
		// comment field if user is logged in. 
		if($this->session->has('user')) {
			$this->dispatcher->forward([
				'controller' => 'answers',
				'action' => 'new',
				'params' => [
					'questionId' => $question->id
				]
			]);
		}
	}
	/**
	*
	* Edit a question
	*
	*
	*/
	public function editAction($id = null) {
		// felmeddelande
		if(is_null($id)) exit('Du angav ingen fråga!');	
		$question = $this->questions->findQuestion($id);
		$this->theme->setTitle('Ändra fråga');
		$form = $this->cf->getEditQuestionForm($question);

		$this->views->add('project/page', [
				'title' => 'Ändra fråga',
				'content' => $form
 			]);
		$isPosted = $this->request->getPost('submit');

		if($isPosted) {
			$question = [
					'title' => trim($this->request->getPost('title')),
					'slug' => $slug = $this->questions->slugify($this->request->getPost('title')),
					'text' => $this->request->getPost('text'),
					'tags' => $this->questions->setTags($this->request->getPost('tags')),
					'updated' => date(DATE_RFC2822),
			];
			$this->questions->save($question, ['acronym']);
			$this->response->redirect($this->url->create('questions/view/'.$slug));
		}
	}
	/**
	*
	* Delete question. Note that answers will not be deleted.
	*
	*
	*/
	public function deleteAction($id = null) {
		$question = $this->questions->find($id);
		if(isset($this->user) && $this->user['id'] == $question->userid) {
			$question->delete($id);
			$this->response->redirect($this->url->create('questions'));
		} else {
			exit('Du har inte behörighet att ta bort den här frågan');
		} 

	}
	/**
	*
	* Tag "search" action
	*
	*
	*/
	public function tagAction($tag = null) {
		$tag = urldecode($tag);
		$sql = "SELECT q.*, u.acronym, (
SELECT COUNT(*) FROM answer AS a
WHERE a.answerto = q.id
) AS c
FROM question AS q
JOIN user AS u ON q.userid = u.id
WHERE tags LIKE ?
ORDER BY q.created DESC";

		$this->db->execute($sql,['%'.$tag.'%']);
		$questions = $this->db->fetchAll();
		$this->theme->setTitle('Taggsökning: ' . $tag);
		$this->views->add('project/questions-search', [
				'title' => 'Taggsökning',
				'tag' => $tag,
				'questions' => $questions
			]);
	}

	/**
	*
	* Vote on questions. To be handled by dispatcher
	* id = id of question, direction = up- or downvote
	*
	*/
	public function voteAction($id, $direction) {
		// vote
		$question = $this->questions->find($id);
		$url = $this->url->create('questions/view/'.$question->slug);
		$score = $question->score;
		switch ($direction) {
			case 'up':
				$newScore = $score + 1;
				break;
			case 'down':
				$newScore = $score - 1;
				break;
		}
		$question = [
			'score' => $newScore
		];
		$this->questions->save($question);
		$this->response->redirect($url);
	}
	/**
	 * Find and return specific.
	 *
	 * @return this
	 */
	public function find($id)
	{
	    $this->db->select()
	             ->from($this->getSource())
	             ->where("id = ?");
	 
	    $this->db->execute([$id]);
	    return $this->db->fetchInto($this);
	}

}