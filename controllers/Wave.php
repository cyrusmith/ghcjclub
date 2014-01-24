<?
class Wave extends DController {
    /**
     * Создание "волны" трека
     * @param $id идентификатор трека
     * @param $userId идентификатор создателя
     * @param $width ширина волны
     * @param $height высота волны
     * @return string ссылка на картинку "волны"
     */
    function create($id, $userId, $width = 890, $height = 90) {
        require_once(CONFIG::$PATH_WaveGraph.'/MP3Visualizer.php');

        MP3Visualizer::setcolor(0, 0, 0);
        MP3Visualizer::setBGcolor(255, 255, 255);
        MP3Visualizer::setcolor(255, 255, 255);

        if (empty($userId))
            $userId = RDS::get()->userId;

        $inputfile 	= (new File)->getPath($id, $userId, 'track');
        $outfile 	= (new File)->getPath($id, $userId, 'wave');

        MP3Visualizer::visualize($inputfile, $outfile, $width, $height, 'PNG', FALSE);

        return (new File)->getPath($id, $userId, 'wave', TRUE);
    }

    /**
     * Удаление файла волны
     * @param $id идентификатор трека
     * @param $userId идентификатор пользователя
     */
    function delete($id, $userId) {
        $path = (new File)->getPath($id, $userId, 'wave');
        @unlink($path);
    }
}
