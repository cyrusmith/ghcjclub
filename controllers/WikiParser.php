<?php
class WikiParser {
    protected $preformat = false;

    // Правила преобразования строки
    protected $line_regexes = [];
    // Правила преобразования части строки
    protected $char_regexes = [];

    function __construct(){
        $this->setRegexps();
    }

    /**
     * Установка регулярных выражений для преобразования
     */
    function setRegexps(){
        // Простые теги
        $this->char_regexes['BB_bold'] = "\[b\](.+?)\[\/b\]";
        $this->char_regexes['BB_italic'] = "\[i\](.+?)\[\/i\]";
        $this->char_regexes['BB_underline'] = "\[u\](.+?)\[\/u\]";
        $this->char_regexes['BB_h3'] = "====(.+?)====";
        $this->char_regexes['BB_h2'] = "===(.+?)===";
        $this->char_regexes['BB_h1'] = "==(.+?)==";
        $this->char_regexes['BB_crossline'] = '\[s\](.+?)\[\/s\]';

        // Списки
        $this->char_regexes['BB_list_num'] = "(([ \t]{0,}\# (.+?)1qazWSX){1,}([ \t]{0,}\#\# (.+?)1qazWSX){0,}){1,}";
        $this->char_regexes['BB_list'] = "(([ \t]{0,}\* (.+?)1qazWSX){1,}([ \t]{0,}\*\* (.+?)1qazWSX){0,}){1,}";

        // Смайлы
        $this->char_regexes['smile'] = "\:([a-zA-Z0-9]{1,})\:";

        // Вложения
        $this->char_regexes['track'] = "\[cjplayer\](\d+?)\[\/cjplayer\]";
        $this->char_regexes['track2'] = "\[cjclub\](\d+?)\[\/cjclub\]";
        $this->char_regexes['file'] = "\[file\](\d+?)\[\/file\]";
        $this->char_regexes['sample'] = "\[sample\](\d+?)\[\/sample\]";
        $this->char_regexes['utube'] = "\[utube\]([A-z0-9-]+?)\[\/utube\]";
        $this->char_regexes['BB_image'] = "\[img\](.+?)\[\/img\]";

        // Ссылки
        $this->char_regexes['BB_url'] = "\[url=(.+?)\](.+?)\[\/url\]";
        $this->char_regexes['BB_urlshort'] = "\[url\](.+?)\[\/url\]";
        $this->char_regexes['BB_link'] = "\[link=(.+?)\](.+?)\[\/link\]";
        $this->char_regexes['plainurl'] = '((?: |^)https?:\/\/(\S+)?)';
        $this->char_regexes['ref'] = '<ref>(.+?)<\/ref>';
        $this->char_regexes['references'] = '<references\/>';

        // Цитаты
        $this->char_regexes['BB_quote'] = "\[quote(?:.*?)\](.+?)\[\/quote\]";
    }

    /**
     * Преобразование одной строки
     * @param $line входящая строка для преобразования
     * @return string преобразованная строка
     */
    function parse_line($line) {
        $line_regexes = $this->line_regexes;
        $char_regexes = $this->char_regexes;

        $this->stop = false;
        $this->stop_all = false;

        $called = array();

        $line = rtrim($line);
        if (count($line_regexes))
            foreach ($line_regexes as $func=>$regex) {
                if (preg_match("/$regex/i",$line,$matches)) {
                    $called[$func] = true;
                    $func = "handle_".$func;
                    if (strpos($matches[2], ':') !== FALSE)
                        break;
                    $line = $this->$func($matches);
                    if ($this->stop || $this->stop_all) break;
                }
            }

        if (!$this->stop_all) {
            $this->stop = false;
            foreach ($char_regexes as $func => $regex) {
                $line = preg_replace_callback("/$regex/i", array(&$this,"handle_".$func),$line);
                if ($this->stop) break;
            }
        }

        $isline = strlen(trim($line))>0;

        // if this wasn't a list item, and we are in a list, close the list tag(s)
        if (($this->list_level>0) && !@$called['list']) $line = $this->handle_list(false,true) . $line;
        if ($this->deflist && !@$called['definitionlist']) $line = $this->handle_definitionlist(false,true) . $line;
        if (@$this->preformat && !@$called['preformat']) $line = $this->handle_preformat(false,true) . $line;

        // suppress linebreaks for the next line if we just displayed one; otherwise re-enable them
        if ($isline) $this->suppress_linebreaks = (@$called['newline'] || @$called['sections']);

        return "<p>".$line."</p>";
    }

    function handle_save_nowiki($matches) {
        array_push($this->nowikis,$matches[1]);
        return "<nowiki></nowiki>";
    }

    function handle_restore_nowiki($matches) {
        return array_pop($this->nowikis);
    }

    /**
     * Преобразование текста
     * @param $text входной текст
     * @return mixed
     */
    function parse($text) {
        $this->redirect   = false;
        $this->nowikis    = [];
        $this->list_level = 0;
        $this->deflist    = false;

        $this->setRegexps();
        $output = "";

        $text = preg_replace_callback('/<nowiki>([\s\S]*)<\/nowiki>/i',array(&$this,"handle_save_nowiki"),$text);

        $lines = explode("\n",$text);

        if (preg_match('/^\#REDIRECT\s+\[\[(.*?)\]\]$/',trim($lines[0]),$matches)) {
            $this->redirect = $matches[1];
        }

        foreach ($lines as $k=>$line) {
            $line = $this->parse_line($line);
            $output .= $line;
        }

        $output = preg_replace_callback('/<nowiki><\/nowiki>/i',array(&$this,"handle_restore_nowiki"),$output);

        return $output;
    }

    private $refs = array();
    function handle_ref($matches){
        if (array_key_exists($matches[1], $this->refs)) return;
        $count = count($this->refs)+1;
        $this->refs[$matches[1]] = $count;
        return sprintf('<sup class="ref">%1$s</sup>', $count);
    }
    function handle_references(){
        $out = '<ol class="ref">';
        foreach ($this->refs as $text => $index){
            $out .= sprintf('<li>%s</li>', $text);
        }
        $out .= '</ol>';
        return $out;
    }
    /**
     * Преобразование части строки (токена) в перенос строки
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_newline($matches) {
        if ($this->suppress_linebreaks) return $this->emphasize_off();
        $this->stop = true;
        return $this->emphasize_off()."<br/>";
    }

    /**
     * Преобразование части строки (токена) в заголовок 1
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_h1($matches){
        return "<h1 style='margin: 10px 0 0 0; font-size: 25px;'>{$matches[1]}</h1>";
    }

    /**
     * Преобразование части строки (токена) в заголовок 2
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_h2($matches){
        return "<h2 style='margin: 10px 0 0 0; display: inline;'>{$matches[1]}</h2>";
    }

    /**
     * Преобразование части строки (токена) в заголовок 3
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_h3($matches){
        return "<h3 style='margin: 10px 0 0 0; display: inline;'>{$matches[1]}</h3>";
    }

    /**
     * Преобразование части строки (токена) в зачёркнутую строку
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_crossline($matches){ return $this->handle_crossline($matches);}
    function handle_crossline($matches){
        return '<span class="crossline">'.$matches[1].'</span>';
    }

    /**
     * Преобразование части строки (токена) в жирный текст
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_bold($matches){
        return "<b>{$matches[1]}</b>";
    }

    /**
     * Преобразование части строки (токена) в наклонный текст
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_italic($matches){
        return "<i>{$matches[1]}</i>";
    }

    /**
     * Преобразование части строки (токена) в ссылку
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_url($matches){
        if(strpos($matches[1],'http')===false and strpos($matches[1],'ftp')===false){
            return sprintf('<a href="http://%s" target="_blank">%s</a>', $matches[1], $matches[2]);}else{
            return sprintf('<a href="%s" target="_blank">%s</a>', $matches[1], $matches[2]);}
    }

    /**
     * Преобразование части строки (токена) в короткую ссылку
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_urlshort($matches){
        if(strpos($matches[1],'http')===false and strpos($matches[1],'ftp')===false){
            return sprintf('<a href="http://%s" target="_blank">%s</a>', $matches[1], $matches[1]);}else{
            return sprintf('<a href="%s" target="_blank">%s</a>', $matches[1], $matches[1]);}
    }
    function handle_BB_link($matches){return $this->handle_BB_url($matches);}

    /**
     * Преобразование части строки (токена) в цитату
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_quote($matches){
        return sprintf('<pre>%s</pre>', $matches[1]);
    }

    /**
     * Преобразование части строки (токена) в картинку
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_image($matches){
        if (!is_numeric($matches[1]))
            return sprintf('<img src="%s" border="0" alt="Изображение"/>', $matches[1]);
        else return files::getAttachView('image', $matches[1]);
    }

    /**
     * Преобразование части строки (токена) в подчёркнутый текст
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_underline($matches){
        //return $this->handle_underline($matches);
    }

    /**
     * Преобразование части строки (токена) в заголовок 1
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_smile($matches){
        if(!isset($smilemapIncluded)) require_once CONFIG::$PATH_ABS.'/funcs/smileMap.php';
        $smileMapObj = new SmileMap();
        $smileMap    = $smileMapObj->Map;

        $out = '';
        foreach ($smileMap as $key => $val) {
            if ($matches[0] == $key) {
                $out = '<img src="'.Registry::get('smilespath').$val.'" />';
                break;
            }
        }
        if (empty($out)) $out = '&nbsp;';
        return $out;
    }

    /**
     * Преобразование части строки (токена) во вложение типа "Архив"
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_file($matches) {
        return files::getAttachView('file', $matches[1]);
    }

    /**
     * Преобразование части строки (токена) во вложение типа "Сэмпл"
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_sample($matches) {
        return files::getAttachView('sample', $matches[1]);
    }

    /**
     * Преобразование части строки (токена) во вложение типа "Трек"
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_track($matches){
        $trackId = $matches[1];
        //$trackInfo = Track::getTrackInfo($trackId);
        //if ($trackInfo == null) return '<i>Трек не найден</i>';
        $playercode=str_replace("#TRACK#", $trackId, Registry::get('cjplayer'));
        return $playercode;
    }

    /**
     * Преобразование части строки (токена) во вложение типа "Трек"
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_track2($matches){
        $trackId = $matches[1];
        //$trackInfo = Track::getTrackInfo($trackId);
        //if ($trackInfo == null) return '<i>Трек не найден</i>';
        $playercode=str_replace("#TRACK#", $trackId, Registry::get('cjplayer'));
        return $playercode;
    }

    /**
     * Преобразование части строки (токена) во вложение типа "Видео"
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_utube($matches){
        $utubeId = $matches[1];
        $playercode=str_replace("#VIDEO#", $utubeId, Registry::get('utube'));
        return $playercode;
    }

    function handle_plainurl($matches){
        return sprintf(' <a href="%1$s">%2$s</a>', $matches[1], $matches[2]);
    }
    function handle_plainmail($matches){
        return sprintf('<a href="%1$s">%2$s</a>', $matches[1], $matches[2]);
    }
    function handle_externallink($matches) {
        $href = $matches[2];
        $title = @$matches[3];
        if (!$title) {
            $title = $href;
        }
        $newwindow = true;

        return sprintf(
            '<a href="%s"%s>%s</a>',
            $href,
            ($newwindow?' target="_blank"':''),
            $title
        );
    }

    /**
     * Преобразование в ненумерованный список
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_list($matches){
        $out = '<ul>';
        $list = explode(' * ', $matches[0]);
        unset($list[0]);
        foreach ($list as $el) {
            $sublist = explode(' ** ', $el);
            if (count($sublist) > 1) {
                $out .= "<li>{$sublist[0]}";
                $out .= '<ul>';
                foreach ($sublist as $subel)
                    $out .= "<li>$subel</li>";
                $out .= "</ul>";
                $out .= "</li>";
            } else
                $out .= "<li>$el</li>";
        }
        $out .= "</ul>";
        return $out;
    }

    /**
     * Преобразование в нумерованный список
     * @param $matches входной токен
     * @return string  преобразованный токен
     */
    function handle_BB_list_num($matches){
        $out = '<ol>';
        $list = explode(' # ', $matches[0]);
        unset($list[0]);
        foreach ($list as $el) {
            $sublist = explode(' ## ', $el);
            if (count($sublist) > 1) {
                $out .= "<li>{$sublist[0]}";
                $out .= '<ol>';
                foreach ($sublist as $subel)
                    $out .= "<li>$subel</li>";
                $out .= "</ol>";
                $out .= "</li>";
            } else
                $out .= "<li>$el</li>";
        }
        $out .= "</ol>";
        return $out;
    }

    /**
     * Преобразование BB-кода
     * @param $text входящий текст
     * @param bool $externalURLReplace флаг. отвечающий за преобразование ссылок на внешние сайты
     * @param bool $onlyURL флаг, отвечающий за преобразование исключительно ссылок
     * @return mixed|string преобразованный текст
     */
    function wikiedValue($text, $externalURLReplace = true, $onlyURL = false) {
        if (!$onlyURL) {
            if (empty($text)) return;

            // заменяем переносы строк
            $value = str_replace("\n","1qazWSX",$text);
            $value = $this->parse($value);

            // восстанавливаем переносы строк
            $value = str_replace("1qazWSX","<br />",$value);
            if ($externalURLReplace){
                $value = preg_replace_callback("/(<a href=\")([^\"]*)?.([^>]*)(>)(.*?)(<\/a>)/", array($this, 'externalUrlReplace'), $value);
            }
            return sprintf('%s', $value);
        }
        else return preg_replace_callback("/\[url=((http:\/\/|https:\/\/)?([^\.\/]+\.)*([a-zA-Z0-9])([a-zA-Z0-9-]*)\.([a-zA-Z]{2,4})\/.*)\](.*)\[\/url\]/i", array($this, 'replaceEditorURL'), $text);
    }
    function replaceEditorURL($m) {
        $text = end($m);
        $html = '<a href="'.$m[1].'">'.$text.'</a>';
        return preg_replace_callback("/(<a href=\")([^\"]*)?.([^>]*)(>)(.*?)(<\/a>)/", array($this, 'externalUrlReplace'), $html);
    }
    function getDomen($inStr){
        preg_match("/^(http:\/\/|ftp:\/\/)?([^\/:]+)/i", $inStr, $site);
        // ex. www.domen.domenzone
        return @$site[2];
    }
    function getProtocol($inStr){
        preg_match("/^(http:\/\/|ftp:\/\/)?/i", $inStr, $site);
        // ex. http:// or ftp://
        return @$site[1];
    }
    function externalUrlReplace($m){
        // Файл, где хранятся домены друзья
        $domens = CONFIG::$PATH_ABS.'/friendlydomens.txt';
        // Временная переменная, содержит дружественный домен
        $tempDomen = '';
        // Домен, который лежит в ссылке
        $domen     = '';
        // Домен, который лежит в конфиге
        $baseDomen = '';
        // Протокол, лежащий в ссылке
        $protocol  = '';
        // Получаем информацию
        $domen     = $this->getDomen($m[2]);
        $protocol  = $this->getProtocol($m[2]);
        $baseDomen = $this->getDomen(CONFIG::$PATH_URL);
        // В базовом домене уберём "префикс" www.
        $baseDomen = str_replace('www.', '', $baseDomen);
        // Простейшая ситуация типа a href="link/script.php?param=1"
        if(empty($protocol))
            return $m[0];
        // Наш домен
        if(preg_match("/".$baseDomen."$/", $domen))
            return $m[0];
        // Перебираем дружественные домены
        if (file_exists($domens)){
            $FRIENDDOMENS = file($domens);
            foreach($FRIENDDOMENS as $friendDomen){
                $friendDomen = trim($friendDomen);
                if (empty($friendDomen)) continue;
                $match = preg_match("/{$friendDomen}$/", $domen);
                if($match):
                    // Домен есть друг! Закрываем вывод и ссылку на вывод.
                    return $m[0];
                endif;
            }
        }
        //... в противном случае модифицируем
        return '<a href="external.html?url='.base64_encode($m[2]).'"'.$m[3].'>'.$m[5].'</a>';
    }

    /**
     * Вырезка из текста объектов, ненужных в анонсе
     * @param $text входящий текст
     * @return mixed обрезанный текст
     */
    function trunkSimple($text) {
        $exps = [
            'track'  => "/\[cjplayer\](\d+?)\[\/cjplayer\]/",
            'track2' => "/\[cjclub\](\d+?)\[\/cjclub\]/",
            'file'   => "/\[file\](\d+?)\[\/file\]/",
            'sample' => "/\[sample\](\d+?)\[\/sample\]/",
            'utube'  => "/\[utube\]([A-z0-9-]+?)\[\/utube\]/",
            'image'  => "/\[img\](.+?)\[\/img\]/"
        ];
        foreach ($exps as $exp)
            $text = preg_replace($exp, '', $text);
        return $text;
    }
}