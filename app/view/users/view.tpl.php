<h1>Användare <?=$user->acronym?></h1>

<div class='profile'>
	<div class='left info-column'>
		<div class='profile-pic left'>
			<figure><img src='<?=$user->getGravatar(120)?>' alt='Profile picture'></figure>
		</div>
		<div class='score-area left'><span class='star'><i class="fa fa-star fa-3x"></i></span><p class='large top score'><?=$user->score?></p></div>
		<div class='user-info small'>
			<p>Användarnamn: <span class='grey'><?=$user->acronym?></span></p>
			<p>Namn: <span class='grey'><?=$user->name?></span></p>
			<p>Mailadress: <span class='grey'><?=$user->email?></span></p>
			<p>Profil skapad: <span class='grey'><?=$user->created?></span></p>
		</div>
	</div>
	<div class='user-presentation-text left'>
		<h3>Presentation:</h3>
		<p><?=nl2br($user->text)?></p>
	</div>
</div>