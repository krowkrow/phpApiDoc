(function(){
    // 内容中标签点击展示
    $("h2[id^='title'],h3[id^=post],h3[id^=get]").on('click',function(){
        if($(this).next().css('display')=='none'){
            if ($(this)[0].tagName == 'H2'){
                $(this).children().css({transform:'rotate(45deg)',transition:'0.5s'})
            }
            $(this).next().css('display','block');
        }else{
            if ($(this)[0].tagName == 'H2') {
                $(this).children().css({ transform: 'rotate(-45deg)', transition: '0.5s' })
            }
            $(this).next().css('display', 'none');            
        }
    })
    // 目录中标签点击展示
    $("a[id^=title]").on('click',function(){
        if ($(this).next().css('display')=='none'){
            $(this).next().css('display', 'block')
        }else{
            $(this).next().css('display', 'none')
        }
    })
    // 目录中标签点击展开置顶
    $("a[id^=post],a[id^=get]").on('click',function(){
        var id = $(this).parent().parent().prev().attr('id');
        if ($('h2#'+id).next().css('display') == "none"){
            $('h2#' + id).next().css('display', "block")
        }
        var nowid = $(this).attr('id')
        if($('h3#'+nowid).next().css('display') == "none"){
            $('h3#' + nowid).next().css('display','block')
        }
        var top = $('h3#' + nowid).offset().top;
        if(top!=0){
            $('body,html').animate({scrollTop:top},100)
        }
    })
    //干净json折叠
    $("p[class^='appJson']").on('click',function(){
        if($(this).next().css('display')=='none'){
            if ($(this)[0].tagName == 'H2'){
                $(this).children().css({transform:'rotate(45deg)',transition:'0.5s'})
            }
            $(this).next().css('display','block');
        }else{
            if ($(this)[0].tagName == 'H2') {
                $(this).children().css({ transform: 'rotate(-45deg)', transition: '0.5s' })
            }
            $(this).next().css('display', 'none');            
        }
    })
})()