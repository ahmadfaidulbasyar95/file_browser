<?php 
$_POST['addpath'] = path_url_decode(@$_POST['addpath']);
if($_POST['addpath']) $_POST['addpath'] .= '/';
switch (@$_POST['tool_act']) {
	case 'create_folder':
		exec('mkdir "'.$output['path'].$_POST['addpath'].@$_POST['name'].'"');
		break;
	case 'move':
		$_POST['target_path'] = path_url_decode(@$_POST['target_path']);
		exec('mv "'.$output['path'].$_POST['addpath'].'" "'.$output['path'].$_POST['target_path'].'"');
		break;
	case 'delete':
		exec('rm -rf "'.$output['path'].$_POST['addpath'].'"');
		break;
}