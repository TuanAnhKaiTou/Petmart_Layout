<?
require("config/init.php");
//var_dump($SESSION['login']);
?>
<!DOCTYPE html>
<html lang="en-vn">
<head>
<title>Đăng nhập vào trang quản trị</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content=""/>
<link rel="stylesheet" href="api/bootstrap/css/bootstrap.min.css" >
<link rel="stylesheet" href="api/bootstrap/css/bootstrap-theme.min.css" >
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/mystyle.css" type="text/css" media="all" />
<script src="js/jquery-3.2.1.min.js"></script>
<script src="api/bootstrap/js/bootstrap.min.js" ></script>
<link rel="stylesheet" href="api/menu/css/styles.css">
<script src="api/menu/js/crbnMenu.js"></script>
<script src="api/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});
</script>
</head>
<body>
	<div class="menu-container">
		<?
			include("menu.php");
			include($cmd.".php");
			include("footer.php");
		?>
	</div>
</body>
</html>