<div class='question'>
	<table class='question-table'>
		<tr>
			<td colspan=2><p class='small'>Fråga: <span class='grey'><?=$question->title?></span></p></td>
		</tr>
		<tr>
			<td class='question-vote-area' rowspan=3>
				<p class='large'>
					<a href="<?=$this->url->create('questions/vote/'.$question->id.'/up')?>" class='thumb-up'><i class="fa fa-thumbs-up"></i></a>
					<?=$question->score?>
					<a href="<?=$this->url->create('questions/vote/'.$question->id.'/down')?>" class='thumb-down'><i class="fa fa-thumbs-down"></i></a>
				</p>
			</td>
			<td class='question-content'><p><?=$this->textFilter->doFilter($question->text, 'shortcode, markdown')?></p></td>
		</tr>
		<tr>
			<td>
				<div class='question-tags small'>
					<?=$question->getTags()?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<p class='question-content right small grey'>Frågat av <a href="<?=$this->url->create('users/profile/'.$question->acronym)?>"><?=$question->acronym?></a>
				den <?=$question->created?>
				<?php if(isset($question->updated)) echo '<br>(uppdaterad ' . $question->updated . ')'?>
				</p>
			</td>
		</tr>
	</table>
</div>