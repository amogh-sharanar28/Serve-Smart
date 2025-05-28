<?php
// Start session at the very top — no output before this
session_start();

include('./header.php');
include('./db_connect.php');

// Redirect if already logged in
if (isset($_SESSION['login_id'])) {
    header("Location: index.php?page=home");
    exit;
}

// Load system settings into session
$query = $conn->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
foreach ($query as $key => $value) {
    if (!is_numeric($key)) {
        $_SESSION['setting_' . $key] = $value;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Admin | Serve Smart</title>
  <style>
	body {
		width: 100%;
	    height: 100%;
	}
	main#main {
		width: 100%;
		height: 100%;
		background: white;
	}
	#login-right {
		position: absolute;
		right: 0;
		width: 40%;
		height: 100%;
		background: #000;
		display: flex;
		align-items: center;
	}
	#login-left {
		position: absolute;
		left: 0;
		width: 60%;
		height: 100%;
		background: url(./../assets/img/<?php echo $_SESSION['setting_cover_img'] ?>);
		background-repeat: no-repeat;
		background-size: cover;
		background-position: center center;
	}
	#login-left:before {
		content: "";
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		backdrop-filter: brightness(.8);
		z-index: 1;
	}
	#login-left .d-flex {
		position: relative;
		z-index: 2;
	}
	#login-left h1 {
		font-family: 'Dancing Script', cursive !important;
		font-weight: bold;
		font-size: 4.5em;
		color: #fff;
		text-shadow: 0 0 5px #000;
	}
	#login-right .card {
		margin: auto;
	}
	.logo {
	    margin: auto;
	    font-size: 8rem;
	    background: white;
	    border-radius: 50%;
	    height: 29vh;
	    width: 13vw;
	    display: flex;
	    align-items: center;
	}
	.logo img {
		height: 80%;
		width: 80%;
		margin: auto;
	}
  </style>
</head>
<body>
  <main id="main" class="bg-dark">
	<div id="login-left">
		<div class="h-100 w-100 d-flex justify-content-center align-items-center">
			<h1 class="text-center"><?= $_SESSION['setting_name'] ?> - Admin Site</h1>
		</div>
	</div>
	<div id="login-right">
		<div class="card col-md-8">
			<div class="card-body">
				<form id="login-form">
					<div class="form-group">
						<label for="username" class="control-label">Username</label>
						<input type="text" id="username" name="username" autofocus class="form-control">
					</div>
					<div class="form-group">
						<label for="password" class="control-label">Password</label>
						<input type="password" id="password" name="password" class="form-control">
					</div>
					<div class="form-group">
						<a href="./../" class="text-dark">Back to Website</a>
					</div>
					<center>
						<button type="submit" class="btn-sm btn-block btn-wave col-md-4 btn-dark">Login</button>
					</center>
				</form>
			</div>
		</div>
	</div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <script>
	$('#login-form').submit(function(e) {
		e.preventDefault();
		const btn = $('#login-form button[type="submit"]');
		btn.attr('disabled', true).text('Logging in...');

		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();

		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err);
				btn.removeAttr('disabled').text('Login');
			},
			success: function(resp) {
				if (resp == 1) {
					location.href = 'index.php?page=home';
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
					btn.removeAttr('disabled').text('Login');
				}
			}
		});
	});
  </script>
</body>
</html>
