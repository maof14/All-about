<div class='order-controls'>
<div class='order-controls-links small'>
Sortera: 
<a href='<?=$this->url->create($this->request->clearGet().'?sort=created')?>'>Datum</a>
<a href='<?=$this->url->create($this->request->clearGet().'?sort=score')?>'>Score</a>
</div>
</div>
<?php foreach($answers as $answer) : ?>
<div class='answer<?if(!empty($answer->iscommentto)) echo ' indent'?>' id='answer-<?=$answer->id?>'>
	<table class='answer-table'>
		<tr>
			<?php if(empty($answer->iscommentto)) : ?>
			<td rowspan=2 class='answer-vote-area'>
				<p>
					<a href="<?=$this->url->create('answers/vote/'.$answer->id.'/up')?>" class='thumb-up'><i class="fa fa-thumbs-up"></i></a>
					<?=$answer->score?>
					<a href="<?=$this->url->create('answers/vote/'.$answer->id.'/down')?>" class='thumb-down'><i class="fa fa-thumbs-down"></i></a>
					<?php if(!empty($correctAns) && $answer->id == $correctAns) : ?>
						<i class="fa fa-check fa-3x"></i>
					<?php endif ?>
					<?php if($myQuestion && empty($correctAns)) : ?>
					<br><a href='<?=$this->url->create('answers/correct/'.$answer->id)?>' class='center'><i class="fa fa-check"></i></a>
					<?php endif ?>
				</p>
			
			</td>
			<?php endif ?>
			<td class='answer-content'><div class='small markdown-answer-content'><?=$this->textFilter->doFilter($answer->text, 'shortcode, markdown')?></div></td>
		</tr>
		<tr>
			<td colspan=2>
				<p class='small right grey' style='margin-bottom: 0;'><?php if(empty($answer->iscommentto)) echo 'Svarat'; else echo 'Kommenterat'?> av <a href="<?=$this->url->create('users/profile/'.$answer->acronym)?>"><?=$answer->acronym?></a>
				den <?=$answer->created?></p>
		</td>
		</tr>
		<?php if($isLoggedIn) : ?>
		<tr><td colspan=2><?=$this->cf->getCreateCommentForm($answer->id)?></td></tr>
		<?php endif ?>
	</table>
</div>
<?php endforeach; ?> 