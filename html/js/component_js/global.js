/*
*   Converts a value to have a front fill of a character
*   @param {String} n - Value to get padding
*   @param {Integer} width - Length to make n
*   @param {String} z - Character to pad n
*   @return {String} - Padding string of width: width and of characters z
*/
function zeroFill(n, width = 3, z = 0) {
    return (String(z).repeat(width) + String(n)).slice(String(n).length)
}

/*
*   Removes all non numerics except '-' and '.'
*   @param {String}
*   @return {String}
*/
function removeNonNumeric(data) {
    return data.replace(/([^-\d.\d]+)/g,'');
}

/*
*   Create a clone of object with null values
*   @param {Array}
*   @return {Array}
*/
function prependNullObject(arr) {
    var obj = Object.assign({}, arr[0]);
    for (var prop in obj) {
        obj[prop] = null;
    }
    return [obj].concat(arr);
}

/*
	Creates promisable floating modal call.
*/
function createFloatingModal(title, content, contentIsFile = false, minWidth = null) {
    var createdModal = new FloatingModal({
        content: content,
        contentIsFile: contentIsFile,
        contentTitle: title,
        minWidth: minWidth,
    });

    return createdModal;
}

/*
    Name: getLimitations
    @param: Array filters
    Return: JSON Obj
*/
function getLimitations(filters = []) {
    return $.ajax({
        url: '/slimapi/v1/limitations',
        async: false,
        data: filters,
        success: function(data) {

        },
        error: function(data) {
            console.log("Error: 0003 - Failed to retrieve limitations.");
        }
    });
}

/*
    Name: getCookie
    @param: String name
    Return: Array
*/
function getCookie(name) {
    // Break the cookie field values to be each array
    var cookieBreaks = document.cookie.split(';');

    // Split the breaks into field value pairs.
    for(var i = 0; i < cookieBreaks.length; i++) {
        var fieldBreaks = cookieBreaks[i].split('=');
        if(fieldBreaks[0].trim() == name) {
            return JSON.parse(fieldBreaks[1]);
        }
    }

    return null;
}

/*
    Name: setCookie
    @param: Array scopes
    Return: N/A
*/
function setScopesCookie(scopes) {
    var perms = [];
    var defaultSelect = [];

    for(var i = 0; i < scopes.length; i++) {
        perms.push(scopes[i]);
    }

    if(scopes.length == 2) {
        defaultSelect.push("SiteDesign");
    } else {
        defaultSelect.push(scopes[0].split(":")[1]);
    }

    document.cookie = "permissions=" + JSON.stringify(perms) + "; expires=Thu, 18 Dec 2033 12:00:00 UTC";
    document.cookie ="defaultCommunitiesView=" + JSON.stringify(defaultSelect) + "; expires=Thu, 18 Dec 2033 12:00:00 UTC;";
}

/*
    Name: isEqual
    @param: Array value
    @param: Array other
    Return: boolen
*/
function isEqual(value, other) {
    // Get the value type
    var type = Object.prototype.toString.call(value);

    // If the two objects are not the same type, return false
    if (type !== Object.prototype.toString.call(other)) return false;

    // If items are not an object or array, return false
    if (['[object Array]', '[object Object]'].indexOf(type) < 0) return false;

    // Compare the length of the length of the two items
    var valueLen = type === '[object Array]' ? value.length : Object.keys(value).length;
    var otherLen = type === '[object Array]' ? other.length : Object.keys(other).length;
    if (valueLen !== otherLen) return false;

    // Compare two items
    var compare = function (item1, item2) {

        // Get the object type
        var itemType = Object.prototype.toString.call(item1);

        // If an object or array, compare recursively
        if (['[object Array]', '[object Object]'].indexOf(itemType) >= 0) {
            if (!isEqual(item1, item2)) return false;
        }

        // Otherwise, do a simple comparison
        else {

            // If the two items are not the same type, return false
            if (itemType !== Object.prototype.toString.call(item2)) return false;

            // Else if it's a function, convert to a string and compare
            // Otherwise, just compare
            if (itemType === '[object Function]') {
                if (item1.toString() !== item2.toString()) return false;
            } else {
                if (item1 !== item2) return false;
            }

        }
    };

    // Compare properties
    if (type === '[object Array]') {
        for (var i = 0; i < valueLen; i++) {
            if (compare(value[i], other[i]) === false) return false;
        }
    } else {
        for (var key in value) {
            if (value.hasOwnProperty(key)) {
                if (compare(value[key], other[key]) === false) return false;
            }
        }
    }

    // If nothing failed, return true
    return true;

};

function decodeToken(token) {
    const payload = token.split('.')[1];
    return JSON.parse(atob(payload));
}

function closeIFrame(isDenied = false){
    if(!isDenied) {
        setupAjax();
    
        $("iframe").remove();
    } else {
        $.getJSON("js/config.json", function(json) {
            window.location.href = json.oauth.logout + "?access_denied=1";
        });
    }
}

function createIframe() {
    $.getJSON("js/config.json", function(json) {
        if(window.sessionStorage.state === null || window.sessionStorage.state === undefined) {
            window.sessionStorage.state = Math.random().toString(36).substring(40);
        }

        var iframe = document.createElement('iframe');
        iframe.style.display = "none";
        iframe.src = json.oauth.server +
        'response_type=' + json.oauth.responseType +
        'client_id=' + json.oauth.clientId + 
        'redirect_uri=' + json.oauth.redirectUrl + 
        'scope=' + json.oauth.scope +
        'state=' + window.sessionStorage.state;
        document.body.appendChild(iframe);
    });
}

function setupAjax() {
    $.ajaxSetup({
        headers:{
            nolog:'1',
            "Authorization": "Bearer " + window.sessionStorage.accessToken,
            username: window.sessionStorage.username
        },
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        type: 'GET',
        beforeSend: function(){
            return window.ajaxEnabled;
        }
    });
}

$.getJSON("/js/config.json", function(json) {
    if(((window.sessionStorage.expiresDate - Math.floor(new Date().getTime() / 1000)) < 60) || (window.sessionStorage.expiresDate === null || window.sessionStorage.expiresDate === undefined)){

        if(window.sessionStorage.state === null || window.sessionStorage.state === undefined) {
            window.sessionStorage.state = Math.random().toString(36).substring(40);
        }

        window.location.href = json.oauth.server +
        'response_type=' + json.oauth.responseType + 
        'client_id=' + json.oauth.clientId + 
        'redirect_uri=' + json.oauth.redirectUrl + 
        'scope=' + json.oauth.scope +
        'state=' + window.sessionStorage.state;
    } else {
        createIframe();
    }
});

setInterval( function() {
    createIframe();
}, 420000);

setupAjax();