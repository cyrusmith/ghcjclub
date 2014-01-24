<?php
/**
 * Слот кэша для лучших треков на главной
 */
class BestTrackSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $period = [/*'week', 'month', */'all'];
        $html = '<div class="tracks"><h3 class="tracks_title">Лучшие треки</h3>';
        foreach ($period as $int) {
            $html .= renderTemplate('trackUnitView.php', (new Track)->getBest($int));
        }
        $html .= '</div>';
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

/**
 * Слот кэша для выборки новых треков для главной
 */
class NewTrackSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $html = '<div class="tracks"><h3 class="tracks_title">Новые треки</h3>';
        $html .= renderTemplate('trackUnitView.php', (new Track)->getNew());
        $html .= '</div>';
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
 * Слот для отображения блока обсуждаемых треков на главной
 */
class DiscussedTrackSlot extends CacheSlotSelfRefreshed {
    function getData() {
        $tracks = (new Track)->getDiscussed();
        $html = '<div class="aside">
                    <h3 class="aside_title">Обсуждаемые треки</h3>
                    <ul class="aside_menu aside_menu_tracks">';
        foreach ($tracks as $track) {
            $html .= "
                <li>
                    <a href='tracks/{$track->id}' class='aside_menu_link'>{$track->name}</a>
                </li>
            ";
        }
        $html .= '  </ul>
                    <div class="aside_more"><a href="tracks.html">Больше треков</a></div>
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
