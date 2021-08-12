// тестирование
window.portalTest = (function ($) {
    "use strict";

    var pub = {

        containerSelector: '.test-container',

        init: function() {
            var $this = this;
            $(this.containerSelector).each(function () {
                $this.requestAjax($(this));
            });
        },

        requestAjax: function(container) {
            var url = container.data('url');
            container.html('<i class="fas fa-spin fa-spinner"></i>');
            $.get(url)
                .done(function (data) {
                    container.html(data);
                    $(window.portalTest).trigger('onRequestDone', [ data, container ]);
                })
                .fail(function (jqXHR) {
                    container.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
                });
        }
    };

    return pub;

})(window.jQuery);

window.portalTest.init();