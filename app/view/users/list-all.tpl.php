<h1>Alla användare</h1>
<p>Här kan du se alla våra användare och deras score.</p>
<?php foreach($users as $user) : ?> 
<div class='user-badge'>
	<img src='<?=\Anax\Users\User::getGravatarStatic($user->email, 80)?>' alt='gravatar' class='left'>
	<div class='badge-content'>
	<p class='top left'><a href='<?=$this->url->create('users/profile/'.$user->acronym)?>'><?=$user->acronym?></a></p> 
	<div class='score'>
	&nbsp;<span class='star'> <i class="fa fa-star"></i></span> <?=$user->score?>
	</div>
	<br><span class='small grey'>Medlem sedan <?=substr($user->created, 0, 16)?></span>
</div>
</div>
<?php endforeach ?> 