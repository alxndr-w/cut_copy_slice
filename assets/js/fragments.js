var cut_copy_slice_fragments = {
    init : function()
    {
        console.log('fragments');
        this.addToggleButtons();
    },

    addToggleButtons : function()
    {
        $(document).on({
            'change.cut_copy_slice' : function(e)
            {
                cut_copy_slice_fragments.toggle(this);
            }
        }, '.cut_copy_slice--setting input[type="checkbox"][name*="[active]"]');
    },

    toggle : function(el)
    {
        var on = $(el).is(':checked'),
            id = $(el).attr('id');

        if(on)
        {
            $('.' + id).removeClass('is--hidden');
        }
        else
        {
            $('.' + id).addClass('is--hidden');
        }
    }
}

$(document).on('ready.cut_copy_slice', $.proxy(cut_copy_slice_fragments.init, cut_copy_slice_fragments));
$(document).on('rex:ready', function(e){
    $('.cut_copy_slice--setting input[type="checkbox"][name*="[active]"]').each(function(i, el){
        cut_copy_slice_fragments.toggle(el);
    });
});
