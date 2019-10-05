<?
class db extends mysqli
{
private $sercet="kaitoukid141206071998";
public function __construct($host, $user, $password, $database) 
	{
			parent::__construct($host, $user, $password, $database);
			if(mysqli_connect_errno())
			{
				throw new exception(mysqli_connect_error(), mysqli_connect_errno()); 
			}
	}
	public function myquery($query)
	{
		if( !$this->real_query($query) ) {
			throw new exception( $this->error, $this->errno );
		}

		$result = new mysqli_result($this);
		return $result;
	}
	public function num_row($result)
	{
		return mysqli_num_rows($result);
	}
	public function fetch_row($result)
	{
		return mysqli_fetch_array($result);
	}
public function encrypt($data)
{
	$data=md5($this->sercet.$data);
	$data=hash('sha1',$data);
	return $data;
}
public function generate_salt($password,$usermail)
{
	$data=$this->encrypt($password.$usermail.time());
	return $data;
}
public function update_salt($usermail,$password)
{
	$salt=$this->generate_salt($usermail,$password);
	$sql="update db_tblaccount set `salt`='$salt' where usermail='".$usermail."' and status=1";
	
	if($this->myquery($sql))
	{
		$_SESSION['login']['salt']=$salt;
		return true;
	}
	else
	{
		$_SESSION['login']['state']="";
		return false;
	}
}
public function check_salt()
{
$sql="select salt from db_tblaccount where usermail='".$_SESSION['login']['usermail']."' and salt='".$_SESSION['login']['salt']."' and status=1";
$result=$this->myquery($sql);
if($this->num_row($result)==1)
{
	return true;
}
else
{
	return false;
}
}
public function login($usermail,$password)
{
$password=$this->encrypt($password);
$sql="select * 
from db_tblaccount 
where usermail ='".$usermail."' and 
password='".$password."' and 
status=1";
$result=$this->myquery($sql);
if($this->num_row($result)==1)
{
$row=$this->fetch_row($result);
$_SESSION['login']['state']=true;
$_SESSION['login']['name']=$row['name'];
$_SESSION['login']['usermail']=$row['usermail'];
$_SESSION['login']['images']=$row['img'];
if($this->update_salt($usermail,$password))
$this->redirect("index.php");
else
$this->redirect("login.php");
}
}
public function checklogin($dir="../")
{
if(!$this->check_salt())
$this->redirect("login.php",$dir);
}
public function redirect($filename,$dir="../")
{
header("location: ".$dir.$filename);
}
public function post($data)
{
isset($_POST[$data])?$result=$_POST[$data]:$result='';
return $result;
}
public function get($data,$value_default="")
{
isset($_GET[$data])?$result=$_GET[$data]:$result=$value_default;
return $result;
}
public function display($data)
{
isset($_SESSION['login'][$data])?$result=$_SESSION['login'][$data]:$result=null;
return $result;
}
function logout()
{
	unset($_SESSION['login']);
	session_destroy();
}
function list_cat($db_table="db_tblcat",$id=0)
{
	$i=1;
	$rs='<select name="cat" class="form-control">';
	$sql="select * from $db_table where status=1";
	$result=$this->myquery($sql);
	while($row=$this->fetch_row($result))
	{	if($row['id']==$id)
			$selected="selected";
		else
			$selected="";
		$rs.='<option value="'.$row['id'].'" '.$selected.'> '.($i++).'.'.$row['name'].'</option>';
	}
	$rs.="</select>";
	return $rs;
}
function id_to_name($db_table="db_tblcat",$id)
{
	$sql="select * from $db_table where id=$id and status=1";
	$result=$this->myquery($sql);
	$row=$this->fetch_row($result);
	return $row['name'];
}

public function __destruct()
{
	parent::close();
}
}
?>