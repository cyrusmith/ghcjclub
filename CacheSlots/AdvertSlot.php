<?
/**
* Слот для отображения блока новостей на главной
*/
class AdvertSlot extends CacheSlotSelfRefreshed {
    function getData() {
        return '';
        $data = new StdClass();
        $data->adverts  = (new Advert)->getListModel();
        $data->sections = (new Advert)->getSections();

        return renderTemplate('advertBoardView.php', $data);
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