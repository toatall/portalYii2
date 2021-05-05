/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).on('pjax:send', function() {
    $('#div-loader').addClass('is-active');
});


$(document).on('pjax:complete', function() {
    $('#div-loader').removeClass('is-active');
});

$(document).ready(function () {
    $('[data-toggle="popover"]')
        .popover({trigger: 'hover'})
        .popover();
    $('[data-toggle="tooltip"]').popover();
});