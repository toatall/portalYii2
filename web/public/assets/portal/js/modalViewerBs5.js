/* 
 * Работа с модальными окнами
 */


/**
 * Класс обеспечивающий взаимодействие с ссылками и адресной строкой
 * @author toatall
 */
class UrlHelper {
    
    /**
    * Проверка наличия параметра в текущей ссылке
    * @param name
    * @returns
    */
    static getURLParameter(name) {
        "use strict";
        let url = new RegExp('([?|&|#]' + name + '=)(.*)').exec(location.hash) || ["", ""];
        if (url.length >= 2 && url[2] != undefined) {
            return decodeURIComponent(url[2].replace(/\+/g, '%20'));
        }
        return null;
    }

    /**
    * Изменение параметра ссылки
    * @param param параметр
    * @param value значение параметра
    * @returns
    */
    static changeUrlParam (param, value) {
        "use strict";
        let currentURL = window.location.href + '&';
        let change = new RegExp('(' + param + ')=(.*)&', 'g');
        let newURL = currentURL.replace(change, '$1=' + value + '&');
        if (this.getURLParameter(param) !== null) {
            try {
                window.history.replaceState('', '', newURL.slice(0, -1));
            } catch (e) {
                window.console.log(e);
            }
        } else {
            window.history.replaceState('', '', currentURL.slice(0, -1) + '#' + param + '=' + value);
        }
    }
    
    
    /**
    * Удаление параметра со значение из ссылки
    * @param url ссылка
    * @returns
    * @uses removeParametrDialog()
    */
    static removeURLParameter (url) {
        "use strict";
        //prefer to use l.search if you have a location/link object
        let urlparts = url.split('#');
        if (urlparts.length >= 2) {
            return urlparts[0];
        } 
        else {
            return url;
        }
    }

    /**
     * Добавление параметров к ссылке (через ? или &)
     * @param {string} url 
     * @param {array} params 
     * @returns {string}
     */
    static addParam (url, params) {
        let paramDelimiter = '?';
        if (url.indexOf('?') >= 0) {
            paramDelimiter = '&';
        }
        for (const [key, value] of Object.entries(params)) {
            url = url + paramDelimiter + key + '=' + value;
            paramDelimiter = '&';
        }        
        return url;
    }

}

/**
 * @author toatall
 */
class ModalViewer {
    
    constructor(params = {}) {
        this.modalId = params.modelId ?? this.generateId();
        this.templateAnimation = params.templateAnimation ?? '<div class="fa-3x" style="color: Dodgerblue;"><i class="fas fa-circle-notch fa-spin"></i></div>';
        this.templateError = params.templateError ?? '<div class="alert alert-danger">{text}</div>';
        this.urlHelper = params.urlHelper ?? UrlHelper;
        this.enablePushState = params.enablePushState ?? true;
        this.bindFormSelector = params.bindFormSelector ?? null;
        this.init();
    }

    /**
     * Генерирование случайного имени
     * @returns string
     */
    generateId() {
        return 'modal-viewer-' + (Math.random() + 1).toString(36).substring(6);
    }
    
    /**
     * Инициализация
     * @returns {undefined}
     */
    init() {        
        this.createModalDialog();        
    }
    
    /**
     * Создание формы диалога
     * @returns {undefined}
     */
    createModalDialog() {
        let $this = this;

        $('body').append(
            '<div class="modal fade" id="' + this.modalId + '" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">'
                + '<div class="modal-dialog" style="max-width: 95%;">'
                    + '<div class="modal-content">'
                        +'<div class="modal-header">'                            
                            + '<div class="modal-dialog-header"></div>'
                            + '<h2 class="modal-title modal-dialog-title">Load title...</h2>'
                            + '<button class="btn-close me-2" type="button" data-bs-dismiss="modal" aria-label="Close"></button>'
                        + '</div>'
                        + '<div class="modal-body modal-dialog-body">'
                            + 'Load body...'
                        + '</div>'
                        + '<div class="modal-footer">'
                            + '<button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Закрыть</button>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>'
        );
        $('#' + this.modalId).data('mv', $(this));

        if (this.enablePushState) {
            // удаление ссылки `w` после закрытия диалога
            $('#' + this.modalId).on('hidden.bs.modal', function() {
                window.history.replaceState({}, document.title, $this.urlHelper.removeURLParameter(window.location.href, 'w'));                
            });                        
        }

        // очистка формы после закрытия
        $('#' + this.modalId).on('hidden.bs.modal', function() {
            $this.getElementTitle().html('');
            $this.getElementHeader().html('');
            $this.getElementBody().html('');
        });

        $(this).on('onRequestJsonDone', function() {
            $this.hideElements('#' + $this.modalId);           
            $this.bindForm();
        });
    }        
    
    /**
     * Если в адресе есть параметр `w`,
     * то автоматически запускается диалог с адресом, 
     * указанным в параметре
     * @returns {undefined}
     */
    openModalFromUrl() {
        let urlDialog = this.urlHelper.getURLParameter('w');
        if (urlDialog != null) {
            this.showModal(urlDialog);
        }
    }
       
    /**
     * Поиск текста залоговка модального окна
     * @returns any
     */
    getElementTitle() {
        return $('#' + this.modalId).find('.modal-dialog-title');        
    }

    /**
     * Поиск блока заголовка модального окна
     * @returns any
     */
    getElementHeader() {
        return $('#' + this.modalId).find('.modal-dialog-header');
    }

    /**
     * Поиск блока тела модального окна
     * @returns any
     */
    getElementBody() {
        return $('#' + this.modalId).find('.modal-dialog-body');
    }

    /**
     * Открыть модальное окно и выполнить запрос по ссылке
     * @returns {undefined}
     */
    showModal(url, requestMethod, requestData, processData) {
        requestMethod = requestMethod ?? 'get';
        requestData = requestData ?? null;

        $(this).trigger('onModalShow', [{ link: url }]);
        this.requestJson(this.getElementTitle(), this.getElementHeader(), this.getElementBody(), url, requestMethod, requestData, processData);
        $('#' + this.modalId).modal('show');

        if (this.enablePushState) {            
            // добавление ссылки `w` после открытия дилога
            this.urlHelper.changeUrlParam('w', url);
        }
    }


    /**
     * Закрытие модального окна
     */
    closeModal() {
        $('#' + this.modalId).modal('hide');
    }
    
    /**
     * Скрытие элементов, которые не требуется показыать в диалоге
     * (например, заголовок)
     * @param {type} modal
     * @returns {undefined}
     */
    hideElements(modal) {
        $(modal).find('.mv-hide').hide();
    }
    
    /**
     * Призязка к форме в окне диалога
     */
    bindForm() {
        let $this = this;
        const formSelector = this.bindFormSelector ?? '#' + this.modalId + ' form:not([data-pjax])'; 
       
        $(document).off('submit', formSelector);
        $(document).on('submit', formSelector, function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let formData = new FormData(this);
            let method = $(this).attr('method') ?? 'post';

            $($this).on('onRequestJsonDone', function(event, data) {
                $this.autoCloseModal(data);
            });
            $this.requestJson($this.getElementTitle(), $this.getElementHeader(), $this.getElementBody(), url, method, formData);
            return false;
        });
    }    

    /**
     * Автоматическое закрытие диалога, если в ответ возвращается текст `OK`
     * @param {type} data
     * @returns {undefined}
     */
    autoCloseModal(data) {       
        let textData = '';
        if (typeof data === 'object' && data !== null && data.hasOwnProperty('content')) {
            textData = data.content;
        } 
        else {
            textData = data;
        }
        
        if (textData.toUpperCase() == 'OK') {
            $('#' + this.modalId).modal('hide');
            $(this).trigger('onRequestJsonAfterAutoCloseModal');
        }
    }
    
    /**
     * Запрос к серверу
     * @param {type} containerTitleId
     * @param {type} containerBodyId
     * @param {type} url
     * @param {type} method
     * @param {type} data
     * @returns {undefined}
     */
    requestJson(containerTitleId, containerHeaderId, containerBodyId, url, method, data, processData) {
        method = method || 'get';
        data = data || null;
        processData = processData || false;
        let $this = this;        

        // анимация
        containerTitleId.html(this.templateAnimation);
        containerBodyId.html(this.templateAnimation);
        
        // ajax
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
                containerHeaderId.html(data.header);
            } 
            else {
                containerHeaderId.html('');
            }            

            // контент
            if (data.hasOwnProperty('content')) {
                containerBodyId.html(data.content);
            }
            else {
                containerBodyId.html(data);                
            }

            // заголовок title
            if (data.hasOwnProperty('title') && data.title) {                
                containerTitleId.html(data.title);
            } 
            else {                
                containerTitleId.html(containerBodyId.find('.title').text().trim());                                 
            }
            
            $($this).trigger('onRequestJsonDone', [ data ]);
        })
        .fail(function (jqXHR) {
            let template = $this.templateError;
            template = template.replace('{text}', jqXHR.status + ' ' + jqXHR.statusText);
            containerTitleId.html('Ошибка');
            containerBodyId.html(template); 
            console.log(jqXHR);
            
            $($this).trigger('onRequestJsonFail', [{ jqXHR: jqXHR }]);
        })
    }
}



(function($) {
    
    let modalViewerForLink = new ModalViewer();
    $(document).off('click', '.mv-link');
    $(document).on('click', '.mv-link', function(){
        let url = $(this).attr('href');        
        modalViewerForLink.showModal(url);
        return false;
    });

} (jQuery));

// если страница была обновлена и в адресной строке сохранился путь для диалогового окна
$(function() {
    
    const urlW = UrlHelper.getURLParameter('w');
    if (urlW != null) {
        (new ModalViewer()).showModal(urlW);
    }

});