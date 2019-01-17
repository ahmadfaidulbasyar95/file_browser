$(document).ready(function() {
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
			$('.go_open_dir_item').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
			$.ajax({
				url: PATH,
				type: 'POST',
				dataType: 'html',
				data: {open_dir_item: $(this).attr('href')},
			})
			.done(function(out) {
				$('.go_open_dir_item').html(out);
			});
		}
	});
	$('.go_open_dir').on('click', 'a', function(event) {
		event.preventDefault();
		if ($(this).find('span').length) 
		{
			var el_result = $(this).parents('.list-group-item').next('.list-group');
			if ($(this).find('span.fa-plus').length) 
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
			$(this).find('span').toggleClass('fa-plus').toggleClass('fa-minus');
		}else 
		{
			window.history.replaceState("URL", "Title", PATH + $(this).attr('href'));
			$('.go_open_dir_item').html('<h3 class="text-center"><i class="fa fa-spinner fa-spin"></i></h3>');
			$.ajax({
				url: PATH,
				type: 'POST',
				dataType: 'html',
				data: {open_dir_item: $(this).attr('href')},
			})
			.done(function(out) {
				$('.go_open_dir_item').html(out);
			});
			$('.go_open_dir .list-group-item.active').removeClass('active');
			$(this).parents('.list-group-item').addClass('active');
		}
	});
});
