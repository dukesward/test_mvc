$(document).ready(function() {
	if(ChartUtils) {

		var config = {
				'id': 'chart_test',
				'width': 800,
				'height': 300,
				'background_hex': '777',
				'axis_config': '444',
				'intervalX': 1,
				'intervalY': 1
			},
			canvas = ChartUtils.init(config);

		var mapX = DataUtils.sequence(0, 5, 1),
			mapY = DataUtils.random(6, 0, 1, 10),
			map = DataUtils.map(mapX, mapY);

		canvas.drawLineChart(map);
	}
});
