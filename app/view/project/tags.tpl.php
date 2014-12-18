<div id='tags-container'>
	<h2>Populära taggar</h2>
<div class='tags small'>
	<?php foreach($tags as $tag => $val) : ?> 
		<a href='<?=$this->url->create('questions/tag/'.$tag)?>'><?=$tag?> (<?=$val?>)</a>
	<?php endforeach ?> 
</div>
</div>