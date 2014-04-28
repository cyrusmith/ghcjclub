angular.module('CjClubUserApp')
	.constant('radioUrls', {
		high: 'http://www.cjradio.ru:8000/high-stream',
		low: 'http://www.cjradio.ru:8000/low-stream'
	})
	.constant('radioUpdateTime', 30000);