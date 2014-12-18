<table class='table-controls'>
	<tr>
		<td><a href='<?=$this->url->create($controller.'/edit/'.$id)?>'><i class="fa fa-pencil-square-o"></i></a></td>
		<td><a href='<?=$this->url->create($controller.'/delete/'.$id)?>'><i class="fa fa-times"></i></a></td>
	</tr>
	<tr class='small'>
		<td>Uppdatera <?=$text?></td>
		<td>Ta bort <?=$text?></td>
	</tr>
</table>