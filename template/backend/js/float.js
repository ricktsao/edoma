	$(window).load(function(){
		//浮動區塊-遊戲下載 & 帳號申請
		var $win = $(window),
			$ad = $('#float_ad').css('opacity', 0).show(),	
			_width = $ad.width(),
			_height = $ad.height(),
			_diffY = 20, _diffX = 20,	
			_moveSpeed = 800;	
		
		$ad.css({
			//top: _diffY,	
			//left: $win.width() - _width - _diffX,
			opacity: 1
		});
		
		// 幫網頁加上 scroll 及 resize 事件
		$win.bind('scroll resize', function(){
			var $this = $(this);
			
			$ad.stop().animate({
				top: $this.scrollTop() ,	// 往上
				//left: $this.scrollLeft() + $this.width() - _width - _diffX
			}, _moveSpeed);
		}).scroll();	

		//TOP 按鈕
		$("#backTop").click(function(){
	        jQuery("html,body").animate({
	            scrollTop:0
	        },1000);
	    });

	    $(window).scroll(function() {
	        if ( $(this).scrollTop() > 300){
	            $('#backTop').fadeIn("fast");
	        } else {
	            $('#backTop').stop().fadeOut("fast");
	        }
	    });
		
	});