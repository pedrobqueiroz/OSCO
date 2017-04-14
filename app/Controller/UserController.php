<?php

namespace Controller;

use \W\Controller\Controller;

class UserController extends Controller {

	/** ***********************************************************************
	 * Signup
	 *
	 *********************************************************************** */
	public function signup() {
		// This empties the alerts that are stored in Session
		//unset($_SESSION['flash']);

		// First, we check to see if $_POST is filled
		if (!empty($_POST)) {

			print_r($_POST);

			// We need to recover the user data from the input fields (email and two passwords)
			$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
			$passwordOne = isset($_POST['passwordOne']) ? trim(strip_tags($_POST['passwordOne'])) : '';
			$passwordTwo = isset($_POST['passwordTwo']) ? trim(strip_tags($_POST['passwordTwo'])) : '';
			$country = isset($_POST['country']) ? trim(strip_tags($_POST['country'])) : '';

			// Now we create an "errorList" variable, which will contain potential errors during the signup process and show them to us
			$errorList = array();

			// User Data Validation (if there is an error, it will
			// be added to errorList and displayed later)
			// ==============================================
			// There needs to be a valid email address
			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$errorList[] = 'Your email address is not valid.';
			}

			// The password needs to be at least 8 characters long
			if (strlen($passwordOne) < 8) {
				$errorList[] = 'Your password needs to be at least 8 characters long.';
			}

			// Password One and Password Two need to be the same
			if ($passwordOne != $passwordTwo) {
				$errorList[] = 'Your passwords do not match.';
			}

			// "Country" needs to be filled
			if (empty($country)) {
				$errorList[] = 'Please provide a country.';
			}

			// If all data is right, do this:
			if (empty($errorList)) {
				// Now we test and see if the email address already exists by using a method that exists in UsersModel
				$userModel = new \W\Model\UsersModel();

				if ($userModel->emailExists($email)) {
					$this->flash('I\'m sorry, but this email address already exists.', 'danger');
				} else {
					// If the email doesn't already exist in the database, continue on with the encryption of the password by using the mthod "hashPassword" which already exists in Framework W
					$authentificationModel = new \W\Security\AuthentificationModel();
					$hashedPassword = $authentificationModel->hashPassword($passwordOne);

					// Then we can insert the user data in the database
					$insertUserData = $userModel->insert(array(
						'usr_email' => $email,
						'usr_password' => $hashedPassword,
						'usr_role' => 'user',
						'usr_country' => $country
					));

					// If the signup was successful, we will redirect the user to the login page
					if (!empty($insertUserData)) {
						// A success message will be displayed
						$this->flash('Congratulations, your signup was successful.', 'success');

						// Now we redirect to the login page
						$this->redirectToRoute('user_login');
					} else {
						// If there is an error, display a message:
						$this->flash('I\'m afraid your signup wasn\'t a success. Try again, please.', 'danger');
					}
				}
			} // if (empty($errorList)) end
		} else {
			$this->flash('Please fill in the form.', 'danger');
		}

		$this->show('user/signup');
	} // public function signup() end


	/** ***********************************************************************
	 * Login
	 *
	 *********************************************************************** */
	public function login() {
		unset($_SESSION['flash']);

		$this->show('user/login');
	}



	/** ***********************************************************************
	 * Login Post Page
	 *
	 *********************************************************************** */
	 public function loginPost() {

		// We first retrieve the data from POST again
		$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
		$password = isset($_POST['passwordOne']) ? trim(strip_tags($_POST['passwordOne'])) : '';

		// Creation of errorList variable
		$errorList = array();

		// User Data Validation
		// =========================================================
		// Is email correct?
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$errorList[] = "Your email address is not valid.";
		}

		// Is password at least 8 characters?
		if (strlen($password) < 8) {
		 	$errorList[] = "Your password needs to be at least 8 characters long.";
		}

		// If everything is OK
		if (empty($errorList)) {
			 // We have to verify the user/password in the database
			if ($userId > 0) {
				$authentificationModel = new \W\Model\UsersModel();
				$userInfos = $userModel->find($userId);

				// Then we add the user to the session
				$authentificationModel->logUserIn($userInfos);

				// Then we display a success message
				$this->flash($userInfos['usr_email'] . " was logged in successfully", 'success');

				// Then the user is redirected to the home page
				$this->redirectToRoute('content_home');
			} else {
				 $this->flash('Your email or password are incorrect.', 'danger');
		 	}
		} else {
			 $this->flash(join('<br>', $errorList), 'danger');
	 	}

		$this->show('user/login');
	}



	 /** ***********************************************************************
 	 * Forgot Password
 	 *
 	 ************************************************************************ */
	 public function forgot() {

		 // When the form has been sent
		 if (!empty($_POST)) {

			 // We need to retrieve the information in POST
			 $email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';

			 // Now we again create the familiar errorList variable containing an empty array
			 $errorList = array();

			 // User Data Validation
			 // ===================================================
			 if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				 $errorList[] = 'Your email address is incorrect.';
			 }

			 // If all the given data is correct, do the following
			 if (empty($errorList)) {
				 // We first instantiate a new model
				 $model = new \W\Model\UsersModel();

				 // Then we call the method which returns the user data for a given email
				 $userData = $model->getUserByUsernameOrEmail($email);

				 // If no account exists
				 if ($userData === false) {
					 $this->flash('Email not valid.', 'danger');
				 } else {
					 // Now we generate the token of 32 characters
					 $token = md5(\W\Security\StringUtils::randomString(128));

					 // Then the token is added to the database
					 $model->update(array(
						'usr_token' => $token,
						'usr_token_created' => date('Y-m-d H:i:s')), $userData['id']);

					 // Now we can create the email which will contain the link to reset the password
					 $htmlContent = 'You have requested to reset your password. Please follow the link below to change your password: <a href="' . $this->generateUrl('user_reset', array('token' => $token), true) . '">' . $this->generateUrl('user_reset', array('token' => $token), true) . '</a>';

					 // After this, we can send the email
					 $isSent = \Helper\utils::sendEmail($userData['usr_email'], 'Change your password', nl2br($htmlContent));

					 if ($isSent) {
						 $this->flash('An email with a link to reset your password has been sent.', 'success');
					 } else {
						 $this->flash('Unfortunately, an error occurred while sending the email.', 'danger');
					 }
				 }
			 } else {
				 $this->flash(join('<br>', $errorList), 'danger');
			 }
		 } // if (!empty($_POST)) end
	 } // public function forgot end


	 /** ***********************************************************************
 	 * Reset Password with token
 	 *
 	 ************************************************************************ */
	 public function reset($token) {

		 // First off, we will have to instantiate the model we created
		 $model = new \Model\UserModel();
		 $userId = $model->getIdByToken($token);

		 // This variable will be used in "reset.php" to display a form or not
		 $displayForm = false;

		 // If the token does not exist or has expired, do this:
		 if ($userId === false) {
			 $this->flash('Your token is invalid.', 'danger');
		 } else {
			 $displayForm = true;
		 }

		 // Now let's look at the sent form
		 if (!empty($_POST)) {
			 // Again, we will have to retrieve the data
			 $passwordOne = isset($_POST['passwordOne']) ? trim($_POST['passwordOne']) : '';
			 $passwordTwo = isset($_POST['passwordTwo']) ? trim($_POST['passwordTwo']) : '';

			 // We create our beloved errorList variable to test for errors
			 $errorList = array();

			 // User Data Validation
			 // ===================================================

			 // Password needs to be at least 8 characters long
			 if (strlen($passwordOne) < 8) {
				 $errorList[] = 'Your password has to be at least 8 characters long.';
			 }

			 // Passwords must match
			 if ($passwordOne != $passwordTwo) {
				 $errorList[] = 'Your passwords do not match.';
			 }

			 // If all data is OK, do this:
			 if (empty($errorList)) {

				 // We have to hash the new password one more time
				 $authentificationModel = new \W\Security\AuthentificationModel();
				 $hashedPassword = $authentificationModel->hashPassword($passwordOne);

				 // Once the password has been hashed, we can update it in the database
				 $model->update(array(
					 'usr_password' => $hashedPassword,
					 'usr_token' => '',
					 'usr_token_created' => ''
				 ), $userId);

				 // When all this has been successful, display a success message
				 $this->flash('Your password has been changed.', 'success');

				 // Then we redirect the person back to the home page
				 $this->redirectToRoute('content_home');
			 } else {
				 // If the errorList array is not empty, display the error(s)
				 $this->flash(join('<br>', $errorList), 'danger');
			 }
		 } // if (!empty($_POST)) end

		 $this->show('user/reset', array(
			 'displayForm' => $displayForm
		 ));
	 }



	 /** ***********************************************************************
 	 * Logout
	 *
 	 ************************************************************************ */
	 public function logout() {

		 /* In order to log the user out, we need to delete his session by
		 using a method that exists in the AuthentificationModel */
		 $authentificationModel = new \W\Security\AuthentificationModel();
		 $authentificationModel->logUserOut();

		 // Then we redirect the user back to home
		 $this->redirectToRoute('content_home');
	 }



	 /** ***********************************************************************
 	 * Profile
	 *
 	 ************************************************************************ */
	 public function profile() {

		 $this->show('user/profile');
	 }
}
