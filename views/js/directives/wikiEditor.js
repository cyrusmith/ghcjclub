App.directive('wikiEditor', function () {
    return {
        restrict: 'A',
        controller: ['$scope', '$element', 'Notifications', function ($scope, $element, Notifications) {

            function goTo(id, n) {
                var o = document.getElementById(id);

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

            if (angular.isObject(window.attachObject)) {
                return;
            }

            window.attachObject = {

                showSmiles: function (id, html, smile) {
                    var smileMap = [
                        [':sorry:', '<img src="views/main/img/smiles/sorry.gif" />'],
                        [':zlost:', '<img src="views/main/img/smiles/zlost.gif" />'],
                        [':horror:', '<img src="views/main/img/smiles/horror.gif" />'],
                        [':music:', '<img src="views/main/img/smiles/music.gif" />'],
                        [':udivlenie:', '<img src="views/main/img/smiles/udivlenie.gif" />'],
                        [':yazik:', '<img src="views/main/img/smiles/yazik.gif" />'],
                        [':fuck:', '<img src="views/main/img/smiles/fuck.gif" />'],
                        [':dance1:', '<img src="views/main/img/smiles/dance1.gif" />'],
                        [':in_love:', '<img src="views/main/img/smiles/in_love.gif" />'],
                        [':shake:', '<img src="views/main/img/smiles/shake.gif" />'],
                        [':cool:', '<img src="views/main/img/smiles/cool.gif" />'],
                        [':dance2:', '<img src="views/main/img/smiles/dance2.gif" />'],
                        [':cry:', '<img src="views/main/img/smiles/cry.gif" />'],
                        [':dance3:', '<img src="views/main/img/smiles/dance3.gif" />'],
                        [':stesnenie:', '<img src="views/main/img/smiles/stesnenie.gif" />'],
                        [':1:', '<img src="views/main/img/smiles/1.gif" />'],
                        [':laugh:', '<img src="views/main/img/smiles/laugh.gif" />'],
                        [':idea:', '<img src="views/main/img/smiles/idea.gif" />'],
                        [':winking:', '<img src="views/main/img/smiles/winking.gif" />'],
                        [':sad:', '<img src="views/main/img/smiles/sad.gif" />'],
                        [':smile:', '<img src="views/main/img/smiles/smile.gif" />'],
                        [':good:', '<img src="views/main/img/smiles/good.gif" />'],
                        [':help:', '<img src="views/main/img/smiles/help.gif" />'],
                        [':rok:', '<img src="views/main/img/smiles/rok.gif" />'],
                        [':alkash:', '<img src="views/main/img/smiles/alkash.gif" />'],
                        [':mega_rzhach:', '<img src="views/main/img/smiles/mega_rzhach.gif" />'],
                        [':crazy:', '<img src="views/main/img/smiles/crazy.gif" />'],
                        [':shock:', '<img src="views/main/img/smiles/shock.gif" />'],
                        [':gipno:', '<img src="views/main/img/smiles/gipno.gif" />'],
                        [':ob_stenu:', '<img src="views/main/img/smiles/ob_stenu.gif" />'],
                        [':sleep:', '<img src="views/main/img/smiles/sleep.gif" />'],
                        [':wacko2:', '<img src="views/main/img/smiles/wacko2.gif" />'],
                        [':unsure2:', '<img src="views/main/img/smiles/unsure2.gif" />'],
                        [':wink2:', '<img src="views/main/img/smiles/wink2.gif" />'],
                        [':rolleyes2:', '<img src="views/main/img/smiles/rolleyes2.gif" />'],
                        [':tongue2:', '<img src="views/main/img/smiles/tongue2.gif" />'],
                        [':cray2:', '<img src="views/main/img/smiles/cray2.gif" />'],
                        [':wub2:', '<img src="views/main/img/smiles/wub2.gif" />'],
                        [':yahoo2:', '<img src="views/main/img/smiles/yahoo2.gif" />'],
                        [':shok2:', '<img src="views/main/img/smiles/shok2.gif" />'],
                        [':rofl2:', '<img src="views/main/img/smiles/rofl2.gif" />'],
                        [':lol2:', '<img src="views/main/img/smiles/lol2.gif" />'],
                        [':Koshechka2:', '<img src="views/main/img/smiles/Koshechka2.gif" />'],
                        [':friends2:', '<img src="views/main/img/smiles/friends2.gif" />'],
                        [':fool2:', '<img src="views/main/img/smiles/fool2.gif" />'],
                        [':drinks2:', '<img src="views/main/img/smiles/drinks2.gif" />'],
                        [':blink2:', '<img src="views/main/img/smiles/blink2.gif" />'],
                        [':argue2:', '<img src="views/main/img/smiles/argue2.gif" />'],
                        [':angry2:', '<img src="views/main/img/smiles/angry2.gif" />'],
                        [':net_zubov2:', '<img src="views/main/img/smiles/net_zubov2.gif" />'],
                        [':smile2:', '<img src="views/main/img/smiles/smile2.gif" />'],
                        [':sad2:', '<img src="views/main/img/smiles/sad2.gif" />'],
                        [':laugh2:', '<img src="views/main/img/smiles/laugh2.gif" />'],
                        [':crazy2:', '<img src="views/main/img/smiles/crazy2.gif" />'],
                        [':cool2:', '<img src="views/main/img/smiles/cool2.gif" />'],
                        [':laugh3:', '<img src="views/main/img/smiles/laugh3.gif" />']
                    ];

                    if (smile)
                        for (var i = 0; smileMap[i]; i++)
                            if (smileMap[i][0] == ':' + smile + ':')
                                return smileMap[i][1];
                    if (id[0] != '#')
                        id = "#" + id;
                    if ($("#jquery-lightbox #comments-window").is(':visible'))
                        id = "#jquery-lightbox #comments-window " + id;
                    var obj_pl = $(id + "PLACE");
                    if (typeof obj_pl.html() == 'string')
                        if (obj_pl.html().length != 0) {
                            obj_pl.html('');
                            return;
                        }
                    var text = "";
                    for (var i = 0; smileMap[i]; i++) {
                        text += "<span class='smile' onMouseOver=\"this.className='smileOver';\" onMouseOut=\"this.className='smile';\" onClick=\"window.attachObject.insertSmile('" + id + "','" + smileMap[i][0] + "');\">" + smileMap[i][1] + "</span>";
                    }
                    if (html)
                        return text;
                    else obj_pl.html(text);
                },

                insertSmile: function (id, smile) {
                    var obj_ta = $(id)[0];
                    if (!obj_ta)
                        obj_ta = document.getElementById(id);
                    id = obj_ta.id;
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

                        goTo(id, endPos + smile.length);
                    }
                    // Для остальных ;)
                    else {
                        obj_ta.value += smile;
                    }

                    if (typeof $(".attach_dialog:last").html() == 'string') $(".attach_dialog:last").dialog("close")
                },

                controller: function (action, type, id) {

                    var dialog = $('.attach_dialog:last');
                    var area;
                    if (this.currentArea)
                        area = this.currentArea;
                    else {
                        var areaId = $('textarea').attr('id');
                        if (typeof areaId === 'undefinied')
                            areaId = '';
                        else areaId = '#' + areaId;
                        area = $('textarea' + areaId);
                    }
                    switch (action) {
                        case 'getAttachForm':
                            var area_id;
                            if (type == 'smile') {
                                if (this.currentArea)
                                    area_id = this.currentArea.attr('id');
                                else area_id = $('textarea').attr('id');

                                dialog.html(window.attachObject.showSmiles(area_id, true));
                                dialog.dialog({
                                    autoOpen: false,
                                    show: "blind",
                                    hide: "explode",
                                    title: "Смайлы",
                                    zIndex: 1012
                                });
                                dialog.dialog("open");
                                dialog.parent().css('background-color', '#f1f1f1');
                                dialog.parent().css('border', '1px solid #e0e0e0');
                            } else if (type != 'link') {

                                //TODO remove this stub function and use backend instead
                                function ajaxRequest(url, options) {
                                    var htmls = {
                                        file: {size: "2Mb", html: "<div class='attach_error'><\/div>\r\n            <div class='attach_form'>\r\n              <form class='ajax attachment_upload' method='post' action='files\/upload' onsubmit='return ajaxFormSubmit(this, function(data){attachObject.controller(\"getBBCode\", \"file\", data);$(\".attach_dialog:last\").dialog(\"close\"); attachObject.currentArea.focus();}, {onError: function(data) { $(\".attach_error:last\").html(\"<h4>\" + data + \"<\/h4>\") }})'>\r\n                <table width='100%' border='0' cellspacing='3' cellpadding='0'>\r\n                  <tr>\r\n                    <td>\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0444\u0430\u0439\u043b:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><input type='file' id='file_value' name='file'><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u0418\u043c\u044f:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='publicName'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435:<\/td>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='description'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><button onclick='attachObject.currentArea.focus()' type='submit'>\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c<\/button><\/td>\r\n                  <\/tr>\r\n                <\/table>\r\n              <\/form>\r\n            <\/div>\r\n            "},
                                        sample: {size: "2Mb", html: "<div class='attach_error'><\/div>\r\n            <div class='attach_form'>\r\n              <form class='ajax attachment_upload' method='post' action='files\/upload' onsubmit='return ajaxFormSubmit(this, function(data){attachObject.controller(\"getBBCode\", \"sample\", data);$(\".attach_dialog:last\").dialog(\"close\"); attachObject.currentArea.focus();}, {onError: function(data) { $(\".attach_error:last\").html(\"<h4>\" + data + \"<\/h4>\") }})'>\r\n                <table width='100%' border='0' cellspacing='3' cellpadding='0'>\r\n                  <tr>\r\n                    <td>\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0444\u0430\u0439\u043b:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><input type='file' id='file_value' name='file'><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u0418\u043c\u044f:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='publicName'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435:<\/td>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='description'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><button onclick='attachObject.currentArea.focus()' type='submit'>\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c<\/button><\/td>\r\n                  <\/tr>\r\n                <\/table>\r\n              <\/form>\r\n            <\/div>\r\n            "},
                                        image: {size: "2Mb", html: "<div class='attach_error'><\/div>\r\n            <div class='attach_form'>\r\n              <form class='ajax attachment_upload' method='post' action='files\/upload' onsubmit='return ajaxFormSubmit(this, function(data){attachObject.controller(\"getBBCode\", \"image\", data);$(\".attach_dialog:last\").dialog(\"close\"); attachObject.currentArea.focus();}, {onError: function(data) { $(\".attach_error:last\").html(\"<h4>\" + data + \"<\/h4>\") }})'>\r\n                <table width='100%' border='0' cellspacing='3' cellpadding='0'>\r\n                  <tr>\r\n                    <td>\u0412\u044b\u0431\u0435\u0440\u0438\u0442\u0435 \u0444\u0430\u0439\u043b:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><input type='file' id='file_value' name='file'><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u0418\u043c\u044f:<\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='publicName'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td>\u041e\u043f\u0438\u0441\u0430\u043d\u0438\u0435:<\/td>\r\n                  <tr>\r\n                    <td><ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' name='description'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul><\/td>\r\n                  <\/tr>\r\n                  <tr>\r\n                    <td><button onclick='attachObject.currentArea.focus()' type='submit'>\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c<\/button><\/td>\r\n                  <\/tr>\r\n                <\/table>\r\n              <\/form>\r\n            <\/div>\r\n            "},
                                        track: {size: "2Mb", html: "\r\n                <div class='attach_form'>\r\n                    <label for='id-track-bbcode'>\u0412\u0432\u0435\u0434\u0438\u0442\u0435 ID \u0442\u0440\u0435\u043a\u0430 \u0438\u043b\u0438 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u043d\u0435\u0433\u043e<\/label><br/>\r\n                    <ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' id='id-track-bbcode'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul>\r\n                    <button onclick='attachObject.controller(\"getBBCode\", \"track\", $(\"#id-track-bbcode\").val()); $(\".attach_dialog:last\").dialog(\"close\")'>\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c<\/button>\r\n                <\/div><br/>\r\n            <\/tbody><\/table>"},
                                        video: {size: "2Mb", html: "\r\n                <div class='attach_form ready'>\r\n                    <label for='id-track-bbcode'>\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0432\u0438\u0434\u0435\u043e<\/label><br/>\r\n                    <ul>\r\n                        <li class='form_input_left'><\/li>\r\n                        <li class='form_input_center'>\r\n                          <input type='text' id='id-track-bbcode'>\r\n                        <\/li>\r\n                        <li class='form_input_right'><\/li>\r\n                      <\/ul>\r\n                    <button onclick='attachObject.controller(\"getBBCode\", \"video\", $(\"#id-track-bbcode\").val()); $(\".attach_dialog:last\").dialog(\"close\")'>\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c<\/button>\r\n                <\/div>\r\n            "}
                                    }
                                    options.onSuccess(htmls[type] || {});
                                }

                                ajaxRequest('files/getAttachForm', {
                                    data: 'type=' + type,
                                    onSuccess: function (response) {
                                        if ($.browser.opera && typeof $('form.attacment_upload').html() != 'string' && type != 'track' && type != 'video') {
                                            dialog.html(response.html);
                                            var html = $('div.attach_form').html();
                                            var onclick = 'return ajaxFormSubmit(this, function(data){attachObject.controller(\'getBBCode\', \'' + type + '\', data);' +
                                                '$(\'.attach_dialog:last\').dialog(\'close\');' +
                                                ' attachObject.currentArea.focus();},' +
                                                ' {onError: function(data) { $(\'.attach_error:last\').html(\'<h4>\' + data + \'</h4>\') }})';
                                            $('div.attach_form:first').after('<div class="attach_form ready"><form class="ajax attacment_upload" method="post" action="files/upload" onsubmit="' + onclick + '">' + html + '</form></div>');

                                            $('div.attach_form').each(function (i, object) {
                                                if (!$(object).hasClass('ready'))
                                                    $(object).remove();
                                            });
                                        }
                                        else dialog.html(response.html);

                                        if (typeof $('#id-file-list').html() !== 'undefined') {
                                            $('#id-file-list').after('<div id="id-file-pager" class="pager">' +
                                                '<form>' +
                                                '<ul>' +
                                                '<li><a href="javascript:void(0)" class="mail_arrows"><img class="first" src="skins/cjclub2/images/mail_arrow_left.png" /></a></li>' +
                                                '<li style="margin-right:2px;"><a href="javascript:void(0)" class="mail_arrows"><img class="prev" src="skins/cjclub2/images/mail_arrow_left_min.png" /></a></li>' +
                                                '<li class="form_input_left"></li>' +
                                                '<li class="form_input_center" style="width: 50px;">' +
                                                '<input type="text" class="pagedisplay">' +
                                                '</li>' +
                                                '<li class="form_input_right"></li>' +
                                                '<li style="margin-left:2px;"><a href="javascript:void(0)" class="mail_arrows"><img class="next" src="skins/cjclub2/images/mail_arrow_right.png" /></a></li>' +
                                                '<li><a href="javascript:void(0)" class="mail_arrows"><img class="last" src="skins/cjclub2/images/mail_arrow_right_min.png" /></a></li>' +
                                                '<li style="float: right; margin-right: -10px;">' +
                                                '<select class="pagesize">' +
                                                '<option value="5" selected="selected">5</option>' +
                                                '<option value="10">10</option>' +
                                                '<option value="20">20</option>' +
                                                '</select>' +
                                                '</li>' +
                                                '</ul>' +
                                                '</form>' +
                                                '</div>');
                                            $('#id-file-list').tablesorter().tablesorterPager({container: $("#id-file-pager"), size: 5});

                                            if (typeof ProgressUploader != 'undefined') {
                                                ProgressUploader.init();
                                                $('form.progressuploader-autoform').attr('action', 'http://cgi.cjclub.ru/cgi-bin/uploader.cgi');
                                            }
                                        }
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
                                        dialog.dialog({
                                            autoOpen: false,
                                            show: "blind",
                                            hide: "explode",
                                            title: title
                                        });
                                        dialog.dialog("open");

                                        var titleEl = dialog.parent().find('.ui-dialog-title');

                                        titleEl.html(titleEl.html().replace('|', '<br>'));
                                        dialog.parent().css('background-color', '#f1f1f1');
                                        dialog.parent().css('border', '1px solid #e0e0e0');
                                    }
                                });
                            } else {
                                var textarea = document.getElementById(area.attr('id'));
                                attachObject.areaStart = textarea.selectionStart;
                                attachObject.areaEnd = textarea.selectionEnd;
                                var selection = '';
                                if (attachObject.areaStart != attachObject.areaEnd) {
                                    var string = area.val();
                                    for (var i = attachObject.areaStart; i < attachObject.areaEnd; i++)
                                        selection += (string[i] || '');
                                }
                                dialog.html('<div class="attach_form ready">' +
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
                                    '<div style="height: 50px;"><button onclick="attachObject.controller(\'getBBCode\', \'link\')" type="button">Добавить</button></div>' +
                                    '</div>');
                                dialog.dialog({
                                    autoOpen: false,
                                    show: "blind",
                                    hide: "explode",
                                    title: "Вставить ссылку",
                                    zIndex: 1012,
                                    minHeight: 150
                                });
                                dialog.dialog("open");
                                dialog.parent().css('background-color', '#f1f1f1');
                                dialog.parent().css('border', '1px solid #e0e0e0');
                                $('#id-attach-link').focus();
                            }
                            break;

                        case 'getBBCode':
                            var text = area.val();
                            var aid = $('input[name=attach_id]:checked').val();
                            if (id) aid = id;

                            var text_before = text.substr(0, area.prop("selectionStart"));
                            var text_after = text.substr(area.prop("selectionStart") + attachObject.areaEnd - attachObject.areaStart, text.length);
                            if ($.browser.opera) {
                                text_before = text.substr(0, attachObject.areaStart);
                                text_after = text.substr(attachObject.areaEnd, text.length);
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
                                    id = id[1];
                                    id = id.split('&');
                                    id = id[0];
                                    insert = '[utube]' + id + '[/utube]';
                                    break;

                                case 'smile':
                                    insert = '';
                                    area.val(text_before + ':' + id + ':' + text_after);
                                    break;

                                case 'link':
                                    insert = '[url=' + $('#id-attach-link').val() + ']' + $('#id-attach-link-text').val() + '[/url]';
                                    dialog.dialog("close");
                                    break;
                            }
                            area.val(text_before + insert + text_after);
                            area.caretTo(Number(text_before.length) + Number(insert.length));
                            area.focus();
                            break;

                        case 'replaceBBCode':
                            var html = '';
                            var data = 'attach_id=' + id + '&type=' + type;
                            if (attachObject.width > 0)
                                data += '&width=' + attachObject.width;
                            $.ajax({
                                url: 'files/getAttachView',
                                type: "POST",
                                data: data,
                                async: false,
                                success: function (data) {
                                    var json = $.parseJSON(data);
                                    html = json.response;
                                }
                            });
                            return html;
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
                                    var dialog = $('.attach_dialog:last');
                                    dialog.html(response);
                                    dialog.dialog({
                                        autoOpen: false,
                                        show: "blind",
                                        hide: "explode",
                                        title: "Вложенность",
                                        width: 350
                                    });
                                    dialog.dialog("open");
                                    dialog.parent().css('background-color', '#f1f1f1');
                                    dialog.parent().css('border', '1px solid #e0e0e0');
                                }
                            })
                            break;
                    }
                }


            };
        }],
        link: function (scope, element, attributes) {
            $(element).markItUp(wikiSettings);
        }
    }
});