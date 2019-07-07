<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$sc = new SiteController();
$sc->route($action);

class SiteController {

	// route to the appropriate class method for this action
	public function route($action) {
		switch($action) {

			case 'home':
				$this->home();
				break;

			case 'map':
				$this->map();
				break;

			case 'login':
				$this->login();
				break;

			case 'camps':
				$this->showCamps();
				break;

			case 'showCamps':
				$this->buildTree();
				break;

			case 'addCamp':
				$this->addCamp();
				break;

			case 'prisoners':
				$this->showPrisoners();
				break;

			case 'addPris':
				$this->addPrisoner();
				break;

			case 'loginProcess':
				$username = $_POST['username'];
				$password = $_POST['password'];
				if ($username == "") { // If user didn't properly access login page, redirect
					header('Location: '.BASE_URL); exit();
				}
				$this->loginProcess($username, $password);
				break;

			case 'signup':
				$this->signup();
				break;

			case 'signupProcess':
				$this->signupProcess();
				break;

			case 'deleteComment':
				$id = $_GET['id'];
				$this->deleteComment($id);
				break;

			case 'logout':
				$this->logout();
				break;

			case '404':
				$this->error404();
				break;

			case 'aboutus':
				$this->aboutus();
				break;

			case 'activities':
				$this->activities();
				break;

			case 'interactions':
				$this-> interactions();
				break;

			case 'remnants':
				$this-> remnants();
				break;

			case 'resources':
				$this-> resources();
				break;

			case 'contact':
				$this-> contact();
				break;

			case 'search':
				$this->search();
				break;
		}
	}
	//redirect to the homepage.
	public function home(){
		$followeeActivities;
		if(isset($_SESSION['username'])){
			$followeeActivities = ActivityFeed::loadUserAndFolloweeFeeds($_SESSION['username'], 0, 10);
		}
		$pageTitle = "home";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/index.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//redirect to the map page
	public function map(){
		$pageTitle = "map";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/map.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//redirect to the list view for all the camps in the table
	public function showCamps(){
		$pageTitle = "camps";
		include_once SYSTEM_PATH.'/view/header.tpl';
		//load all camps to display
		$campArray = Camp::loadAll();
		include_once SYSTEM_PATH.'/view/camps.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//redirect to the list view for all existing prisoners in the table
	public function showPrisoners(){
		$pageTitle = "prisoners";
		include_once SYSTEM_PATH.'/view/header.tpl';
		//load all prisoners to display
		$prisArray = Prisoner::loadAll();
		include_once SYSTEM_PATH.'/view/prisoners.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//Redirect to the page to add a camp
	public function addCamp() {
		// If user is not logged in, redirect to home
		if (!isset($_SESSION['username']) || $_SESSION['username'] == null) {
			header('Location: '.BASE_URL); exit();
		}
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addCamp.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//redirect to a page to add a prisoner
	public function addPrisoner() {
		// If user is not logged in, redirect to home
		if (!isset($_SESSION['username']) || $_SESSION['username'] == null) {
			header('Location: '.BASE_URL); exit();
		}
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addPrisoner.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//Checks credentials of user data when logging in
    public function loginProcess($username, $password){
		//attempt to load the username from the database
		$user = User::load($username);
		// The database threw an error
		if (is_string($user)) {
			$_SESSION['loginError'] = $user;
			header('Location: '.BASE_URL.'/login'); exit();
		}

		if ($user->rank == 1){
			$_SESSION['loginError'] = 'You have been banned for misconduct. If you believe this ban is in error, contact us at xxxxx@vt.edu.';
			header('Location: '.BASE_URL.'/login'); exit();
		}
		//if the entered password matches the stored password, start a session
		if(($user->pw) == md5($password)){
			$_SESSION['username'] = $username;
			$_SESSION['rank'] = $user->rank;
		}
		//the password is incorrect
		else {
			$_SESSION['loginError'] = 'The password you entered is incorrect.';
			header('Location: '.BASE_URL.'/login'); exit();
		}
		//redirect to home upon success
		header('Location: '.BASE_URL); exit();
    }
		//redirect to the signup page
    public function signup(){
		$pageTitle = "signup";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/signup.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
    }
		//redirect to the login page
	public function login(){
		$pageTitle = "login";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/login.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//allows an unregistered user to create an account
	public function signupProcess(){
		$u = $_POST['username'];
		$p = $_POST['password'];
		$c = $_POST['confirm'];
		$f = $_POST['first'];
		$l = $_POST['last'];
		$e = $_POST['email'];
		$d = $_POST['date'];
		$g = $_POST['gender'];
		//If the username is empty, redirect to home
		if ($u == "") {
			header('Location: '.BASE_URL); exit();
		}
		//If the password and confirm fields don't match, refresh the signup page
		if($p != $c){ // If user didn't properly access signup page, redirect
			$_SESSION['signupError'] = 'Passwords do not match';
			header('Location: '.BASE_URL.'/signup'); exit();
		}
		//create a new user object and insert it into the database
		$newuser = new User(
			$u,
			$p,
			$f,
			$l,
			$e,
			$d,
			$g,
			-1,
			-1,
			-1,
			-1,
			2,
			null,
			BASE_URL."/public/img/profile-img.jpg"
		);

		$success = $newuser->insert();

		// Record a feed event for account creation, but we must also check for success here as below
		if (is_numeric($success)) {
			$activityFeed = new ActivityFeed(
				0,
				$u,
				null,
				$u,
				null,
				'add_user',
				null
			);
			$activityFeedSuccess = $activityFeed->save();

			if (is_string($activityFeedSuccess)) {
				$_SESSION['signupError'] = $activityFeedSuccess;
				header('Location: '.BASE_URL.'/signup'); exit();
			}
		}

		//If successful, start a session, else refresh the signup page
		if (!is_string($success)){
			$_SESSION['username'] = $u;
			$_SESSION['rank'] = $user->rank;
			header('Location:'.BASE_URL); exit();
		} else {
			$_SESSION['signupError'] = $success;
			header('Location: '.BASE_URL.'/signup'); exit();
		}
	}

	public function deleteComment($id){
		$comment = Comment::load($id);
		$success = $comment->delete();
		header("Location: " . $_SERVER["HTTP_REFERER"]); exit();
	}

//end session and go to homepage
	public function logout() {
		unset($_SESSION['username']);
		session_destroy();
		header('Location: '.BASE_URL); exit();
	}

	// Redirect user to custom 404 page
	public function error404() {
		$pageTitle = "404 Error";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/404page.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to About Us page
	public function aboutus() {
		$pageTitle = "About";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/aboutus.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to Activities page
	public function activities() {
		$pageTitle = "Activities";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/activities.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to Interactions with Americans page
	public function interactions() {
		$pageTitle = "Interactions with Americans";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/interactions.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to Remnants of Nazism page
	public function remnants() {
		$pageTitle = "Remnants of Nazism";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/remnants.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to Resources page
	public function resources() {
		$pageTitle = "Resources";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/resources.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	// Redirect user to Contact Us page
	public function contact() {
		$pageTitle = "Contact";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/contact.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}


	public function buildTree() {
		$campArray = Camp::loadAll();
		$masterArray = array();
		$masterArray['name']='Camps';
		foreach($campArray as $camp){
			$actArray = Workload::loadActivitiesFromCamp($camp->id);
			if ($actArray !== NO_RESULTS){
				foreach($actArray as $act){
					$act->name = $act->type;
				}
				$camp->children = $actArray;
			}
		}
		$masterArray['children'] = $campArray;
		echo json_encode($masterArray);
	}
	// Process a search query
	public function search() {
		if (isset($_GET['terms'])) $terms = $_GET['terms'];
		else $terms = null;

		$userArray = Search::searchUsers($terms);
		$campArray = Search::searchCamps($terms);
		$prisArray = Search::searchPrisoners($terms);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/search.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';

	}
}
