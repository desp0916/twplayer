<html>
<head>
<title><?php echo $title ?> - CodeIgniter 2 Tutorial</title>
<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&language=zh-TW&region=TW"></script>
<script type="text/javascript" http://code.google.com/apis/gears/gears_init.js"></script>
<script type="text/javascript">
var map;

var getPosition = function (pos) {
	this.latitude = pos.coords.latitude;
	this.longitude = pos.coords.longitude;
}

var errorCallback = function(error) {
	fallbackToIPGeoLocation();
};

function fallbackToIPGeoLocation() {
	if (google.loader.ClientLocation) {
		google.loader.ClientLocation.latitude;
		google.loader.ClientLocation.longitude;
	} else {
		getPositionFailed();
	}
}

function initialize() {
	var myOptions = {
		zoom: 16,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

	// Try HTML5 geolocation
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			//alert(position.coords.latitude);
			// detectBrowser();
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			var infowindow = new google.maps.InfoWindow({
				map: map,
				position: pos,
				content: '您現在的位置.'	// 使用 HTML 5
			});
			map.setCenter(pos);
		}, errorCallback, {enableHighAccuracy: true});
	} else {
		if (window.google && google.gears) {
			var geo = google.gears.factory.create('beta.location');
			geo.getCurrentPosition(function(position) {
				var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				var infowindow = new google.maps.InfoWindow({
					map: map,
					position: pos,
					content: '您現在的位置.'	// 使用 HTML 5
				});
				map.setCenter(pos);
			}, errorCallback, {enableHighAccuracy: true});
			map.setCenter(pos);
		}
		// Browser doesn't support Geolocation
		getPositionFailed(false);
	}
}

function getPositionFailed(errorFlag) {
	if (errorFlag) {
		var content = '錯誤: 起始 Geolocation 服務失敗。';
	} else {
		var content = '錯誤: 您的瀏覽器不支援 Geolocation。';
	}
	var options = {
		map: map,
//		position: new google.maps.LatLng(24.9730334, 121.53914585391458),
		position: new google.maps.LatLng(24, 121),
		content: content
	};
	var infowindow = new google.maps.InfoWindow(options);
	map.setCenter(options.position);
}

function detectBrowser() {
	var useragent = navigator.userAgent;
	var mapdiv = document.getElementById("map_canvas");

	if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
		mapdiv.style.width = '100%';
		mapdiv.style.height = '100%';
	} else {
		mapdiv.style.width = '600px';
		mapdiv.style.height = '800px';
	}
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
<body>
<div id="map_canvas"></div>
</body>
</html>