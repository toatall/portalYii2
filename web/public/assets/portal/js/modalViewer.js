/* 
 * Работа с модальными окнами
 */

var urlHelper = {

    
    /**
    * Проверка наличия параметра в текущей ссылке
    * @param name
    * @returns
    */
    getURLParameter: function(name) {
        "use strict";
        var url = new RegExp('([?|&|#]' + name + '=)(.*)').exec(location.hash) || ["", ""];
        if (url.length >= 2 && url[2] != undefined) {
            return decodeURIComponent(url[2].replace(/\+/g, '%20'));
        }
        return null;
    },

    /**
    * Изменение параметра ссылки
    * @param param параметр
    * @param value значение параметра
    * @returns
    */
    changeUrlParam: function (param, value)
    {
        "use strict";
        var currentURL = window.location.href + '&';
        var change = new RegExp('(' + param + ')=(.*)&', 'g');
        var newURL = currentURL.replace(change, '$1=' + value + '&');
        if (this.getURLParameter(param) !== null) {
            try {
                window.history.replaceState('', '', newURL.slice(0, -1));
            } catch (e) {
                window.console.log(e);
            }
        } else {
            window.history.replaceState('', '', currentURL.slice(0, -1) + '#' + param + '=' + value);
        }
    },
    
    
    /**
    * Удаление параметра со значение из ссылки
    * @param url ссылка
    * @returns
    * @uses removeParametrDialog()
    */
    removeURLParameter: function(url) {
        "use strict";
        //prefer to use l.search if you have a location/link object
        var urlparts = url.split('#');
        if (urlparts.length >= 2) {

            return urlparts[0];
        } 
        else {
            return url;
        }
    }
    
};


var modalViewer = {

    modalId: '#modal-dialog-main',
    modalTitle: '#modal-dialog-title',
    modalHeader: '#modal-dialog-header',
    modalBody: '#modal-dialog-body',
    
    templateError: '<div class="alert alert-danger">{text}</div>',
    templateAnimation: '<div class="fa-3x" style="color: Dodgerblue;"><i class="fas fa-circle-notch fa-spin"></i></div>',
    
    urlHelper: urlHelper,
    
    /**
     * Инициализация
     * @returns {undefined}
     */
    init: function() {
        "use strict";
        this.createModalDialog();
        this.openModalFromUrl();
        this.bind();
    },
    
    /**
     * Привязать все
     * @returns {undefined}
     */
    bind: function() {
        "use strict";
        this.bindLinks();
        this.bindModalForm();         
    },
    
    /**
     * Создание формы диалога
     * @returns {undefined}
     */
    createModalDialog: function() {
        "use strict";
        $('body').append(
            '<div class="modal fade" id="modal-dialog-main" role="dialog" data-backdrop="static" data-result="false">'
                + '<div class="modal-dialog" role="document" style="max-width: 95%;">'
                    + '<div class="modal-content">'
                        +'<div class="modal-header">'                            
                            + '<div id="modal-dialog-header"></div>'
                            + '<h2 class="modal-title" id="modal-dialog-title">Load title...</h2>'
                            + '<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'
                        + '</div>'
                        + '<div class="modal-body" id="modal-dialog-body">'
                            + 'Load body...'
                        + '</div>'
                        + '<div class="modal-footer">'
                            + '<button type="button" data-dismiss="modal" class="btn btn-secondary">Закрыть</button>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>'
        );
    },
    
    /**
     * Если в адресе есть параметр `w`,
     * то автоматически запускается диалог с адресом, 
     * указанным в параметре
     * @returns {undefined}
     */
    openModalFromUrl: function() {
        "use strict";
        var urlDialog = this.urlHelper.getURLParameter('w');
        if (urlDialog != null) {
            this.showModal(urlDialog);
        }
    },
    
    /**
     * Привязка к ссылкам
     * @returns {undefined}
     */
    bindLinks: function() {
        "use strict";
        var $this = this;
        $(document).off('click', '.mv-link');
        $(document).on('click', '.mv-link', function() {
            $this.showModal($(this).attr('href'));
            return false;
        });
    },
    
    /**
     * Открыть модальное окно и выполнить запрос по ссылке
     * @returns {undefined}
     */
    showModal: function(url) {
        "use strict";
                
        $(this).trigger('onModalShow', [{ link: url }]);
        this.requestJson(this.modalTitle, this.modalHeader, this.modalBody, url);
        $(this.modalId).modal('show');

        // удаление ссылки `w` после закрытия диалога
        var $this = this;
        $(this.modalId).on('hide.bs.modal', function() {               
            window.history.replaceState({}, document.title, $this.urlHelper.removeURLParameter(window.location.href, 'w'));
        });

        // добавление ссылки `w` после открытия дилога
        this.urlHelper.changeUrlParam('w', url);

        $(this).on('onRequestJsonDone', function() {
            $this.hideElements($this.modalBody);
            $this.bindModalForm();
        });
    },

    /**
     * Открытие модального окна (вручную)
     * Например, если требуется дополнительно передать массив данных
     * @param url
     * @param changeUrl
     * @param requestMethod
     * @param requestData
     */
    showModalManual: function(url, changeUrl, requestMethod, requestData, processData) {
        'use strict';
        requestMethod = requestMethod || 'get';
        requestData = requestData || null;
        $(this).trigger('onModalShow', [{ link: url }]);
        this.requestJson(this.modalTitle, this.modalHeader, this.modalBody, url, requestMethod, requestData, processData);
        $(this.modalId).modal('show');

        var $this = this;
        if (changeUrl) {
            // удаление ссылки `w` после закрытия диалога
            $(this.modalId).on('hide.bs.modal', function() {
                window.history.replaceState({}, document.title, $this.urlHelper.removeURLParameter(window.location.href, 'w'));
            });

            // добавление ссылки `w` после открытия дилога
            this.urlHelper.changeUrlParam('w', url);
        }

        $(this).on('onRequestJsonDone', function() {
            $this.hideElements($this.modalBody);
            $this.bindModalForm();
        });
    },

    closeModal: function() {
        'use strict';
        $(this.modalId).modal('hide');
    },
    
    /**
     * Скрытие элементов, которые не требуется показыать в диалоге
     * (например, заголовок)
     * @param {type} modal
     * @returns {undefined}
     */
    hideElements: function(modal) {
        "use strict";
        $(modal).find('.mv-hide').hide();
    },
    
    
    /**
     * Сохранение формы через ajax
     * @returns {undefined}
     */    
    bindModalForm: function() {
        "use strict";
        var $this = this;
        $(document).off('submit', '.mv-form');
        $(document).on('submit', '.mv-form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var formData = new FormData(this);
            
            $($this).on('onRequestJsonDone', function(event, data) {
                $this.autoCloseModal(data);
            });
            $this.requestJson($this.modalTitle, this.modalHeader, $this.modalBody, url, 'post', formData);

            return false;
        });
    },

    bindModalFormManual: function(form_container) {
        "use strict";
        var $this = this;
        $(form_container).submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var formData = new FormData(this);

            $($this).on('onRequestJsonDone', function(event, data) {
                $this.autoCloseModal(data);
            });
            $this.requestJson($this.modalTitle, this.modalHeader, $this.modalBody, url, 'post', formData);

            return false;
        });
    },
    
    /**
     * Автоматическое закрытие диалога, если в ответ возвращается текст `OK`
     * @param {type} data
     * @returns {undefined}
     */
    autoCloseModal: function(data) {
        "use strict";
        var textData = '';
        if (data.hasOwnProperty('content')) {
            textData = data.content;
        } 
        else {
            textData = data;
        }
        
        if (textData == 'OK' || textData == 'ok') {
            $(this.modalId).modal('hide');
            $(this).trigger('onRequestJsonAfterAutoCloseModal');
        }        
    },

    modalUpate: function(url, method, data) {
        "use strict";
        this.requestJson(this.modalTitle, this.modalHeader, this.modalBody, url, method, data);
    },
    
    /**
     * Запрос к серверу
     * @param {type} containerTitleId
     * @param {type} containerBodyId
     * @param {type} url
     * @param {type} method
     * @param {type} data
     * @returns {undefined}
     */
    requestJson: function(containerTitleId, containerHeaderId, containerBodyId, url, method, data, processData) {
        "use strict";
        method = method || 'get';
        data = data || null;
        processData = processData || false;

        // анимация
        $(containerTitleId).html(this.templateAnimation);
        $(containerBodyId).html(this.templateAnimation);
        
        // ajax
        var $this = this;
        $.ajax({
            type: method,
            url: url,
            dataType: 'json',
            processData: processData,
            contentType: false,
            data: data
        })
        .done(function (data) {
            
            // заголовок header
            if (data.hasOwnProperty('header')) {
                $(containerHeaderId).html(data.header);
            } 
            else {
                $(containerHeaderId).html('');
            }

            // заголовок title
            if (data.hasOwnProperty('title')) {
                $(containerTitleId).html(data.title);
            } 
            else {
                $(containerTitleId).html('');
            }

            // контент
            if (data.hasOwnProperty('content')) {
                $(containerBodyId).html(data.content);
            }
            else
            {
                $(containerBodyId).html(data);                
            }
            
            $($this).trigger('onRequestJsonDone', [ data ]);
        })
        .fail(function (jqXHR) {
            var template = $this.templateError;
            template = template.replace('{text}', jqXHR.status + ' ' + jqXHR.statusText);
            $(containerTitleId).html('Ошибка');
            $(containerBodyId).html(template);            
            
            $($this).trigger('onRequestJsonFail', [{ jqXHR: jqXHR }]);
        });
    }
}

$(document).ready(function() {
    "use strict";
    modalViewer.init();
});