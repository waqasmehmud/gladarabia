<?php session_start();
include("config.php");
include("csrf.class.php");
include("PasswordHash.php");
$csrf = new csrf();
// Generate Token Id and Valid
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
// Generate Random Form Names
$form_names = $csrf->form_names(array('name','email', 'phone_number','password','gender','country','age'), false);
$error = '';
if(@$_POST[$form_names['name']] !='' && @$_POST[$form_names['email']] !=''&& @$_POST[$form_names['password']] !=''&& @$_POST[$form_names['phone_number']] !=''&& @$_POST[$form_names['country']] !=''&& @$_POST[$form_names['gender']] !=''&& @$_POST[$form_names['country']] !='') {
	// Check if token id and token value are valid.
	if($csrf->check_valid('post')) {
		// Get the Form Variables.
		$name = $_POST[$form_names['name']];
		$email = $_POST[$form_names['email']];
		$password = create_hash($_POST[$form_names['password']]);
		$phone_number = $_POST[$form_names['phone_number']];
		$gender = $_POST[$form_names['gender']];
		$age = $_POST[$form_names['age']];
		$country = $_POST[$form_names['country']];
		
		
		$sql = sprintf("SELECT * FROM users WHERE email='%s';",
			   mysql_real_escape_string($email));
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$count=mysql_num_rows($result);
		if($count == 1)
		{
			$error = 'Error: Email already exists!';
		}else{
			$query = "INSERT INTO `users` (`email` ,`password`,`name` ,`phone_number` ,`country` ,`age` ,`gender`) VALUES 
			('".$email."', '".$password."', '".$name."', '".$phone_number."', '".$country."', '".$age."', '".$gender."');";
			mysql_query($query);
			header("location: ".baseUrl."upload-dish.php");
			exit;
		}

	}
	// Regenerate a new random value for the form.
	$form_names = $csrf->form_names(array('name','email', 'phone_number','password','gender','country','age'), true);
}else{
	if(isset($_POST['submit_button'])){
		$error = 'Error: Please fill form fields properly';
	}
	
	
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
  <body class="inner">
  
   <div id="wrapper">
   <div class="col-md-5">
   &nbsp;
   </div>
   <div class="col-md-5" style="text-align:center;">
   	<div class="do-you-think">
    <img class="img-responsive" src="images/do-you-think-text.jpg" height="174" width="396">
    </div>
   	<div class="register-form">
    	<?php if(!empty($error)){?>
        <div class="response-msg error ui-corner-all">
            <?php echo $error;?>
		</div>
        <?php }?>
    	<form action="" method="post" id="login">
        	<input type="hidden" value="<?php echo $token_value;?>" name="<?php echo $token_id;?>">
        	<div class="input-group">
        	<label>Name<span class="red-star">*</span></label>
            <input type="text" value="" name="<?php echo $form_names['name']?>" class="required">
            </div>
            <div class="input-group">
        	<label>Email<span class="red-star">*</span></label>
            <input type="text" value="" name="<?php echo $form_names['email']?>" class="required email">
            </div>
            <div class="input-group">
            <label>Password<span class="red-star">*</span></label>
            <input type="password" value="" class="required" name="<?php echo $form_names['password']?>">
            </div>
            <div class="input-group">
        	<label>Phone number<span class="red-star">*</span></label>
            <input type="text" value="" name="<?php echo $form_names['phone_number']?>" class="required">
            </div>
            <div class="input-group">
        	<label>Country<span class="red-star">*</span></label>
            <input type="text" value="" name="<?php echo $form_names['country']?>" class="required">
            </div>
            <div class="row">
            <div class="col-sm-3">
            <div class="input-group">
        	<label>Age<span class="red-star">*</span></label>
            <input type="text" value="" name="<?php echo $form_names['age']?>" class="required">
            </div>
            </div>
            <div class="col-sm-10" style="text-align:left;">
            <label>Male</label>
            <input type="radio" name="<?php echo $form_names['gender']?>" value="Male" class="required">
            <label>Female</label>
            <input type="radio" name="<?php echo $form_names['gender']?>" value="Female" class="required">
            </div>
            <div class="clearfix"></div>
            <div class="input-group">
            <input type="checkbox" value="1" name="terms" class="required"> I agree to Terms and Conditions <span class="red-star">*</span>
            <p>&nbsp;</p>
            </div>
            <div class="col-sm-6">
            
            </div>
            </div>
            <div class="input-group">
            	<input type="submit" value="Submit" name="submit_button">
            </div>
            
        </form>
    </div>
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