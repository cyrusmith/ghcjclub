<?php
class StylesCtrl extends DController {
	function lists() {
		return (new DModelsCollection('MusicStyleModel'))->load('1 order by value');
	}
}