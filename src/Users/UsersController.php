<?php 

namespace Anax\Users;

/**
 * A Controller for users and admin related events.
 *
 */

class UsersController implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 * (Automatically called..)
	 */
	public function initialize() {
		$this->users = new \Anax\Users\User(); // create user model
		$this->users->setDI($this->di);
		if($this->session->has('user')) {
	    	$this->loggedInUser = $this->session->get('user');
	    }

	}
	/**
	*
	* user index - list of users
	*
	*
	*/
	public function indexAction() {
		$this->theme->setTitle('Användare');
		$this->dispatcher->forward([
				'controller' => 'users',
				'action' => 'list',
			]);
	}
	/**
	*
	* create new user.
	*
	*
	*/
	public function createAction() {
		// jo men det funkar ju hittills iaf. 
		$this->theme->setTitle('Skapa konto');
		$form = $this->cf->getCreateForm();
		$this->views->add('project/page', [
			'title' => 'Skapa användare',
			'content' => $form
		]);
		// kopierat från nedan
		
		$isPosted = $this->request->getPost('submit');
		$now = date(DATE_RFC2822);
		if($isPosted && ($this->request->getPost('password') == $this->request->getPost('password-repeat'))) {
			$user = [
					'acronym' => $this->request->getPost('acronym'), // "användare" i formen
					'email' => $this->request->getPost('email'),
					'name' => $this->request->getPost('name'), 
					'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), 
					'created' => $now,
					'score' => 0  
				];
			$this->users->create($user);
			$url = $this->url->create('users/profile/' . $this->users->id);
			$this->response->redirect($url);
		}
	}
	/**
	*
	* Login a user
	*
	*
	*/
	public function loginAction() {
		$this->theme->setTitle('Logga in');
		$form = $this->cf->getLoginForm();
		$this->views->add('project/page', [
			'title' => 'Logga in',
			'content' => $form
		]);
		// om man har loggar in
		$isPosted = $this->request->getPost('submit');

		if($isPosted){
			$this->users->login($this->request->getPost('acronym'), $this->request->getPost('password'));
			$this->response->redirect($this->url->create(''));
		} 
	}
	/**
	*
	* logout the user
	*
	*
	*/
	public function logoutAction() {
		$this->users->logout();
		$this->response->redirect($this->url->create(''));
	}
	/**
	*
	*
	* Lis all users - to be called by dispatcher in index.
	*
	*/
	public function listAction() {
		$all = $this->users->findAll();
		$this->views->add('users/list-all', [
				'users' => $all, 
				'title' => "Visa alla användare"
			]);
	}

	/**
	 * List user with id.££
	 *
	 * @param int $id of user to display
	 * What if no id specified or user don't exist? Error appears. 
	 *
	 * @return void
	 */
	public function profileAction($id = null)
	{
		if(is_null($id)) exit('Du har inte angett en användare!');

	    $user = $this->users->findUser($id);

	    $this->theme->setTitle($user->acronym.'\'s profil');
	    $this->views->add('users/view', [
	        'user' => $user,
	    ]);

	    $this->dispatcher->forward([
	    		'controller' => 'users',
	    		'action' => 'activity',
	    		'params' => [
	    			'id' => $user->id
	    		]
	    	]);

	    	if(isset($this->loggedInUser) && $this->loggedInUser['id'] == $user->id) {
	    		$this->views->add('project/controls', [
					'controller' => 'users',
					'text' => 'profilen',
					'id' => $user->id
				]);
	    }
	}
	/**
	*
	* get the users actitity. To be called by dispatched on profileaction
	*
	*
	*/
	public function activityAction($id = null) {
		$activities = $this->users->getActivity($id);
		$this->views->add('project/activities', [
				'activities' => $activities
			]);
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

	/** 
	* @param integer $id of user to "hard delete".
	*
	* @return void.
	* 
	*/
	public function deleteAction($id = null) {
		if(is_null($id)) exit('Du har inte specificerat en användare!');
		$user = $this->users->find($id);

		if(isset($this->loggedInUser) && ($this->loggedInUser['id'] == $user->id)) {
			$user->delete($id);
			$this->session->clear('user'); // clear the user from the session.. 
			// redirect to users/list - user is gone!
			$url = $this->url->create('');
			$this->response->redirect($url);
		} else {
			exit('Du får inte ta bort den här användaren!');
		}
	}


	/**
	*
	*
	* Edit the user
	*
	*/

	public function editAction($id = null) {

		$this->theme->setTitle('Ändra användare');
		$user = $this->users->find($id);
		if($this->loggedInUser['id'] != $user->id) exit('Du har inte behörighet att ändra den här profilen.');
		$form = $this->cf->getEditAccountForm($user);
		$this->views->add('project/article', [
				'content' => $form
			]);

		$isPosted = $this->request->getPost('submit');

		if($isPosted) {
			if($this->request->getPost('password') == $this->request->getPost('password-repeat')) {
				$this->users->save([
					'email' => $this->request->getPost('email'),
					'name' => $this->request->getPost('name'),
					'text' => $this->request->getPost('text')
				]);	
			$url = $this->url->create('users/profile/' . $this->users->acronym);
			$this->response->redirect($url);
			} else {
				exit('Lösenorden stämde inte! Pröva igen.');
			}
		}
	}
}