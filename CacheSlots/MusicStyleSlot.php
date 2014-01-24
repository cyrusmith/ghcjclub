<?
/**
 * Слот для кэширования списка стилей
 */
class MusicStyleSlot extends CacheSlotSelfRefreshed {
    protected $type;
    function __construct($some){
        $this->type = $some;
    }
    function getData(){
        $html = '';
        $minSize = 11.0;
        $maxSize = 22.0;
        $sizeRange = $maxSize - $minSize;

        $stylesCloud = (new MusicStyle)->getListModel();
        if ($this->type == 'tracks')
            $stylesCloud->load('id IN(SELECT DISTINCT(style_id) FROM cjclub_tracks WHERE true) ORDER BY value');
        else // 'authors'
            $stylesCloud->load('id IN(SELECT DISTINCT(style_id) FROM cjclub_tracks WHERE true) ORDER BY tracks DESC');

        foreach ($stylesCloud as $style)
            $style->tracks = dbSelect('tracks', 'COUNT(*)', 'style_id = '.$style->id, DB_SELECT_ONE);
        $max = $min = 0;
        foreach ($stylesCloud as $style) {
            if ($max < $style->tracks) $max = $style->tracks;
            if ($min > $style->tracks) $min = $style->tracks;
        }

        foreach ($stylesCloud as $style) {
            $size = $minSize + ( ($style->tracks - $min) / ($max - $min) * $sizeRange);
            $html .= '<a href="'.$this->type.'.html?style_id='.$style->id.'"><big class="tag4" style="font-size:'.str_replace(',', '.', round($size, 2)).'px">'.$style->value.'</big></a>&nbsp; ';
        };
        return $html;
    }
    function getTTL() {
        return 300; // время жизни - 5 минута
    }
    function onLoadFailed() {
        $data = $this->getData();
        $this->save($data);
        return $data;
    }
}
class MusicStyleForAuthorsSlot extends MusicStyleSlot {
    function __construct(){
        $this->type = 'authors';
    }
}
class MusicStyleForTracksSlot extends MusicStyleSlot {
    function __construct(){
        $this->type = 'tracks';
    }
}
?>
