var KoolReport = KoolReport || {};
KoolReport.dashboard = KoolReport.dashboard || {
    security: {
        csrf: null,
        debugMode: false,
    },
    exception: {
        unexpectedResponse: function (content) {
            //Delegate to theme to show
            KoolReport.dashboard.theme.unexpectedResponse(content);
        },
        errorMessage: function (content) {
            //Delegate to theme to show
            KoolReport.dashboard.theme.errorMessage(content);
        }
    },
    downloadFile:function(code){
        var url = window.location.href.replace(window.location.hash, "");
        if (url.indexOf("?")<0) {
            url+="?rdc="+code;
        } else {
            url+="&rdc="+code;
        }
        window.location.href = url;
    },
    contact: function (route, action, params, state) {
        var data = {
            kdr: {
                route: route,
                action: action,
                params: params
            }
        };

        //Create encode string
        if(params) {
            data.kdr.encodedParams = base64_encode(JSON.stringify(params));
        }

        if (state) {
            data.state = base64_encode(JSON.stringify(state));
        }

        if (KoolReport.dashboard.security.csrf) {
            data[KoolReport.dashboard.security.csrf.name] = KoolReport.dashboard.security.csrf.token;
        }
        $.ajax({
            url:window.location.href.replace(window.location.hash, ""),
            method: "POST",
            data: data,
            async: true,
            dataType: "json",
        }).fail(function (xhr) {
            KoolReport.dashboard.contactFail(xhr);
        }).done(function (response) {
            KoolReport.dashboard.contactDone(response);
        });
    },
    contactFail:function(xhr)
    {
        if (KoolReport.dashboard.security.debugMode === true) {
            if (xhr.responseText.indexOf("<error-message>") > -1) {
                KoolReport.dashboard.exception.errorMessage(xhr.responseText);
            } else {
                KoolReport.dashboard.exception.unexpectedResponse(xhr.responseText);
            }
            setTimeout(function () {
                showLoader(false);
                Ladda.stopAll();
            }, 500);
        }
    },
    contactDone:function(response)
    {
        for (var id in response.panels) {
            switch (response.panels[id][1]) {
                case null:
                case "fade":
                    $('ajaxpanel#ajp' + id + '> div').fadeOut("fast", function () {
                        var aid = $(this).parent().attr("id").substr(3);
                        $('ajaxpanel#ajp' + aid).html("<div style='visibiliy:hidden'>" + (response.panels[aid][0]!=null?response.panels[aid][0]:"") + "</div>");
                        $('ajaxpanel#ajp' + aid + '> div')
                            .css("visibility", "visible")
                            .css("display", "none")
                            .fadeIn();
                    });
                    break;
                case "none":
                default:
                    $('ajaxpanel#ajp' + id).html("<div>" + (response.panels[id][0]!=null?response.panels[id][0]:"") + "</div>");
                    break;
            }
        }
        response.scripts.forEach(function (script) {
            var func = new Function(script);
            func();
        });
        setTimeout(function () {
            showLoader(false);
            Ladda.stopAll();
        }, 500);
    },
    loginPage: {
        login: function (event) {
            event.preventDefault();
            showLoader();
            KoolReport.dashboard.contact("App/Login", "login", {
                username: $("#loginPage #username").val(),
                password: $("#loginPage #password").val(),
                continueKDR:$("#loginPage #continueKDR").val()
            });
            return false;
        },
        logout: function () {
            showLoader();
            KoolReport.dashboard.contact("App/Login", "logout");
            removeKDR();
        }
    },
    headerTitle: function (title) {
        document.title = title;
    },
    navigate:function(route, params = null, forceUrl = true, state = null){
        //Route to page or dashboard
        showLoader(true);
        if (forceUrl === true) {
            saveCurrentKDRState(
                KoolReport.dashboard.dboard.getFullName(),
                KoolReport.dashboard.dboard.state
            );
            setKDR(route,params);
        }

        if(params==null) {
            params = {};
        }
        params['currentRoute'] = KoolReport.dashboard.dboard.getFullName();
        KoolReport.dashboard.contact(route, "navigate", params, state);
        KoolReport.dashboard.theme.selectMenuOnUserNavigate(route);
    },
    app:{
        action:function(action, params) {
            KoolReport.dashboard.contact(
                "App",
                action,
                params,
                KoolReport.dashboard.dboard.state,
            );
        },
        changeLanguage:function(name) {
            KoolReport.dashboard.app.action("changeLanguage",{
                currentRoute:KoolReport.dashboard.dboard.getFullName(),
                name:name
            });
            showLoader();
        }
    },
    page: {
        name:null,
        navMove: function (a) {
            KoolReport.dashboard.page.loadDashboard($(a).attr("data-name").replace("App/"+KoolReport.dashboard.page.name+"/",""));
            KoolReport.dashboard.theme.closeMenuOnMobile();
        },
        loadDashboard: function (name, params, forceUrl = true, state = null) {
            var route = "App/"+KoolReport.dashboard.page.name+"/"+name;
            KoolReport.dashboard.navigate(route,params,forceUrl,state);
        },
        breadcrumb: function (data) {
            KoolReport.dashboard.theme.breadcrumb(data);
        },
        selectMenuItem: function (route) {
            //Delegate to theme
            KoolReport.dashboard.theme.selectMenuItem(route);
        },
        action: function (action, params) {
            KoolReport.dashboard.contact("App/"+KoolReport.dashboard.page.name,
                action,
                params,
                KoolReport.dashboard.dboard.state
            );
        },
        start: function (name) {
            KoolReport.dashboard.page.name = name;
        },
        end:function() {
            KoolReport.dashboard.theme.pageInit();
        }
    },
    dboard: {
        name: null,
        state: null,
        label: null,
        start: function (name, label) {
            KoolReport.dashboard.dboard.name = name;
            var state = {};
            state[name] = {};
            KoolReport.dashboard.dboard.state = state;
            KoolReport.dashboard.dboard.setLabelInBreadscrumb(label);
            //To overwrite the label set by main in first load
            setTimeout(function(){
                KoolReport.dashboard.dboard.setLabelInBreadscrumb(label);
            },20);
            window.scrollTo(0, 0);
            //Minic resize
            mimicResize();
            if(window.history.state==null) {
                setKDR(KoolReport.dashboard.dboard.getFullName(),null);    
            }
        },
        setState: function (dashboardState) {
            KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()] = dashboardState;
        },
        saveState: function (key, value) {
            KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()][key] = value;
        },
        getName: function () {
            return KoolReport.dashboard.dboard.name;
        },
        getFullName: function () {
            return "App/"+KoolReport.dashboard.page.name+(KoolReport.dashboard.dboard.name!=""?"/"+KoolReport.dashboard.dboard.name:"");
        },
        action: function(action,params) {
            KoolReport.dashboard.contact(
                KoolReport.dashboard.dboard.getFullName(),
                action,
                params,
                KoolReport.dashboard.dboard.state
            );
        },
        widgetSaveState:function (name, key, value) {
            var dashboardState = KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()];
            var widgetState = {};
            if(dashboardState.hasOwnProperty(name)) {
                widgetState = dashboardState[name];
            }
            widgetState[key] = value;
            dashboardState[name] = widgetState;
            KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()] = dashboardState;
        },
        widgetRemoveState(name,key) {
            var dashboardState = KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()];
            var widgetState = {};
            if(dashboardState.hasOwnProperty(name)) {
                widgetState = dashboardState[name];
            }
            if(widgetState.hasOwnProperty(key)) {
                delete widgetState[key];
            }
            dashboardState[name] = widgetState;
            KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()] = dashboardState;
        },
        widgetGetState:function(name) {
            var dashboardState = KoolReport.dashboard.dboard.state[KoolReport.dashboard.dboard.getName()];
            var widgetState = null;
            if(dashboardState.hasOwnProperty(name)) {
                widgetState = dashboardState[name];
            }
            return widgetState;
        },
        widgetAction: function (widgetName, action, params) {
            //Add state to command
            KoolReport.dashboard.contact(
                KoolReport.dashboard.dboard.getFullName() + "/" + widgetName,
                action,
                params,
                KoolReport.dashboard.dboard.state
            );
        },
        widgetShowDetail(widgetName,params) {
            $("ajaxpanel#ajp"+widgetName+"detail").remove();
            $("ajaxpanel#ajp"+widgetName+">div").append("<ajaxpanel id='ajp"+widgetName+"detail'><div></div></ajaxpanel>");
            KoolReport.dashboard.dboard.widgetAction(widgetName,"detail",params);
        },
        custom:{
            submit: function (action,params) {
                KoolReport.dashboard.contact(
                    "App/"+KoolReport.dashboard.page.name+"/" + KoolReport.dashboard.dboard.getName(),
                    action,
                    params,
                    KoolReport.dashboard.dboard.state
                );
            },    
            initForms:function() {
                $('dashboard[name='+KoolReport.dashboard.dboard.getName()+'] form[method=ajax]').submit(function(e){
                    var formData = $(this).serializeArray();
                    var params = {};
                    var action = $(this).attr("action");
                    formData.forEach(function(item){
                        params[item.name] = item.value;
                    });
                    KoolReport.dashboard.dboard.custom.submit(action,params);
                    showLoader();
                    e.preventDefault();
                });
            }
        },
        setLabelInBreadscrumb: function (label) {
            KoolReport.dashboard.theme.setLabelInBreadscrumb(label);
        }
    }
};

//Perform any action of dashboard
function action(action,params) {
    if(!action) {
        return;
    }
    if(!params) {
        params = {};
    }
    KoolReport.dashboard.dboard.custom.submit(action,params);
}

//Short functions
function saveState(key, value) {
    KoolReport.dashboard.dboard.saveState(key, value);
}

function updateWidget(widgetName) {
    KoolReport.dashboard.dboard.widgetAction(widgetName, null, null);
}

function autoUpdate(dashboardName, widgetName, milliseconds) {
    setTimeout(function () {
        if(dashboardName===KoolReport.dashboard.dboard.name) {
            updateWidget(widgetName);
        }
    },
    milliseconds);
}

function appAction(action, params) {
    KoolReport.dashboard.app.action(action, params);
}

function pageAction(action, params) {
    KoolReport.dashboard.page.action(action, params);
}

function widgetAction(widgetName, action, params) {
    KoolReport.dashboard.dboard.widgetAction(widgetName, action, params);
}

function widgetShowDetail(widgetName,params){
    KoolReport.dashboard.dboard.widgetShowDetail(widgetName,params);
}

function downloadFile(code)
{
    KoolReport.dashboard.downloadFile(code);
}

function dashboardAction(action,params) {
    KoolReport.dashboard.dboard.action(action,params);
}

function navMove(a) {
    KoolReport.dashboard.page.navMove(a);
}

function loadDashboard(name, params, forceUrl = true, state = null) {
    KoolReport.dashboard.page.loadDashboard(name, params, forceUrl, state);
}

function navigate(name, params, forceUrl = true, state = null) {
    KoolReport.dashboard.navigate(name, params, forceUrl, state);
}

function logout() {
    KoolReport.dashboard.loginPage.logout();
}

function base64_encode(str) {
    return window.btoa(unescape(encodeURIComponent(str)));
}

function base64_decode(str) {
    return decodeURIComponent(escape(window.atob(str)));
}

function saveCurrentKDRState(route,state){
    var historyState = history.state;
    if(historyState) {
        historyState.name = route;
        historyState.state = state;
        window.history.replaceState(historyState,route);    
    }
}

function setKDR(route,params) {
    
    var kdr = base64_encode(JSON.stringify({
        route:route,
        action:"index",
        params:params
    }));
    
    var url = window.location.href.replace(window.location.hash, "");
    var parts = url.split("?");
    if (parts.length > 1) {
        // There is parameters
        var stParams = parts[1].split("&");
        stParams.forEach(function (v, i) {
            if (v.indexOf("kdr=") == 0) {
                stParams[i] = "kdr=" + kdr;
            }
        });
        var joinedParams = stParams.join("&");
        if (joinedParams.indexOf("kdr=") === -1) {
            joinedParams += "&kdr=" + kdr;
        }
        url = parts[0] + "?" + joinedParams + window.location.hash;
    } else {
        //No parameters
        url += "?kdr=" + kdr + window.location.hash;
    }
    window.history.pushState({ name: route, params: params, state:null }, route, url);
}

function removeKDR() {
    var url = window.location.href.replace(window.location.hash, "");
    var parts = url.split("?");
    if (parts.length > 1) {
        var params = parts[1].split("&");
        params = params.filter(function(v){
            return v.indexOf("kdr=")<0;
        });
        var joinedParams = params.join("&");
        url = parts[0] + (joinedParams!=""?"?":"") + joinedParams + window.location.hash;
        window.history.pushState({ name: "" }, "", url);
    }
}

window.onpopstate = function (e) {
    KoolReport.dashboard.navigate(e.state.name, e.state.params, false, e.state.state);
}

function historyBack()
{
    if(window.history.state!==null) {
        window.history.back();
    }
}

function _jparams($base64String) {
    return JSON.parse(base64_decode($base64String));
}

function _exec($base64String) {
    (new Function(base64_decode($base64String)))();
}

function showLoader(bool) {
    KoolReport.dashboard.theme.showLoader(bool);
}