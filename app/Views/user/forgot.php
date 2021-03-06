<?php $this->layout('layout', ['title' => 'Forgot your password', 'currentPage' => 'forgot']) ?>

<?php $this->start('main_content') ?>

<!-- Form with input field to get user's email to send them a reset password email -->

    <div class="row">
    	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2  col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
			<form action="" method="post">
				<div class="form-group">
					<input type="email" class="form-control" name="email" value="" placeholder="Enter your email address" />
				</div>

				<div class="form-group">
					<input type="submit" class="btn btn-success btn-block" value="Click to send a reset email" />
				</div>
			</form>
		</div>
	</div>

<?php $this->stop('main_content') ?>
