jQuery(document).ready(function($){
	$('.ky_saved_articles_dashboard_delete').click(function(e){
		 	e.preventDefault();
			 if (!confirm("Do you want to delete entries?")) return false;
				var post =$(this).data('post');
				action =$(this).data('action');
				parent = $(this).parent(),
				loader = parent.next(),
				li = $(this).closest('li');
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data:{
							security:ky_sav_art.nonce,
							srt: action,
							action: 'ky_saved_articles_record',
							postId: post,
						},
						beforeSend: function(){
								parent.fadeOut(200, function(){
											loader.fadeIn();
								});
						},
						success: function(result){
							loader.fadeOut(200, function(){
								li.html(result);
							});
						},
						error: function(){
							alert('error');
						}
					});
	});
	$('.ky_saved_articles_dashboard_delete_all').click(function(e){
				e.preventDefault();
				if (!confirm("Do you want to delete all entries?")) return false;
				var $this = $(this),
				parent = $this.parent(), 
				list = $this.parent();
				li = $(this).closest('li');
				console.log(li);
							$.ajax({
								type: 'POST',
								url: ajaxurl,
								data:{
									security:ky_sav_art.nonce,
									action: 'ky_saved_articles_dashboard_delete_all',
									},
								beforeSend: function(){
										$this.fadeOut(200, function(){
													$('.ky_saved_articles_hide').fadeIn();
										});
								},
								success: function(res){
									$('.ky_saved_articles_hide').fadeOut(200, function(){
										if(res =='list is empty'){
											li.fadeOut()
											parent.html(res);
										}else{
												$this.fadeIn();
										}
									});
								},
								error: function(){
									alert('error');
								}
							});
		});	
});