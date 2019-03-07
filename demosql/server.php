<?php 
	session_start();

	// variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'registration');

	// REGISTER USER
	if (isset($_POST['reg_user'])) {
		// receive all input values from the form
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = ($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO users (username, email, password) 
					  VALUES('$username', '$email', '$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}

	}

	// ... 

	// LOGIN USER
	if (isset($_POST['login_user'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = ($password);
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
				header('location: index.html');
			}



			else {
				
				array_push($errors, "Wrong username/password combination");
			}
		}

	}




?>



<!--PHP program for Naive Pattern 
// Searching algorithm 
-->

<!--<?php 


function search($pat, $txt) 
{ 
	$M = strlen($pat); 
	$N = strlen($txt); 

	
	for ($i = 0; $i <= $N - $M; $i++) 
	{ 

	
		for ($j = 0; $j < $M; $j++) 
			if ($txt[$i + $j] != $pat[$j]) 
				break; 

		if ($j == $M) 
			echo "Pattern found at index ", $i. "\n"; 
		
	} 
} 

	$txt = "9"; 
	$pattern= fopen("sql_payloads.csv", "r");
	print_r(fgetcsv($pattern));

	search($pattern, $txt); 
	fclose($pattern);

?> 
-->

<!-- after pattern matching writing it into a csv file--> 
<!--
$file = fopen('demosql.csv', 'w');
 
// save the column headers
fputcsv($file, array('Column 1'));
 
// Sample data. This can be fetched from mysql too
$data = array( );
 
// save each row of the data
foreach ($data as $row)
{
fputcsv($file, $row);
}
 
// Close the file
fclose($file);
-->



