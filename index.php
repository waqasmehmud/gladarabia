<?php session_start();
include("config.php");
include("csrf.class.php");
include("PasswordHash.php");
$csrf = new csrf();
// Generate Token Id and Valid
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
// Generate Random Form Names
$form_names = $csrf->form_names(array('email', 'password'), false);
$error = '';
if(isset($_POST[$form_names['email']], $_POST[$form_names['password']])) {
	// Check if token id and token value are valid.
	if($csrf->check_valid('post')) {
		// Get the Form Variables.
		$user = $_POST[$form_names['email']];
		$password = $_POST[$form_names['password']];
		$sql = sprintf("SELECT * FROM users WHERE email='%s';",
			   mysql_real_escape_string($user));
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$count=mysql_num_rows($result);
		if($count == 1)
		{
			$hash = $row['password'];
			if(validate_password($password,$hash)){
				$_SESSION['login_user']=$row['id'];
				header("location: ".baseUrl."upload-dish.php");
				exit;
			}else{
				$error = 'Error: Invalid email or password';
			}

		}else{
			$error = 'Error: Invalid email or password';
		}

	}
	// Regenerate a new random value for the form.
	$form_names = $csrf->form_names(array('email', 'password'), true);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glad Arabia - Cooking Challenge</title>
	<!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/layout.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
   <div id="wrapper">
   	<div class="round-image">
    	<img class="img-responsive" src="images/glad-arabic-challenge.png" height="374" width="365">
    </div>
   	<div class="login-form">
    	<?php if(!empty($error)){?>
        <div class="response-msg error ui-corner-all">
            <?php echo $error;?>
		</div>
        <?php }?>
    	<form action="" method="post" id="login">
        	<input type="hidden" value="<?php echo $token_value;?>" name="<?php echo $token_id;?>">
        	<div class="input-group">
        	<label>Email:</label>
            <input type="text" value="" name="<?php echo $form_names['email']?>" class="required">
            </div>
            <div class="input-group">
            <label>Password:</label>
            <input type="password" value="" class="required" name="<?php echo $form_names['password']?>">
            <input type="submit" class="button" value="ENTER">
            </div>
            <p align="center">Don't have account ? click here to <a href="register.php">Register</a></p>
            
        </form>
    </div>
   </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script>
    $(document).ready(function(){
		$('#login').validate();
	});
    </script>
  </body>
</html>