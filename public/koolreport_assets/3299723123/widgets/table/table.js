var KoolReport = KoolReport || {};
KoolReport.dashboard = KoolReport.dashboard || {};
KoolReport.dashboard.widgets = KoolReport.dashboard.widgets || {};
KoolReport.dashboard.widgets.Table = KoolReport.dashboard.widgets.Table || function (name, options) {
    this.options = options;
    this.name = name;
    this.recentSearchText = String($("#" + this.name + "_searchBox").val());//.trim();
    this.initPaging();
    this.rowSelect();

    //For the purpose of row click
    $('#' + name + ' td input').click(function (event) {
        event.stopPropagation();
    });
    $('#' + name + ' td button').click(function (event) {
        event.stopPropagation();
    });
    $('#' + name + ' td a').click(function (event) {
        event.stopPropagation();
    });
};

KoolReport.dashboard.widgets.Table.prototype = {
    options: null,
    name: null,
    recentSearchText: null,
    toggleSorting: function (fieldName) {
        showLoader();
        KoolReport.dashboard.dboard.widgetAction(this.name, "sorting", { fieldName: fieldName });
    },
    rowSelect: function (el) {
        if(!KoolReport.dashboard.dboard) {
            return;
        }
        var _currentState = KoolReport.dashboard.dboard.widgetGetState(this.name);
        if (el && _currentState && _currentState.hasOwnProperty("selectedOption") && _currentState.selectedOption!=null) {
            //Refuse to select because it is not in manual mode
            return false;
        }

        if (el) {
            if ($(el)[0].nodeName == "TR" || $(el)[0].nodeName == "TD") {
                var input = $(el).find(".row-select");
                if (input) {
                    input.prop("checked", input.is(':checked') ? false : true);
                }
            }
            if ($(el).hasClass("row-select-all")) {
                $("#" + this.name + " .row-select").prop("checked", $(el).is(':checked'));
            }
        }

        if ($("#" + this.name + " .row-select").length > 0) {

            $("#" + this.name + " .row-select").each(function (k, checkbox) {
                var tr = $(checkbox).parent().parent();
                if ($(checkbox).is(":checked")) {
                    tr.addClass("row-selected");
                } else {
                    tr.removeClass("row-selected");
                }
            });

            var data = [];  
            
            if (_currentState && _currentState.hasOwnProperty("selectedKeys")) {
                data = _currentState.selectedKeys;
            }
            if (Array.isArray(data)==false) {
                data = [];
            }
            var allChecked = true;
            $("#" + this.name + " .row-select").each(function (k, checkbox) {
                if ($(checkbox).is(":checked")) {
                    if (data.indexOf($(checkbox).val()) < 0) {
                        data.push($(checkbox).val());
                    }
                } else {
                    if (data.indexOf($(checkbox).val()) > -1) {
                        data.splice(data.indexOf($(checkbox).val()),1);
                    }
                    allChecked = false;
                }
            });
            $("#" + this.name + " .row-select-all").prop("checked", allChecked);
            KoolReport.dashboard.dboard.widgetSaveState(this.name, "selectedKeys", data);
        }
    },
    clearSelectedKeys: function () {
        $("#" + this.name + " .row-select").prop("checked", false);
        $("#" + this.name + " .row-select-all").prop("checked", false);
        $("#" + this.name + " tr.row-selected").removeClass("row-selected");
        KoolReport.dashboard.dboard.widgetSaveState(this.name, "selectedKeys", []);
    },
    disabledManualSelect: function () {
        $("#" + this.name + " .row-select").prop("disabled", true);
        $("#" + this.name + " .row-select-all").prop("disabled", true);
    },
    enableManualSelect:function()
    {
        this.clearSelectedKeys();
        $("#" + this.name + " .row-select").prop("disabled", false);
        $("#" + this.name + " .row-select-all").prop("disabled", false);
        $("#" + this.name + " .option-selected").removeClass("option-selected");
        KoolReport.dashboard.dboard.widgetSaveState(this.name, "selectedOption", null);
    },
    goPage: function (index) {
        showLoader();
        KoolReport.dashboard.dboard.widgetAction(this.name, "paging", { index: index });
    },
    changePageSize(size) {
        showLoader();
        KoolReport.dashboard.dboard.widgetAction(this.name, "paging", { size: size });
    },
    search: function (_this) {
        var text = String($(_this).val());
        if (text != this.recentSearchText) {
            showLoader();
            KoolReport.dashboard.dboard.widgetAction(this.name, "search", { text: text });
            this.recentSearchText = text;
        }
        // $(_this).val(text);
    },
    initPaging: function () {
        if (this.options.paging == null) return;
        var _nav = $('#' + this.name + '_paging nav');
        _nav.empty();
        var _ul = $("<ul class='pagination'></ul>");
        _nav.append(_ul);

        //Previous button
        var _prev = $("<li class='page-item'><a class='page-link' href='#'>&laquo;</a></li>");
        if (this.options.paging.pageIndex <= 0) {
            _prev.addClass("disabled");
            _prev.find("a").prop("href", "javascript:void(0)");
        }
        else {
            _prev.find("a").prop("href", "javascript:" + this.name + ".goPage(" + (this.options.paging.pageIndex - 1) + ")");
        }
        _ul.append(_prev);
        //Middle

        var start = Math.floor(this.options.paging.pageIndex / 5) * 5;
        var end = (start + 5 > this.options.paging.pageCount) ? this.options.paging.pageCount : (start + 5);

        if (start > 0) {
            var _first = $("<li class='page-item'><a class='page-link'>1</a><li>");
            _first.find("a").prop("href", "javascript:" + this.name + ".goPage(0)");
            var _start = $("<li class='page-item'><a class='page-link'>...</a><li>");
            _start.find("a").prop("href", "javascript:" + this.name + ".goPage(" + (start - 5) + ")");
            _ul.append(_first);
            _ul.append(_start);
        }

        for (var i = start; i < end; i++) {
            var _li = $("<li class='page-item'><a class='page-link'></a><li>");
            _li.find("a").text(i + 1);
            if (i == this.options.paging.pageIndex) {
                _li.addClass("active");
                _li.find("a").prop("href", "javascript:void(0)");
            }
            else {
                _li.find("a").prop("href", "javascript:" + this.name + ".goPage(" + i + ")");
            }
            _ul.append(_li);
        }
        if (end < this.options.paging.pageCount) {
            var _end = $("<li class='page-item'><a class='page-link'>...</a><li>");
            _end.find("a").prop("href", "javascript:" + this.name + ".goPage(" + end + ")");
            var _last = $("<li class='page-item'><a class='page-link'></a><li>");
            _last.find("a").text(this.options.paging.pageCount).prop("href", "javascript:" + this.name + ".goPage(" + (this.options.paging.pageCount - 1) + ")");
            _ul.append(_end);
            _ul.append(_last);
        }


        //Next button
        var _next = $("<li class='page-item'><a class='page-link' href='#'>&raquo;</a></li>");
        if (this.options.paging.pageIndex >= this.options.paging.pageCount - 1) {
            _next.addClass("disabled");
            _next.find("a").prop("href", "javascript:void(0)");
        }
        else {
            _next.find("a").prop("href", "javascript:" + this.name + ".goPage(" + (this.options.paging.pageIndex + 1) + ")");
        }
        _ul.append(_next);
    }
};