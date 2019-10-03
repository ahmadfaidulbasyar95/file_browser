<?php 
include 'function.php';
include 'config.php';
$output             = array();
$output['url']      = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']._URI;
$output['root']     = dirname(__FILE__).'/';
$output['path']     = _PATH;
$output['path_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']._PATH_URI;
if (_URI=='/') {
	$output['path_req'] = explode('/', @$_SERVER['REQUEST_URI']);
}else{
	$output['path_req'] = explode('/', str_replace(_URI, '', @$_SERVER['REQUEST_URI']));
}
foreach ($output['path_req'] as $key => $value) {
	if(empty($value)) unset($output['path_req'][$key]);
}
$output['path_req'] = array_values($output['path_req']);
if (@$_POST['tool_act']) 
{
	include 'tool_act.php';
}else
if (@$_POST['open_dir']) 
{
	$_POST['open_dir'] = path_url_decode($_POST['open_dir']);
	foreach (get_list_dir($output['path'].$_POST['open_dir']) as $value) 
	{
		?>
		<div class="list-group-item">
			<a href="<?php echo path_url_encode($_POST['open_dir'].'/'.$value); ?>">
				<span class="fa fa-caret-right"></span>
			</a>
			<a href="<?php echo path_url_encode($_POST['open_dir'].'/'.$value); ?>" class="ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
		</div>
		<div class="list-group" style="display: none;"></div>
		<?php 
	}
}else
if (@$_POST['open_dir_item']) 
{
	$_POST['open_dir_item'] = path_url_decode($_POST['open_dir_item']);
	$add_path               = ($_POST['open_dir_item'] == '/') ? '' : $_POST['open_dir_item'].'/';
	if (@$_POST['keyword']) 
	{
		$data_list      = search_directory($output['path'].$_POST['open_dir_item'],$_POST['keyword']);
		$data_list_dir  = array();
		$data_list_file = array();
		foreach ($data_list['dir'] as $value) {
			$data_list_dir[]  = ($_POST['open_dir_item']) ? str_replace($output['path'].$_POST['open_dir_item'].'/', '', $value) : str_replace($output['path'], '', $value);
		}
		foreach ($data_list['file'] as $value) {
			$data_list_file[]  = ($_POST['open_dir_item']) ? str_replace($output['path'].$_POST['open_dir_item'].'/', '', $value) : str_replace($output['path'], '', $value);
		}
	}else
	{
		$data_list_dir          = get_list_dir($output['path'].$_POST['open_dir_item']);
		$data_list_file         = get_list_file($output['path'].$_POST['open_dir_item']);
	}
	?>
	<h4 class="box-nav">
		<a href="/">Directory</a>
		<?php
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
				/
				<a href="<?php echo path_url_encode($open_dir_item_nav); ?>"><?php echo $value; ?></a>
				<?php 
			}
		}
		?>
		<a href="<?php echo $output['path_url'].$_POST['open_dir_item']; ?>" class="external" target="_BLANK">
			<i class="fa fa-external-link"></i>
		</a>
		<span class="text-muted">(<?php echo count($data_list_dir); ?>)</span>
	</h4>
	<form action="<?php echo ($_POST['open_dir_item']) ? path_url_encode($_POST['open_dir_item']):'/'; ?>" method="POST" class="form-inline form_search" role="form">
		<div class="form-group" style="display: none;">
			<input type="text" class="form-control" placeholder="Search">
		</div>
		<i class="fa fa-search btn"></i>
	</form>
	<div class="col-xs-12">
		<div class="col-xs-12 no_pad">
			<?php 
			foreach ($data_list_dir as $value) 
			{
				?>
				<div class="col-xs-12 col-sm-6 col-md-4 item">
					<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis selection" title="<?php echo $value; ?>"><?php echo $value; ?></a>
				</div>
				<?php 
			}
			?>
		</div>
		<div class="col-xs-12 no_pad">
			<h4>File 
				<a href="<?php echo path_url_encode($_POST['open_dir_item']); ?>" class="btn btn-default file_download_all">
					<i class="fa fa-download"></i> Download Link
				</a>
				<span class="text-muted">(<?php echo count($data_list_file); ?>)</span>
			</h4>
		</div>
		<div class="col-xs-12 no_pad">
			<?php 
			foreach ($data_list_file as $value) 
			{
				?>
				<div class="col-xs-12 col-sm-6 col-md-4 item">
					<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis file selection" title="<?php echo $value; ?>"><?php echo $value; ?></a>
				</div>
				<?php 
			}
			?>
		</div>
	</div>
	<div id="data_addpath"><?php echo path_url_encode($add_path); ?></div>
	<?php 
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
							<i class="fa fa-download"> Download Link</i>
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
							<a href="<?php echo path_url_link($output['path_url'].$_POST['open_dir_item_download'].'/'.$value); ?>" class="btn btn-default btn-block" target="_BLANK">
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
					<td><?php echo path_url_link($output['path_url'].$_POST['open_item_detail']); ?></td>
				</tr>
				<tr>
					<td>Download</td>
					<td>:</td>
					<td>
						<a href="<?php echo path_url_link($output['path_url'].$_POST['open_item_detail']); ?>" class="btn btn-default" target="_BLANK">
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
			<div class="tool_box col-xs-12 no_pad" style="display: none;">
				<a href="#modal-tool-create_folder" class="tool_item btn btn-default" data-toggle="modal" title="Create Folder">
					*<i class="fa fa-folder-o"></i>
				</a>
				<a href="#modal-tool-delete" class="tool_item btn btn-default" data-toggle="modal" title="Delete (Del)">
					<i class="fa fa-trash"></i>
				</a>
				<a href="#modal-tool-cut" class="tool_item btn btn-default" data-toggle="modal" title="Cut (Ctrl + X)">
					<i class="fa fa-cut"></i>
				</a>
				<a href="#modal-tool-copy" class="tool_item btn btn-default" data-toggle="modal" title="Copy (Ctrl + C)">
					<i class="fa fa-copy"></i>
				</a>
				<a href="#modal-tool-paste" class="tool_item btn btn-default" data-toggle="modal" title="Paste (Ctrl + V)">
					<i class="fa fa-paste"></i>
				</a>
				<a href="#modal-tool-rename" class="tool_item btn btn-default" data-toggle="modal" title="Rename (F2)">
					A <i class="fa fa-angle-right"></i> B
				</a>
				<a href="#tool-clear_selected" class="tool_item btn btn-default" title="Clear Selected (Esc)">
					x
				</a>
			</div>
			<div class="col-xs-12 tool_box_open">
				<a href="" class="" title="Open Toolbox">
					<i class="fa fa-chevron-down"></i>
				</a>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 no_pad">
				<div class="list-group go_open_dir">
					<?php 
					foreach (get_list_dir($output['path']) as $value) 
					{
						?>
						<div class="list-group-item">
							<a href="<?php echo path_url_encode($value); ?>">
								<span class="fa fa-caret-right"></span>
							</a>
							<a href="<?php echo path_url_encode($value); ?>" class="ellipsis" title="<?php echo $value; ?>"><?php echo $value; ?></a>
						</div>
						<div class="list-group" style="display: none;"></div>
						<?php 
					}
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-9 go_open_dir_item no_pad">
				<?php 
				$add_path                         = '';
				if($output['path_req']) $add_path = implode('/', $output['path_req']); 
				$add_path                         = path_url_decode($add_path).'/';
				$data_list_dir                    = get_list_dir($output['path'].$add_path);
				$data_list_file                   = get_list_file($output['path'].$add_path);
				?>
				<h4 class="box-nav">
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
							/
							<a href="<?php echo path_url_encode($open_dir_item_nav); ?>"><?php echo $value; ?></a>
							<?php 
						}
					}
					?>
					<a href="<?php echo $output['path_url'].$add_path; ?>" class="external" target="_BLANK">
						<i class="fa fa-external-link"></i>
					</a>
					<span class="text-muted">(<?php echo count($data_list_dir); ?>)</span>
				</h4>
				<form action="<?php echo (path_url_encode($add_path)) ? path_url_encode($add_path) : '/'; ?>" method="POST" class="form-inline form_search" role="form">
					<div class="form-group" style="display: none;">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<i class="fa fa-search btn"></i>
				</form>
				<div class="col-xs-12">
					<div class="col-xs-12 no_pad">
						<?php 
						foreach ($data_list_dir as $value) 
						{
							?>
							<div class="col-xs-12 col-sm-6 col-md-4 item">
								<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis selection" title="<?php echo $value; ?>"><?php echo $value; ?></a>
							</div>
							<?php 
						}
						?>
					</div>
					<div class="col-xs-12 no_pad">
						<h4>File 
							<a href="<?php echo path_url_encode($add_path); ?>" class="btn btn-default file_download_all">
								<i class="fa fa-download"></i> Download Link
							</a>
							<span class="text-muted">(<?php echo count($data_list_file); ?>)</span>
						</h4>
					</div>
					<div class="col-xs-12 no_pad">
						<?php 
						foreach ($data_list_file as $value) 
						{
							?>
							<div class="col-xs-12 col-sm-6 col-md-4 item">
								<a href="<?php echo path_url_encode($add_path.$value); ?>" class="btn btn-default btn-block ellipsis file selection" title="<?php echo $value; ?>"><?php echo $value; ?></a>
							</div>
							<?php 
						}
						?>
					</div>
				</div>
				<div id="data_addpath"><?php echo path_url_encode($add_path); ?></div>
			</div>
			<div class="modal fade" id="modal-tool-create_folder">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Create Folder</h4>
						</div>
						<div class="modal-body">
							<h3 class="text-center loader" style="display: none;"><i class="fa fa-spinner fa-spin"></i></h3>
							<form action="" method="POST" role="form">
								<div class="form-group">
									<label for="">Folder Name</label>
									<input type="text" class="form-control" required="required">
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-tool-rename">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Rename</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<tbody class="list_wrapper"></tbody>
								</table>
							</div>
							<a href="" class="btn btn-info rename_btn">A <i class="fa fa-angle-right"></i> B</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-tool-delete">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Delete</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<tbody class="list_wrapper"></tbody>
								</table>
							</div>
							<a href="" class="btn btn-danger delete_btn"><i class="fa fa-trash"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-tool-cut">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Cut</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<tbody class="list_wrapper"></tbody>
								</table>
							</div>
							<a href="" class="btn btn-warning cut_btn"><i class="fa fa-cut"></i> Goto target directory and then Paste</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-tool-copy">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Copy</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<tbody class="list_wrapper"></tbody>
								</table>
							</div>
							<a href="" class="btn btn-warning copy_btn"><i class="fa fa-copy"></i> Goto target directory and then Paste</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-tool-paste">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Paste</h4>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<tbody class="list_wrapper"></tbody>
								</table>
							</div>
							<a href="" class="btn btn-warning paste_btn"><i class="fa fa-paste"></i></a>
						</div>
					</div>
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