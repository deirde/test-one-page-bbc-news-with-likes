$(document).ready(function() {
    
    /**
     * Show / hide submit button.
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
        $('#modal').fadeOut('fast');
        $('.row').removeClass('on-hold');
    }
    
    /**
     * Ajax.
     */
    $('#votes').submit(function(e) {
        
        e.preventDefault();
        
        var post = $(this).serialize();
        
        if (post && $('#modal:hidden')) {
                
            // The parent element on hold.
            $('.row').addClass('on-hold');
            
            $.ajax({
                method: 'POST',
                data: {
                    action: 'xhrGetVotes',
                    data: post
                }
            }).done(function(data) {
                
                if (data) {
                    
                    var obj = jQuery.parseJSON(data);
                    $.each(obj, function(key, val) {
                        var item = $('#items-wrapper').find('.votes[id=\'' + key + '\']');
                        item.html(parseInt(item.html()) + 1);
                    });
                    
                    // Reset all the checkboxes.
                    $('input[type=checkbox]').prop('checked', false);
                    
                    // Shows the modal.
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
                
            });
            
        }
        
    });
    
});