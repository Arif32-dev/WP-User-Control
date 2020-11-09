; (function ($) {
    const backend = {
        init: () => {
            backend.events();
            backend.select();
        },

        events: () => {
        },
        select: () => {
            new SlimSelect({
                select: '#multiple'
            })
        }
    };

    document.addEventListener('DOMContentLoaded', backend.init);

})(jQuery);