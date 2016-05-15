    $(function(){

        /* FLASH 下載按鈕 */

        var flashvars = {};
        var params = {quality:'high', wmode:'transparent', loop:'true', align:'top', play:'true'};
        var attributes = {};
        swfobject.embedSWF("flash/download.swf", "flashID", "235", "112", "6.0.65", "/flash/expressInstall.swf", flashvars, params, attributes);

        /**********************************************/

        /* 門派介紹 */
        var $block = $('#idx_info'), 
            $link = $block.find('.link li'), 
            $showBox = $block.find('.showbox'), 
            _default = 0;
        
        $link.mouseover(function(){
            var $this = $(this);
            $showBox.html('<a href="'+$this.find('a').attr('href')+'"><img src="'+$this.find('a').attr('data-img')+'" /></a>');
        }).click(function(){
            return false;
        }).eq(_default).mouseover();

        /**********************************************/

        /* 首頁公告 */
        // 預設顯示第一個 Tab
        var _showTab = 0;
        var $defaultLi = $('ul.tabs li').eq(_showTab).addClass('active');
        $($defaultLi.find('a').attr('href')).siblings().hide();
        
        $('ul.tabs li').click(function() {
            var $this = $(this),
                _clickTab = $this.find('a').attr('href');
           
            $this.addClass('active').siblings('.active').removeClass('active');
            $(_clickTab).stop(false, true).show().siblings().hide();

            return false;
        }).find('a').focus(function(){
            this.blur();
        });

        /**********************************************/

        /* 輪播KV */
        var adHeight = 220,
            animateSpeed = 400,
            timer,
            speed = 3500;

        function showNext(){
            var $li = $('#adblock ul.link li'),
                no = $li.has('a.selected').index();
            
            no = (no + 1) % $li.length;
            
            $li.eq(no).children('a').mouseover();

            timer = setTimeout(showNext, speed);
        }

        $('#adblock ul.link li a').each(function(i){
            $(this).css('top', i * 44);
        }).hover(function(){
            var $this = $(this),
                no = $this.parent().index();
            
            $('#adblock ul.link li a.selected').removeClass('selected');
            $this.addClass('selected');
            
            $('#adblock ul.showbox').stop().animate({
                top: adHeight * no * -1
            }, animateSpeed);
            
            // 移除計時器
            clearTimeout(timer);
        }, function(){
            timer = setTimeout(showNext, speed);
        }).focus(function(){
            $(this).blur();
        }).eq(0).addClass('selected');
        
        $('#adblock ul.showbox li').hover(function(){
            clearTimeout(timer);
        }, function(){
            timer = setTimeout(showNext, speed);
        });
        
        timer = setTimeout(showNext, speed);

        /**********************************************/

        /* 精彩影片 */
        $(".video").colorbox({iframe:true, innerWidth:800, innerHeight:600});

        /**********************************************/

        /* 異業合作 */
        var $block = $('#partner'), 
            $ad = $block.find('.ad'),
            showIndex = 0,          // 預設要先顯示那一張
            fadeOutSpeed = 2000,    // 淡出的速度
            fadeInSpeed = 3000,     // 淡入的速度
            defaultZ = 10,          // 預設的 z-index
            isHover = false,
            timer, speed = 2000;    // 計時器及輪播切換的速度
        
        $ad.css({
            opacity: 0,
            zIndex: defaultZ - 1
        }).eq(showIndex).css({
            opacity: 1,
            zIndex: defaultZ
        });
        
        var str = '';
        for(var i=0;i<$ad.length;i++){
            str += '<a href="#">' + (i + 1) + '</a>';
        }
        var $controlA = $('#partner').append($('<div class="control">' + str + '</div>').css('zIndex', defaultZ + 1)).find('.control a');

        $controlA.click(function(){

            showIndex = $(this).text() * 1 - 1;
            
            $ad.eq(showIndex).stop().fadeTo(fadeInSpeed, 1, function(){
                if(!isHover){
                    // 啟動計時器
                    timer = setTimeout(autoClick, speed + fadeInSpeed);
                }
            }).css('zIndex', defaultZ).siblings('a').stop().fadeTo(fadeOutSpeed, 0).css('zIndex', defaultZ - 1);
            $(this).addClass('on').siblings().removeClass('on');

            return false;
        }).focus(function(){
            $(this).blur();
        }).eq(showIndex).addClass('on');

        $block.hover(function(){
            isHover = true;
            clearTimeout(timer);
        }, function(){
            isHover = false;
            timer = setTimeout(autoClick, speed);
        })
        
        // 自動點擊下一個
        function autoClick(){
            if(isHover) return;
            showIndex = (showIndex + 1) % $controlA.length;
            $controlA.eq(showIndex).click();
        }
        
        // 啟動計時器
        timer = setTimeout(autoClick, speed);

        /**********************************************/

        /* 新品專區 */

        var $silder = $('.slides'), 
            $li = $('ul li', $silder).not(':first').css('opacity', 0).end(),
            _titleHeight = 0, // 預設標題區塊 .caption 的位置
            $liBlock = $('ul li', $silder), 
            $caption = $('.caption', $(this)),
            _liHeight = $li.height(), 
            _captionHeight = $caption.outerHeight(true),
            _captionSpeed = 200,
            arrowWidth = 32 * -1, 
            arrowOpacity = 1, 
            $arrows = $('<a href="#" class="prev"></a><a href="#" class="next"></a>').css('opacity', arrowOpacity),
            $prev = $arrows.filter('.prev'), 
            $next = $arrows.filter('.next'), 
            fadeSpeed = 400;
        
        $silder.append($arrows).hover(function(){
            var no = $li.filter('.selected').index();
            arrowAction(no > 0 ? 0 : arrowWidth, no < $li.length - 1 ? 0 : arrowWidth);
        }, function(){
            arrowAction(arrowWidth, arrowWidth);
        });
        
        $arrows.click(function(){
            var $selected = $li.filter('.selected'),
                no = $selected.index();
            
            no = this.className=='prev' ? no - 1 : no + 1;
            $li.eq(no).stop().fadeTo(fadeSpeed + 100, 1, function(){
                arrowAction(no > 0 ? 0 : arrowWidth, no < $li.length - 1 ? 0 : arrowWidth);
            }).addClass('selected').siblings().fadeTo(fadeSpeed, 0).removeClass('selected');

            return false;
        }).focus(function(){
            this.blur();
        });
        
        function arrowAction(l, r){
            $prev.stop().animate({ left: l });
            $next.stop().animate({ right: r });
        }

        $liBlock.each(function(){
            $(this).hover(function(){
                $caption.stop().animate({
                    top: _liHeight - _captionHeight
                }, _captionSpeed);
            }, function(){
                $caption.stop().animate({
                    top: _liHeight - _titleHeight
                }, _captionSpeed);
            });
        });             

      
    });
