<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "pweb");

if (isset($_COOKIE['no']) && isset($_COOKIE['name'])) {
	$id = $_COOKIE['no'];
	$key = $_COOKIE['name'];

	$result = mysqli_query($conn, "SELECT username FROM akun WHERE id = $id");
	$row = mysqli_fetch_assoc($result);

	if ($key === hash('sha256', $row['username'])) {
		$_SESSION['login'] = true;
	}
}


if (isset($_SESSION["login"])) {
	header("Location: index.php");
	exit;
}

if(isset($_POST["login"])){
	$username = $_POST["username"];
	$password = $_POST["password"];
	$result = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");

	if (mysqli_num_rows($result) === 1) {
		$row = mysqli_fetch_assoc($result);
		if ($password == $row["password"]) {

			$_SESSION["login"] = true;

			if (isset($_POST['remember'])) {

				setcookie('no', $row['id'], time()+60);

				setcookie('name', hash('sha256', $row['username']), time()+60);
			}

			header("Location: index.php");
			exit;
		}
	}

	$error = true;

}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">LOGIN</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
						<div class="icon d-flex align-items-center justify-content-center">
							<span class="fa fa-user-o"></span>
						</div>
						<h3 class="text-center mb-4">Sign In</h3>
						<?php if(isset($error)) : ?>
							<p>Incorrect username or password!</p>
						<?php endif; ?>
						<form action="" class="login-form" method="post">
							<div class="form-group">
								<input type="text" class="form-control rounded-left" name="username" placeholder="Username" required>
							</div>
							<div class="form-group d-flex">
								<input type="password" class="form-control rounded-left" name="password" placeholder="Password" required>
							</div>
 							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary rounded submit px-3" name="login">Login</button>
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50">
									<label class="checkbox-wrap checkbox-primary">Remember Me
										<input type="checkbox" name="remember" checked>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

