$(document).ready(function() {
    
    /**
     * The post has been submitted, switchs on the modal.
     */
     if (flash == 'ok') {
         modalOn();
     }
    
    /**
     * Show and hide the submit btns.
     */
    $('li.item').mouseover(function() {
        $(this).find('.not-visible').addClass('visible').removeClass('hidden');
    }).mouseout(function() {
        $(this).find('.visible').removeClass('visible').addClass('hidden');
    });
    
    /**
     * Switchs off the modal.
     */      
    function modalOff() {
        
        $('#modal').fadeOut('fast', function() {
            $('#wrapper').removeClass('on-hold');
        });
        
    }
    
    /**
     * Switchs on the modal.
     */ 
    function modalOn() {
        
        // The parent element on hold.
        $('#wrapper').addClass('on-hold');
         
        $('#modal').fadeIn('fast', function() {
            
            // Closes the modal on click outside.
            $('html').click(function (e) {
                if (e.target.id != 'modal') {
                    modalOff();
                }
            });
            
            // Closes the modal on close button click.
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
        
        var post = $('#votes').serialize();
        
        if (post && $('#modal:hidden')) {
            
            // The parent element on hold.
            $('#wrapper').addClass('on-hold'); // @TODO. It's fired only the first time, weird behavior, to investigate.
            
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
                        var item = $('#items-wrapper').find('.votes[id=\'' + val + '\']');
                        item.html(parseInt(item.html()) + 1);
                    });
                    
                    // Resets the checkboxes.
                    $('input[type=checkbox]').prop('checked', false);
                    
                    // Opens the modal.
                    modalOn();
                    
                }
                
            });
            
        }
        
    });
    
});