var KoolReport = KoolReport || {};
KoolReport.dashboard = KoolReport.dashboard || {};
KoolReport.dashboard.theme = KoolReport.dashboard.theme || {
    unexpectedResponse: function (content) {
        function htmlEntities(str) {
            return String(str).replace(/\\\//g, '/').replace(/\\"/g, '"').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/\\r\\n/g, '</br>');
        }
        content = htmlEntities(content);
        var modalHtml = `<div class="unexpectedResponseModal modal fade" role="dialog" style="display: none">
        <div class="modal-dialog modal-danger modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Unexpected Response</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <pre style='white-space: normal;'><code>${content}</code></pre>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>`;
        var objModal = $(modalHtml);
        $('body').append(objModal);
        objModal.modal('show').on('hidden.bs.modal', function (e) {
            $('.unexpectedResponseModal').remove();
        });
    },
    errorMessage: function (content) {
        function processString(str) {
            var start = String(str).indexOf("<error-message>");
            var end = String(str).indexOf("</error-message>");
            return String(str).substring(start, end).replace(/\\\\/g, '\\').replace(/\\\//g, '/');
        }
        content = processString(content);
        var modalHtml = `<div class="errorMessageModal modal fade" role="dialog" style="display: none">
        <div class="modal-dialog modal-danger modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Error Message</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <pre style='white-space: nowrap;'><code>${content}</code></pre>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
        </div>`;
        var objModal = $(modalHtml);
        $('body').append(objModal);
        objModal.modal('show').on('hidden.bs.modal', function (e) {
            $('.errorMessageModal').remove();
        });
    },
    breadcrumb: function (data) {
        $("#Main_Breadcrumb").empty();
        data.forEach(function (value) {
            $("#Main_Breadcrumb").append($("<li class='breadcrumb-item'>" + value + "</li>"));
        });
        $("#Main_Breadcrumb > li:last-child").addClass("active");
    },
    selectMenuItem: function (route) {
        //Auto select the dashboard, postion scrolling sidebar
        var alink = $("div.sidebar ul.nav a[data-name='" + route + "']");
        if (alink.hasClass("active") === false) {
            alink.addClass("active");
            if (alink.parent().parent().hasClass("nav-dropdown-items")) {
                alink.parent().parent().parent().addClass("open");
            }
        }
        KoolReport.dashboard.theme.breadcrumb(JSON.parse($(alink).attr("data-path")));
    },
    selectMenuOnUserNavigate:function(route) {
        var a = $("a[data-name='" + route + "']");
        if (a.length > 0) {
            $("div.sidebar ul.nav a.active").removeClass("active");
            $(a).addClass("active");
            KoolReport.dashboard.page.breadcrumb(JSON.parse($(a).attr("data-path")));
            $("#Main_Breadcrumb > li.active").html("<i class='fa fa-spin fa-spinner'></i>");
        }
    },
    pageInit:function() {
        mimic_resize = false;
        function resizeBroadcast() {

            var timesRun = 0;
            var interval = setInterval(function () {
                timesRun += 1;

                if (timesRun === 5) {
                    clearInterval(interval);
                }
                mimic_resize = true;
                if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
                    var evt = document.createEvent('UIEvents');
                    evt.initUIEvent('resize', true, false, window, 0);
                    window.dispatchEvent(evt);
                } else {
                    window.dispatchEvent(new Event('resize'));
                }
                if (timesRun === 5) {
                    mimic_resize = false;
                }

            }, 62.5);
        }
        $(window).on('resize', function () {
            if (mimic_resize === false) {
                resizeBroadcast();
            }
        });

        $('.sidebar-toggler').click(function () {
            $('#Main').toggleClass('sidebar-hidden');
            resizeBroadcast();
        });
        $('.mobile-sidebar-toggler').click(function () {
            $('#Main').toggleClass('sidebar-mobile-show');
            resizeBroadcast();
        });
        // Dropdown Menu
        $.navigation = $('nav > ul.nav');
        $.navigation.on('click', 'a', function (e) {

            if ($.ajaxLoad) {
                e.preventDefault();
            }

            if ($(this).hasClass('nav-dropdown-toggle')) {
                $(this).parent().toggleClass('open');
                resizeBroadcast();
            }
        });
        KoolReport.dashboard.page.selectMenuItem(
            KoolReport.dashboard.dboard.getFullName()
        );
    },
    setLabelInBreadscrumb:function(label) {
        $("#Main_Breadcrumb li.active").text(label);
    },
    showLoader:function(bool) {
        if (bool !== false) {
            if ($("div.loader").is(":visible") === false) {
                $("div.loader").fadeIn();
            }
        } else {
            if ($("div.loader").is(":visible") === true) {
                $("div.loader").fadeOut();
            }
        }
    },
    closeMenuOnMobile:function() {
        $("#Main").removeClass("sidebar-mobile-show");
    }    
};

function traces_toggle(a) {
    if ($(a).next().hasClass("d-none")) {
        $(a).next().removeClass("d-none");
        $(a).html("<i class='far fa-minus-square'></i> Collapse");
    } else {
        $(a).next().addClass("d-none");
        $(a).html("<i class='far fa-plus-square'></i> Expand");
    }
}

function mimicResize() {
    if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
        var evt = document.createEvent('UIEvents');
        evt.initUIEvent('resize', true, false, window, 0);
        window.dispatchEvent(evt);
    } else {
        window.dispatchEvent(new Event('resize'));
    }
}

$(document).ready(function () {
    setTimeout(function () {
        var sidebar_length = $('nav.sidebar-nav').height();
        var active_link_offset = $('a.nav-link.active').offset();
        if (active_link_offset) {
            $('nav.sidebar-nav').scrollTop(active_link_offset.top - sidebar_length / 2);
        }
    }, 150);
});