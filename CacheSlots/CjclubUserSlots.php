<?php
/**
 * Слот кэша для новых юзеров (отбражение на главной)
 */
class NewCjclubUserSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $users = (new CjclubUser)->getForHomePage("type_id > 1 AND hasAvatar = 'true' ORDER BY RAND() LIMIT 0,9");
        $html = '<div class="aside2">
                    <h3 class="aside_title">Новые лица</h3>
                    <ul class="faces cf">';
        foreach ($users as $user) {
            $ava = (new File)->getPath($user->id, 0, 'avatar', TRUE);
            $html .= "
                <li class='faces_item'>
                    <a href='authors/{$user->id}'><img src='$ava' width='68' alt='{$user->name}' title='{$user->name}' /></a>
                </li>
            ";
        }
        $html .= '  </ul>
                </div>';
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
 * Слот кэша для юзеров-онлайн (отображение на главной)
 */
class OnlineCjclubUserSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $users = (new CjclubUser)->getForHomePage("isOnline = 'true' AND hasAvatar = 'true' ORDER BY regdate DESC LIMIT 0,9");
        $html = '<div class="aside2">
                    <h3 class="aside_title">Онлайн</h3>
                    <ul class="faces cf">';
        foreach ($users as $user) {
            $ava = (new File)->getPath($user->id, 0, 'avatar', TRUE);
            $html .= "
                <li class='faces_item'>
                    <a href='authors/{$user->id}'><img src='$ava' width='68' alt='{$user->name}' title='{$user->name}' /></a>
                </li>
            ";
        }
        $html .= '  </ul>
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
