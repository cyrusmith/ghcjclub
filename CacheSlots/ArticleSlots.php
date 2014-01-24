<?
/**
 * Слот для отображения блока новостей на главной
 */
class NewsSlot extends CacheSlotSelfRefreshed {
    function getData() {
        $articles = (new Article)->getTagged('news', 5, 'promoarticles');
        $html = '<div class="aside">
                    <h3 class="aside_title">Новости</h3>
                    <ul class="aside_menu aside_menu_news">';
        foreach ($articles as $article) {
            $html .= "
                <li>
                    <a href='articles/{$article->id}' class='aside_menu_link'>{$article->subject}</a>
                    {$article->subject}
                </li>
            ";
        }
        $html .= '  </ul>
                    <div class="aside_more"><a href="articles.html">Больше новостей</a></div>
                </div>';
        return $html;
    }
    function getTTL() {
        return 300;
    }
    function onLoadFailed() {
        $data = $this->getData();
        $this->save($data);
        return $data;
    }
}

/**
 * Слот для отображения блока промо-статей на главной
 */
class PromoArticleSlot extends CacheSlotSelfRefreshed {
    function getData() {
        $articles = (new Article)->getTagged('promoarticles', 5);
        $html = '<div class="aside">
                    <h3 class="aside_title">Промо-статьи</h3>
                    <ul class="aside_menu aside_menu_articles">';
        foreach ($articles as $article) {
            $html .= "
                <li>
                    <a href='articles/{$article->id}' class='aside_menu_link'>{$article->subject}</a>
                </li>
            ";
        }
        $html .= '  </ul>
                    <div class="aside_more"><a href="articles.html?tags=promoarticles">Больше статей</a></div>
                </div>';
        return $html;
    }
    function getTTL() {
        return 300;
    }
    function onLoadFailed() {
        $data = $this->getData();
        $this->save($data);
        return $data;
    }
}

/**
 * Слот для отображения блока персональных блогов на главной
 */
class BlogSlot extends CacheSlotSelfRefreshed {
    function getData() {
        $articles = (new Article)->getTagged('blog', 5);
        $html = '<div class="aside">
                    <h3 class="aside_title">Обсуждаемые блоги</h3>
                    <ul class="aside_menu aside_menu_blogs">';
        foreach ($articles as $article) {
            $html .= "
                <li>
                    <a href='articles/{$article->id}' class='aside_menu_link'>{$article->subject}</a>
                </li>
            ";
        }
        $html .= '  </ul>
                    <div class="aside_more"><a href="articles.html?tags=promoarticles">Больше статей</a></div>
                </div>';
        return $html;
    }
    function getTTL() {
        return 300;
    }
    function onLoadFailed() {
        $data = $this->getData();
        $this->save($data);
        return $data;
    }
}

