; (function ($) {
    const backend = {
        init: () => {
            backend.select();
        },
        select: () => {
            new SlimSelect({
                select: '#multiple',
                placeholder: 'Select Accessible User'
            })
        }
    };

    backend.init();

})(jQuery);