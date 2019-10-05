<?
function message($title,$cmd,$act='manager')
{
echo "<script>alert('".$title."');document.location='index.php?cmd=".$cmd."&act=".$act."'</script>";
}
function upload_file($filename)
{
global $upload_dir,$max_file_size_upload,$thumb_dir,$thumbnail_height,$thumbnail_width;
$target_dir = $upload_dir;
$target_file = $target_dir . basename($_FILES[$filename]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES[$filename]["tmp_name"]);
if($check !== false) 
{
	$uploadOk = 1;
}
else 
{
	$uploadOk = 0;
}
if ($_FILES[$filename]["size"] > $max_file_size_upload) 
{
    $uploadOk = 0;
}
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" )
{
	$uploadOk = 0;
}
if ($uploadOk == 0)
{
	$newfilename="";
	return $newfilename;
}
else
{
	$newfilename=time().'.'.$imageFileType;
    if (move_uploaded_file($_FILES[$filename]["tmp_name"], $target_dir.$newfilename))
	{
        createThumbnail($target_dir.$newfilename, $thumb_dir.$newfilename, $thumbnail_width, $thumbnail_height,false);
		return $newfilename;
    }
	else
	{
        $newfilename="";
		return $newfilename;
    }
}
}
function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background=false)
{
    list($original_width, $original_height, $original_type) = getimagesize($filepath);
    if ($original_width > $original_height)
	{
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    }
	else
	{
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);

    if ($original_type === 1)
	{
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
	else if ($original_type === 2)
	{
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
	else if ($original_type === 3)
	{
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
	else
	{
        return false;
    }

    $old_image = $imgcreatefrom($filepath);
    $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background

    // figuring out the color for the background
    if(is_array($background) && count($background) === 3)
	{
      list($red, $green, $blue) = $background;
      $color = imagecolorallocate($new_image, $red, $green, $blue);
      imagefill($new_image, 0, 0, $color);
    // apply transparent background only if is a png image
    }
	else if($background === 'transparent' && $original_type === 3)
	{
		imagesavealpha($new_image, TRUE);
      $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
      imagefill($new_image, 0, 0, $color);
    }
	imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
    $imgt($new_image, $thumbpath);
}
function img_display($name,$width=50)
{
global $upload_dir;
$rs="<img src='".$upload_dir.$name."' alt='' width='".$width."'/>";
return $rs;
}
function displayPaginationBelow($cmd,$total,$per_page,$page)
{
    $page_url="?cmd=".$cmd."&";
	$adjacents = "2"; 

	$page = ($page == 0 ? 1 : $page);  
	$start = ($page - 1) * $per_page;								
	
	$prev = $page - 1;							
	$next = $page + 1;
	$setLastpage = ceil($total/$per_page);
	$lpm1 = $setLastpage - 1;
	
	$setPaginate = "";
	if($setLastpage > 1)
	{	
		$setPaginate .= "<ul class='setPaginate pagination'>";
		$setPaginate .= "<li style='padding:0px 10px;line-height: 35px;'>".$page."/".$setLastpage."</li>";
		if ($setLastpage < 7 + ($adjacents * 2))
		{	
			for ($counter = 1; $counter <= $setLastpage; $counter++)
			{
				if ($counter == $page)
					$setPaginate.= "<li class='active'><a class='current_page'>$counter</a></li>";
				else
					$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";					
			}
		}
		elseif($setLastpage > 5 + ($adjacents * 2))
		{
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$setPaginate.= "<li class='active'><a class='current_page'>$counter</a></li>";
					else
						$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";					
				}
				$setPaginate.= "<li class='dot'>...</li>";
				$setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
				$setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";		
			}
			elseif($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
				$setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
				$setPaginate.= "<li class='dot'>...</li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$setPaginate.= "<li class='active'><a class='current_page'>$counter</a></li>";
					else
						$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";					
				}
				$setPaginate.= "<li class='dot'>..</li>";
				$setPaginate.= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
				$setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";		
			}
			else
			{
				$setPaginate.= "<li><a href='{$page_url}page=1'>1</a></li>";
				$setPaginate.= "<li><a href='{$page_url}page=2'>2</a></li>";
				$setPaginate.= "<li class='dot'>..</li>";
				for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++)
				{
					if ($counter == $page)
						$setPaginate.= "<li class='active'><a class='current_page'>$counter</a></li>";
					else
						$setPaginate.= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";					
				}
			}
		}
		
		if ($page < $counter - 1){ 
			$setPaginate.= "<li><a href='{$page_url}page=$next'>Next</a></li>";
			$setPaginate.= "<li><a href='{$page_url}page=$setLastpage'>Last</a></li>";
		}else{
			$setPaginate.= "<li><a class='current_page'>Next</a></li>";
			$setPaginate.= "<li><a class='current_page'>Last</a></li>";
		}

		$setPaginate.= "</ul>\n";		
	}
	return $setPaginate;
}

?>