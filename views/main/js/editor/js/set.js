// -------------------------------------------------------------------
// markItUp!
// -------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// -------------------------------------------------------------------
// Mediawiki Wiki tags example
// -------------------------------------------------------------------
// Feel free to add more tags
// -------------------------------------------------------------------
wikiSettings = {
	previewParserPath:	'', // path to your Wiki parser
	onShiftEnter:		{keepDefault:false, replaceWith:'\n\n'},
	markupSet: [
		{name:'H1', key:'1', openWith:'== ', closeWith:' ==', placeHolder:'Заголовок 1...' },
		{name:'H2', key:'2', openWith:'=== ', closeWith:' ===', placeHolder:'Заголовок 2...' },
		{name:'H3', key:'3', openWith:'==== ', closeWith:' ====', placeHolder:'Заголовок 3...' },
        {separator:'---------------' },
		{name:'b', key:'B', openWith:"[b]", closeWith:"[/b]"},
		{name:'i', key:'I', openWith:"[i]", closeWith:"[/i]"},
		{name:'U', key:'U', openWith:"[u]", closeWith:"[/u]"},
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
		{name:'link', /*openWith:"[url=http://] ", closeWith:'[/url]', placeHolder:'Текст...',*/ seperator: 'no'},
		/*{name:'Quotes', openWith:'(!(> |!|>)!)', placeHolder:''},*/
        //{separator:'---------------' },,
		{name:'file', className:'bbcode_box', nextline: true},
		{name:'sample', className:'bbcode_music'},
		{name:'image', className:'bbcode_image'},
		{name:'music', className:'bbcode_music'},
		{name:'video', className:'bbcode_video'},
        {separator:'---------------' },
        {name:'smile'},
        {separator:'---------------' },
        {name:'video', call:'preview', className:'preview', preview: 'Предпросмотр'}
	]
}