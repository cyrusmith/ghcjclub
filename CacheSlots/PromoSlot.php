<?php
class PromoSlot extends CacheSlotSelfRefreshed {
    function getData(){
        $tracks = (new Track)->getPromo();
        $html   = "<section id='promo' class='promo'>
			<ins class='promo_bg_l'></ins>
			<ins class='promo_bg_r'></ins>
			<ins class='promo_bg_c'></ins>

			<a href='goToPromoBox.html' class='promo_what'>что это?</a>

            <div class='promo_list cf'>";

        $trackFile = new File();
        foreach($tracks as $track) {
            $link = $trackFile->getListenLink($track->id);
            $path = $trackFile->getDownloadLink($track->id);
            $pic  = $trackFile->getPath($track->id,(new Project)->getShowModel($track->projectId->id)->creatorId->id, 'trackPic', TRUE);
            $size = formatbytes($track->filesize, true, 2);
            $time = number_format($track->timelength/60, 2, ':', ' ');
            $html .= "
                <div class='promo_item'>
					<div class='promo_item__name'><a href='projects/{$track->projectId->id}' title='{$track->projectId->name}'>{$track->projectId->name}</a> <a href='javascript:void(0)' class='user_stat user_stat_1'></a></div>
                    <div class='promo_item__cover'>
						<div class='track_image'>
							<img src='$pic' width='94' alt='Картинка трека' title='Картинка трека' />
							<div class='track_image__controls'>
								<a href='javascript:void(0)' class='cover_play'></a>
								<a href='javascript:void(0)' class='cover_plus'></a>
							</div>
						</div>
					</div>
					<a href='tracks/{$track->id}' class='promo_item__title' title='{$track->name}'>{$track->name}</a>
					<div class='promo_item__data'>Track | {$track->style_id->value} | $time | $size </div>
					<div class='player cf'>
						<a href='javascript:void(0)' class='player_ico player_start' onclick='TrackPlayer.playMusic(\"$link\", this, {$track->id})' timelength='{$track->timelength}'>{$track->count_listen}</a>
						<a href='$path' class='player_ico player_dwnl'>{$track->count_download}</a>
						<a href='javascript:void(0)' class='player_ico player_like'>{$track->points}</a>
						<a href='javascript:void(0)' class='player_ico player_plus'>+</a>
					</div>
				</div>
            ";
        }
        $html .= "</div></section>";
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
