<h3>Senaste aktivitet</h3>
<div class='activity small'>
<ul class='activities-list'>
<?php if(!empty($activities)) : ?>
<?php foreach($activities as $activity) : ?> 
	<li><span class='grey'><?=$activity->acronym?></span><?php if(is_numeric($activity->action)) echo ' kommenterade på '; elseif(is_string($activity->action)) echo $activity->action; else echo ' svarade på frågan '?><a href='<?=$this->url->create('questions/view/'.$activity->slug)?>'><?=$activity->title?></a>, <?=$activity->created?></li>
<?php endforeach ?>
<?php else : ?> 
	<li>Inga aktiviteter hittades.</li>
<?php endif ?>
</ul>
</div>

