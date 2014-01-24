/*
 * Kajabity Wiki Text Plugin for jQuery http://www.kajabity.com/jquery-wikitext/
 *
 * Copyright (c) 2011 Williams Technologies Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * Kajabity is a trademark of Williams Technologies Limited.
 * http://www.williams-technologies.co.uk
 */
/**
 * @fileOverview Kajabity Wiki Text Plugin for jQuery
 * @author Simon J. Williams
 * @version: 0.3
 */
(function( jQuery )
{
    /**
     * jQuery definition to anchor JsDoc comments.
     *
     * @see http://jquery.com/
     * @name jQuery
     * @class jQuery Library
     */

    /**
     * jQuery Utility Function to convert Wiki formatted text to HTML.
     *
     * @namespace Kajabity Wiki Text
     * @function
     * @param {string} text the Wiki text to be converted to HTML.
     * @return {string} HTML formatted text.
     * @memberOf jQuery
     */
    jQuery.wikiText = function( text )
    {
        // The source text with nulls/undefined taken care of.
        var source = (text || '').toString();
        // The resultant HTML string - initially empty.
        var html = '';

        // A regular expression to read the source one line at a time.
        var regex = /([^\r\n]*)(\r\n?|\n)/g;
        var lineMatches;
        var offset = 0;
        var line;

        // Regular expressions to match each kind of line level format mark-up.
        var re_blank = /^([ \t]*)$/;
        var re_heading = /^(={1,6})[ \t]+([^=]+)(={1,6})[ \t]*$/;
        var re_bullet = /^[ \t]{0,}\*[ \t]+(.+)$/;
        var re_bullet_sub = /^[ \t]{0,}\*\*[ \t]+(.+)$/;
        var re_numbered = /^[ \t]{0,}#[ \t]+(.+)$/;
        var re_numbered_sub = /^[ \t]{0,}##[ \t]+(.+)$/;
        var re_mono_start = /^\{{3}$/;
        var re_mono_end = /^\}{3}$/;
        var re_blockquote = /^[ \t]+(.+)$/;
        var re_hr = /^-{4,}$/;
        var matches;

        // Flags indicating which kind of block we are currently in.
        var paragraph = false;
        var olist = false;
        var olist_sub = false;
        var ulist = false;
        var ulist_sub = false;
        var bq = false;
        var mono = false;

        // Keep track of inline format nesting.
        var tagStack = [];
        var poppedStack = [];

        // Inline formatting start tags.
        var beginings =
        {
            italic : "<em>",
            italic_end : "</em>",
            monospace : "<tt>",
            strikethrough : "<strike>",
            superscript : "<sup>",
            subscript : "<sub>"
        };

        // ...and end tags.
        var endings =
        {
            monospace : "</tt>",
            strikethrough : "</strike>",
            superscript : "</sup>",
            subscript : "</sub>"
        };

        /**
         * Remove inline formatting at end of a block. Puts it on the
         * poppedStack to add at the start of the next block.
         *
         * @return {string} end tags for any current inline formatting.
         */
        var endFormatting = function()
        {
            var tags = '';
            var popped;
            while( tagStack.length > 0 )
            {
                popped = tagStack.pop();
                tags += endings[popped];
                poppedStack.push( popped );
            }
            return tags;
        };

        /**
         * End the current block and, temporarily, any nested inline formatting,
         * if any.
         *
         * @return {string} the block (and inline formatting) HTML end tags.
         */
        var endBlock = function()
        {
            html += endFormatting();

            if( paragraph )
            {
                html += "</p>\n";
                paragraph = false;
            }
            if( olist )
            {
                html += "</li>\n</ol>\n";
                olist = false;
            }
            if( olist_sub )
            {
                html += "</li>\n</ol>\n";
                olist_sub = false;
            }
            if( ulist )
            {
                html += "</li>\n</ul>\n";
                ulist = false;
            }
            if( bq )
            {
                html += "</p>\n";
                bq = false;
            }
        };

        /**
         * Re-add nested formatting removed at the end of the previous block.
         *
         * @return {string} HTML start tags for all continued formatting.
         */
        var restartFormatting = function()
        {
            var tags = '';
            while( poppedStack.length > 0 )
            {
                var popped = poppedStack.pop();
                tags += beginings[popped];
                tagStack.push( popped );
            }
            return tags;
        };

        /**
         * As most inline format tags are the same at the start or end, this
         * toggles the formatting on or off depending if it is currently in the
         * tagStack.
         *
         * @param {string} label the name of the format to toggle.
         * @return {string} any HTML start or end tags to toggle the formatting
         *         with proper nesting.
         */
        var toggleFormatting = function( label )
        {
            var tags = '';
            if( jQuery.inArray( label, tagStack ) > -1 )
            {
                var popped;
                do
                {
                    popped = tagStack.pop();
                    tags += endings[popped];
                    if( popped === label )
                    {
                        break;
                    }
                    poppedStack.push( popped );
                } while( popped !== label );

                tags += restartFormatting();
            }
            else
            {
                tagStack.push( label );
                tags = beginings[label];
            }

            return tags;
        };

        /**
         * Парсинг смайла
         *
         * @param str совпавшая строка
         * @param p то, что в ()
         * @param offset положение в тексте
         * @param s исходная строка
         * @return html-код смайла
         */
        var replaceSmile = function(str, p, offset, s) {
            var id;
            if (typeof $(this).attr('id') == 'string') {
                id = $(this).attr('id').split('id="')[1];
                id = id.split('"')[0];
                id = $('#'+id).find('textarea').attr('id');
            }

            var regexp = /<img [^<]+\/>/g;
            var smile = ShowSmiles('#'+id, true, p);
            if (smile.match(regexp).length > 1)
                return ':' + p + ':';
            else return smile;
        }

        /**
         * Замена вложения
         *
         * @param str совпавшая строка
         * @param p то, что в ()
         * @param offset положение в тексте
         * @param s исходная строка
         * @return {*}
         */
        var replaceAttachment = function(str, p, offset, s) {
            if (str.match(/\[file\]/))
                return attachObject.controller('replaceBBCode', 'file', p);
            if (str.match(/\[sample\]/))
                return attachObject.controller('replaceBBCode', 'sample', p);
            if (str.match(/\[img\]/))
                return attachObject.controller('replaceBBCode', 'image', p);
            if (str.match(/\[cjplayer\]/))
                return attachObject.controller('replaceBBCode', 'track', p);
            if (str.match(/\[cjclub\]/))
                return attachObject.controller('replaceBBCode', 'track', p);
            if (str.match(/\[utube\]/))
                return attachObject.controller('replaceBBCode', 'video', p);
            return 'Ошибка!';
        }

        /**
         * Парсер редактора
         *
         * @param {string} text the plain text to be formatted and escaped.
         * @param {number} flag for parsing
         * @return {string} HTML formatted text.
         */
        var formatText = function( text, flag )
        {
            var sourceToken = (text || '').toString();
            var formattedText = '';
            var token;
            var offset = 0;
            var tokenArray;
            var linkText;
            var nl_tokenArray;
            var id, link;
            // Iterate through any mark-up tokens in the line.
            if (sourceToken.indexOf('![')) {
                sourceToken = sourceToken.split('![');
                sourceToken = sourceToken.join('! [');
            }
            sourceToken   = sourceToken.replace(/Цитируемый текст:/g, '');

            /*
             * Простые теги
             */
            formattedText = sourceToken;
            formattedText = formattedText.replace(/\[b\]/g, '<strong>');
            formattedText = formattedText.replace(/\[\/b\]/g, '</strong>');
            formattedText = formattedText.replace(/\[i\]/g, '<em>');
            formattedText = formattedText.replace(/\[\/i\]/g, '</em>');
            formattedText = formattedText.replace(/\[u\]/g, '<u>');
            formattedText = formattedText.replace(/\[\/u\]/g, '</u>');

            formattedText = formattedText.replace(/====\s(.+)\s====/g, '<h4 style="font-size: 12px;">$1</h4>');
            formattedText = formattedText.replace(/===\s(.+)\s===/g, '<h3 style="font-size: 17px;">$1</h3>');
            formattedText = formattedText.replace(/==\s(.+)\s==/g, '<h2 style="font-size: 25px;">$1</h2>');

            formattedText = formattedText.replace(/\[\[Image:([\w\/\&\+\?\.%=(\[\]);,-:]+)\|(\w+)\]\]/g, '<img src="$1" title="$2" alt="$2" style="max-width: 468px;">');

            /*
             * Вложения
             */
            formattedText = formattedText.replace(/\[file\](\d+)\[\/file\]/g, replaceAttachment);
            formattedText = formattedText.replace(/\[sample\](\d+)\[\/sample\]/g, replaceAttachment);
            formattedText = formattedText.replace(/\[img\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/img\]/g, replaceAttachment);
            formattedText = formattedText.replace(/\[cjclub\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjclub\]/g, replaceAttachment);
            formattedText = formattedText.replace(/\[cjplayer\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjplayer\]/g, replaceAttachment);
            formattedText = formattedText.replace(/\[utube\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/utube\]/g, replaceAttachment);

            /*
             * Ссылки
             */
            formattedText = formattedText.replace(/\[\/url\]/g, '</a>');
            formattedText = formattedText.replace(/\[url=([\w\s\/\&\+\?\.%=(\[\]);,-:]+)\](.+)/g, '<a href="$1" target="_blank">$2');
            formattedText = formattedText.replace(/\[url\]([\w\s\/\&\+\?\.%=(\[\]);,-:]+)/g, '<a href="$1" target="_blank">$1');
            formattedText = formattedText.replace(/((?:\s|^)https?:\/\/(\S+)?)/g, '<a href="$1" target="_blank">$1</a>');

            /*
             * Смайлы
             */
            formattedText = formattedText.replace(/:([a-zA-Z0-9]{1,}):/g, replaceSmile);

            /*
             * Цитаты
             */
            formattedText = formattedText.replace(/\[quote\]/g, '<div class="quote"><i><div style="border: 1px solid #d0d0d0; max-width: 350px; background-color: #efefef; padding: 5px;"><b>Цитата:</b><br/>');
            formattedText = formattedText.replace(/\[\/quote\]/g, '</div></i></div>');

            return formattedText;
        };

        /**
         * Get a single line from the input. This resolves the issue where the
         * last line is not returned because it doesn't end with CR/LF.
         *
         * @return {string} a single line of input - or null at end of string.
         */
        var getLine = function()
        {
            if( offset < source.length )
            {
                lineMatches = regex.exec( source );
                if( lineMatches != null )
                {
                    offset = regex.lastIndex;
                    line = lineMatches[1];
                }
                else
                {
                    line = source.substring( offset );
                    offset = source.length;
                }
            }
            else
            {
                line = null;
            }

            return line;
        };

        // --------------------------------------------------------------------

        while( getLine() != null )
        {
            if( mono )
            {
                if( line.match( re_mono_end ) )
                {
                    mono = false;
                    html += "</pre>\n";
                }
                else
                {
                    html += jQuery.wikiText.safeText( line ) + "\n";
                }
            }
            else if( line.length === 0 || re_blank.test( line ) )
            {
                endBlock();
            }
            else if( (matches = line.match( re_heading )) !== null )
            {
                endBlock();
                var headingLevel = matches[1].length;

                var style = '';
                switch (headingLevel){
                    case 2:
                        style = " style='font-size: 25px;'";
                        break;

                    case 3:
                        style = " style='font-size: 17px;'";
                        break;

                    case 4:
                        style = " style='font-size: 12px;'";
                        break;
                }
                html += "\n<h" + headingLevel + style + ">" + restartFormatting()
                    + formatText( matches[2] ) + endFormatting() + "</h"
                    + headingLevel + ">\n\n";
            }
            else if( (matches = line.match( re_bullet )) !== null )
            {
                if( ulist_sub )
                {
                    html += endFormatting() + "</ul>\n";
                    ulist_sub = false;
                }
                if( ulist )
                {
                    html += endFormatting() + "</li>\n";
                }
                else
                {
                    endBlock();
                    html += "<ul>\n";
                    ulist = true;
                }

                html += "<li>" + restartFormatting() + formatText( matches[1] );
            }
            else if( (matches = line.match( re_bullet_sub )) !== null )
            {
                if( ulist_sub )
                {
                    html += endFormatting() + "</li>\n";
                }
                else
                {
                    //endBlock();
                    html += "</li><ul>\n";
                    ulist_sub = true;
                }

                html += "<li>" + restartFormatting() + formatText( matches[1] );
            }
            else if( (matches = line.match( re_numbered )) !== null )
            {
                if( olist_sub )
                {
                    html += endFormatting() + "</ol>\n";
                    olist_sub = false;
                }
                if( olist )
                {
                    html += endFormatting() + "</li>\n";
                }
                else
                {
                    endBlock();
                    html += "<ol>\n";
                    olist = true;
                }

                html += "<li>" + restartFormatting() + formatText( matches[1] );
            }
            else if( (matches = line.match( re_numbered_sub )) !== null )
            {
                if( olist_sub )
                {
                    html += endFormatting() + "</li>\n";
                }
                else
                {
                    //endBlock();
                    html += "</li><ol>\n";
                    olist_sub = true;
                }

                html += "<li>" + restartFormatting() + formatText( matches[1] );
            }
            else if( line.match( re_mono_start ) )
            {
                endBlock();
                html += "<pre>\n";
                mono = true;
            }
            else if( line.match( re_hr ) )
            {
                endBlock();
                html += "<hr/>\n";
            }
            else if( (matches = line.match( re_blockquote )) )
            {
                // If not already in blockquote - or a list...
                if( !(bq || olist || ulist) )
                {
                    endBlock();
                    html += "<p>\n";
                    html += restartFormatting();
                    bq = true;
                }

                html += "\n" + formatText( matches[1] );
            }
            else
            {
                if( !paragraph )
                {
                    endBlock();
                    html += "<p>\n";
                    html += restartFormatting();
                    paragraph = true;
                }

                html += formatText( line ) + "\n";
            }
        }

        endBlock();

        return html;
    };

    /**
     * Escape HTML special characters.
     *
     * @param {string} text which may contain HTML mark-up characters.
     * @return {string} text with HTML mark-up characters escaped.
     * @memberOf jQuery.wikiText
     */
    jQuery.wikiText.safeText = function( text )
    {
        return (text || '').replace( /&/g, "&amp;" ).replace( /</g, "&lt;" )
            .replace( />/g, "&gt;" );
    };

    /**
     * A regular expression which detects HTTP(S) and FTP URLs.
     * @type RegExp
     */
    jQuery.wikiText.re_link = /^((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+$/;

    /**
     * A regular expression to match an email address with or without "mailto:"
     * in front.
     * @type RegExp
     */
    jQuery.wikiText.re_mail = /^(mailto:)?([_.\w\-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})$/;

    /**
     * Create a HTML link from a URL and Display Text - default the display to
     * the URL (tidied up).
     * <p>
     * If the URL is missing, the text is returned, if the Name is missing the
     * URL is tidied up (remove 'mailto:' and un-escape characters) and used as
     * the name.
     * </p>
     * <p>
     * The name is then escaped using safeText.
     * </p>
     *
     * @param {string} url the URL which may be a full HTTP(S), FTP or Email URL
     *            or a relative URL.
     * @param {string} name
     * @return {string} text containing a HTML link tag.
     * @memberOf jQuery.wikiText
     */
    jQuery.wikiText.namedLink = function( url, name )
    {
        var linkUrl;
        var linkText;

        if( !url ) { return jQuery.wikiText.safeText( name ); }

        if( jQuery.wikiText.re_mail.test( url ) )
        {
            url = url.replace( /mailto:/, "" );
            linkUrl = encodeURI( "mailto:" + url );
        }
        else
        {
            linkUrl = url;
        }

        if( !name )
        {
            name = decodeURI( url );
        }

        linkText = jQuery.wikiText.safeText( name );
        return linkText.link( linkUrl );
    };

    /**
     * jQuery 'fn' definition to anchor JsDoc comments.
     *
     *
     * @see http://jquery.com/
     * @name fn
     * @class jQuery Library
     * @memberOf jQuery
     */

    /**
     * A jQuery Wrapper Function to append Wiki formatted text to a DOM object
     * converted to HTML.
     *
     * @class Wiki Text Wrapper
     * @param {string} text text with Wiki mark-up.
     * @return {jQuery} chainable jQuery class
     * @memberOf jQuery.fn
     */
    jQuery.fn.wikiText = function( text )
    {
        return this.html( jQuery.wikiText( text ) );
    };
})( jQuery );