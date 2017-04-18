<?php $this->layout('layout', ['title' => 'Search results', 'currentPage' => 'search']) ?>

<?php $this->start('main_content') ?>
	<section>
	<div>
	<?php if ($searchInput != '') : ?>
		<?= $nbResults ?> Result(s) for "<?= $searchInput ?>"
	<?php else : ?>
		<div class="pagination">
		<?php if ($page >= 2){
		?>
			<a href="?searchInput=<?= $searchInput ?>&page=<?= $page-1 ?>&order=DESC" name="page" class="btn btn-xs btn-success">précédant</a>
		<?php
		}
			
		if ($nbResults >= 4){
		?>
			<a href="?searchInput=<?= $searchInput ?>&page=<?= $page+1 ?>&order=DESC" name="page" class="btn btn-xs btn-success">suivant</a>
		<?php
		}
		?>
		</div>
  	<?php endif; ?>
	</div>

	<div class="container">
		<div class="filterBox">
			Sort by 
			<a href="?searchInput=<?= $searchInput ?>&order=DESC">
				<button name="order">Recent</button>
			</a>
			<a href="?searchInput=<?= $searchInput ?>&order=ASC">
				<button name="order">Oldest</button>
			</a>
		</div>
	<?php foreach ($searchResults as $currentResult) : ?>
		<div >
			<p><?= $currentResult['usr_username']; ?></p>
			<p><?= $currentResult['sto_title']; ?></p>
			<p><?= $currentResult['sto_inserted']; ?></p>
			<p><?= \Controller\ContentController::getShortDescription($currentResult['sto_content']); ?></p>
			<p><?= $currentResult['sto_tags']; ?></p>
		</div>
	<?php endforeach; ?>
	</div>

	</section>
<?php $this->stop('main_content') ?>