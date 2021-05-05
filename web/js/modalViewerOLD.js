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
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
    },

    /**
    * Изменение параметра ссылки
    * @param param параметр
    * @param value значение параметра
    * @returns
    */
    changeUrlParam: function (param, value)
    {
        let currentURL = window.location.href + '&';
        let change = new RegExp('(' + param + ')=(.*)&', 'g');
        let newURL = currentURL.replace(change, '$1=' + value + '&');
        if (this.getURLParameter(param) !== null) {
            try {
                window.history.replaceState('', '', newURL.slice(0, -1));
            } catch (e) {
                console.log(e);
            }
        } else {
            let currURL = window.location.href;
            if (currURL.indexOf("?") !== -1) {
                window.history.replaceState('', '', currentURL.slice(0, -1) + '&' + param + '=' + value);
            } else {
                window.history.replaceState('', '', currentURL.slice(0, -1) + '?' + param + '=' + value);
            }
        }
    },
    
    
    /**
    * Удаление параметра со значение из ссылки
    * @param url ссылка
    * @param parameter параметр, который необходимо удалить
    * @returns
    * @uses removeParametrDialog()
    */
    removeURLParameter: function(url, parameter) {
        //prefer to use l.search if you have a location/link object
        var urlparts = url.split('?');
        if (urlparts.length >= 2) {

           var prefix = encodeURIComponent(parameter) + '=';
           var pars = urlparts[1].split(/[&;]/g);

           //reverse iteration as may be destructive
           for (var i = pars.length; i-- > 0; ) {
               //idiom for string.startsWith
               if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                   pars.splice(i, 1);
               }
           }

           url = urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
           return url;
           
        } 
        else {
            return url;
        }
    }
    
};


var modalViewer = {
    
    modalId: '#modal-dialog-main',
    modalTitle: '#modal-dialog-title',
    modalBody: '#modal-dialog-body',
    
    templateError: '<div class="alert alert-danger">{text}</div>',
    templateAnimation: '<div class="fa-3x" style="color: Dodgerblue;"><i class="fas fa-circle-notch fa-spin"></i></div>',
    
    urlHelper: urlHelper,
    
    /**
     * Инициализация
     * @returns {undefined}
     */
    init: function() {        
        this.createModalDialog();   
        this.openModalFromUrl();
        this.bind();
    },
    
    /**
     * Привязать все
     * @returns {undefined}
     */
    bind: function() {             
        this.bindLinks();
        this.bindModalForm();         
    },
    
    /**
     * Создание формы диалога
     * @returns {undefined}
     */
    createModalDialog: function() {
        $('body').append(`
            <div class="modal fade" id="modal-dialog-main" role="dialog" data-backdrop="static" data-result="false" data-dialog="">
                <div class="modal-dialog modal-dialog-large modal-dialog-super-large" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                            <h2 id="modal-dialog-title" style="font-weight: bold">Load title...</h2>
                        </div>
                        <div class="modal-body" id="modal-dialog-body">
                            Load body...
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    },
    
    /**
     * Если в адресе есть параметр `w`,
     * то автоматически запускается диалог с адресом, 
     * указанным в параметре
     * @returns {undefined}
     */
    openModalFromUrl: function() {
        urlDialog = this.urlHelper.getURLParameter('w');
        if (urlDialog != null) {
            this.showModal(urlDialog, true);
        }
    },
    
    /**
     * Привязка к ссылкам
     * @returns {undefined}
     */
    bindLinks: function() {
        let $this = this;
        $('.mv-link').on('click', function() {            
            $this.showModal($(this).attr('href'), !$(this).hasClass('mv-no-change-url'));
            return false;
        });
    },
    
    /**
     * Открыть модальное окно и выполнить запрос по ссылке
     * @returns {undefined}
     */
    showModal: function(url, changeUrl) {
        $(this).trigger('onModalShow', [{ link: url }]);
        this.requestJson(this.modalTitle, this.modalBody, url);
        $(this.modalId).modal('show');

        if (changeUrl) {
            // удаление ссылки `w` после закрытия диалога
            let $this = this;
            $(this.modalId).on('hide.bs.modal', function() {               
                window.history.replaceState({}, document.title, $this.urlHelper.removeURLParameter(window.location.href, 'w'));
            });

            // добавление ссылки `w` после открытия дилога
            this.urlHelper.changeUrlParam('w', url);
        }

        $this = this;
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
    showModalManual: function(url, changeUrl, requestMethod, requestData) {
        'use strict';
        requestMethod = requestMethod || 'get';
        requestData = requestData || null;
        $(this).trigger('onModalShow', [{ link: url }]);
        this.requestJson(this.modalTitle, this.modalBody, url, requestMethod, requestData);
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
        $(modal).find('.mv-hide').hide();
    },
    
    
    /**
     * Сохранение формы через ajax
     * @returns {undefined}
     */    
    bindModalForm: function() {
        let $this = this;
        $('.mv-form').on('submit', function(e) {
            let url = $(this).attr('action');
            let formData = new FormData(this);

            $($this).on('onRequestJsonDone', function(event, data) {
                $this.autoCloseModal(data);
            });
            $this.requestJson($this.modalTitle, $this.modalBody, url, 'post', formData);
            e.preventDefault();
            return false;
        });
    },
    
    openUrl: function(url, method='get', formData=null) {
        if (method == undefined || method != 'get' || method != 'post') {
            method = 'get';
        }
        if (formData == undefined) {
            formData = null;
        }
        this.requestJson(this.modalTitle, this.modalBody, url, method, formData);
    },
    
    /**
     * Автоматическое закрытие диалога, если в ответ возвращается текст `OK`
     * @param {type} data
     * @returns {undefined}
     */
    autoCloseModal: function(data) {
        let textData = '';
        if (data.hasOwnProperty('content')) {
            textData = data.content;
        } 
        else {
            textData = data;
        }
        
        if (textData == 'OK' || textData == 'ok') {
            $(this.modalId).modal('hide');
            return true;
        }
        return false;
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
    requestJson: function(containerTitleId, containerBodyId, url, method = 'get', data = null) {
        
        // анимация
        $(containerTitleId).html(this.templateAnimation);
        $(containerBodyId).html(this.templateAnimation);
        
        // ajax
        let $this = this;
        $.ajax({
            type: method,
            url: url,
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data
        })
        .done(function (data) {
            
            // заголовок
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
            let template = $this.templateError;
            template = template.replace('{text}', jqXHR.status + ' ' + jqXHR.statusText);
            $(containerTitleId).html('Ошибка');
            $(containerBodyId).html(template);            
            
            $($this).trigger('onRequestJsonFail', [{ jqXHR: jqXHR }]);
        });
        
    },    
}

$(document).ready(function() {
    modalViewer.init();
});