if(!Array.prototype.indexOf)
	Array.prototype.indexOf = function(searchElement, fromIndex){
		for(var i = fromIndex||0, length = this.length; i<length; i++)
			if(this[i] === searchElement) return i;
		return -1
	};

$.browser = {
	msie: /msie/.test(navigator.userAgent.toLowerCase())
};

/*
 * Placeholder plugin for jQuery
 */
(function(b){function d(a){this.input=a;a.attr("type")=="password"&&this.handlePassword();b(a[0].form).submit(function(){if(a.hasClass("placeholder")&&a[0].value==a.attr("placeholder"))a[0].value=""})}d.prototype={show:function(a){if(this.input[0].value===""||a&&this.valueIsPlaceholder()){if(this.isPassword)try{this.input[0].setAttribute("type","text")}catch(b){this.input.before(this.fakePassword.show()).hide()}this.input.addClass("placeholder");this.input[0].value=this.input.attr("placeholder")}},
	hide:function(){if(this.valueIsPlaceholder()&&this.input.hasClass("placeholder")&&(this.input.removeClass("placeholder"),this.input[0].value="",this.isPassword)){try{this.input[0].setAttribute("type","password")}catch(a){}this.input.show();this.input[0].focus()}},valueIsPlaceholder:function(){return this.input[0].value==this.input.attr("placeholder")},handlePassword:function(){var a=this.input;a.attr("realType","password");this.isPassword=!0;if(b.browser.msie&&a[0].outerHTML){var c=b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
		"type=$1text$1"));this.fakePassword=c.val(a.attr("placeholder")).addClass("placeholder").focus(function(){a.trigger("focus");b(this).hide()});b(a[0].form).submit(function(){c.remove();a.show()})}}};var e=!!("placeholder"in document.createElement("input"));b.fn.placeholder=function(){return e?this:this.each(function(){var a=b(this),c=new d(a);c.show(!0);a.focus(function(){c.hide()});a.blur(function(){c.show(!1)});b.browser.msie&&(b(window).load(function(){a.val()&&a.removeClass("placeholder");c.show(!0)}),
	a.focus(function(){if(this.value==""){var a=this.createTextRange();a.collapse(!0);a.moveStart("character",0);a.select()}}))})}})(jQuery);


/*
 * Tiny Carousel 1.9
 */
(function($){"use strict";$.tiny=$.tiny||{};$.tiny.carousel={options:{start:1,display:1,axis:'x',controls:true,pager:false,interval:false,intervaltime:3000,rewind:true,animation:true,duration:300,callback:null}};$.fn.tinycarousel_start=function(){$(this).data('tcl').start()};$.fn.tinycarousel_stop=function(){$(this).data('tcl').stop()};$.fn.tinycarousel_move=function(a){$(this).data('tcl').move(a-1,true)};function Carousel(d,e){var f=this,oViewport=$('.viewport:first',d),oContent=$('.overview:first',d),oPages=oContent.children(),oBtnNext=$('.next:first',d),oBtnPrev=$('.prev:first',d),oPager=$('.pager:first',d),iPageSize=0,iSteps=0,iCurrent=0,oTimer=undefined,bPause=false,bForward=true,bAxis=e.axis==='x';function setButtons(){if(e.controls){oBtnPrev.toggleClass('disable',iCurrent<=0);oBtnNext.toggleClass('disable',!(iCurrent+1<iSteps))}if(e.pager){var a=$('.pagenum',oPager);a.removeClass('active');$(a[iCurrent]).addClass('active')}}function setPager(a){if($(this).hasClass("pagenum")){f.move(parseInt(this.rel,10),true)}return false}function calcStep(){iCurrent=iCurrent+1===iSteps?-1:iCurrent;bForward=iCurrent+1===iSteps?false:iCurrent===0?true:bForward;return bForward?1:-1}function setTimer(){if(e.interval&&!bPause){clearTimeout(oTimer);oTimer=setTimeout(function(){f.move(calcStep())},e.intervaltime)}}function setEvents(){if(e.controls&&oBtnPrev.length>0&&oBtnNext.length>0){oBtnPrev.click(function(){if(!$(this).hasClass('disable')){f.move(-1)}else if(e.rewind){f.move(iSteps-1)}return false});oBtnNext.click(function(){if(!$(this).hasClass('disable')){f.move(1)}else if(e.rewind){f.move(calcStep())}return false})}if(e.interval){d.hover(f.stop,f.start)}if(e.pager&&oPager.length>0){$('a',oPager).click(setPager)}}this.stop=function(){clearTimeout(oTimer);bPause=true};this.start=function(){bPause=false;setTimer()};this.move=function(a,b){iCurrent=b?a:iCurrent+=a;if(iCurrent>-1&&iCurrent<iSteps){var c={};c[bAxis?'marginLeft':'marginTop']=-(iCurrent*(iPageSize*e.display));oContent.animate(c,{queue:false,duration:e.animation?e.duration:0,complete:function(){if(typeof e.callback==='function'){e.callback.call(this,oPages[iCurrent],iCurrent)}}});setButtons();setTimer()}};function initialize(){iPageSize=bAxis?$(oPages[0]).outerWidth(true):$(oPages[0]).outerHeight(true);var a=Math.max(0,Math.floor(((bAxis?oViewport.outerWidth():oViewport.outerHeight())/(iPageSize*e.display))-1));iSteps=Math.max(1,Math.round(oPages.length/e.display)-a);iCurrent=Math.min(iSteps,Math.max(1,e.start))-2;oContent.css(bAxis?'width':'height',(iPageSize*oPages.length));f.move(1);setEvents();return f}return initialize()}$.fn.tinycarousel=function(a){var b=$.extend({},$.tiny.carousel.options,a);this.each(function(){$(this).data('tcl',new Carousel($(this),b))});return this}}(jQuery));

// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2007-2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function($) {
	$.fn.markItUp = function(settings, extraSettings) {
		var options, ctrlKey, shiftKey, altKey;
		ctrlKey = shiftKey = altKey = false;

		options = {	id:						'',
			nameSpace:				'',
			root:					'',
			previewInWindow:		'', // 'width=800, height=600, resizable=yes, scrollbars=yes'
			previewAutoRefresh:		true,
			previewPosition:		'after',
			previewTemplatePath:	'~/templates/preview.html',
			previewParser:			false,
			previewParserPath:		'',
			previewParserVar:		'data',
			resizeHandle:			true,
			beforeInsert:			'',
			afterInsert:			'',
			onEnter:				{},
			onShiftEnter:			{},
			onCtrlEnter:			{},
			onTab:					{},
			markupSet:			[	{ /* set */ } ],
			buildFlag:              1
		};
		$.extend(options, settings, extraSettings);

		// compute markItUp! path
		if (!options.root) {
			$('script').each(function(a, tag) {
				miuScript = $(tag).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);
				if (miuScript !== null) {
					options.root = miuScript[1];
				}
			});
		}

		return this.each(function() {
			var $$, textarea, levels, scrollPosition, caretPosition, caretOffset,
				clicked, hash, header, footer, previewWindow, template, iFrame, abort;
			$$ = $(this);
			textarea = this;
			levels = [];
			abort = false;
			scrollPosition = caretPosition = 0;
			caretOffset = -1;

			options.previewParserPath = localize(options.previewParserPath);
			options.previewTemplatePath = localize(options.previewTemplatePath);

			// apply the computed path to ~/
			function localize(data, inText) {
				if (inText) {
					return 	data.replace(/("|')~\//g, "$1"+options.root);
				}
				return 	data.replace(/^~\//, options.root);
			}

			// init and build editor
			function init() {
				id = ''; nameSpace = '';
				if (options.id) {
					id = 'id="'+options.id+'"';
				} else if ($$.attr("id")) {
					id = 'id="markItUp'+($$.attr("id").substr(0, 1).toUpperCase())+($$.attr("id").substr(1))+'"';

				}
				if (options.nameSpace) {
					nameSpace = 'class="'+options.nameSpace+'"';
				}
				$$.wrap('<div '+nameSpace+'></div>');
				$$.wrap('<div '+id+' class="markItUp"></div>');
				$$.wrap('<div class="markItUpContainer"></div>');
				$$.addClass("markItUpEditor");

				// add the header before the textarea
				header = $('<div class="bbcode_top markItUpHeader"></div>').insertBefore($$);
				$(dropMenus(options.markupSet)).appendTo(header);

				// add the footer after the textarea
				footer = $('<div class="markItUpFooter"></div>').insertAfter($$);

				// add the resize handle after textarea
				if (options.resizeHandle === true && $.browser.safari !== true) {
					resizeHandle = $('<div class="markItUpResizeHandle"></div>')
						.insertAfter($$)
						.bind("mousedown", function(e) {
							var h = $$.height(), y = e.clientY, mouseMove, mouseUp;
							mouseMove = function(e) {
								$$.css("height", Math.max(20, e.clientY+h-y)+"px");
								return false;
							};
							mouseUp = function(e) {
								$("html").unbind("mousemove", mouseMove).unbind("mouseup", mouseUp);
								return false;
							};
							$("html").bind("mousemove", mouseMove).bind("mouseup", mouseUp);
						});
					footer.append(resizeHandle);
				}

				// listen key events
				$$.keydown(keyPressed).keyup(keyPressed);

				// bind an event to catch external calls
				$$.bind("insertion", function(e, settings) {
					if (settings.target !== false) {
						get();
					}
					if (textarea === $.markItUp.focused) {
						markup(settings);
					}
				});

				// remember the last focus
				$$.focus(function() {
					$.markItUp.focused = this;
				});
			}

			// recursively build header with dropMenus from markupset
			function dropMenus(markupSet) {
				var ul = "<div>", i = 0;
				//$('div:hover > div', $(ul)).css('display', 'block');
				ul += '<div class="bbcode_group">';

				//$('<li class="bbcode_button_left"></li>').appendTo(ul);
				$.each(markupSet, function(index) {
					var button = this, t = '', text = '', custom = '', title, li, j;
					title = (button.key) ? (button.name||'')+' [Ctrl+'+button.key+']' : (button.name||'');
					switch (button.name) {
						case 'H1':
							title = 'Заголовок 1-го уровня';
							break;

						case 'H2':
							title = 'Заголовок 2-го уровня';
							break;

						case 'H3':
							title = 'Заголовок 3-го уровня';
							break;

						case 'b':
							title = 'Жирный текст';
							break;

						case 'i':
							title = 'Наклонный текст';
							break;

						case 'U':
							title = 'Подчёркнутый текст';
							break;

						case 'align':
							title = 'Сдвиг текста';
							break;

						case 'picture':
							title = 'Вставка картинки';
							break;

						case 'list_num':
							title = 'Элемент числового списка';
							break;

						case 'list_num_sub':
							title = 'Подэлемент числового списка';
							break;

						case 'list':
							title = 'Элемент маркированного списка';
							break;

						case 'list_sub':
							title = 'Подэлемент маркированного списка';
							break;

						case 'link':
							title = 'Ссылка';
							break;

						case 'smile':
							title   = 'Смайлы';
							custom  = 'custom_a';
							text    = 'Смайлы';
							onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'smile\')"';
							break;
					}
					if (button.preview) {
						title = button.preview;
						text  = button.preview;
						button.name  = 'custom_a';
						custom  = 'custom_a';
					}
					if (button.nextline) {
						ul += '</div><div class="bbcode_line">';
						/*
						$('<li class="bbcode_button_right"></li>').appendTo(ul);
						$('<br/><br/>').appendTo(ul);
						$('<li class="bbcode_button_left"></li>').appendTo(ul);
						*/
					}

					if (button.name) {
						var onclick;
						switch (button.name) {

							case 'box':
							case 'file':
								title = 'Загрузка архивов';
								text  =  '';
								button.name  = 'box';
								custom  = 'box';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'file\')"';
								break;

							case 'dynamic':
							case 'sample':
								title = 'Загрузка семпла';
								text  =  '';
								button.name  = 'dynamic';
								custom  = 'dynamic';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'sample\')"';
								break;

							case 'image':
								title = 'Загрузка картинки';
								text  =  '';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'image\')"';
								break;

							case 'music':
								title = 'Прикрепить трек';
								text  =  '';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'track\')"';
								break;

							case 'video':
								title = 'Прикрепить видео';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'video\')"';
								break;

							case 'link':
								title = 'Вставить ссылку';
								onclick = 'onclick = "attachObject.controller(\'getAttachForm\', \'link\')"';
								break;
						}
					}
					key   = (button.key) ? 'accesskey="'+button.key+'"' : '';
					key   = (button.key) ? 'accesskey="'+button.key+'"' : '';
					if (button.separator) {
						ul += '</div><div class="bbcode_group">';
						/*
						$('<li class="bbcode_button_right"></li>').appendTo(ul);
						$('<li class="bbcode_button_left"></li>').appendTo(ul);
						*/
					} else {
						i++;
						for (j = levels.length -1; j >= 0; j--) {
							t += levels[j]+"-";
						}
						li = '<div class=" bbcode_button_center markItUpButton markItUpButton'+t+(i)+' '+(button.className||'')+'"><a href="javascript:void(0)" '+key+' title="'+title+'" class="bbcode_' + button.name + ' '+custom+'" '+(onclick||'')+'>'+text+'</a></div>';
						ul += li;

						// наверняка все бинды снизу работать не будут, какой то говнокод, нужно вынести общие обработчики вверх к общим
						$(li).bind("contextmenu", function() { // prevent contextmenu on mac and allow ctrl+click
								return false;
							}).click(function() {
								return false;
							}).bind("focusin", function(){
								$$.focus();
							}).mouseup(function() {
								if (button.call) {
									eval(button.call)();
								}
								setTimeout(function() { markup(button) },1);
								return false;
							}).hover(function() {
								$('> ul', this).show();
								$(document).one('click', function() { // close dropmenu if click outside
										$('ul ul', header).hide();
									}
								);
							}, function() {
								$('> ul', this).hide();
							});
							//).appendTo(ul);

						/*
						if (typeof markupSet[Number(index) + Number(1)] != 'undefined')
							if (typeof markupSet[Number(index) + Number(1)].separator == 'undefined' && !button.seperator)
								$('<div class="bbcode_button_seperator"></li>').appendTo(ul);
						*/
						if (button.dropMenu) {
							levels.push(i);
							$(li).addClass('markItUpDropMenu').append(dropMenus(button.dropMenu));
						}
					}
				});
				text = '';
				custom = '';
				//$('<li class="bbcode_button_right"></li>').appendTo(ul);
				levels.pop();
				ul += '</div>';
				return ul;
			}

			// markItUp! markups
			function magicMarkups(string) {
				if (string && !options.buildFlag) {
					string = string.toString();
					string = string.replace(/\(\!\(([\s\S]*?)\)\!\)/g,
						function(x, a) {
							var b = a.split('|!|');
							if (altKey === true) {
								return (b[1] !== undefined) ? b[1] : b[0];
							} else {
								return (b[1] === undefined) ? "" : b[0];
							}
						}
					);
					// [![prompt]!], [![prompt:!:value]!]
					string = string.replace(/\[\!\[([\s\S]*?)\]\!\]/g,
						function(x, a) {
							var b = a.split(':!:');
							if (abort === true) {
								return false;
							}
							value = prompt(b[0], (b[1]) ? b[1] : '');
							if (value === null) {
								abort = true;
							}
							return value;
						}
					);

					// [url=http://]
					/*string = string.replace(/\[url=http:\/\/\]/,
					 function(x, a) {
					 var str = x.split('[url=');
					 str = str[1].split(']');
					 str = str[0];
					 if (abort === true) {
					 return false;
					 }
					 value = prompt('Ссылка', (str) ? str : 'http://');
					 if (value === null) {
					 abort = true;
					 }
					 return '[url=' + value + ']';
					 }
					 );*/
					return string;
				}
				return "";
			}

			// prepare action
			function prepare(action) {
				if ($.isFunction(action)) {
					action = action(hash);
				}
				return magicMarkups(action);
			}

			// build block to insert
			function build(string, size) {
				var openWith 			= prepare(clicked.openWith);
				var placeHolder 		= prepare(clicked.placeHolder);
				var replaceWith 		= prepare(clicked.replaceWith);
				var closeWith 			= prepare(clicked.closeWith);
				var openBlockWith 		= prepare(clicked.openBlockWith);
				var closeBlockWith 		= prepare(clicked.closeBlockWith);
				var multiline 			= clicked.multiline;

				var text = string || selection || replaceWith || placeHolder;
				var curPos = get(true);
				if (size)
					return [openWith.length + curPos[0], text.length];

				if (replaceWith !== "") {
					block = openWith + replaceWith + closeWith;
				} else if (selection === '' && placeHolder !== '') {
					block = openWith + placeHolder + closeWith;
				} else {
					string = string || selection;

					var lines = selection.split(/\r?\n/), blocks = [];

					for (var l=0; l < lines.length; l++) {
						line = lines[l];
						var trailingSpaces;
						if (trailingSpaces = line.match(/ *$/)) {
							blocks.push(openWith + line.replace(/ *$/g, '') + closeWith + trailingSpaces);
						} else {
							blocks.push(openWith + line + closeWith);
						}
					}

					block = blocks.join("\n");
				}

				block = openBlockWith + block + closeBlockWith;

				return {	block:block,
					openWith:openWith,
					replaceWith:replaceWith,
					placeHolder:placeHolder,
					closeWith:closeWith
				};
			}

			// define markup to insert
			function markup(button) {
				var len, j, n, i;
				hash = clicked = button;
				get();
				$.extend(hash, {	line:"",
						root:options.root,
						textarea:textarea,
						selection:(selection||''),
						caretPosition:caretPosition,
						ctrlKey:ctrlKey,
						shiftKey:shiftKey,
						altKey:altKey
					}
				);
				// callbacks before insertion
				prepare(options.beforeInsert);
				prepare(clicked.beforeInsert);
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.beforeMultiInsert);
				}
				$.extend(hash, { line:1 });

				var curPos;
				var realSelection = selection;
				if ((ctrlKey === true && shiftKey === true)) {
					lines = selection.split(/\r?\n/);
					for (j = 0, n = lines.length, i = 0; i < n; i++) {
						if ($.trim(lines[i]) !== '') {
							$.extend(hash, { line:++j, selection:lines[i] } );
							lines[i] = build(lines[i]).block;
						} else {
							lines[i] = "";
						}
					}
					string = { block:lines.join('\n')};
					start = caretPosition;
					len = string.block.length + (($.browser.opera) ? n-1 : 0);
				} else if (ctrlKey === true) {
					curPos = build(selection,true);
					options.buildFlag = 0;
					string = build(selection);
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
					len = len - (string.block.match(/ $/) ? 1 : 0);
					len -= fixIeBug(string.block);
				} else if (shiftKey === true) {
					curPos = build(selection,true);
					options.buildFlag = 0;
					string = build(selection);
					start = caretPosition;
					len = string.block.length;
					len -= fixIeBug(string.block);
				} else {
					curPos = build(selection,true);
					options.buildFlag = 0;
					string = build(selection);
					start = caretPosition + string.block.length ;
					len = 0;
					start -= fixIeBug(string.block);
				}
				if ((selection === '' && string.replaceWith === '')) {
					caretOffset += fixOperaBug(string.block);

					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;

					caretOffset = $$.val().substring(caretPosition,  $$.val().length).length;
					caretOffset -= fixOperaBug($$.val().substring(0, caretPosition));
				}
				$.extend(hash, { caretPosition:caretPosition, scrollPosition:scrollPosition } );

				if (string.block !== selection && abort === false) {
					insert(string.block);
					set(start, len);
				} else {
					caretOffset = -1;
				}
				get();

				$.extend(hash, { line:'', selection:selection });

				// callbacks after insertion
				if ((ctrlKey === true && shiftKey === true) || button.multiline === true) {
					prepare(clicked.afterMultiInsert);
				}
				prepare(clicked.afterInsert);
				prepare(options.afterInsert);

				// refresh preview if opened
				if (previewWindow && options.previewAutoRefresh) {
					refreshPreview();
				}

				if (string.openWith)
					curPos[0] += string.openWith.length;
				if (!realSelection && string.placeHolder)
					curPos[1] += string.placeHolder.length;

				set(curPos[0], curPos[1]);
				// reinit keyevent
				shiftKey = altKey = ctrlKey = abort = false;

				options.buildFlag = 1;
			}

			// Substract linefeed in Opera
			function fixOperaBug(string) {
				if ($.browser.opera) {
					return string.length - string.replace(/\n*/g, '').length;
				}
				return 0;
			}
			// Substract linefeed in IE
			function fixIeBug(string) {
				if ($.browser.msie) {
					return string.length - string.replace(/\r*/g, '').length;
				}
				return 0;
			}

			// add markup
			function insert(block) {
				if (document.selection) {
					var newSelection = document.selection.createRange();
					newSelection.text = block;
				} else {
					textarea.value =  textarea.value.substring(0, caretPosition)  + block + textarea.value.substring(caretPosition + selection.length, textarea.value.length);
				}
			}

			// set a selection
			function set(start, len) {
				if (textarea.createTextRange){
					// quick fix to make it work on Opera 9.5
					if ($.browser.opera && $.browser.version >= 9.5 && len == 0) {
						return false;
					}
					range = textarea.createTextRange();
					range.collapse(true);
					range.moveStart('character', start);
					range.moveEnd('character', len);
					range.select();
				} else if (textarea.setSelectionRange ){
					textarea.setSelectionRange(start, start + len);
				}
				textarea.scrollTop = scrollPosition;
				textarea.focus();
			}

			// get the selection
			function get(size) {
				textarea.focus();
				if (size) {
					var realPos = caretPosition;
				}
				scrollPosition = textarea.scrollTop;
				if (document.selection) {
					selection = document.selection.createRange().text;
					if ($.browser.msie) { // ie
						var range = document.selection.createRange(), rangeCopy = range.duplicate();
						rangeCopy.moveToElementText(textarea);
						caretPosition = -1;
						while(rangeCopy.inRange(range)) {
							rangeCopy.moveStart('character');
							caretPosition ++;
						}
					} else { // opera
						caretPosition = textarea.selectionStart;
					}
				} else { // gecko & webkit
					caretPosition = textarea.selectionStart;

					selection = textarea.value.substring(caretPosition, textarea.selectionEnd);
				}
				if (!size)
					return selection;
				else {
					caretPosition = realPos;
					return [caretPosition, textarea.selectionEnd];
				}
			}

			// open preview window
			function preview() {
				refreshPreview();
			}

			// refresh Preview window
			function refreshPreview() {
				renderPreview();
			}

			function renderPreview() {
				var text = $.wikiText(localize($$.val(), 1));
				text = text.replace(/<p><br>/g, '<p>');
				text = text.replace(/<p><br\/>/g, '<p>');
				if ($('.markItUpPreview').length == 1)
					$('.markItUpPreview').html('<br/><div style="padding-bottom: 0;"><h3><strong>Предпросмотр:</strong></h3></div>' + text);
				else $(textarea).parents('.wiki_editor').find('.markItUpPreview').html('<br/><div style="padding-bottom: 0;"><h3><strong>Предпросмотр:</strong></h3></div>' + text);

				if ($('.sample_player').length) {
					/* Перемотка сэмпла */
					$(".version_time_line").find(".version_line_style").slider({animate: true, max:100, min:1, value: 1, stop: function(event, ui) {
						CjPlayer.setPosition($(this).slider('value') * $(this).attr('timelength') / 100);
					}});
					/* Изменение громкости */
					$(".sample_player").find('.version_volume_line').find(".version_line_style").slider({animate: true, max:100, min:1, value: 75, change: function(event, ui) {
						CjPlayer.setVolume(ui.value);
						$('.version_volume_line').slider({value: ui.value});
						$('iframe#player-frame').contents().find('#slider').slider({value: ui.value});
						$('.trackBar').slider({value: ui.value});
					}});
					/* Бегунок */
					$(".sample_player").find(".ui-slider-handle").addClass("version_line_pointer");
				}
			}

			// set keys pressed
			function keyPressed(e) {
				shiftKey = e.shiftKey;
				altKey = e.altKey;
				ctrlKey = (!(e.altKey && e.ctrlKey)) ? (e.ctrlKey || e.metaKey) : false;

				if (e.type === 'keydown') {
					if (ctrlKey === true) {
						li = $('a[accesskey="'+String.fromCharCode(e.keyCode)+'"]', header).parent('li');
						if (li.length !== 0) {
							ctrlKey = false;
							setTimeout(function() {
								li.triggerHandler('mouseup');
							},1);
							return false;
						}
					}
					if (e.keyCode === 13 || e.keyCode === 10) { // Enter key
						if (ctrlKey === true) {  // Enter + Ctrl
							ctrlKey = false;
							markup(options.onCtrlEnter);
							return options.onCtrlEnter.keepDefault;
						} else if (shiftKey === true) { // Enter + Shift
							shiftKey = false;
							markup(options.onShiftEnter);
							return options.onShiftEnter.keepDefault;
						} else { // only Enter
							markup(options.onEnter);
							return options.onEnter.keepDefault;
						}
					}
					if (e.keyCode === 9) { // Tab key
						if (shiftKey == true || ctrlKey == true || altKey == true) {
							return false;
						}
						if (caretOffset !== -1) {
							get();
							caretOffset = $$.val().length - caretOffset;
							set(caretOffset, 0);
							caretOffset = -1;
							return false;
						} else {
							markup(options.onTab);
							return options.onTab.keepDefault;
						}
					}
				}
			}

			init();
		});
	};

	$.fn.markItUpRemove = function() {
		return this.each(function() {
				var $$ = $(this).unbind().removeClass('markItUpEditor');
				$$.parent('div').parent('div.markItUp').parent('div').replaceWith($$);
			}
		);
	};

	$.markItUp = function(settings) {
		var options = { target:false };
		$.extend(options, settings);
		if (options.target) {
			return $(options.target).each(function() {
				$(this).focus();
				$(this).trigger('insertion', [options]);
			});
		} else {
			$('textarea').trigger('insertion', [options]);
		}
	};
})(jQuery);


/*
 * Kajabity Wiki Text Plugin for jQuery http://www.kajabity.com/jquery-wikitext/
 * http://www.williams-technologies.co.uk
 */
(function(Z){Z.wikiText=function(k){var l=(k||'').toString();var m='';var n=/([^\r\n]*)(\r\n?|\n)/g;var o;var q=0;var r;var t=/^([ \t]*)$/;var u=/^(={1,6})[ \t]+([^=]+)(={1,6})[ \t]*$/;var v=/^[ \t]{0,}\*[ \t]+(.+)$/;var w=/^[ \t]{0,}\*\*[ \t]+(.+)$/;var x=/^[ \t]{0,}#[ \t]+(.+)$/;var y=/^[ \t]{0,}##[ \t]+(.+)$/;var z=/^\{{3}$/;var A=/^\}{3}$/;var B=/^[ \t]+(.+)$/;var C=/^-{4,}$/;var D;var E=false;var F=false;var G=false;var H=false;var I=false;var J=false;var K=false;var L=[];var M=[];var N={italic:"<em>",italic_end:"</em>",monospace:"<tt>",strikethrough:"<strike>",superscript:"<sup>",subscript:"<sub>"};var O={monospace:"</tt>",strikethrough:"</strike>",superscript:"</sup>",subscript:"</sub>"};var P=function(){var a='';var b;while(L.length>0){b=L.pop();a+=O[b];M.push(b)}return a};var Q=function(){m+=P();if(E){m+="</p>\n";E=false}if(F){m+="</li>\n</ol>\n";F=false}if(G){m+="</li>\n</ol>\n";G=false}if(H){m+="</li>\n</ul>\n";H=false}if(J){m+="</p>\n";J=false}};var R=function(){var a='';while(M.length>0){var b=M.pop();a+=N[b];L.push(b)}return a};var S=function(a){var b='';if(Z.inArray(a,L)>-1){var c;do{c=L.pop();b+=O[c];if(c===a){break}M.push(c)}while(c!==a);b+=R()}else{L.push(a);b=N[a]}return b};var T=function(a,p,b,s){var c;if(typeof $(this).attr('id')=='string'){c=$(this).attr('id').split('id="')[1];c=c.split('"')[0];c=$('#'+c).find('textarea').attr('id')}var d=/<img [^<]+\/>/g;var e=ShowSmiles('#'+c,true,p);if(e.match(d).length>1)return':'+p+':';else return e}; var U=function(a,p,b,s){if(a.match(/\[file\]/))return attachObject.controller('replaceBBCode','file',p);if(a.match(/\[sample\]/))return attachObject.controller('replaceBBCode','sample',p);if(a.match(/\[img\]/))return attachObject.controller('replaceBBCode','image',p);if(a.match(/\[cjplayer\]/))return attachObject.controller('replaceBBCode','track',p);if(a.match(/\[cjclub\]/))return attachObject.controller('replaceBBCode','track',p);if(a.match(/\[utube\]/))return attachObject.controller('replaceBBCode','video',p);return'Ошибка!'}; var V=function(a,b){var c=(a||'').toString();var d='';var e;var f=0;var g;var h;var i;var j,link;if(c.indexOf('![')){c=c.split('![');c=c.join('! [')}c=c.replace(/Цитируемый текст:/g,'');d=c;d=d.replace(/\[b\]/g,'<strong>');d=d.replace(/\[\/b\]/g,'</strong>');d=d.replace(/\[i\]/g,'<em>');d=d.replace(/\[\/i\]/g,'</em>');d=d.replace(/\[u\]/g,'<u>');d=d.replace(/\[\/u\]/g,'</u>');d=d.replace(/====\s(.+)\s====/g,'<h4 style="font-size: 12px;">$1</h4>');d=d.replace(/===\s(.+)\s===/g,'<h3 style="font-size: 17px;">$1</h3>');d=d.replace(/==\s(.+)\s==/g,'<h2 style="font-size: 25px;">$1</h2>');d=d.replace(/\[\[Image:([\w\/\&\+\?\.%=(\[\]);,-:]+)\|(\w+)\]\]/g,'<img src="$1" title="$2" alt="$2" style="max-width: 468px;">');d=d.replace(/\[file\](\d+)\[\/file\]/g,U);d=d.replace(/\[sample\](\d+)\[\/sample\]/g,U);d=d.replace(/\[img\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/img\]/g,U);d=d.replace(/\[cjclub\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjclub\]/g,U);d=d.replace(/\[cjplayer\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjplayer\]/g,U);d=d.replace(/\[utube\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/utube\]/g,U);d=d.replace(/\[\/url\]/g,'</a>');d=d.replace(/\[url=([\w\s\/\&\+\?\.%=(\[\]);,-:]+)\](.+)/g,'<a href="$1" target="_blank">$2');d=d.replace(/\[url\]([\w\s\/\&\+\?\.%=(\[\]);,-:]+)/g,'<a href="$1" target="_blank">$1');d=d.replace(/((?:\s|^)https?:\/\/(\S+)?)/g,'<a href="$1" target="_blank">$1</a>');d=d.replace(/:([a-zA-Z0-9]{1,}):/g,T);d=d.replace(/\[quote\]/g,'<div class="quote"><i><div style="border: 1px solid #d0d0d0; max-width: 350px; background-color: #efefef; padding: 5px;"><b>Цитата:</b><br/>');d=d.replace(/\[\/quote\]/g,'</div></i></div>');return d};var W=function(){if(q<l.length){o=n.exec(l);if(o!=null){q=n.lastIndex;r=o[1]}else{r=l.substring(q);q=l.length}}else{r=null}return r};while(W()!=null){if(K){if(r.match(A)){K=false;m+="</pre>\n"}else{m+=Z.wikiText.safeText(r)+"\n"}}else if(r.length===0||t.test(r)){Q()}else if((D=r.match(u))!==null){Q();var X=D[1].length;var Y='';switch(X){case 2:Y=" style='font-size: 25px;'";break;case 3:Y=" style='font-size: 17px;'";break;case 4:Y=" style='font-size: 12px;'";break}m+="\n<h"+X+Y+">"+R()+V(D[2])+P()+"</h"+X+">\n\n"}else if((D=r.match(v))!==null){if(I){m+=P()+"</ul>\n";I=false}if(H){m+=P()+"</li>\n"}else{Q();m+="<ul>\n";H=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(w))!==null){if(I){m+=P()+"</li>\n"}else{m+="</li><ul>\n";I=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(x))!==null){if(G){m+=P()+"</ol>\n";G=false}if(F){m+=P()+"</li>\n"}else{Q();m+="<ol>\n";F=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(y))!==null){if(G){m+=P()+"</li>\n"}else{m+="</li><ol>\n";G=true}m+="<li>"+R()+V(D[1])}else if(r.match(z)){Q();m+="<pre>\n";K=true}else if(r.match(C)){Q();m+="<hr/>\n"}else if((D=r.match(B))){if(!(J||F||H)){Q();m+="<p>\n";m+=R();J=true}m+="\n"+V(D[1])}else{if(!E){Q();m+="<p>\n";m+=R();E=true}m+=V(r)+"\n"}}Q();return m};Z.wikiText.safeText=function(a){return(a||'').replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")};Z.wikiText.re_link=/^((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+$/;Z.wikiText.re_mail=/^(mailto:)?([_.\w\-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})$/;Z.wikiText.namedLink=function(a,b){var c;var d;if(!a){return Z.wikiText.safeText(b)}if(Z.wikiText.re_mail.test(a)){a=a.replace(/mailto:/,"");c=encodeURI("mailto:"+a)}else{c=a}if(!b){b=decodeURI(a)}d=Z.wikiText.safeText(b);return d.link(c)};Z.fn.wikiText=function(a){return this.html(Z.wikiText(a))}})(jQuery);


// markItUp!
// http://markitup.jaysalvat.com/
wikiSettings = {
	previewParserPath:	'', // path to your Wiki parser
	onShiftEnter:		{keepDefault:false, replaceWith:'\n\n'},
	markupSet: [
		{name:'b', key:'B', openWith:"[b]", closeWith:"[/b]"},
		{name:'i', key:'I', openWith:"[i]", closeWith:"[/i]"},
		{name:'U', key:'U', openWith:"[u]", closeWith:"[/u]"},
		{name:'S', key:'S', openWith:"[s]", closeWith:"[/s]"},
		{separator:'---------------' },
		{name:'H1', key:'1', openWith:'== ', closeWith:' ==', placeHolder:'Заголовок 1...' },
		{name:'H2', key:'2', openWith:'=== ', closeWith:' ===', placeHolder:'Заголовок 2...' },
		{name:'H3', key:'3', openWith:'==== ', closeWith:' ====', placeHolder:'Заголовок 3...' },
		{separator:'---------------' },
		/*{name:'S', key:'S', openWith:'<s>', closeWith:'</s>'},*/
		//{name:'align', openWith:'(!( :|!|)!)'},
		{name:'list_num', openWith:'(!( # |!| #)!)'},
		{name:'list_num_sub', openWith:'(!( ## |!| ##)!)'},
		{name:'list', openWith:'(!( * |!| *)!)'},
		{name:'list_sub', openWith:'(!( ** |!| **)!)'},
		{separator:'---------------' },
		//{name:'picture', key:"P", replaceWith:'[[Image:[![Url:!:http://]!]|[![name]!]]]'},
		/*{name:'link', key:"L", openWith:"[[![Link]!] ", closeWith:']', placeHolder:'Ссылка...' },*/
		{name:'link'}, /*openWith:"[url=http://] ", closeWith:'[/url]', placeHolder:'Текст...',*/
		/*{name:'Quotes', openWith:'(!(> |!|>)!)', placeHolder:''},*/
		{separator:'---------------' },
		{name:'file', className:'bbcode_box'},
		{name:'sample', className:'bbcode_music'},
		{name:'image', className:'bbcode_image'},
		{name:'music', className:'bbcode_music'},
		{name:'video', className:'bbcode_video'},
		{separator:'---------------' },
		{name:'smile'},
		{separator:'---------------' },
		{name:'video', call:'preview', className:'preview', preview: 'Предпросмотр'}
	]
};

// main.js
window.CJ = window.CJ || {};

// count
CJ = {
	slide: function(){
		var scrollContainer = $('#playlist_content'),
			scrollList = $('#playlist_list'),
			scrollSlider = $('#playlist_scroll'),
			delta = scrollList.height() - scrollContainer.height();

		//console.log(delta, scrollList.outerHeight(), scrollList.height());

		if ( delta > 0 ) {
			scrollSlider.show().slider({
				animate:true,
				orientation: "vertical",
				range: "min",
				min:0,
				max:100,
				value:100,
				slide: function( event, ui ) {
					scrollContainer.stop(true, true).animate({"scrollTop": (1 - ui.value / 100) * delta });
				}
			});
		} else {
			scrollSlider.hide();
		}
	},
	showToggle: function(){
		var link = $(this).attr('href');
		$(link).toggle();
		return false;
	},
	menuToggle: function(){
		$(this).next('.am_submenu').slideToggle(300);
		return false;
	},
	addTextToggle: function(){
		$(this).parents('.gb').find('.col_text_s_add').slideToggle(300);
		return false;
	},
	toTop: function(e){
		e.preventDefault();
		$("body,html").animate( {scrollTop: 0}, 800);
	},
	scroller: function(){
		var top = document.documentElement.scrollTop || document.body.scrollTop,
			d = document.body,
			header = document.getElementById('header'),
			headerShadow = document.getElementById('header_shadow'),
			maxScroll = 250;

		headerShadowHeight = top ? ((top > maxScroll) ? 20 : 20 * top/maxScroll ) : 0;

		headerShadow.style.height = headerShadowHeight + "px";
		d.className = top === 0 ? 'onTop' : '';
		header.className = top ? 'fixed' : '';
	},
	toggleDeletedComment: function(){
		$(this).parents('.com').toggleClass('com_deleted_prev');
		return false;
	},
	toggleAppealComment: function(){
		$(this).parents('.com').toggleClass('com_appeal_prev');
		return false;
	},
	/*
	 * TODO: change into 1 article edit function module
	 */
	aeImages: function(id){
		var $album = $('#' + id),
			$form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			data = $album.data("image");


	},
	aeImageDescription: function(e){
		var th = this,
			$inp = $(th).parents('.ae_album__item').find('.ae_image__desc'),
			inp_val = $inp.val(),
			$form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			$album = $('#ae_album'),
			data = $.data($album, "image");

		if (inp_val) {
			$text.val(inp_val);
		} else {
			$text.val("");
		}

		$('.ae_image__desc_add', $album).removeClass('link_active');
		$(this).addClass('link_active');
		$album.data("image", $inp.attr('name'));  //заносим идентификатор поля в темп

		var l = $(this).offset().left - 190,
			t = $(this).offset().top + 20;

		$form.show().css({top: t, left: l<0?0:l});
		return false;
	},
	aeImageDescriptionText: function(){
		var $form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			$album = $('#ae_album'),
			data = $album.data("image");

		if (data) {
			$('.ae_image__desc[name="' + data + '"]', $album).val($text.val());
		}

		$form.hide();
		$.removeData($album, "image");
		$('.ae_image__desc_add', $album).removeClass('link_active');
	},
	aeImageRemove: function(){
		$(this).parents('.ae_album__item').remove();
		return false;
	},
	baloonOpener: function(e){
		if($(e.target).hasClass('opener')) {
			$(this).toggleClass('open');
		}
	},
	likeTrack: function(){
		$(this).toggleClass('gbt_like_ico_ok');
	},
	favoTrack: function(){
		$(this).toggleClass('gbt_favo_ico_ok');
	},
	playPauseTrack: function(){
		$(this).toggleClass('gbt_pause__ico');
	}
	/*
	 * end module
	 */
};


//function()

// DOM ready
$(function(){
	$('input[placeholder], textarea[placeholder]').placeholder();
	$('#tl_slider').tinycarousel();

	$.each($('.sound_control_slider'), function(count, item){
		//console.log(count);
		$(item).slider({
			animate:true,
			orientation: "vertical",
			range: "min",
			min:0,
			max:100,
			value:75,
			slide: function( event, ui ) {
				$.each($('.sound_control_slider:not(:eq(' + count + '))'), function(c, i){
					$(i).slider('value', ui.value);
				});
			}
		});
	});

	$('#playlist_opener').on('click', function(){
		$('#playlist').slideToggle(300, function(){
			CJ.slide();
		});
	});


	var $ae_image_text_form = $('#ae_image__description'),
		$ae_image_text_form_btn = $ae_image_text_form.find('.btn_orange');

	$('.com_deleted').on('click', CJ.toggleDeletedComment);
	$('.com_appeal').on('click', CJ.toggleAppealComment);
	$('.show_link').on('click', CJ.showToggle);
	$('.am_link').on('click', CJ.menuToggle);
	$('.gb_show').on('click', CJ.addTextToggle);
	$('#totop').on('click', CJ.toTop);
	$('.ae_image__desc_add', '#ae_album').on('click', CJ.aeImageDescription);
	$('.ae_image__remove', '#ae_album').on('click', CJ.aeImageRemove);
	$ae_image_text_form_btn.on('click',  CJ.aeImageDescriptionText);
	$('.opener').on('click',  CJ.baloonOpener);
	$('.gbt_like_ico').on('click',  CJ.likeTrack);
	$('.gbt_favo_ico').on('click',  CJ.favoTrack);
	$('.gbt_play__ico').on('click',  CJ.playPauseTrack);

	$(".track_list").sortable({ handle: ".sortable" });
	$(".wiki_editor").markItUp( wikiSettings );
});

window.onload = CJ.scroller;
window.onscroll = CJ.scroller;