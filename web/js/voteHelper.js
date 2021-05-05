(function ($) {
    "use strict";
    $.portalVoteHelper = {
        $: $,

        init: function () {
            this.bind();
            this.bindBtnVote();
        },

        /**
         * <div class="portal-vote" data-href=""></div>
         */
        bind: function () {
            $('.portal-vote').each(function () {
                var $this = $(this);
                $this.html('<i class="fas fa-circle-notch fa-spin"></i>');
                $.get($this.data('href'))
                    .done(function (data) {
                        $this.html(data);
                    })
                    .fail(function (jqXHR) {
                        $this.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
                    });
            });
        },

        /**
         * <a href="/vote/voted?id=2" class="link-voted"></a>
         */
        bindBtnVote: function () {
            $('.link-voted')
                .off('click')
                .on('click', function() {
                    var $btn = $(this);
                    var $cont = $(this).children('span');
                    $cont.html('<i class="fas fa-circle-notch fa-spin"></i>');
                    $btn.prop('disabled', true);
                    $.ajax({
                        type: 'post',
                        url: $btn.data('href')
                    })
                    .done(function (data) {
                        //$cont.html('<span style="background:white; padding:5px; border-radius:10px;"><i class="fas fa-check-circle text-success"></i></span>');
                        $cont.html(data);
                    })
                    .fail(function (jqXHR) {
                        $cont.html('<span class="text-danger">' + jqXHR.responseText + '</span>');
                    });
                    return false;
                });
        }
    };
})(jQuery);
