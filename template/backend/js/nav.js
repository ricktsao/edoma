$(function(){

        /* 主選單 */
        $('#nav').mouseenter(function() {
              $(this).parent().find('#levelBg').stop(true, true).slideDown(400, "easeOutExpo");
              $(this).find('#level').css({display: "block"});
          }).mouseleave(function() {
              $(this).parent().find('#levelBg').stop(true, true).slideUp(600, "easeOutExpo");
              $(this).find('#level').css({display: "none"});
          });

    });
