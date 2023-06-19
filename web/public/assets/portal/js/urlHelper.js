
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