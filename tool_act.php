<?php 
$_POST['addpath'] = path_url_decode(@$_POST['addpath']);
switch (@$_POST['tool_act']) {
	case 'create_folder':
		if($_POST['addpath']) $_POST['addpath'] .= '/';
		exec('mkdir "'.$output['path'].$_POST['addpath'].@$_POST['name'].'"');
		break;
	case 'move':
		$_POST['target_path'] = path_url_decode(@$_POST['target_path']);
		exec('mv "'.$output['path'].$_POST['addpath'].'" "'.$output['path'].$_POST['target_path'].'"');
		break;
	case 'copy':
		$_POST['target_path'] = path_url_decode(@$_POST['target_path']);
		if (is_dir($output['path'].$_POST['addpath'])) {
			exec('cp -r "'.$output['path'].$_POST['addpath'].'" "'.$output['path'].$_POST['target_path'].'/"');
		}else{			
			exec('cp "'.$output['path'].$_POST['addpath'].'" "'.$output['path'].$_POST['target_path'].'/"');
		}
		break;
	case 'delete':
		exec('rm -rf "'.$output['path'].$_POST['addpath'].'"');
		break;
}