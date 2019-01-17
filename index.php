<?php 
include 'function.php';
$output             = array();
$output['url']      = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/file_browser/';
$output['root']     = dirname(__FILE__).'/';
$output['path']     = str_replace('/file_browser', '', $output['root']);
$output['path_url'] = str_replace('/file_browser', '', $output['url']);
$output['path_req'] = explode('/', str_replace('/file_browser/', '', $_SERVER['REQUEST_URI']));
if(empty(@$output['path_req'][0])) $output['path_req'] = array();

if (@$_POST['open_dir']) 
{
	$_POST['open_dir'] = path_url_decode($_POST['open_dir']);
	foreach (get_list_dir($output['path'].$_POST['open_dir']) as $value) 
	{
		?>
		<div class="list-group-item">
			<a href="<?php echo path_url_encode($_POST['open_dir'].'/'.$value); ?>" class="ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
			<a href="<?php echo path_url_encode($_POST['open_dir'].'/'.$value); ?>">
				<span class="fa fa-plus"></span>
			</a>
		</div>
		<div class="list-group" style="display: none;"></div>
		<?php 
	}
}else
if (@$_POST['open_dir_item']) 
{
	$_POST['open_dir_item'] = path_url_decode($_POST['open_dir_item']);
	$add_path = ($_POST['open_dir_item'] == '/') ? '' : $_POST['open_dir_item'].'/';
	echo '<h4><a href="/">Directory</a>';
	$open_dir_item_nav = '';
	foreach (explode('/', $_POST['open_dir_item']) as $value) 
	{
		if ($value) 
		{
			if($open_dir_item_nav) {
				$open_dir_item_nav .= '/'.$value;
			}else{
				$open_dir_item_nav = $value;
			}
			?>
			->
			<a href="<?php echo path_url_encode($open_dir_item_nav); ?>"><?php echo $value; ?></a>
			<?php 
		}
	}
	echo ' <a href="'.$output['path_url'].$_POST['open_dir_item'].'" class="external" target="_BLANK"><i class="fa fa-external-link"></i></a></h4><div>';
	foreach (get_list_dir($output['path'].$_POST['open_dir_item']) as $value) 
	{
		?>
		<div class="col-xs-6 col-sm-4 col-md-3 item">
			<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
		</div>
		<?php 
	}
	echo '</div><h4>File <a href="'.path_url_encode($_POST['open_dir_item']).'" class="btn btn-default file_download_all"><i class="fa fa-download"></i> Download All</a></h4><div>';
	foreach (get_list_file($output['path'].$_POST['open_dir_item']) as $value) 
	{
		?>
		<div class="col-xs-6 col-sm-4 col-md-3 item">
			<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis file" title="<?php echo $value; ?>"><?php echo $value; ?></a>
		</div>
		<?php 
	}
	echo '</div>';
}else
if (isset($_POST['open_dir_item_download'])) 
{
	$_POST['open_dir_item_download'] = path_url_decode($_POST['open_dir_item_download']);
	?>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>File Name</th>
					<th></th>
					<th>
						<button class="btn btn-default btn-block file_download_all_now" style="display: none;">
							<i class="fa fa-download"> Download All</i>
						</button>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach (get_list_file($output['path'].$_POST['open_dir_item_download']) as $key => $value) 
				{
					?>
					<tr>
						<td><?php echo $value; ?></td>
						<td>:</td>
						<td>
							<a href="<?php echo $output['path_url'].$_POST['open_dir_item_download'].'/'.$value; ?>" class="btn btn-default btn-block" target="_BLANK">
								<i class="fa fa-download"></i>
								<?php echo FileSizeConvert(filesize($output['path'].$_POST['open_dir_item_download'].'/'.$value)); ?>
							</a>
						</td>
					</tr>
					<?php 
				}
				?>
			</tbody>
		</table>
	</div>
	<?php 
}else
if (@$_POST['open_item_detail']) 
{
	$_POST['open_item_detail'] = path_url_decode($_POST['open_item_detail']);
	?>
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<tbody>
				<tr>
					<td>Name</td>
					<td>:</td>
					<td><?php echo basename($_POST['open_item_detail']); ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td>:</td>
					<td><?php echo finfo_file(finfo_open(FILEINFO_MIME_TYPE),$output['path'].$_POST['open_item_detail']); ?></td>
				</tr>
				<tr>
					<td>Size</td>
					<td>:</td>
					<td><?php echo FileSizeConvert(filesize($output['path'].$_POST['open_item_detail'])); ?></td>
				</tr>
				<tr>
					<td>Last Modified</td>
					<td>:</td>
					<td><?php echo date('Y M d H:i:s',filemtime($output['path'].$_POST['open_item_detail'])); ?></td>
				</tr>
				<tr>
					<td>Path</td>
					<td>:</td>
					<td><?php echo $output['path_url'].$_POST['open_item_detail']; ?></td>
				</tr>
				<tr>
					<td>Download</td>
					<td>:</td>
					<td>
						<a href="<?php echo $output['path_url'].$_POST['open_item_detail']; ?>" class="btn btn-default" target="_BLANK">
							<i class="fa fa-download"></i>
						</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php 
}else
{
	?>
	<!DOCTYPE html>
	<html lang="">
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>File Browser</title>
			<link rel="stylesheet" href="<?php echo $output['url']; ?>css/style_compress.css" >
		</head>
		<body>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<h4>Navigation</h4>
				<div class="list-group go_open_dir">
					<?php 
					foreach (get_list_dir($output['path']) as $value) 
					{
						?>
						<div class="list-group-item">
							<a href="<?php echo path_url_encode($value); ?>" class="ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
							<a href="<?php echo path_url_encode($value); ?>">
								<span class="fa fa-plus"></span>
							</a>
						</div>
						<div class="list-group" style="display: none;"></div>
						<?php 
					}
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-9 go_open_dir_item">
				<?php 
				$add_path = '';
				if($output['path_req']) $add_path = implode('/', $output['path_req']); 
				$add_path = path_url_decode($add_path).'/';
				?>
				<h4>
					<a href="/">Directory</a>
					<?php 
					$open_dir_item_nav = '';
					foreach (explode('/', $add_path) as $value) 
					{
						if ($value) 
						{
							if($open_dir_item_nav) {
								$open_dir_item_nav .= '/'.$value;
							}else{
								$open_dir_item_nav = $value;
							}
							?>
							->
							<a href="<?php echo path_url_encode($open_dir_item_nav); ?>"><?php echo $value; ?></a>
							<?php 
						}
					}
					?>
					<a href="<?php echo $output['path_url'].$add_path; ?>" class="external" target="_BLANK"><i class="fa fa-external-link"></i></a>
				</h4>
				<div>
					<?php 
					foreach (get_list_dir($output['path'].$add_path) as $value) 
					{
						?>
						<div class="col-xs-6 col-sm-4 col-md-3 item">
							<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
						</div>
						<?php 
					}
					?>
				</div>
				<h4>File <a href="<?php echo path_url_encode($add_path); ?>" class="btn btn-default file_download_all"><i class="fa fa-download"></i> Download All</a></h4>
				<div>
					<?php 
					foreach (get_list_file($output['path'].$add_path) as $value) 
					{
						?>
						<div class="col-xs-6 col-sm-4 col-md-3 item">
							<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis file" title="<?php echo $value; ?>"><?php echo $value; ?></a>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
			<div class="modal fade" id="modal-file_detail">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">File Detail</h4>
						</div>
						<div class="modal-body"></div>
					</div>
				</div>
			</div>
			<script type="text/javascript"> var PATH = '<?php echo $output['url']; ?>'; </script>
			<script src="<?php echo $output['url']; ?>js/script_compress.js"></script>
		</body>
	</html>
	<?php 
}
?>