<?php $this->layout('layout', ['title' => 'Home', 'currentPage' => 'home']) ?>

<?php $this->start('main_content') ?>
	<!--Here starts the "Need Help" box -->
<div>
	
	<section id="rightColumn">
		<a href="<?= $this->url("content_needhelp")?>">		
			<div id="needHelpBox">
				<h1>Need Help?</h1>
				<p>OSCo has the contact information you need to get help. If you prefer, we also provide a form for you to get anonymous help-we handle the contact for you.
				<br>You don't need to register to get help. Click this box, or the icon "Need Help" in the nav bar from any page, and get the support you need.</p>
			</div>
		</a>
	</section>

	<a href=""><span id="refreshStories" class="glyphicon glyphicon-refresh" aria-hidden="true"><br> Refresh</span></a>

	<section id="ajaxHomeStories">
	<?php foreach($randomStories as $story) : ?>
		<article id="storyBox">
			<a href="<?= $this->url('content_story',['id' => $story['sto_id']]); ?>">
				<div>
					<h1 id="title"><?= \Controller\ContentController::getShortTitle($story['sto_title']) ?></h1>
					<p><?= \Controller\ContentController::getShortDescription($story['sto_content'])?></p>
				</div>
			</a>
		</article>
	<?php endforeach; ?>
	</section>
</div>	

<?php $this->stop('main_content') ?>
