$(document).ready(function() {
    
    $('li.item').mouseover(function() {
        $(this).find('.hidden').addClass('visible').removeClass('hidden');
    }).mouseout(function() {
        $(this).find('.visible').removeClass('visible').addClass('hidden');
    });
    
    $('#likes').submit(function(e) {
        
        e.preventDefault();
        
        $.ajax({
            method: 'POST',
            data: {
                action: 'xhrGetLikes',
                data: $(this).serialize()
            }
        }).done(function(data) {
            var obj = jQuery.parseJSON(data);
            
            $.each(obj, function(key, val) {
                var item = $('#items-wrapper').find('.likes[id=\'' + key + '\']');
                item.html(parseInt(item.html()) + 1);
            });

            $('input[type=checkbox]').prop('checked', false);
            
            $('#modal').fadeIn('fast');
            $('#modal-close').click(function() {
                $('#modal').fadeOut('fast');
            });
            
        });
        
    });
    
});