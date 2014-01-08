;
var ops={
    init:function(){
        this.showChinese();
        this.showEnglish();
    },
    showChinese:function(){
        $(".lesson-control .ch-menu").click(function(){
            if($(this).hasClass("cur") && $(".lesson-control .en-menu").hasClass("cur")){
                $(this).removeClass("cur");
                $(".article-content .chinese").hide();
                $("#chineseTitle").hide();
            }else{
                $(this).addClass("cur");
                $(".article-content .chinese").show();
                $("#chineseTitle").show();
            }

        });
    },
    showEnglish:function(){
        $(".lesson-control .en-menu").click(function(){
            if($(this).hasClass("cur") && $(".lesson-control .ch-menu").hasClass("cur")){
                $(this).removeClass("cur");
                $(".article-content .english").hide();
                $("#englishTitle").hide();
            }else{
                $(this).addClass("cur");
                $(".article-content .english").show();
                $("#englishTitle").show();
            }

        });
    }
}
$(document).ready(function(){
    ops.init();
});
