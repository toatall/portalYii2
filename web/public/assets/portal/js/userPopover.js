function bindUserPopover() {
    $('user').popover({
        html: true,
        trigger: "click",
        content: function() {
            return $(this).data('content');
        },
        template: '<div class="popover"></div>'
    })    
    .click(function(e) {
        $(this).popover('toggle');
    });    
}

bindUserPopover();

$(document).ajaxComplete(function() {
    bindUserPopover();
});