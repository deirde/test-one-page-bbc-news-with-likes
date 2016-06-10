$(document).ready(function() {
    
    /**
     * Show / hide submit button.
     */
    $('li.item').mouseover(function() {
        $(this).find('.hidden').addClass('visible').removeClass('hidden');
    }).mouseout(function() {
        $(this).find('.visible').removeClass('visible').addClass('hidden');
    });
    
    /**
     * Ajax.
     */
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
            
            // Reset all the checkboxes.
            $('input[type=checkbox]').prop('checked', false);
            
            // Shows the modal.
            $('#modal').fadeIn('fast');
            
            // Closes the modal.
            $('#modal-close').click(function() {
                $('#modal').fadeOut('fast');
            });
            
        });
        
    });
    
});