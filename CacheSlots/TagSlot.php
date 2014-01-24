<?
/**
 * Слот отображения тегов на сайте в боковушках
 */
class TagSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $html = '<article class="b"><div class="tags">
                    <form action="#" class="search search_tags">
                        <input type="submit" class="search_btn" value="найти">
                        <input type="text" class="search_txt" name="qtags" value="Поиск по тегам" onfocus="if(this.value==\'Поиск по тегам\') this.value = \'\';" onblur="if(this.value == \'\') this.value = \'Поиск по тегам\';">
                    </form>
                    <h3 class="tags_title">Популярные тэги</h3>
                    <ul class="tags_list">
        ';
        $minSize = 13.0;
        $maxSize = 30.0;
        $sizeRange = $maxSize - $minSize;
        $tagsCloud = json_decode( Registry::get('blogTagsCloud'));
        $minCount = log($tagsCloud->min + 1);
        $maxCount = log($tagsCloud->max + 1);
        $countRange = $maxCount - $minCount;
        foreach ($tagsCloud->list as $tag) {
            $size = $minSize + (log($tag->count + 1) - $minCount) * $sizeRange / $countRange;
            $html .= "<li>
                    <a href='blogs.html?tag=".urlencode(base64_encode($tag->value))."' style='font-size:".str_replace(',', '.', round($size, 2))."px'>".$tag->value."</a>
                </li>
            ";
        }
        $html .= '</ul></div></article>';
        return $html;
    }
    function getTTL() {
        return 300; // время жизни - 5 минута
    }
    public function onLoadFailed() {
        $data = $this->getData();
        $this->save($data);
        return $data;
    }
}
?>
