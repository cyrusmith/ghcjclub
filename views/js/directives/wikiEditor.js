App.directive('wikiEditor', ['$log', '$timeout', function ($log, $timeout) {

    //Wiki text plugin
    (function (Z) {
        Z.wikiText = function (k, handler) {

            if (typeof handler != "function") {
                handler = function () {
                    $log.warn("Empty handler", arguments);
                    return '';
                }
            }

            var l = (k || '').toString();
            var m = '';
            var n = /([^\r\n]*)(\r\n?|\n)/g;
            var o;
            var q = 0;
            var r;
            var t = /^([ \t]*)$/;
            var u = /^(={1,6})[ \t]+([^=]+)(={1,6})[ \t]*$/;
            var v = /^[ \t]{0,}\*[ \t]+(.+)$/;
            var w = /^[ \t]{0,}\*\*[ \t]+(.+)$/;
            var x = /^[ \t]{0,}#[ \t]+(.+)$/;
            var y = /^[ \t]{0,}##[ \t]+(.+)$/;
            var z = /^\{{3}$/;
            var A = /^\}{3}$/;
            var B = /^[ \t]+(.+)$/;
            var C = /^-{4,}$/;
            var D;
            var E = false;
            var F = false;
            var G = false;
            var H = false;
            var I = false;
            var J = false;
            var K = false;
            var L = [];
            var M = [];
            var N = {italic: "<em>", italic_end: "</em>", monospace: "<tt>", strikethrough: "<strike>", superscript: "<sup>", subscript: "<sub>"};
            var O = {monospace: "</tt>", strikethrough: "</strike>", superscript: "</sup>", subscript: "</sub>"};
            var P = function () {
                var a = '';
                var b;
                while (L.length > 0) {
                    b = L.pop();
                    a += O[b];
                    M.push(b)
                }
                return a
            };
            var Q = function () {
                m += P();
                if (E) {
                    m += "</p>\n";
                    E = false
                }
                if (F) {
                    m += "</li>\n</ol>\n";
                    F = false
                }
                if (G) {
                    m += "</li>\n</ol>\n";
                    G = false
                }
                if (H) {
                    m += "</li>\n</ul>\n";
                    H = false
                }
                if (J) {
                    m += "</p>\n";
                    J = false
                }
            };
            var R = function () {
                var a = '';
                while (M.length > 0) {
                    var b = M.pop();
                    a += N[b];
                    L.push(b)
                }
                return a
            };
            var S = function (a) {
                var b = '';
                if (Z.inArray(a, L) > -1) {
                    var c;
                    do {
                        c = L.pop();
                        b += O[c];
                        if (c === a) {
                            break
                        }
                        M.push(c)
                    } while (c !== a);
                    b += R()
                } else {
                    L.push(a);
                    b = N[a]
                }
                return b
            };
            var T = function (a, p, b, s) {
                var c;
                if (typeof $(this).attr('id') == 'string') {
                    c = $(this).attr('id').split('id="')[1];
                    c = c.split('"')[0];
                    c = $('#' + c).find('textarea').attr('id')
                }
                var d = /<img [^<]+\/>/g;
                var e = handler('smile', p);
                return e
            };
            var U = function (a, p, b, s) {
                if (a.match(/\[file\]/))return handler('file', p);
                if (a.match(/\[sample\]/))return handler('sample', p);
                if (a.match(/\[img\]/))return handler('image', p);
                if (a.match(/\[cjplayer\]/))return handler('track', p);
                if (a.match(/\[cjclub\]/))return handler('track', p);
                if (a.match(/\[utube\]/))return handler('video', p);
                return'Ошибка!'
            };
            var V = function (a, b) {
                var c = (a || '').toString();
                var d = '';
                var e;
                var f = 0;
                var g;
                var h;
                var i;
                var j, link;
                if (c.indexOf('![')) {
                    c = c.split('![');
                    c = c.join('! [')
                }
                c = c.replace(/Цитируемый текст:/g, '');
                d = c;
                d = d.replace(/\[b\]/g, '<strong>');
                d = d.replace(/\[\/b\]/g, '</strong>');
                d = d.replace(/\[i\]/g, '<em>');
                d = d.replace(/\[\/i\]/g, '</em>');
                d = d.replace(/\[u\]/g, '<u>');
                d = d.replace(/\[\/u\]/g, '</u>');
                d = d.replace(/====\s(.+)\s====/g, '<h4 style="font-size: 12px;">$1</h4>');
                d = d.replace(/===\s(.+)\s===/g, '<h3 style="font-size: 17px;">$1</h3>');
                d = d.replace(/==\s(.+)\s==/g, '<h2 style="font-size: 25px;">$1</h2>');
                d = d.replace(/\[\[Image:([\w\/\&\+\?\.%=(\[\]);,-:]+)\|(\w+)\]\]/g, '<img src="$1" title="$2" alt="$2" style="max-width: 468px;">');
                d = d.replace(/\[file\](\d+)\[\/file\]/g, U);
                d = d.replace(/\[sample\](\d+)\[\/sample\]/g, U);
                d = d.replace(/\[img\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/img\]/g, U);
                d = d.replace(/\[cjclub\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjclub\]/g, U);
                d = d.replace(/\[cjplayer\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjplayer\]/g, U);
                d = d.replace(/\[utube\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/utube\]/g, U);
                d = d.replace(/\[\/url\]/g, '</a>');
                d = d.replace(/\[url=([\w\s\/\&\+\?\.%=(\[\]);,-:]+)\](.+)/g, '<a href="$1" target="_blank">$2');
                d = d.replace(/\[url\]([\w\s\/\&\+\?\.%=(\[\]);,-:]+)/g, '<a href="$1" target="_blank">$1');
                d = d.replace(/((?:\s|^)https?:\/\/(\S+)?)/g, '<a href="$1" target="_blank">$1</a>');
                d = d.replace(/:([a-zA-Z0-9]{1,}):/g, T);
                d = d.replace(/\[quote\]/g, '<div class="quote"><i><div style="border: 1px solid #d0d0d0; max-width: 350px; background-color: #efefef; padding: 5px;"><b>Цитата:</b><br/>');
                d = d.replace(/\[\/quote\]/g, '</div></i></div>');
                return d
            };
            var W = function () {
                if (q < l.length) {
                    o = n.exec(l);
                    if (o != null) {
                        q = n.lastIndex;
                        r = o[1]
                    } else {
                        r = l.substring(q);
                        q = l.length
                    }
                } else {
                    r = null
                }
                return r
            };
            while (W() != null) {
                if (K) {
                    if (r.match(A)) {
                        K = false;
                        m += "</pre>\n"
                    } else {
                        m += Z.wikiText.safeText(r) + "\n"
                    }
                } else if (r.length === 0 || t.test(r)) {
                    Q()
                } else if ((D = r.match(u)) !== null) {
                    Q();
                    var X = D[1].length;
                    var Y = '';
                    switch (X) {
                        case 2:
                            Y = " style='font-size: 25px;'";
                            break;
                        case 3:
                            Y = " style='font-size: 17px;'";
                            break;
                        case 4:
                            Y = " style='font-size: 12px;'";
                            break
                    }
                    m += "\n<h" + X + Y + ">" + R() + V(D[2]) + P() + "</h" + X + ">\n\n"
                } else if ((D = r.match(v)) !== null) {
                    if (I) {
                        m += P() + "</ul>\n";
                        I = false
                    }
                    if (H) {
                        m += P() + "</li>\n"
                    } else {
                        Q();
                        m += "<ul>\n";
                        H = true
                    }
                    m += "<li>" + R() + V(D[1])
                } else if ((D = r.match(w)) !== null) {
                    if (I) {
                        m += P() + "</li>\n"
                    } else {
                        m += "</li><ul>\n";
                        I = true
                    }
                    m += "<li>" + R() + V(D[1])
                } else if ((D = r.match(x)) !== null) {
                    if (G) {
                        m += P() + "</ol>\n";
                        G = false
                    }
                    if (F) {
                        m += P() + "</li>\n"
                    } else {
                        Q();
                        m += "<ol>\n";
                        F = true
                    }
                    m += "<li>" + R() + V(D[1])
                } else if ((D = r.match(y)) !== null) {
                    if (G) {
                        m += P() + "</li>\n"
                    } else {
                        m += "</li><ol>\n";
                        G = true
                    }
                    m += "<li>" + R() + V(D[1])
                } else if (r.match(z)) {
                    Q();
                    m += "<pre>\n";
                    K = true
                } else if (r.match(C)) {
                    Q();
                    m += "<hr/>\n"
                } else if ((D = r.match(B))) {
                    if (!(J || F || H)) {
                        Q();
                        m += "<p>\n";
                        m += R();
                        J = true
                    }
                    m += "\n" + V(D[1])
                } else {
                    if (!E) {
                        Q();
                        m += "<p>\n";
                        m += R();
                        E = true
                    }
                    m += V(r) + "\n"
                }
            }
            Q();
            return m
        };
        Z.wikiText.safeText = function (a) {
            return(a || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        };
        Z.wikiText.re_link = /^((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+$/;
        Z.wikiText.re_mail = /^(mailto:)?([_.\w\-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})$/;
        Z.wikiText.namedLink = function (a, b) {
            var c;
            var d;
            if (!a) {
                return Z.wikiText.safeText(b)
            }
            if (Z.wikiText.re_mail.test(a)) {
                a = a.replace(/mailto:/, "");
                c = encodeURI("mailto:" + a)
            } else {
                c = a
            }
            if (!b) {
                b = decodeURI(a)
            }
            d = Z.wikiText.safeText(b);
            return d.link(c)
        };
        Z.fn.wikiText = function (a) {
            return this.html(Z.wikiText(a))
        }
    })(jQuery);

    //Init the markItUp jQuery plugin
    (function ($) {

        function randString(n) {
            if (!n) {
                n = 5;
            }
            var text = '';
            var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            for (var i = 0; i < n; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            return text;
        }

        $.fn.markItUp = function (p, q, controller, scope) {
            var r, ctrlKey, shiftKey, altKey;
            ctrlKey = shiftKey = altKey = false;
            r = {id: '', nameSpace: '', root: '', previewInWindow: '', previewAutoRefresh: true, previewPosition: 'after', previewTemplatePath: '~/templates/preview.html', previewParser: false, previewParserPath: '', previewParserVar: 'data', resizeHandle: true, beforeInsert: '', afterInsert: '', onEnter: {}, onShiftEnter: {}, onCtrlEnter: {}, onTab: {}, markupSet: [
                {}
            ], buildFlag: 1};
            $.extend(r, p, q);
            if (!r.root) {
                $('script').each(function (a, b) {
                    miuScript = $(b).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);
                    if (miuScript !== null) {
                        r.root = miuScript[1]
                    }
                })
            }
            return this.each(function () {

                var randomControllerName;
                var thisEl = $(this);

                do
                {
                    randomControllerName = 'markItUp_' + randString(10);
                }
                while (randomControllerName in window);

                $log.log('markItUp randomControllerName', randomControllerName);

                window[randomControllerName] = {
                    controller: function () {
                        $log.log(arguments);
                        if (typeof controller == "function") {
                            controller.apply(scope || null, arguments)
                        }
                    }
                };

                var o, textarea, levels, scrollPosition, caretPosition, caretOffset, clicked, hash, header, footer, previewWindow, template, iFrame, abort;
                o = $(this);
                textarea = this;
                levels = [];
                abort = false;
                scrollPosition = caretPosition = 0;
                caretOffset = -1;
                r.previewParserPath = localize(r.previewParserPath);
                r.previewTemplatePath = localize(r.previewTemplatePath);
                function localize(a, b) {
                    if (b) {
                        return a.replace(/("|')~\//g, "$1" + r.root)
                    }
                    return a.replace(/^~\//, r.root)
                }

                function init() {
                    id = '';
                    nameSpace = '';
                    if (r.id) {
                        id = 'id="' + r.id + '"'
                    } else if (o.attr("id")) {
                        id = 'id="markItUp' + (o.attr("id").substr(0, 1).toUpperCase()) + (o.attr("id").substr(1)) + '"'
                    }
                    if (r.nameSpace) {
                        nameSpace = 'class="' + r.nameSpace + '"'
                    }
                    o.wrap('<div ' + nameSpace + '></div>');
                    o.wrap('<div ' + id + ' class="markItUp"></div>');
                    o.wrap('<div class="markItUpContainer"></div>');
                    o.addClass("markItUpEditor");
                    header = $('<div class="bbcode_top markItUpHeader"></div>').insertBefore(o);
                    $(dropMenus(r.markupSet)).appendTo(header);
                    footer = $('<div class="markItUpFooter"></div>').insertAfter(o);
                    if (r.resizeHandle === true && $.browser.safari !== true) {
                        resizeHandle = $('<div class="markItUpResizeHandle"></div>').insertAfter(o).bind("mousedown", function (e) {
                            var h = o.height(), y = e.clientY, mouseMove, mouseUp;
                            mouseMove = function (e) {
                                o.css("height", Math.max(20, e.clientY + h - y) + "px");
                                return false
                            };
                            mouseUp = function (e) {
                                $("html").unbind("mousemove", mouseMove).unbind("mouseup", mouseUp);
                                return false
                            };
                            $("html").bind("mousemove", mouseMove).bind("mouseup", mouseUp)
                        });
                        footer.append(resizeHandle)
                    }
                    o.keydown(keyPressed).keyup(keyPressed);
                    o.bind("insertion", function (e, a) {
                        if (a.target !== false) {
                            get()
                        }
                        if (textarea === $.markItUp.focused) {
                            markup(a)
                        }
                    });
                    o.focus(function () {
                        $.markItUp.focused = this
                    })
                }

                function dropMenus(e) {
                    var f = $('<div class="ul"></div>'), i = 0;
                    $('.li:hover > .ul', f).css('display', 'block');
                    $('<div class="bbcode_button_left"></div>').appendTo(f);
                    $.each(e, function (a) {
                        var b = this, t = '', text = '', custom = '', title, li, j;
                        title = (b.key) ? (b.name || '') + ' [Ctrl+' + b.key + ']' : (b.name || '');
                        switch (b.name) {
                            case'H1':
                                title = 'Заголовок 1-го уровня';
                                break;
                            case'H2':
                                title = 'Заголовок 2-го уровня';
                                break;
                            case'H3':
                                title = 'Заголовок 3-го уровня';
                                break;
                            case'b':
                                title = 'Жирный текст';
                                break;
                            case'i':
                                title = 'Наклонный текст';
                                break;
                            case'U':
                                title = 'Подчёркнутый текст';
                                break;
                            case'align':
                                title = 'Сдвиг текста';
                                break;
                            case'picture':
                                title = 'Вставка картинки';
                                break;
                            case'list_num':
                                title = 'Элемент числового списка';
                                break;
                            case'list_num_sub':
                                title = 'Подэлемент числового списка';
                                break;
                            case'list':
                                title = 'Элемент маркированного списка';
                                break;
                            case'list_sub':
                                title = 'Подэлемент маркированного списка';
                                break;
                            case'link':
                                title = 'Ссылка';
                                break;
                            case'smile':
                                title = 'Смайлы';
                                custom = 'custom_a';
                                text = 'Смайлы';
                                c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'smile\')"';
                                break
                        }
                        if (b.preview) {
                            title = b.preview;
                            text = b.preview;
                            b.name = 'custom_a';
                            custom = 'custom_a'
                        }
                        if (b.nextline) {
                            $('<div class="bbcode_button_right"></div>').appendTo(f);
                            $('<div class="bbcode_line"></div>').appendTo(f);
                            $('<div class="bbcode_button_left"></div>').appendTo(f)
                        }
                        if (b.name) {
                            var c;
                            switch (b.name) {
                                case'box':
                                case'file':
                                    title = 'Загрузка архивов';
                                    text = '';
                                    b.name = 'box';
                                    custom = 'box';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'file\')"';
                                    break;
                                case'dynamic':
                                case'sample':
                                    title = 'Загрузка семпла';
                                    text = '';
                                    b.name = 'dynamic';
                                    custom = 'dynamic';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'sample\')"';
                                    break;
                                case'image':
                                    title = 'Загрузка картинки';
                                    text = '';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'image\')"';
                                    break;
                                case'music':
                                    title = 'Прикрепить трек';
                                    text = '';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'track\')"';
                                    break;
                                case'video':
                                    title = 'Прикрепить видео';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'video\')"';
                                    break;
                                case'link':
                                    title = 'Вставить ссылку';
                                    c = 'onclick = "window.' + randomControllerName + '.controller(\'getAttachForm\', \'link\')"';
                                    break
                            }
                        }
                        key = (b.key) ? 'accesskey="' + b.key + '"' : '';
                        key = (b.key) ? 'accesskey="' + b.key + '"' : '';
                        if (b.separator) {
                            var d = f.children().last();
                            d.prevUntil('.bbcode_button_left').add(d).wrapAll('<div class="bbcode_group"></div>');
                            $('<div class="bbcode_button_right"></div>').appendTo(f);
                            $('<div class="bbcode_button_left"></div>').appendTo(f)
                        } else {
                            i++;
                            for (j = levels.length - 1; j >= 0; j--) {
                                t += levels[j] + "-"
                            }
                            li = $('<div class=" bbcode_button_center markItUpButton markItUpButton' + t + (i) + ' ' + (b.className || '') + '"><a href="javascript:void(0)" ' + key + ' title="' + title + '" class="bbcode_' + b.name + ' ' + custom + '" ' + (c || '') + ' style="text-decoration: none;">' + text + '</a></div>').bind("contextmenu",function () {
                                return false
                            }).click(function () {
                                    return false
                                }).bind("focusin",function () {
                                    o.focus()
                                }).mouseup(function () {
                                    if (b.call) {
                                        eval(b.call)()
                                    }
                                    setTimeout(function () {
                                        markup(b)
                                    }, 1);
                                    return false
                                }).hover(function () {
                                    $('> .ul', this).show();
                                    $(document).one('click', function () {
                                        $('.ul .ul', header).hide()
                                    })
                                },function () {
                                    $('> div', this).hide()
                                }).appendTo(f);
                            if (b.dropMenu) {
                                levels.push(i);
                                $(li).addClass('markItUpDropMenu').append(dropMenus(b.dropMenu))
                            }
                        }
                    });
                    text = '';
                    custom = '';
                    $('<li class="bbcode_button_right"></li>').appendTo(f);
                    var g = f.children().last();
                    g.prevUntil('.bbcode_button_left').add(g).wrapAll('<div class="bbcode_group"></div>');
                    levels.pop();
                    return f
                }

                function magicMarkups(c) {
                    if (c && !r.buildFlag) {
                        c = c.toString();
                        c = c.replace(/\(\!\(([\s\S]*?)\)\!\)/g, function (x, a) {
                            var b = a.split('|!|');
                            if (altKey === true) {
                                return(b[1] !== undefined) ? b[1] : b[0]
                            } else {
                                return(b[1] === undefined) ? "" : b[0]
                            }
                        });
                        c = c.replace(/\[\!\[([\s\S]*?)\]\!\]/g, function (x, a) {
                            var b = a.split(':!:');
                            if (abort === true) {
                                return false
                            }
                            value = prompt(b[0], (b[1]) ? b[1] : '');
                            if (value === null) {
                                abort = true
                            }
                            return value
                        });
                        return c
                    }
                    return""
                }

                function prepare(a) {
                    if ($.isFunction(a)) {
                        a = a(hash)
                    }
                    return magicMarkups(a)
                }

                function build(a, b) {
                    var c = prepare(clicked.openWith);
                    var d = prepare(clicked.placeHolder);
                    var e = prepare(clicked.replaceWith);
                    var f = prepare(clicked.closeWith);
                    var g = prepare(clicked.openBlockWith);
                    var h = prepare(clicked.closeBlockWith);
                    var i = clicked.multiline;
                    var j = a || selection || e || d;
                    var k = get(true);
                    if (b)return[c.length + k[0], j.length];
                    if (e !== "") {
                        block = c + e + f
                    } else if (selection === '' && d !== '') {
                        block = c + d + f
                    } else {
                        a = a || selection;
                        var m = selection.split(/\r?\n/), blocks = [];
                        for (var l = 0; l < m.length; l++) {
                            line = m[l];
                            var n;
                            if (n = line.match(/ *$/)) {
                                blocks.push(c + line.replace(/ *$/g, '') + f + n)
                            } else {
                                blocks.push(c + line + f)
                            }
                        }
                        block = blocks.join("\n")
                    }
                    block = g + block + h;
                    return{block: block, openWith: c, replaceWith: e, placeHolder: d, closeWith: f}
                }

                function markup(a) {
                    var b, j, n, i;
                    hash = clicked = a;
                    get();
                    $.extend(hash, {line: "", root: r.root, textarea: textarea, selection: (selection || ''), caretPosition: caretPosition, ctrlKey: ctrlKey, shiftKey: shiftKey, altKey: altKey});
                    prepare(r.beforeInsert);
                    prepare(clicked.beforeInsert);
                    if ((ctrlKey === true && shiftKey === true) || a.multiline === true) {
                        prepare(clicked.beforeMultiInsert)
                    }
                    $.extend(hash, {line: 1});
                    var c;
                    var d = selection;
                    if ((ctrlKey === true && shiftKey === true)) {
                        lines = selection.split(/\r?\n/);
                        for (j = 0, n = lines.length, i = 0; i < n; i++) {
                            if ($.trim(lines[i]) !== '') {
                                $.extend(hash, {line: ++j, selection: lines[i]});
                                lines[i] = build(lines[i]).block
                            } else {
                                lines[i] = ""
                            }
                        }
                        string = {block: lines.join('\n')};
                        start = caretPosition;
                        b = string.block.length + (($.browser.opera) ? n - 1 : 0)
                    } else if (ctrlKey === true) {
                        c = build(selection, true);
                        r.buildFlag = 0;
                        string = build(selection);
                        start = caretPosition + string.openWith.length;
                        b = string.block.length - string.openWith.length - string.closeWith.length;
                        b = b - (string.block.match(/ $/) ? 1 : 0);
                        b -= fixIeBug(string.block)
                    } else if (shiftKey === true) {
                        c = build(selection, true);
                        r.buildFlag = 0;
                        string = build(selection);
                        start = caretPosition;
                        b = string.block.length;
                        b -= fixIeBug(string.block)
                    } else {
                        c = build(selection, true);
                        r.buildFlag = 0;
                        string = build(selection);
                        start = caretPosition + string.block.length;
                        b = 0;
                        start -= fixIeBug(string.block)
                    }
                    if ((selection === '' && string.replaceWith === '')) {
                        caretOffset += fixOperaBug(string.block);
                        start = caretPosition + string.openWith.length;
                        b = string.block.length - string.openWith.length - string.closeWith.length;
                        caretOffset = o.val().substring(caretPosition, o.val().length).length;
                        caretOffset -= fixOperaBug(o.val().substring(0, caretPosition))
                    }
                    $.extend(hash, {caretPosition: caretPosition, scrollPosition: scrollPosition});
                    if (string.block !== selection && abort === false) {
                        insert(string.block);
                        set(start, b)
                    } else {
                        caretOffset = -1
                    }
                    get();
                    $.extend(hash, {line: '', selection: selection});
                    if ((ctrlKey === true && shiftKey === true) || a.multiline === true) {
                        prepare(clicked.afterMultiInsert)
                    }
                    prepare(clicked.afterInsert);
                    prepare(r.afterInsert);
                    if (previewWindow && r.previewAutoRefresh) {
                        refreshPreview()
                    }
                    if (string.openWith)c[0] += string.openWith.length;
                    if (!d && string.placeHolder)c[1] += string.placeHolder.length;
                    set(c[0], c[1]);
                    shiftKey = altKey = ctrlKey = abort = false;
                    r.buildFlag = 1;

                    thisEl.trigger("insert_markup", [c]);

                }

                function fixOperaBug(a) {
                    if ($.browser.opera) {
                        return a.length - a.replace(/\n*/g, '').length
                    }
                    return 0
                }

                function fixIeBug(a) {
                    if ($.browser.msie) {
                        return a.length - a.replace(/\r*/g, '').length
                    }
                    return 0
                }

                function insert(a) {
                    if (document.selection) {
                        var b = document.selection.createRange();
                        b.text = a
                    } else {
                        textarea.value = textarea.value.substring(0, caretPosition) + a + textarea.value.substring(caretPosition + selection.length, textarea.value.length)
                    }
                }

                function set(a, b) {
                    if (textarea.createTextRange) {
                        if ($.browser.opera && $.browser.version >= 9.5 && b == 0) {
                            return false
                        }
                        range = textarea.createTextRange();
                        range.collapse(true);
                        range.moveStart('character', a);
                        range.moveEnd('character', b);
                        range.select()
                    } else if (textarea.setSelectionRange) {
                        textarea.setSelectionRange(a, a + b)
                    }
                    textarea.scrollTop = scrollPosition;
                    textarea.focus()
                }

                function get(a) {
                    textarea.focus();
                    if (a) {
                        var b = caretPosition
                    }
                    scrollPosition = textarea.scrollTop;
                    if (document.selection) {
                        selection = document.selection.createRange().text;
                        if ($.browser.msie) {
                            var c = document.selection.createRange(), rangeCopy = c.duplicate();
                            rangeCopy.moveToElementText(textarea);
                            caretPosition = -1;
                            while (rangeCopy.inRange(c)) {
                                rangeCopy.moveStart('character');
                                caretPosition++
                            }
                        } else {
                            caretPosition = textarea.selectionStart
                        }
                    } else {
                        caretPosition = textarea.selectionStart;
                        selection = textarea.value.substring(caretPosition, textarea.selectionEnd)
                    }
                    if (!a)return selection; else {
                        caretPosition = b;
                        return[caretPosition, textarea.selectionEnd]
                    }
                }

                function preview() {
                    refreshPreview()
                }

                function refreshPreview() {
                    controller.call(scope || null, 'preview');
                }

                function keyPressed(e) {
                    shiftKey = e.shiftKey;
                    altKey = e.altKey;
                    ctrlKey = (!(e.altKey && e.ctrlKey)) ? (e.ctrlKey || e.metaKey) : false;
                    if (e.type === 'keydown') {
                        if (ctrlKey === true) {
                            li = $('a[accesskey="' + String.fromCharCode(e.keyCode) + '"]', header).parent('li');
                            if (li.length !== 0) {
                                ctrlKey = false;
                                setTimeout(function () {
                                    li.triggerHandler('mouseup')
                                }, 1);
                                return false
                            }
                        }
                        if (e.keyCode === 13 || e.keyCode === 10) {
                            if (ctrlKey === true) {
                                ctrlKey = false;
                                markup(r.onCtrlEnter);
                                return r.onCtrlEnter.keepDefault
                            } else if (shiftKey === true) {
                                shiftKey = false;
                                markup(r.onShiftEnter);
                                return r.onShiftEnter.keepDefault
                            } else {
                                markup(r.onEnter);
                                return r.onEnter.keepDefault
                            }
                        }
                        if (e.keyCode === 9) {
                            if (shiftKey == true || ctrlKey == true || altKey == true) {
                                return false
                            }
                            if (caretOffset !== -1) {
                                get();
                                caretOffset = o.val().length - caretOffset;
                                set(caretOffset, 0);
                                caretOffset = -1;
                                return false
                            } else {
                                markup(r.onTab);
                                return r.onTab.keepDefault
                            }
                        }
                    }
                }

                init()
            })
        };
        $.fn.markItUpRemove = function () {
            return this.each(function () {
                var a = $(this).unbind().removeClass('markItUpEditor');
                a.parent('div').parent('div.markItUp').parent('div').replaceWith(a)
            })
        };
        $.markItUp = function (a) {
            var b = {target: false};
            $.extend(b, a);
            if (b.target) {
                return $(b.target).each(function () {
                    $(this).focus();
                    $(this).trigger('insertion', [b])
                })
            } else {
                $('textarea').trigger('insertion', [b])
            }
        }
    })(jQuery);

    var wikiSettings = {
        previewParserPath: '', // path to your Wiki parser
        onShiftEnter: {keepDefault: false, replaceWith: '\n\n'},
        markupSet: [
            {name: 'b', key: 'B', openWith: "[b]", closeWith: "[/b]"},
            {name: 'i', key: 'I', openWith: "[i]", closeWith: "[/i]"},
            {name: 'U', key: 'U', openWith: "[u]", closeWith: "[/u]"},
            {name: 'S', key: 'S', openWith: "[s]", closeWith: "[/s]"},
            {separator: '---------------' },
            {name: 'H1', key: '1', openWith: '== ', closeWith: ' ==', placeHolder: 'Заголовок 1...' },
            {name: 'H2', key: '2', openWith: '=== ', closeWith: ' ===', placeHolder: 'Заголовок 2...' },
            {name: 'H3', key: '3', openWith: '==== ', closeWith: ' ====', placeHolder: 'Заголовок 3...' },
            {separator: '---------------' },
            /*{name:'S', key:'S', openWith:'<s>', closeWith:'</s>'},*/
            //{name:'align', openWith:'(!( :|!|)!)'},
            {name: 'list_num', openWith: '(!( # |!| #)!)'},
            {name: 'list_num_sub', openWith: '(!( ## |!| ##)!)'},
            {name: 'list', openWith: '(!( * |!| *)!)'},
            {name: 'list_sub', openWith: '(!( ** |!| **)!)'},
            {separator: '---------------' },
            //{name:'picture', key:"P", replaceWith:'[[Image:[![Url:!:http://]!]|[![name]!]]]'},
            /*{name:'link', key:"L", openWith:"[[![Link]!] ", closeWith:']', placeHolder:'Ссылка...' },*/
            {name: 'link'},
            /*openWith:"[url=http://] ", closeWith:'[/url]', placeHolder:'Текст...',*/
            /*{name:'Quotes', openWith:'(!(> |!|>)!)', placeHolder:''},*/
            {separator: '---------------' },
            {name: 'file', className: 'bbcode_box'},
            {name: 'sample', className: 'bbcode_music'},
            {name: 'image', className: 'bbcode_image'},
            {name: 'music', className: 'bbcode_music'},
            {name: 'video', className: 'bbcode_video'},
            {separator: '---------------' },
            {name: 'smile'},
            {separator: '---------------' },
            {name: 'video', call: 'preview', className: 'preview', preview: 'Предпросмотр'}
        ]
    };

    var smilesMap = [
        [':sorry:', 'sorry.gif'],
        [':zlost:', 'zlost.gif'],
        [':horror:', 'horror.gif'],
        [':music:', 'music.gif'],
        [':udivlenie:', 'udivlenie.gif'],
        [':yazik:', 'yazik.gif'],
        [':fuck:', 'fuck.gif'],
        [':dance1:', 'dance1.gif'],
        [':in_love:', 'in_love.gif'],
        [':shake:', 'shake.gif'],
        [':cool:', 'cool.gif'],
        [':dance2:', 'dance2.gif'],
        [':cry:', 'cry.gif'],
        [':dance3:', 'dance3.gif'],
        [':stesnenie:', 'stesnenie.gif'],
        [':1:', '1.gif'],
        [':laugh:', 'laugh.gif'],
        [':idea:', 'idea.gif'],
        [':winking:', 'winking.gif'],
        [':sad:', 'sad.gif'],
        [':smile:', 'smile.gif'],
        [':good:', 'good.gif'],
        [':help:', 'help.gif'],
        [':rok:', 'rok.gif'],
        [':alkash:', 'alkash.gif'],
        [':mega_rzhach:', 'mega_rzhach.gif'],
        [':crazy:', 'crazy.gif'],
        [':shock:', 'shock.gif'],
        [':gipno:', 'gipno.gif'],
        [':ob_stenu:', 'ob_stenu.gif'],
        [':sleep:', 'sleep.gif'],
        [':wacko2:', 'wacko2.gif'],
        [':unsure2:', 'unsure2.gif'],
        [':wink2:', 'wink2.gif'],
        [':rolleyes2:', 'rolleyes2.gif'],
        [':tongue2:', 'tongue2.gif'],
        [':cray2:', 'cray2.gif'],
        [':wub2:', 'wub2.gif'],
        [':yahoo2:', 'yahoo2.gif'],
        [':shok2:', 'shok2.gif'],
        [':rofl2:', 'rofl2.gif'],
        [':lol2:', 'lol2.gif'],
        [':Koshechka2:', 'Koshechka2.gif'],
        [':friends2:', 'friends2.gif'],
        [':fool2:', 'fool2.gif'],
        [':drinks2:', 'drinks2.gif'],
        [':blink2:', 'blink2.gif'],
        [':argue2:', 'argue2.gif'],
        [':angry2:', 'angry2.gif'],
        [':net_zubov2:', 'net_zubov2.gif'],
        [':smile2:', 'smile2.gif'],
        [':sad2:', 'sad2.gif'],
        [':laugh2:', 'laugh2.gif'],
        [':crazy2:', 'crazy2.gif'],
        [':cool2:', 'cool2.gif'],
        [':laugh3:', 'laugh3.gif']
    ];


    return {

        restrict: 'A',
        templateUrl: 'views/main/includes/wikiEditor.html',
        scope: {
            modelName: '=',
            textareaId: '@',
            initText: '@'
        },
        link: function (scope, element, attrs) {

            var textareaElement = jQuery(element).find('textarea'),
                previewElement = jQuery(element).find('.markItUpPreview'),
                attachDialogElement = jQuery('<div/>'),
                selectionState = {};


            $('body').append(attachDialogElement);
            attachDialogElement.css({
                'display': 'none'
            })

            function moveCursor(n) {
                var o = textareaElement[0];

                if (!document.all) {
                    o.setSelectionRange(n, n);
                    o.focus();
                } else {
                    var r = o.createTextRange();
                    r.collapse(true);
                    r.moveStart("character", n);
                    r.moveEnd("character", 0);
                    r.select();
                }
            }

            function insertSmile(smile) {
                var obj_ta = textareaElement[0];
                smile += '\n';
                //  Для MSIE
                if (document.selection) {
                    obj_ta.focus();
                    sel = document.selection.createRange();
                    sel.text = smile;
                }
                // Для нормальных браузеров
                else if (obj_ta.selectionStart || obj_ta.selectionStart == '0') {
                    obj_ta.focus();
                    var startPos = obj_ta.selectionStart;
                    var endPos = obj_ta.selectionEnd;
                    obj_ta.value = obj_ta.value.substring(0, startPos) + smile + obj_ta.value.substring(endPos, obj_ta.value.length);

                    moveCursor(endPos + smile.length);
                }
                // Для остальных ;)
                else {
                    obj_ta.value += smile;
                }

                attachDialogElement.dialog("close");

            };

            function wikiTextReplaceHandler(target, source) {
                switch (target) {
                    case 'smile':
                        for (var i = 0; smilesMap[i]; i++) {
                            if (smilesMap[i][0] == ':' + source + ':') {
                                return '<img src="/views/main/img/smiles/' + smilesMap[i][1] + '" />';
                            }
                        }

                        break;
                    case 'file':
                    case 'sample':
                    case 'image':
                    case 'track':
                    case 'video':
                        //TODO
                        return source;
                        break;
                }

                return '';
            };

            function handleAction(action, type, id) {

                $log.log("handleAction", action, type, id)

                switch (action) {

                    case 'preview':
                        previewElement.html($.wikiText(textareaElement.val(), wikiTextReplaceHandler));
                        break;

                    case 'getAttachForm':

                        if (type == 'smile') {

                            var dialogHtml = "";
                            for (var i = 0; smilesMap[i]; i++) {
                                dialogHtml += "<span class='smile'><img class='markitup-dialog-smiles-smile' data-smile='" + smilesMap[i][0] + "' src=\"/views/main/img/smiles/" + smilesMap[i][1] + "\" /></span>";
                            }

                            attachDialogElement.html(dialogHtml);

                            attachDialogElement.find('.markitup-dialog-smiles-smile').on('click', function () {
                                insertSmile($(this).data().smile);
                            });

                            attachDialogElement.dialog({
                                autoOpen: false,
                                show: "blind",
                                hide: "explode",
                                title: "Смайлы",
                                zIndex: 1012
                            });
                            attachDialogElement.dialog("open");
                        } else if (type != 'link') {

                            //TODO remove this stub function and use backend instead
                            function ajaxRequest(url, options) {

                                var fileAttachHtml = '<div class="attach_form"> ' +
                                    '	<form data-attachment="file" data-inputName="publicName" class="ajax attachment_upload" method="post" action="files/upload">' +
                                    '		<table width="100%" border="0" cellspacing="3" cellpadding="0">' +
                                    '			<tr>' +
                                    '				<td>\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0444\u0430\u0439\u043b:</td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td><input type="file" id="file_value" name="file"></td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td>\u0418\u043c\u044f:</td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td>' +
                                    '					<ul>' +
                                    '						<li class="form_input_center">' +
                                    '							<input type="text" name="publicName">' +
                                    '						</li>' +
                                    '					</ul>' +
                                    '				</td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td>\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435:</td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td>' +
                                    '					<ul>' +
                                    '						<li class="form_input_center">' +
                                    '							<input type="text" name="description">' +
                                    '						</li>' +
                                    '					</ul>' +
                                    '				</td>' +
                                    '			</tr>' +
                                    '			<tr>' +
                                    '				<td>' +
                                    '					<button type="submit">\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c</button>' +
                                    '				</td>' +
                                    '			</tr>' +
                                    '		</table>' +
                                    '	</form>' +
                                    '</div>';

                                var htmls = {
                                    file: {size: "2Mb", html: fileAttachHtml},
                                    sample: {size: "2Mb", html: fileAttachHtml},
                                    image: {size: "2Mb", html: fileAttachHtml},
                                    track: {size: "2Mb", html: "<div class='attach_form'>\r\n              <form data-attachment='track' data-inputName='trackName'>      <label for='id-track-bbcode'>\u0412\u0432\u0435\u0434\u0438\u0442\u0435 ID \u0442\u0440\u0435\u043a\u0430 \u0438\u043b\u0438 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u043d\u0435\u0433\u043e<\/label><br/>\r\n                    <ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='trackName' id='id-track-bbcode'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul>\r\n                    <button type='submit'>\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c<\/button>\r\n                <\/form><\/div>"},
                                    video: {size: "2Mb", html: " <div class='attach_form ready'>\r\n       <form data-attachment='video' data-inputName='imageName'>        <label for='id-track-bbcode'>\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0432\u0438\u0434\u0435\u043e<\/label><br/>\r\n                    <ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='imageName' id='id-track-bbcode'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul>\r\n                    <button type='submit'>\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c<\/button>\r\n               <\/form> <\/div>\r\n            "}
                                }
                                options.onSuccess(htmls[type] || {});
                            }

                            ajaxRequest('files/getAttachForm', {
                                data: 'type=' + type,
                                onSuccess: function (response) {

                                    attachDialogElement.html(response.html);

                                    var title = '';
                                    switch (type) {
                                        case 'file':
                                            title = 'Загрузка архивов | (RAR,ZIP. Файл не более ' + response.size + ')';
                                            break;

                                        case 'sample':
                                            title = 'Загрузка звукового семпла | (Mp3. Файл не более ' + response.size + ')';
                                            break;

                                        case 'image':
                                            title = 'Загрузка изображения | (PNG,JPG,GIF. Файл не более ' + response.size + ')';
                                            break;

                                        case 'track':
                                            title = 'Прикрепить трек с сайта';
                                            break;

                                        case 'video':
                                            title = 'Прикрепить видео YouTube';
                                            break;
                                    }

                                    attachDialogElement.dialog({
                                        autoOpen: false,
                                        show: "blind",
                                        hide: "explode",
                                        title: title
                                    });

                                    var titleEl = attachDialogElement.parent().find('.ui-dialog-title');

                                    titleEl.html(titleEl.html().replace('|', '<br>'));
                                    attachDialogElement.dialog("open");

                                    attachDialogElement.find('form').on('submit', function () {

                                        var data = $(this).data(),
                                            inputName = data.inputname,
                                            input = attachDialogElement.find('input[name="' + inputName + '"]');

                                        textareaElement.focus();
                                        attachDialogElement.dialog("close");
                                        handleAction("getBBCode", data.attachment, input.val());

                                        return false;

                                    });

                                }
                            });
                        } else { //link
                            selectionState.areaStart = textareaElement.prop("selectionStart");
                            selectionState.areaEnd = textareaElement.prop("selectionEnd");
                            var selection = '';
                            if (selectionState.areaStart != selectionState.areaEnd) {
                                var string = textareaElement.val();
                                for (var i = selectionState.areaStart; i < selectionState.areaEnd; i++)
                                    selection += (string[i] || '');
                            }
                            attachDialogElement.html('<div class="attach_form ready">' +
                                '<div style="height: 50px;"><label for="id-attach-link">Ссылка:</label>' +
                                '<ul>' +
                                '<li class="form_input_left"></li>' +
                                '<li class="form_input_center">' +
                                '<input type="text" name="attach_link" id="id-attach-link" value="http://">' +
                                '</li>' +
                                '<li class="form_input_right"></li>' +
                                '</ul></div>' +
                                '<div style="height: 50px;"><label for="id-attach-link-text">Текст:</label>' +
                                '<ul>' +
                                '<li class="form_input_left"></li>' +
                                '<li class="form_input_center">' +
                                '<input type="text" name="attach_link_text" id="id-attach-link-text" value="' + selection + '">' +
                                '</li>' +
                                '<li class="form_input_right"></li>' +
                                '</ul></div>' +
                                '<div style="height: 50px;"><button type="button">Добавить</button></div>' +
                                '</div>');

                            attachDialogElement.find('button').on('click', function () {
                                handleAction('getBBCode', 'link');
                            })

                            attachDialogElement.dialog({
                                autoOpen: false,
                                show: "blind",
                                hide: "explode",
                                title: "Вставить ссылку",
                                zIndex: 1012,
                                minHeight: 150
                            });
                            attachDialogElement.dialog("open");
                            attachDialogElement.parent().css('background-color', '#f1f1f1');
                            attachDialogElement.parent().css('border', '1px solid #e0e0e0');
                            $('#id-attach-link').focus();

                        }

                        break;

                    case 'getBBCode':
                        var text = textareaElement.val();
                        var aid = $('input[name=attach_id]:checked').val();
                        if (id) aid = id;

                        var text_before = text.substr(0, textareaElement.prop("selectionStart"));
                        var text_after = text.substr(textareaElement.prop("selectionStart") + selectionState.areaEnd - selectionState.areaStart, text.length);
                        if ($.browser.opera) {
                            text_before = text.substr(0, selectionState.areaStart);
                            text_after = text.substr(selectionState.areaEnd, text.length);
                        }

                        var insert = '';

                        switch (type) {
                            case 'file':
                                insert = '[file]' + aid + '[/file]';
                                break;

                            case 'sample':
                                insert = '[sample]' + aid + '[/sample]';
                                break;

                            case 'image':
                                insert = '[img]' + aid + '[/img]';
                                break;

                            case 'track':
                                insert = '[cjplayer]' + id + '[/cjplayer]';
                                break;

                            case 'video':
                                id = id.split('/watch?v=');
                                if (id.length == 2) {
                                    id = id[1];
                                    id = id.split('&');
                                    id = id[0];
                                    insert = '[utube]' + id + '[/utube]';
                                }
                                break;

                            case 'smile':
                                insert = '';
                                textareaElement.val(text_before + ':' + id + ':' + text_after);
                                break;

                            case 'link':
                                insert = '[url=' + $('#id-attach-link').val() + ']' + $('#id-attach-link-text').val() + '[/url]';
                                attachDialogElement.dialog("close");
                                break;
                        }
                        textareaElement.val(text_before + insert + text_after);
                        textareaElement.caretTo(Number(text_before.length) + Number(insert.length));
                        textareaElement.focus();

                        scope.$apply(function () {
                            scope.modelName = textareaElement.val();
                        });

                        break;

                    case 'playSample':
                        playTrack(type, id);
                        $('.version_play').find('img').attr('src', 'skins/cjclub2/images/play_version.png');
                        $('.version_line_style').css('visibility', 'hidden');
                        $('.version_line_style').find('.version_line_pointer').css('visibility', 'hidden');
                        $('.version_volume_line').find('.version_time').css('visibility', 'hidden');

                        clearInterval(attachObject.sampleTime);
                        if (CjPlayer.isPlaying) {
                            $(id).find('img').attr('src', 'skins/cjclub2/images/pause_version.png');
                            $(id).parents('table').find('.version_line_style').css('visibility', 'visible');
                            $(id).parents('table').find('.version_line_style').find('.version_line_pointer').css('visibility', 'visible');
                            $(id).parents('table').find('.version_volume_line').find('.version_time').css('visibility', 'visible');
                            var attach_id = $(id).parents('table').attr('id');
                            attach_id = attach_id.split('attach-sample-')[1];
                            attachObject.sampleTime = setInterval('attachObject.controller(\'updateSamplePosition\', 310, ' + attach_id + ')', 1000);
                        }
                        break;

                    case 'updateSamplePosition':
                        if (window.frames[0] != 'undefined' && window.frames[0].activePlay || $('.orplay').hasClass('orplaypr') || $('.orlow').hasClass('orlowpr')) {
                            clearInterval(attachObject.sampleTime);
                            $('.version_line_style').css('visibility', 'hidden');
                            $('.version_line_pointer').css('visibility', 'hidden');
                            $('.version_name.version_time').css('visibility', 'hidden');
                            return;
                        }
                        var pos = CjPlayer.getPosition();
                        time = attachObject.formatTime(pos, '2str');
                        $('#id-sample-time-' + id).text(time);
                        var time = $('#id-sample-' + id).parents('table').find('.version_line_style.version_time_curr').attr('timelength');
                        $('#id-sample-' + id).parents('table').find('.version_line_style.version_time_curr').slider({value: 100 * pos / time });
                        break;

                    case 'samplePlayerInit':
                        if ($('.wiki_new').length && !type) {
                            $('.wiki_new').each(function () {
                                if (!$(this).hasClass('ready')) {
                                    var text = $(this).html();
                                    $(this).html($.wikiText(text));
                                    $(this).addClass('ready');
                                }
                            });
                        }

                        if ($('.sample_player').length) {
                            /* Перемотка сэмпла */
                            $(".sample_player").find(".version_line_style").slider({animate: true, max: 100, min: 1, value: 1, stop: function (event, ui) {
                                CjPlayer.setPosition($(this).slider('value') * $(this).attr('timelength') / 100);
                            }});
                            /* Изменение громкости */
                            $(".sample_player").find('.version_volume_line').find(".version_line_style").slider({animate: true, max: 100, min: 1, value: 75, stop: function (event, ui) {
                                CjPlayer.setVolume(ui.value);
                                $('.version_volume_line').slider({value: ui.value});
                                $('iframe#player-frame').contents().find('#slider').slider({value: ui.value});
                                $('.trackBar').slider({value: ui.value});
                                $(this).css('top', '-10px');
                            }});
                            /* Бегунок */
                            $(".sample_player").find(".ui-slider-handle").addClass("version_line_pointer");
                        }
                        break;

                    case 'getAttachBind':
                        ajaxRequest('FilesAdministration/getAttachBind', {
                            data: 'id=' + id,
                            onSuccess: function (response) {
                                attachDialogElement.html(response);
                                attachDialogElement.dialog({
                                    autoOpen: false,
                                    show: "blind",
                                    hide: "explode",
                                    title: "Вложенность",
                                    width: 350
                                });
                                attachDialogElement.dialog("open");
                            }
                        });
                        break;
                }
            };

            $timeout(function () {
                textareaElement.markItUp(wikiSettings, {}, function () {
                    handleAction.apply(null, arguments);
                });
                textareaElement.val(scope.initText);
            });

            textareaElement.on('insert_markup', function () {
                scope.$apply(function () {
                    scope.modelName = textareaElement.val();
                });
            });

            attachDialogElement.on('close', function () {
                scope.$apply(function () {
                    scope.modelName = textareaElement.val();
                });
            });

        }
    }
}]);