jQuery(document).ready(function($){
		$('.ky_saved_articles_link a').click(function(e){
			var action = $(this).data('action');
				$.ajax({
					type: 'POST',
					url: ky_sav_art.url,
					data:{
						security: ky_sav_art.nonce,
						srt: action,
						action: 'ky_saved_articles_record',
						postId:ky_sav_art.postId
					},
					beforeSend: function(){
						$('.ky_saved_articles_link a').fadeOut(200,
									function(){
										$('.ky_saved_articles_link .ky_saved_articles_hide').fadeIn();
									});
					},
					success: function(result){
						$('.ky_saved_articles_link .ky_saved_articles_hide').fadeOut(200,
									function(){
										$('.ky_saved_articles_link').html(result);
									});
					},
					error: function(){
						alert('error')
					}
				});
				e.preventDefault();
		});
});