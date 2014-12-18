<h1><?=$title?></h1>
<p>Här visas alla frågor.</p>
	<?php foreach($questions as $question) : ?>
		<div class='question-container' id='<?=$question->id?>'>
			<div class='vote-area'>
				<div class='vote-badge'>
					<span class='question-score'><span class='large bold'><?=$question->score?></span> Score</span><br>
					<span class='question-answers'><span class='bold'><?=$question->c?></span> Svar</span>
				</div>
			</div>
			<div class='question-content'>
				<div class='question-summary'>
					<h3 class='top'><a href='<?=$this->url->create('questions/view/'.$question->slug)?>'><?=$question->title?></a></h3>
				</div>
				<div class='question-tags small'>
					<?=\Anax\Questions\Question::getTagsStatic($question->tags, $this->url->create('questions/tag'))?>
				</div>
				<div class='question-user'>
					<span class='grey small'>Frågad av <a href='<?=$this->url->create('users/profile/'.$question->acronym)?>'><?=$question->acronym?></a> den <?=$question->created?></span>
				</div>
			</div>
		</div>
	<?php endforeach ?>