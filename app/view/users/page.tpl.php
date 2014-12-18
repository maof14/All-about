<h1><?=$title?></h1>

<?=$content?>

<p><a href='<?=$this->url->create('users/list')?>'>Tillbaka till användare</a></p>

<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
