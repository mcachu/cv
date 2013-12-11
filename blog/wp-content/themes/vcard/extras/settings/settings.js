jQuery(function($){
    $('.colorpicker-wrapper').each(function(){
        var $$ = $(this);

        var picker = $.farbtastic($$.find('.farbtastic-container').hide());
        
        picker.linkTo(function(color){
            $$.find('input').val(color);
            $$.find('.color-indicator').css('background', color);
        });

        picker.setColor($$.find('input').val());

        $$.find('input')
            .focus(function(){ $$.find('.farbtastic-container').show() })
            .blur(function(){ $$.find('.farbtastic-container').hide() });
    });
    
    // We're going to use jQuery to transform the settings page into a tabbed interface
    var $$ = $('form[action="options.php"]');
    var tabs = $('<h2></h2>').addClass('nav-tab-wrapper').prependTo($$);
    $$.find('h3').each(function(i, el){
        var h = $(el).hide();
        var a = $('<a href="#"></a>').addClass('nav-tab').html(h.html()).appendTo(tabs);
        if(i == 0) a.addClass('nav-tab-active');
        
        var table = h.next().hide();
        a.click(function(){
            $$.find('> table').hide();
            table.show();
            tabs.find('a').removeClass('nav-tab-active');
            a.addClass('nav-tab-active');
            return false;
        });
        
        if(i == 0) a.click();
    });
});