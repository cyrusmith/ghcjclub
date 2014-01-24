<?
$smilemapIncluded = 1;

class SmileMap extends CRUD{
	public $Map;
	
	function SmileMap(){
        $this->Map = array();
        $type = array("gif");
        if (is_dir(Registry::get('smilespath'))) {
            $handle = opendir(Registry::get('smilespath'));
            while($aux = readdir($handle)) {
                if ($aux != "." && $aux != "..") {
                    $name   = basename($aux);
                    $s      = explode(".",$name);
                    $counts = count($s)-1;
                    $ext    = strtolower($s[$counts]);

                    if (in_array($ext,$type))
                        $this->Map[':'.$s[0].':'] = basename($aux);
                }
            }
            closedir($handle);
        }
	}
}

function Text2Smile($text){
    $smileMapObj = new SmileMap();
    $smileMap    = $smileMapObj ->Map;

    foreach ($smileMap as $key => $val){
        $smileText[] = $key;
        $smileImg[] = '<img src="'.Registry::get('smilespath').$val.'" />';
    }

    $text = str_replace($smileText, $smileImg, $text);
    return $text;
}
?>