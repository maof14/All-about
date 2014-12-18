<?php 

namespace Anax\Tags;

class TagsController implements \Anax\DI\IInjectionAware {
	use \Anax\DI\TInjectable;
	/**
	*
	* Controller constructor
	*
	*
	*/
	public function initialize() {
		$this->tags = new \Anax\Tags\Tag(); // create user model
		$this->tags->setDI($this->di);
	}
	/**
	*
	* Get the list of tags. 
	* To be called by dispatched on the front page. 
	*
	*/
	public function listAction() {
		$tags = $this->tags->getTags(); 
		$tags = $this->tags->stringify($tags);
		$tags = $this->tags->createArray($tags);
		$tags = $this->tags->getTagCount($tags);

		$this->views->add('project/tags', [
			'tags' => $tags
		]);
	}
}