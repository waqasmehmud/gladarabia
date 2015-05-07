<?php session_start();
include("config.php");
if(!isset($_SESSION['login_user'])){
	header('Location: '.baseUrl.'');
	exit;	
}
$user_id = $_SESSION['login_user'];
$user_id = mysql_real_escape_string($user_id);
$ses_sql=mysql_query("select * from users where id='$user_id' ");
$user_detail=mysql_fetch_array($ses_sql);
if(isset($_FILES['user_file']['name'])){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["user_file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["user_file"]["tmp_name"]);
    if($check !== false) {
		$name = $_POST['name'];
		$description = $_POST['description'];
		$filename = time().$user_id.'_dish.'.$imageFileType;
         move_uploaded_file ( $_FILES["user_file"]["tmp_name"] , $target_dir .$filename);
        $uploadOk = 1;
		$query = "INSERT INTO `dishes` (`user_id` ,`name`,`description` ,`filename`) VALUES 
		('".$user_id."', '".$name."', '".$description."', '".$filename."');";
		mysql_query($query);
		header("location: ".baseUrl."gallery.php");
		exit;
    } else {
        echo "File is not an image.";exit;
        $uploadOk = 0;
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
    <h2>Welcome <?php echo $user_detail['name'];?>!</h2>
    <p>Start uploading your dishes to participate in challenge.<br> click here to <a href="gallery.php">view gallery</a></p>
    <form action="" method="post" enctype="multipart/form-data" id="login">
    <div class="input-group">
    <label>Name<span class="red-star">*</span></label>
    <input type="text" value="" name="name" class="required">
    </div>
    <div class="input-group">
    <label>Description<span class="red-star">*</span></label>
    <textarea name="description" class="required"></textarea>
    </div>
    <input type='file' name="user_file" class="required"/>
    <p>&nbsp;</p>
    <input type="submit" value="upload your dish" name="submit_button" style="float:left;">
    </form>
	<img id="myImg" src="#" alt="preview will come here" class="img-responsive" width="350" />

    </div>
   	</div>
   </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script>
    $(function () {
    $(":file").change(function () {
			if (this.files && this.files[0]) {
				var reader = new FileReader();
				reader.onload = imageIsLoaded;
				reader.readAsDataURL(this.files[0]);
			}
		});
	$('#login').validate();	
	});
	
	function imageIsLoaded(e) {
		$('#myImg').attr('src', e.target.result);
	};
    </script>
  </body>
</html>