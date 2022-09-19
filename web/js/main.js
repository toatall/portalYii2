/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).on('pjax:send', function(event) {
    $('#div-loader').addClass('is-active');
});

$(document).on('pjax:complete', function() {
    $('#div-loader').removeClass('is-active');
});

$(document).on('pjax:error', function(xhr, textStatus, error, options) {
    if (textStatus.responseText != "" && error != 'abort') {
        bs4Toast.error('Ошибка', textStatus.responseText, { delay: 15000 });       
    }
    console.log(error);
    return false;
});

$(document).ready(function () {
    $('[data-bs-toggle="popover"]')
        .popover({trigger: 'hover'});
});


// для главного меню отображение пунктов подменю при наведении
$('.dropdown-menu a.dropdown-toggle').on('hover', function(e) {
    if (!$(this).next().hasClass('show')) {
        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    }
    var subMenu = $(this).next(".dropdown-menu");
    subMenu.toggleClass('show');
    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
        $('.dropdown-submenu .show').removeClass("show");
    });
    return false;
});