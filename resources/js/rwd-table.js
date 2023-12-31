/*!
 * Responsive Tables v5.1.0 (http://gergeo.se/RWD-Table-Patterns)
 * This is an awesome solution for responsive tables with complex data.
 * Authors: Nadan Gergeo <nadan.gergeo@gmail.com> (www.gergeo.se) & Maggie Wachs (www.filamentgroup.com)
 * Licensed under MIT (https://github.com/nadangergeo/RWD-Table-Patterns/blob/master/LICENSE-MIT)
 */
! function(a) {
    "use strict";

    function b() {
        return "undefined" != typeof window.matchMedia || "undefined" != typeof window.msMatchMedia || "undefined" != typeof window.styleMedia
    }

    function c() {
        return "ontouchstart" in window
    }

    function d() {
        return !!(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i))
    }
    var e = function(b, c) {
        var e = this;
        if (this.options = c, this.$tableWrapper = null, this.$tableScrollWrapper = a(b), this.$table = a(b).find("table"), 1 !== this.$table.length) throw new Error("Exactly one table is expected in a .table-responsive div.");
        this.$tableScrollWrapper.attr("data-pattern", this.options.pattern), this.id = this.$table.prop("id") || this.$tableScrollWrapper.prop("id") || "id" + Math.random().toString(16).slice(2), this.$tableClone = null, this.$stickyTableHeader = null, this.$thead = this.$table.find("thead"), this.$tbody = this.$table.find("tbody"), this.$hdrCells = this.$thead.find("th"), this.$bodyRows = this.$tbody.find("tr"), this.$btnToolbar = null, this.$dropdownGroup = null, this.$dropdownBtn = null, this.$dropdownContainer = null, this.$displayAllBtn = null, this.$focusGroup = null, this.$focusBtn = null, this.displayAllTrigger = "display-all-" + this.id + ".responsive-table", this.idPrefix = this.id + "-col-", this.iOS = d(), this.wrapTable(), this.createButtonToolbar(), this.setupHdrCells(), this.setupStandardCells(), this.options.stickyTableHeader && this.createStickyTableHeader(), this.$dropdownContainer.is(":empty") && this.$dropdownGroup.hide(), a(window).bind("orientationchange resize " + this.displayAllTrigger, function() {
            e.$dropdownContainer.find("input").trigger("updateCheck"), a.proxy(e.updateSpanningCells(), e)
        })
    };
    e.DEFAULTS = {
        pattern: "priority-columns",
        stickyTableHeader: !0,
        fixedNavbar: ".navbar-fixed-top",
        addDisplayAllBtn: !0,
        addFocusBtn: !0,
        focusBtnIcon: "glyphicon glyphicon-screenshot",
        i18n: {
            focus: "Focus",
            display: "Display",
            displayAll: "Display all"
        }
    }, e.prototype.wrapTable = function() {
        this.$tableScrollWrapper.wrap('<div class="table-wrapper"/>'), this.$tableWrapper = this.$tableScrollWrapper.parent()
    }, e.prototype.createButtonToolbar = function() {
        var b = this;
        this.$btnToolbar = a('<div class="btn-toolbar" />'), this.$dropdownGroup = a('<div class="btn-group dropdown-btn-group pull-right" />'), this.$dropdownBtn = a('<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">' + this.options.i18n.display + ' <span class="caret"></span></button>'), this.$dropdownContainer = a('<ul class="dropdown-menu"/>'), this.options.addFocusBtn && (this.$focusGroup = a('<div class="btn-group focus-btn-group" />'), this.$focusBtn = a('<button class="btn btn-default">' + this.options.i18n.focus + "</button>"), this.options.focusBtnIcon && this.$focusBtn.prepend('<span class="' + this.options.focusBtnIcon + '"></span> '), this.$focusGroup.append(this.$focusBtn), this.$btnToolbar.append(this.$focusGroup), this.$focusBtn.click(function() {
            a.proxy(b.activateFocus(), b)
        }), this.$bodyRows.click(function() {
            a.proxy(b.focusOnRow(a(this)), b)
        })), this.options.addDisplayAllBtn && (this.$displayAllBtn = a('<button class="btn btn-default">' + this.options.i18n.displayAll + "</button>"), this.$dropdownGroup.append(this.$displayAllBtn), this.$table.hasClass("display-all") && this.$displayAllBtn.addClass("btn-primary"), this.$displayAllBtn.click(function() {
            a.proxy(b.displayAll(null, !0), b)
        })), this.$dropdownGroup.append(this.$dropdownBtn).append(this.$dropdownContainer), this.$btnToolbar.append(this.$dropdownGroup), this.$tableScrollWrapper.before(this.$btnToolbar)
    }, e.prototype.clearAllFocus = function() {
        this.$bodyRows.removeClass("unfocused"), this.$bodyRows.removeClass("focused")
    }, e.prototype.activateFocus = function() {
        this.clearAllFocus(), this.$focusBtn && this.$focusBtn.toggleClass("btn-primary"), this.$table.toggleClass("focus-on")
    }, e.prototype.focusOnRow = function(b) {
        if (this.$table.hasClass("focus-on")) {
            var c = a(b).hasClass("focused");
            this.clearAllFocus(), c || (this.$bodyRows.addClass("unfocused"), a(b).addClass("focused"))
        }
    }, e.prototype.displayAll = function(b, c) {
        this.$displayAllBtn && this.$displayAllBtn.toggleClass("btn-primary", b), this.$table.toggleClass("display-all", b), this.$tableClone && this.$tableClone.toggleClass("display-all", b), c && a(window).trigger(this.displayAllTrigger)
    }, e.prototype.preserveDisplayAll = function() {
        var b = "table-cell";
        a("html").hasClass("lt-ie9") && (b = "inline"), a(this.$table).find("th, td").css("display", b), this.$tableClone && a(this.$tableClone).find("th, td").css("display", b)
    }, e.prototype.createStickyTableHeader = function() {
        var b = this;
        b.$tableClone = b.$table.clone(), b.$tableClone.prop("id", this.id + "-clone"), b.$tableClone.find("[id]").each(function() {
            a(this).prop("id", a(this).prop("id") + "-clone")
        }), b.$tableClone.wrap('<div class="sticky-table-header"/>'), b.$stickyTableHeader = b.$tableClone.parent(), b.$stickyTableHeader.css("height", b.$thead.height() + 2), a("html").hasClass("lt-ie10") ? b.$tableWrapper.prepend(b.$stickyTableHeader) : b.$table.before(b.$stickyTableHeader), a(window).bind("scroll resize", function() {
            a.proxy(b.updateStickyTableHeader(), b)
        }), a(b.$tableScrollWrapper).bind("scroll", function() {
            a.proxy(b.updateStickyTableHeader(), b)
        })
    }, e.prototype.updateStickyTableHeader = function() {
        var b = this,
            c = 0,
            d = b.$table.offset().top,
            e = a(window).scrollTop() - 1,
            f = b.$table.height() - b.$stickyTableHeader.height(),
            g = e + a(window).height() - a(document).height(),
            h = !b.iOS,
            i = 0;
        if (a(b.options.fixedNavbar).length) {
            var j = a(b.options.fixedNavbar).first();
            i = j.height(), e += i
        }
        var k = e > d && e < d + b.$table.height();
        if (h) {
            if (b.$stickyTableHeader.scrollLeft(b.$tableScrollWrapper.scrollLeft()), b.$stickyTableHeader.addClass("fixed-solution"), c = i - 1, e - d > f ? (c -= e - d - f, b.$stickyTableHeader.addClass("border-radius-fix")) : b.$stickyTableHeader.removeClass("border-radius-fix"), k) return void b.$stickyTableHeader.css({
                visibility: "visible",
                top: c + "px",
                width: b.$tableScrollWrapper.innerWidth() + "px"
            });
            b.$stickyTableHeader.css({
                visibility: "hidden",
                width: "auto"
            })
        } else {
            b.$stickyTableHeader.removeClass("fixed-solution");
            var l = 400;
            c = e - d - 1, c < 0 ? c = 0 : c > f && (c = f), g > 0 && (c -= g), k ? (b.$stickyTableHeader.css({
                visibility: "visible"
            }), b.$stickyTableHeader.animate({
                top: c + "px"
            }, l), b.$thead.css({
                visibility: "hidden"
            })) : b.$stickyTableHeader.animate({
                top: "0"
            }, l, function() {
                b.$thead.css({
                    visibility: "visible"
                }), b.$stickyTableHeader.css({
                    visibility: "hidden"
                })
            })
        }
    }, e.prototype.setupHdrCells = function() {
        var b = this;
        b.$hdrCells.each(function(c) {
            var d = a(this),
                e = d.prop("id"),
                f = d.text();
            if (e || (e = b.idPrefix + c, d.prop("id", e)), "" === f && (f = d.attr("data-col-name")), d.is("[data-priority]")) {
                var g = a('<li class="checkbox-row"><input type="checkbox" name="toggle-' + e + '" id="toggle-' + e + '" value="' + e + '" /> <label for="toggle-' + e + '">' + f + "</label></li>"),
                    h = g.find("input");
                b.$dropdownContainer.append(g), g.click(function() {
                    h.prop("checked", !h.prop("checked")), h.trigger("change")
                }), a("html").hasClass("lt-ie9") && h.click(function() {
                    a(this).trigger("change")
                }), g.find("label").click(function(a) {
                    a.stopPropagation()
                }), g.find("input").click(function(a) {
                    a.stopPropagation()
                }).change(function() {
                    var c = a(this),
                        d = c.val(),
                        e = b.$tableWrapper.find("#" + d + ", #" + d + "-clone, [data-columns~=" + d + "]");
                    b.$table.hasClass("display-all") && (a.proxy(b.preserveDisplayAll(), b), b.$table.removeClass("display-all"), b.$tableClone && b.$tableClone.removeClass("display-all"), b.$displayAllBtn.removeClass("btn-primary")), e.each(function() {
                        var b = a(this);
                        c.is(":checked") ? ("none" !== b.css("display") && b.prop("colSpan", parseInt(b.prop("colSpan")) + 1), b.show()) : parseInt(b.prop("colSpan")) > 1 ? b.prop("colSpan", parseInt(b.prop("colSpan")) - 1) : b.hide()
                    })
                }).bind("updateCheck", function() {
                    "none" !== d.css("display") ? a(this).prop("checked", !0) : a(this).prop("checked", !1)
                }).trigger("updateCheck")
            }
        })
    }, e.prototype.setupStandardCells = function() {
        var b = this;
        b.$bodyRows.each(function() {
            var c = 0;
            a(this).find("th, td").each(function() {
                for (var d = a(this), e = "", f = d.prop("colSpan"), g = 0, h = c; h < c + f; h++) {
                    e = e + " " + b.idPrefix + h;
                    var i = b.$tableScrollWrapper.find("#" + b.idPrefix + h),
                        j = i.attr("data-priority");
                    j && d.attr("data-priority", j), "none" === i.css("display") && g++
                }
                f > 1 && (d.addClass("spn-cell"), g !== f ? d.show() : d.hide()), d.prop("colSpan", Math.max(f - g, 1)), e = e.substring(1), d.attr("data-columns", e), c += f
            })
        })
    }, e.prototype.updateSpanningCells = function() {
        var b = this;
        b.$table.find(".spn-cell").each(function() {
            for (var b = a(this), c = b.attr("data-columns").split(" "), d = c.length, e = 0, f = 0; f < d; f++) "none" === a("#" + c[f]).css("display") && e++;
            e !== d ? b.show() : b.hide(), b.prop("colSpan", Math.max(d - e, 1))
        })
    };
    var f = a.fn.responsiveTable;
    a.fn.responsiveTable = function(b) {
        return this.each(function() {
            var c = a(this),
                d = c.data("responsiveTable"),
                f = a.extend({}, e.DEFAULTS, c.data(), "object" == typeof b && b);
            "" !== f.pattern && (d || c.data("responsiveTable", d = new e(this, f)), "string" == typeof b && d[b]())
        })
    }, a.fn.responsiveTable.Constructor = e, a.fn.responsiveTable.noConflict = function() {
        return a.fn.responsiveTable = f, this
    }, a(document).on("ready.responsive-table.data-api", function() {
        a("[data-pattern]").each(function() {
            var b = a(this);
            b.responsiveTable(b.data())
        })
    }), a(document).on("click.dropdown.data-api", ".dropdown-menu .checkbox-row", function(a) {
        a.stopPropagation()
    }), a(document).ready(function() {
        a("html").removeClass("no-js").addClass("js"), b() ? a("html").addClass("mq") : a("html").addClass("no-mq"), c() ? a("html").addClass("touch") : a("html").addClass("no-touch")
    })
}(jQuery);