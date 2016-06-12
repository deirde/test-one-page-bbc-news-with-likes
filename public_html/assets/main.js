$(document).ready(function() {
    
    /**
     * If the flash variable exists opens the modal.
     */
     if (flash == 'ok') {
        modalOn();
     }
    
    /**
     * Shows and hides the submit btns.
     */
    $('li.item').mouseover(function() {
        $(this).find('.not-visible').addClass('visible').removeClass('hidden');
    }).mouseout(function() {
        $(this).find('.visible').removeClass('visible').addClass('hidden');
    });
    
    /**
     * Opens the modal.
     */
     function modalOff() {
        $('#modal').fadeOut('fast');
        $('#wrapper').removeClass('on-hold');
     } 
    
    /**
     * Closes the modal.
     */ 
    function modalOn() {
        
        $('#wrapper').addClass('on-hold');
        
        // Resets the checkboxes.
        $('input[type=checkbox]').prop('checked', false);
         
        $('#modal').fadeIn('fast', function(e) {
            
            // On click outside.
            $('html').click(function (e) {
                if ($('#modal').is(":visible")
                    && e.target.id != 'modal') {
                    modalOff();
                }
            });
            
            // On button click.
            $('#modal-close').click(function() {
                modalOff();
            });
            
        });
        
    }
    
    /**
     * The ajax logic.
     */
    $('#votes .xhr').click(function(e) {
        
        e.preventDefault();
        
        if ((post = $('#votes').serialize())
            && $('#modal:hidden')) {
            
            // The parent element is put immediately on hold.
            $('#wrapper').addClass('on-hold');
            
            $.ajax({
                method: 'POST',
                data: {
                    action: 'xhr',
                    data: post
                }
            }).done(function(data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    $.each(obj, function(key, val) {
                        var item = $('#items-wrapper')
                            .find('.votes[id=\'' + val + '\']');
                        item.html(parseInt(item.html()) + 1);
                    });
                    modalOn(); // It opens the modal.
                }
            });
            
        }
        
    });
    
});