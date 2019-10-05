<div class="content">
<a href="index.php?cmd=<?=$cmd?>&sub=manager" class="btn btn-primary">Quản lý chung</a>
<a href="index.php?cmd=<?=$cmd?>&sub=add" class="btn btn-success">Tạo mới</a>
<?
if(isset($_GET['sub']))
$sub=$_GET['sub'];
else
$sub="manager";
$db_table="members";
switch ($sub)
{
    case "manager":
        manager();
        break;
    case "add":
        add();
        break;
    case "del":
        del();
        break;
	case "edit":
        edit();
        break;
	case "lock":
        lock();
        break;
	case "unlock":
        unlock();
        break;
    default:
        manager();
}
function manager()
{
global $db,$cmd,$db_table;
$stt=1;
$kq="
<div class='table-responsive'>
<table class='table table-hover'>
<tr class='bg-primary text-center'>
<td>Stt</td>
<td>Ảnh</td>
<td>Username</td>
<td>Email</td>
<td>Chứng thực</td>
<td>Thời gian</td>
<td>Xóa</td>
<td>Sửa</td>
<td>Trạng thái</td>
</tr>
";
$query="Select * From $db_table";
$total=mysqli_num_rows($db->query($query));//Tổng số dòng lấy về từ database
isset($_GET["page"])==true?$page = (int)$_GET["page"]:$page = 1;//Lấy số trang hiện hành
$setLimit =2;//số dòng trên 1 trang, ví dụ 2 dòng trên 1 trang
$pageLimit = ($page * $setLimit) - $setLimit;//xác định số trang từ tổng số dòng lấy về từ biến $total
$result=$db->query($query." LIMIT ".$pageLimit." , ".$setLimit);
while($row = $result->fetch_assoc())
{
$row['img']!=''?$img=$row['img']:$img='noimages.jpg';
$kq.="
<tr class='detail'>
<td>".$stt++."</td>
<td><img src='../upload/".$img."' width='50'/></td>
<td>".$row['username']."</td>
<td>".$row['email']."</td>
<td class='text-center'>".$row['verified']."</td>
<td class='text-center'>".$row['mod_timestamp']."</td>
<td class='text-center'><a href='index.php?cmd=".$cmd."&sub=del&id=".$row['id']."' class='text-danger'><i class='fa fa-trash-o'></i></a></td>
<td class='text-center'><a href='index.php?cmd=".$cmd."&sub=edit&id=".$row['id']."'><i class='fa fa-pencil-square'></i></a></td>
<td class='text-center'>".check_status($cmd,$row['status'],$row['id'])."</td>
</tr>
";
}
$kq.="
</table>
</div>
".displayPaginationBelow($cmd,$total,$setLimit,$page);
echo $kq;
}
function add()
{
global $db,$cmd,$db_table;
if(isset($_POST['submit']))
{
$fullname=$_POST['fullname'];
$img=uploadimages("../upload/",2000000);
$username=$_POST['username'];
$email=$_POST['email'];
$description=$_POST['description'];
$password=$_POST['password'];
$repassword=$_POST['repassword'];
$uid = uniqid(rand(), false);
$pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
$query="INSERT INTO $db_table (id,fullname,username, password, email, img,description)
VALUES ('$uid','$fullname', '$username', '$pw', '$email','$img','$description')";
$ok = $db->query($query);//chuẩn bị chuỗi truy vấn chèn/insert dữ liệu vào Database
if($ok)//thực thi câu truy vấn trên
msgbox("Thành công","?cmd=".$cmd."");//msgbox() là hàm thông báo kết quả viết phía dưới
else
msgbox("Thất bại","?cmd=".$cmd."&sub=add");
}
?>
<form Method="Post" action="" enctype="multipart/form-data">
<div class="form-group">
    <label class="control-label col-sm-2" for="fullname">Fullname:</label>
    <div class="col-sm-10">
    <input type="text" id="fullname"  name="fullname" class="form-control" placeholder="Vui lòng nhập username">
	</div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="img">Ảnh:</label>
    <div class="col-sm-10">
    <input type="file" id="img"  name="img" class="form-control" placeholder="Vui lòng nhập username">
	</div>
  </div>
 <div class="form-group">
    <label class="control-label col-sm-2" for="username">Username:</label>
    <div class="col-sm-10">
    <input type="text" id="username"  name="username" class="form-control" placeholder="Vui lòng nhập username">
	</div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Email:</label>
    <div class="col-sm-10">
    <input type="text" id="email"  name="email" class="form-control" placeholder="Vui lòng nhập email...">
	</div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-2" for="password">Password:</label>
    <div class="col-sm-10">
    <input type="password" id="password"  name="password" class="form-control">
	</div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-2" for="repassword">Re-password:</label>
    <div class="col-sm-10">
    <input type="password" id="repassword"  name="repassword" class="form-control">
	</div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-2" for="description">Mô tả:</label>
    <div class="col-sm-10">
   <textarea class="form-control" rows="5" name="description" id="description"></textarea>
	<script>CKEDITOR.replace( 'description' )</script>
	</div>
  </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" name="submit">Submit</button>
    </div>
  </div>
</form>
<?
}
function edit()
{
global $db,$cmd,$db_table;
$id=get('id');
if(isset($_POST['submit']))
{
$fullname=$_POST['fullname'];
$oldimg=$_POST['oldimg'];
$description=$_POST['description'];
uploadimages("../upload/",2000000)==""?$img=$oldimg:$img=uploadimages("../upload/",2000000);
$username=$_POST['username'];
$email=$_POST['email'];
$password=$_POST['password'];
$oldpassword=$_POST['oldpassword'];
$password==""?$pw=$oldpassword:$pw=password_hash($_POST['password'],PASSWORD_DEFAULT);
$query="
UPDATE $db_table SET
fullname='$fullname',
username='$username',
description='$description',
password= '$pw',
email='$email',
img='$img'
WHERE id='$id'";
$ok = $db->query($query);
if($ok)
msgbox("Thành công","?cmd=".$cmd."");
else
msgbox("Thất bại","?cmd=".$cmd."&sub=add");
}
$query="Select * From $db_table where id='".$id."'";
$result=$db->query($query);//thực thi câu truy vấn select/delete/update/insert 
$row = $result->fetch_assoc();
?>
<form Method="Post" action="" enctype="multipart/form-data">
<div class="form-group">
    <label class="control-label col-sm-2" for="fullname">Fullname:</label>
    <div class="col-sm-10">
    <input type="text" id="fullname"  name="fullname" class="form-control" value="<?=$row['fullname'];?>">
	</div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="img">Ảnh:</label>
    <div class="col-sm-10">
    <input type="file" id="img"  name="img" class="form-control" >
	<input type="hidden" id="oldimg"
	name="oldimg" class="form-control" 
	value="<?=$row['img'];?>">
	<img src="../upload/<?=$row['img']?>" alt='chưa có' width='30'/>
	</div>
  </div>
 <div class="form-group">
    <label class="control-label col-sm-2" for="username">Username:</label>
    <div class="col-sm-10">
    <input type="text" id="username"  name="username" class="form-control" value="<?=$row['username'];?>">
	</div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Email:</label>
    <div class="col-sm-10">
    <input type="text" id="email"  name="email" class="form-control" value="<?=$row['email'];?>"/>
	</div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-2" for="password">Password:</label>
    <div class="col-sm-10">
    <input type="password" id="password"  name="password" class="form-control">
	<input type="hidden" id="oldpassword"
	name="oldpassword" class="form-control" 
	value="<?=$row['password'];?>">
	</div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="description">Mô tả:</label>
    <div class="col-sm-10">
   <textarea class="form-control" rows="5" name="description" id="description"><?=$row['description'];?></textarea>
	<script>CKEDITOR.replace( 'description' )</script>
	</div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" name="submit">Submit</button>
    </div>
  </div>
</form>
<?
}
function del()
{
Global $db,$cmd,$db_table;
$id=$_GET['id'];
$query="Delete From $db_table Where id='".$id."'";
$ok=$db->query($query);
if($ok)
msgbox("Thành công","?cmd=".$cmd."");
else
msgbox("Thất bại","?cmd=".$cmd."&sub=manager");
}
function lock()
{
Global $db,$cmd;
$id=$_GET['id'];
$query="update members set status=0 Where id='".$id."'";
$ok=$db->query($query);
if($ok)
msgbox("Thành công","?cmd=".$cmd."");
else
msgbox("Thất bại","?cmd=".$cmd."&sub=manager");
}
function unlock()
{
Global $db,$cmd,$db_table;
$id=$_GET['id'];
$query="update $db_table set status=1 Where id='".$id."'";
$ok=$db->query($query);
if($ok)
msgbox("Thành công","?cmd=".$cmd."");
else
msgbox("Thất bại","?cmd=".$cmd."&sub=manager");
}
?>
</div>
