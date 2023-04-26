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

// $(document).on('pjax:error', function(xhr, textStatus, error, options) {
//     if (textStatus.responseText != undefined && textStatus.responseText != "" && error != 'abort' && error != 'timeout') {
//         // bs4Toast.error('Ошибка', textStatus.responseText, { delay: 15000 });       
//         alert(textStatus.responseText);
//         console.log(xhr);
//     }
//     console.log(error);
//     return false;
// });

// для главного меню отображение пунктов подменю при наведении
// $('.dropdown-menu a.dropdown-toggle').on('hover', function(e) {
//     if (!$(this).next().hasClass('show')) {
//         $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
//     }
//     var subMenu = $(this).next(".dropdown-menu");
//     subMenu.toggleClass('show');
//     $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
//         $('.dropdown-submenu .show').removeClass("show");
//     });
//     return false;
// });

// $(document).on('click', 'a[href="33"]', () => false)