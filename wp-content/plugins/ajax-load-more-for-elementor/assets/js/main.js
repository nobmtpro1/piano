'use strict';
(function ($) {
	jQuery(window).on('elementor/frontend/init', function(){
		elementorFrontend.hooks.addAction('frontend/element_ready/pd-alm.default', function ($scope, $) {
			var elem = $scope.find('.wbel_pd_alm_wrapper');
			var load_more_text = $scope.find('.pd-alm-load-btn').data('load_more_text');
			var loading_more_text = $scope.find('.pd-alm-load-btn').data('loading_text');

			jQuery(window).on('resize', function(){
				var win_width = jQuery(this).innerWidth();
				if( win_width < 768 && win_width > 479 ){
					// pd_alm_item pd-alm-col-3
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-3');
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-1');
					jQuery(elem).find('.pd_alm_item').addClass('pd-alm-col-2');
				}else if( win_width < 480 ){
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-3');
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-2');
					jQuery(elem).find('.pd_alm_item').addClass('pd-alm-col-1');
				}else if( win_width > 767 ){
					jQuery(elem).find('.pd_alm_item').addClass('pd-alm-col-3');
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-1');
					jQuery(elem).find('.pd_alm_item').removeClass('pd-alm-col-2');
				}
			});

			jQuery(window).trigger('resize');

			jQuery('.pd_alm_loadmore_btn').on('click', function(e){
				e.preventDefault();
				var _this = jQuery(this);
				var args = jQuery(this).parents('.pd-alm-container').data('settings');
				var offset = jQuery(this).parents('.pd-alm-container').find('.pd_alm_item').length;
				args['offset'] = offset;
				jQuery.ajax({
					// fas fa-spinner fa-spin
					url: pd_alm_ajax_object.ajax_url,
					type: 'post',
					data : {
						'action': 'load_posts',
						'args' : args,
					},
					beforeSend: function(){
						// _this.find('.fas').addClass('fa-spin');
						_this.find('.pd_alm_load_icon').addClass('pd-alm-d-none');
						_this.find('.pd_alm_loading_icon').removeClass('pd-alm-d-none');
						_this.find('.pd-alm-load-more-text').text(loading_more_text);
					},
					complete: function(xhr,status){
						// console.log(xhr.responseText);
						if(_this.parents('.pd-alm-container').find('.pd_alm_reach_limit').length > 0){
							_this.parent('.pd-alm-load-btn').remove();
						}
						_this.find('.pd-alm-load-more-text').text(load_more_text);
						_this.find('.pd_alm_load_icon').removeClass('pd-alm-d-none');
						_this.find('.pd_alm_loading_icon').addClass('pd-alm-d-none');
						_this.parents('.pd-alm-container').find('.wbel_pd_alm_wrapper').append(xhr.responseText);
						if(_this.parents('.pd-alm-container').find('.wbel_pd_alm_wrapper').find('.pd_alm_reach_limit').length > 0){
							_this.parent('.pd-alm-load-btn').addClass('pd-alm-d-none');
						}
					}
				})
			});


		});
	});
})(jQuery);