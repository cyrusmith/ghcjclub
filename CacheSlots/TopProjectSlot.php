<?

/**
 * Слот кэша для вывода топа пользователей
 */
class TopProjectSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $projects = (new Project)->getBest();
        $html = '<section class="slider" id="top-project-slider">
            <div class="slider_in">
                <div class="slider_holder viewport">
                    <ul class="slider_line overview cf">';
        foreach($projects as $project) {
            $ava = (new File)->getPath(0, 0, 'avatar', TRUE);
            $html .= "
                        <li class='slider_item'>
                            <img class='man_photo' src='$ava' alt='{$project->name}' title='{$project->name}' />
                            <div class='man_text'>
                                <div class='man_title'>
                                    <div class='man_name'><a href='projects/{$project->id}'>{$project->name}</a> <a href='#' class='user_stat user_stat_2'></a></div>
                                    <div class='man_city'>{$project->city_id->name}</div>
                                </div>
                                <div class='man_desc'>{$project->infoShort}</div>
                            </div>
                        </li>
            ";
        }
        $html .= '</ul>
                </div>
                <div class="slider_arr buttons prev disable"></div>
                <div class="slider_arr buttons next"></div>
            </div>
        </section>';

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