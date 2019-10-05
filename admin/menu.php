<div class="crbnMenu">
	<div class="link-stack">
		<img src="<?=$upload_dir.$db->display('images')?>" width="80"/>
		<span class="brand all"><?=$db->display('name')?></span>
			<a id="nav-toggle" class="nav-toggle">
				<span></span>
			 </a>
	</div>
	<ul class="menu">
		<li>
			<a class="nav-link" href="#">
				<i class="fa fa-th"></i> <span>Quản lí sản phẩm</span>
				<span class="menu-toggle"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
			</a>
			<ul>
				<li>
					<a href="index.php?cmd=product">Sản phẩm</a>
				</li>
				<li>
					<a href="index.php?cmd=cat">Danh mục</a>
				</li>
			</ul>
		</li>
		<li>
			<a class="nav-link" href="#">
				<i class="fa fa-cogs"></i> <span>Cấu hình website</span>
				<span class="menu-toggle"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
			</a>
			<ul>
				<li>
					<a href="index.php?cmd=account">Quản lí tài khoản</a>
				</li>
				<li>
					<a href="#">Khai báo thông tin website</a>
				</li>
				<li>
					<a href="#">Tạo sitemap</a>
				</li>
			</ul>
		</li>
		<li>
			<a class="nav-link" href="index.php?cmd=logout">
				<i class="fa fa-sign-in"></i> <span>Thoát</span>
			</a>
		</li>
	</ul>
</div>
<div class="content">
	<div class="row">
		<div class="col-sm-12 all">
			<div class="col-sm-6">
				<a href="index.php" class="btn btn-default"><i class="fa fa-home "></i>&nbsp;Trang chủ</a>
				<a href="index.php?cmd=<?=$cmd?>&act=manager" class="btn btn-primary"><i class="fa fa-th"></i>&nbsp;Quản lý chung</a>
				<a href="index.php?cmd=<?=$cmd?>&act=add" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;Tạo mới</a>
			</div>
			<div class="col-sm-6">
				<div class="col-sm-8">
					<form name="frmsearch" class="form-inline" action="index.php?cmd=<?=$cmd?>&act=search" method="POST">
						<div class="form-group">
							<label for="email"></label>
							<input type="text" class="form-control" id="search" name="search" value="" placeholder="Nhập id/name">
						</div>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;Tìm kiếm</button>
					</form>
				</div>
				<div class="col-sm-4 message">
					<a href="#" data-toggle="popover" data-placement="bottom" title="Thông tin đặt hàng" data-content="Tin nhắn đến"><i class="fa fa-envelope-o"></i></a>
				</div>
			</div>
		</div>
<script>
	$(document).ready(function(){
		$('.menu').crbnMenu({
			hideActive: true
		});
	});
</script>