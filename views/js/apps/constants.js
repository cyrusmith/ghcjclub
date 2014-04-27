angular.module('CjClubUserApp')
	.constant('radioUrls', {
		low: 'http://www.cjradio.ru:8000/high-stream',
		high: 'http://www.cjradio.ru:8000/low-stream'
	})
	.constant('radioUpdateTime', 30000);