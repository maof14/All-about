<div id='front-questions-container'>
<h2>Senaste frågorna</h2>
<div id='front-questions'>
	<?php foreach($questions as $question) : ?> 
	<div class='front-question small left'>
		<a href='<?=$this->url->create('questions/view/'.$question->slug)?>'><?=$question->title?></a>
		av <span class='grey'><a href="<?=$this->url->create('users/profile/'.$question->acronym)?>"><?=$question->acronym?></a></span>
		<?=\Anax\Questions\Question::getTagsStatic($question->tags, $this->url->create('questions/tag'))?>
	</div>
	<?php endforeach ?> 
</div>
</div>