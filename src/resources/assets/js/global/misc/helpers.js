window._ENV = {
    MIX_APP_NAME: process.env.MIX_APP_NAME,
    MIX_HOST: process.env.MIX_HOST,
    MIX_API_BASE_URL: process.env.MIX_API_BASE_URL,
    MIX_CDN_URL: process.env.MIX_CDN_URL,
    MIX_ADMIN_WEB_PREFIX: process.env.MIX_ADMIN_WEB_PREFIX,
    MIX_ADMIN_WEB_API_PREFIX: process.env.MIX_ADMIN_WEB_API_PREFIX
};
export default {
    toJson(json, res = {}) {
        if (this.isJson(json)) {
            return json;
        } else if (typeof json == 'string') {
            try {
                return JSON.parse(json);
            } catch (e) {
            }
        }
        return res;
    },
    isJson(json) {
        return typeof json == 'object' && json != null;
    },
    getSubdomain() {
        let host = window.location.host;
        return ((host.replace(this.getEnv('MIX_HOST'), "")).replace(/:[0-9]*/g, '')).replace(/^[.]+|[.]+$/gm, '');
    },
    inArray(needle, haystack) {
        for (var i = 0; i < haystack.length; i++) {
            if (haystack[i] == needle) {
                return true;
            }
        }
        return false;
    },
    isPath(needle, haystack) {
        needle = (needle + '').trim().toLowerCase();
        for (var i = 0; i < haystack.length; i++) {
            let patt = new RegExp(haystack[i].trim().toLowerCase(), "g");
            if (patt.test(needle)) {
                return true;
            }
        }
        return false;
    },
    isAssoc(arr) {
        if(typeof arr == 'array' || typeof arr == 'object'){
            for (var i = 0; i < Object.keys(arr).length; i++) {
                if (typeof arr[i] == 'undefined') {
                    return 1;
                }
            }
        }
        return 0;
    },
    capitalize(string) {
        return string.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    },
    getHost(subdomain) {
        if (subdomain) {
            return location.protocol + '//' + subdomain + '.' + this.getEnv('MIX_HOST');
        }
        return location.protocol + '//' + this.getEnv('MIX_HOST');
    },
    getApiURL() {
        return this.getEnv('MIX_API_URL') + '/' + this.getEnv('MIX_API_VERSION');
    },
    getEnv(value, _default) {
        _default = (typeof _default == 'undefined') ? '' : _default;
        return (typeof _ENV[value] == 'undefined') ? _default : _ENV[value];
    },
    blockCard($elem) {
        $($elem).block({
            message: '<div class="ft-refresh-cw icon-spin font-medium-4"></div>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.9,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    },
    unblockCard($elem, time) {
        time = (time != 'undefined') ? time : 0;
        setTimeout(function () {
            $($elem).unblock()
        }, time);
    },
    block() {
        $.LoadingOverlay("show");
    },
    unblock(time) {
        time = (time != 'undefined') ? time : 0;
        setTimeout(function () {
            $.LoadingOverlay("hide");
        }, time);
    },
    blockPage() {
        $('#page-spinner').css('display', 'block');
    },
    unBlockPage(time) {
        time = (time != 'undefined') ? time : 0;
        setTimeout(function () {
            $('#page-spinner').css('display', 'none');
        }, time);
    },
    guidGenerator() {
        var S4 = function () {
            return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        };
        return (S4() + S4() + S4() + S4() + S4() + Date.now());
    },
    unknownError(error) {
        return {
            'status': 0,
            'errors': [
                'Connection Error!'
            ],
            'data': error
        };
    },
    generateRandomString(length = 8)
    {
        var characters = 'A0B1C2D3E4F5G6H78I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6';
        var str = '';
        var characters_length = characters.length - 1;
        for (var i = 0; i < length; i++) {
            str += characters[this.getRandomInt(0, characters_length)];
        }
        return str;
    },
    getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    getValue(obj, key, _default) {
        _default = (typeof _default == 'undefined') ? '' : _default;
        return key.split(".").reduce(function (o, x) {
            return (typeof o[x] == "undefined" || o[x] === null || o[x] == null) ? _default : o[x];
        }, obj);
    },
    has(obj, key) {
        return key.split(".").every(function (x) {
            if (typeof obj != "object" || obj === null || !x in obj)
                return false;
            obj = obj[x];
            return true;
        });
    },
    getAvatarByUID(uid, v_img) {
        const cdn = this.getEnv('MIX_CDN_URL', '');
        uid = (typeof uid == 'undefined') ? '' : uid;
        v_img = (typeof v_img == 'undefined') ? Date.now() : v_img;
        if (uid != '') {
            return cdn + '/users/' + uid + '/avatar/avatar.png?v=' + v_img;
        } else {
            let user = Auth.getUser();
            if (user['avatar'] == '/fe/images/avatar_incognito.png') {
                return '//' + this.getEnv('MIX_HOST', '') + user['avatar'];
            } else {
                return cdn + user['avatar'];
            }
        }
    },
    getAvatarByURL(url) {
        url = (typeof url == 'undefined' || url == '') ? '/fe/images/avatar_incognito.png' : url;
        if (url.indexOf('/fe/images/avatar') !== -1) {
            return '//' + this.getEnv('MIX_HOST', '') + url;
        } else {
            return this.getEnv('MIX_CDN_URL', '') + url;
        }
    },
    getLogoByURL(url) {
        url = (typeof url == 'undefined' || url == '') ? '/fe/images/logo_blue.png' : url;
        if (url.indexOf('/fe/images') !== -1) {
            return '//' + this.getEnv('MIX_HOST', '') + url;
        } else {
            return this.getEnv('MIX_CDN_URL', '') + url;
        }
    },
    getAvatar() {
        const cdn = this.getEnv('MIX_CDN_URL', '');
        let user = Auth.getUser();
        let url = user.avatar;
        if (user.avatar.indexOf('/fe/images/avatar') !== -1) {
            return '//' + this.getEnv('MIX_HOST', '') + url;
        } else {
            return cdn + url;
        }
    },
    validateEmail(email) {
        email = email.trim().toLowerCase();
        var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g;
        var result = emailRegExp.test(email);
        return result;
    },
    validateCVV(cvv) {
        var cvvRegExp = /^[0-9]{3,4}$/g;
        var result = cvvRegExp.test(cvv);
        return result;
    },
    validateExpDate(ExpDate) {
        var ExpdateRegExp = /^(0[1-9]|1[0-2])\/?(([0-9]{4}|[0-9]{2})$)/g;
        var result = ExpdateRegExp.test(ExpDate);
        return result;
    },
    validateExpDateMMYYYY(ExpDate) {
        var ExpdateRegExp = /^((0[1-9])|(1[0-2]))\/(\d{4})$/g;
        var result = ExpdateRegExp.test(ExpDate);
        if (result == true) {
            var dt = new Date();
            var year = ExpDate.split('/')[1];
            if (year >= dt.getFullYear()) {
                return true;
            }
        }
        return false;
    },
    validateCCNumber(ccn) {
        var valid = false;
        ccn.validateCreditCard(function (result) {
            valid = result.valid;
        });
        return valid;
    },
    setTitle(title, with_app_name) {
        with_app_name = (typeof with_app_name == 'undefined') ? true : with_app_name;
        if (with_app_name) {
            title = (this.getEnv('MIX_APP_NAME', '') + ' - ' + title).replace(/^\s+[-]\s+|\s+$/gm, '');
        }
        if (window.jQuery) {
            $('#html-head-title').html(title);
        } else if (document) {
            document.getElementById("html-head-title").innerHTML = title;
        }
    },
    bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
    sizeToBytes(val, unit) {
        var sizes = {
            'Bytes': 0,
            'KB': 1,
            'MB': 2,
            'GB': 3,
            'TB': 4
        };
        return Math.round(val * Math.pow(1024, sizes[unit]), 2);
    },
    isReservedDomain(domain) {
        return this.inArray(domain, this.getEnv('MIX_RESERVED_DOMAINS', ['app', 'api', 'www', 'mail', 'cdn']));
    },
    setCookie(cname, cvalue, expdays, cpath, cdomain, csecure) {
        var expires = '';
        var szCookieText = escape(cname) + '=' + escape(cvalue);
        if (expdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            expires = "EXPIRES=" + d.toUTCString();
        }
        szCookieText += expires;
        szCookieText += (cpath ? '; PATH=' + cpath : '');
        szCookieText += (cdomain ? '; DOMAIN=' + cdomain : '');
        szCookieText += (csecure ? '; SECURE' : '');
        document.cookie = szCookieText;
    },
    getCookie(cname) {
        var cvalue = 'undefined';
        if (document.cookie) //only if exists
        {
            var arr = document.cookie.split((escape(cname) + '='));
            if (2 <= arr.length) {
                var arr2 = arr[1].split(';');
                cvalue = unescape(arr2[0]);
            }
        }
        return cvalue;
    },
    checkCookie(name) {
        return getCookie(name)
    },
    deleteCookie(cname) {
        var tmp = getCookie(cname);
        if (tmp) {
            setCookie(cname, tmp, (new Date(1)));
        }
    },
    gotoTop() {
        return $("html, body").animate({
            scrollTop: 0
        }, "slow");
    },
    numberFormat(number, decimal) {
        decimal = (typeof decimal != 'undefined') ? decimal : 2;
        return (number * 1).toFixed(decimal);
    },
    jsonToFormData(data) {
        const formData = new FormData();
        this.buildFormData(formData, data);
        return formData;
    },
    buildFormData(formData, data, parentKey) {
        var self = this;
        if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
            Object.keys(data).forEach(key => {
                self.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
            });
        } else {
            const value = data == null ? '' : data;
            formData.append(parentKey, value);
        }
    },
    getQueryParameters(){
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);
        let urlParams = {};
        while (match = search.exec(query)){
            if(typeof urlParams[decode(match[1])] != 'undefined'){
                if(Array.isArray(urlParams[decode(match[1])])){
                    urlParams[decode(match[1])].push(decode(match[2]));
                }else{
                    urlParams[decode(match[1])] = [urlParams[decode(match[1])], decode(match[2])];
                }
            }else{
                urlParams[decode(match[1])] = decode(match[2]);
            }
        }
        return urlParams;
    },
    getQueryParameter(name, res){
        if (!res) res = '';
        if (!name) return res;
        return this.getValue(this.getQueryParameters(),name,res);
    }
}