$(document).ready(function() {
	$('body').on('click', '.form_search .btn', function(event) {
		event.preventDefault();
		var el_input = $('.form_search .form-group');
		el_input.slideToggle(200);
		el_input.toggleClass('active');
		if (el_input.hasClass('active')) {
			setTimeout(function() {
				el_input.find('input').focus();
			}, 500);
		}else
		if (el_input.find('input').val()) {
			el_input.parents('form').submit();
		}
	});
	$('body').on('submit', '.form_search', function(event) {
		$('.go_open_dir_item').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
		$.ajax({
			url: PATH,
			type: 'POST',
			dataType: 'html',
			data: {open_dir_item: $(this).attr('action') , keyword: $(this).find('input').val()},
		})
		.done(function(out) {
			$('.go_open_dir_item').html(out);
			tool_box_show();
		});
	});
	$('body').on('click', '.file_download_all_now', function(event) {
		event.preventDefault();
		$(this).parents('.table').find('a').each(function(index, el) {
			window.open($(this).attr('href'),'_blank');
		});
	});
	$('.go_open_dir_item').on('click', 'a', function(event) {
		event.preventDefault();
		if ($(this).hasClass('file')) 
		{
			$('#modal-file_detail').modal('show');
			$('#modal-file_detail .modal-body').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
			$.ajax({
				url: PATH,
				type: 'POST',
				dataType: 'html',
				data: {open_item_detail: $(this).attr('href')},
			})
			.done(function(out) {
				$('#modal-file_detail .modal-body').html(out);
			});
		}else 
		if ($(this).hasClass('file_download_all')) 
		{
			$('#modal-file_detail').modal('show');
			$('#modal-file_detail .modal-body').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
			$.ajax({
				url: PATH,
				type: 'POST',
				dataType: 'html',
				data: {open_dir_item_download: $(this).attr('href')},
			})
			.done(function(out) {
				$('#modal-file_detail .modal-body').html(out);
			});
		}else 
		if ($(this).hasClass('external')) 
		{
			window.open($(this).attr('href'),'_blank');
		}else
		{
			window.history.replaceState("URL", "Title", PATH + $(this).attr('href'));
			data_refresh($(this).attr('href'));
		}
	});
	$('.go_open_dir').on('click', 'a', function(event) {
		event.preventDefault();
		if ($(this).find('span').length) 
		{
			var el_result = $(this).parents('.list-group-item').next('.list-group');
			if ($(this).find('span.fa-caret-right').length) 
			{
				el_result.slideDown(300);
				el_result.html('<div class="list-group-item"><h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3></div>');
				$.ajax({
					url: PATH,
					type: 'POST',
					dataType: 'html',
					data: {open_dir: $(this).attr('href')},
				})
				.done(function(out) {
					el_result.html(out);
				});
			}else {
				el_result.slideUp(300);
			}
			$(this).find('span').toggleClass('fa-caret-down').toggleClass('fa-caret-right');
		}else 
		{
			window.history.replaceState("URL", "Title", PATH + $(this).attr('href'));
			data_refresh($(this).attr('href'));
			$('.go_open_dir .list-group-item.active').removeClass('active');
			$(this).parents('.list-group-item').addClass('active');
		}
	});
	$('.go_open_dir_item').on('contextmenu', '.selection', function(event) {
		event.preventDefault();
		$(this).toggleClass('selected');
		tool_box_show();
	});
	$('.tool_box_open a').on('click', function(event) {
		event.preventDefault();
		$('.tool_box').slideDown(200);
		$('.tool_box_open').hide();
	});
	$('#modal-tool-create_folder form').on('submit', function(event) {
		event.preventDefault();
		var el = $(this);
		el.hide();
		$('#modal-tool-create_folder .loader').show();
		$.ajax({
			url: PATH,
			type: 'POST',
			dataType: 'html',
			data: {tool_act: 'create_folder',name: el.find('input').val(),addpath:$('#data_addpath').html()},
		})
		.done(function(out) {
			el.show();
			$('#modal-tool-create_folder .loader').hide();
			$('#modal-tool-create_folder .close').trigger('click');
			el.find('input').val('');
			data_refresh($('#data_addpath').html());
		});
	});
	$('[href="#tool-clear_selected"]').on('click', function(event) {
		event.preventDefault();
		$('.go_open_dir_item .selection.selected').removeClass('selected');
		tool_box_show();
	});
	$('[href="#modal-tool-rename"]').on('click', function(event) {
		var x = '';
		$('.go_open_dir_item .selection.selected').each(function(index, el) {
			x += '<tr><td><div class="form-group"><label>'+$(this).html()+'</label><input type="text" class="form-control" value="'+$(this).html()+'" placeholder="'+$(this).html()+'" data-value="'+$(this).attr('href')+'" required="required" title="'+$(this).html()+'"></div></td></tr>';
		});
		$('#modal-tool-rename .list_wrapper').html(x);
	});
	$('#modal-tool-rename .rename_btn').on('click', function(event) {
		event.preventDefault();
		if (confirm('Rename ?')) {
			var rename_list = $('#modal-tool-rename .list_wrapper input').length;
			var rename_list_count = 0;
			$('#modal-tool-rename .list_wrapper input').each(function(index, el) {
				var rename_el = $(this);
				rename_el.parent('div').append('<span class="text-info">renaming...</span>');
				$.ajax({
					url: PATH,
					type: 'POST',
					dataType: 'html',
					data: {tool_act: 'move',addpath:rename_el.data('value'),target_path:$('#data_addpath').html()+'/'+rename_el.val()},
				})
				.done(function(out) {
					rename_el.parents('tr').remove();
					rename_list_count++;
					if (rename_list==rename_list_count) {
						$('#modal-tool-rename .close').trigger('click');
						data_refresh($('#data_addpath').html());
					}
				});
			});
		}
	});
	$('[href="#modal-tool-delete"]').on('click', function(event) {
		var x = '';
		$('.go_open_dir_item .selection.selected').each(function(index, el) {
			x += '<tr><td><div class="checkbox"><label><input checked type="checkbox" value="'+$(this).attr('href')+'"> '+$(this).html()+' </label></div></td></tr>';
		});
		$('#modal-tool-delete .list_wrapper').html(x);
	});
	$('#modal-tool-delete .delete_btn').on('click', function(event) {
		event.preventDefault();
		if (confirm('Delete selected ?')) {
			var del_list = $('#modal-tool-delete .list_wrapper [type="checkbox"]:checked').length;
			var del_list_count = 0;
			$('#modal-tool-delete .list_wrapper [type="checkbox"]:checked').each(function(index, el) {
				var del_el = $(this);
				del_el.parents('label').append('<span class="text-info">deleting...</span>');
				$.ajax({
					url: PATH,
					type: 'POST',
					dataType: 'html',
					data: {tool_act: 'delete',addpath:del_el.val()},
				})
				.done(function(out) {
					del_el.parents('tr').remove();
					del_list_count++;
					if (del_list==del_list_count) {
						$('#modal-tool-delete .close').trigger('click');
						data_refresh($('#data_addpath').html());
					}
				});
			});
		}
	});
});
function tool_box_show() {
	if ($('.go_open_dir_item .selection.selected').length) {
		$('.tool_box').slideDown(200);
		$('.tool_box_open').hide();
	}else{
		$('.tool_box').slideUp(200);
		$('.tool_box_open').show();
	}
}
function data_refresh(data_path) {
	if(!data_path) data_path = '/';
	$('.go_open_dir_item').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
	$.ajax({
		url: PATH,
		type: 'POST',
		dataType: 'html',
		data: {open_dir_item: data_path},
	})
	.done(function(out) {
		$('.go_open_dir_item').html(out);
		tool_box_show();
	});
}
