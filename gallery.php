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
    <p>Start uploading your dishes to participate in challenge. <br>click here to <a href="upload-dish.php">upload</a></p>
    </div>
    <div class="dish-listing">
    <?php $result=mysql_query("select * from dishes where user_id='$user_id' ");
		 $count=mysql_num_rows($result);
	if($count > 0){
		while($dish = mysql_fetch_array($result)){
	?>
    <div class="col-sm-5">
    	<h4><?php echo $dish['name'];?></h4>
        <a class="fancybox" href="uploads/<?php echo $dish['filename'];?>" data-fancybox-group="gallery" title="<?php echo $dish['name'];?>">
        <img class="img-responsive" src="uploads/<?php echo $dish['filename'];?>">
        </a>
    </div>
    <?php }
	}?>
    </div>
   	</div>
   </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />
    <script>
    $(document).ready(function(){
		$('.fancybox').fancybox();
	});
    </script>
  </body>
</html>