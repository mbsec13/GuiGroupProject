/*
	This function will fire when the map loads in a map.tpl
	Coordinates set: Drillfield
*/

function myMap() {
	var map = new google.maps.Map(document.getElementById("map"),{
		center: new google.maps.LatLng(37.2296, -80.4139), // blacksburg default atm
		zoom: 5,
		mapTypeId: google.maps.MapTypeId.HYBRID
	});
	$.get(BASE_URL + '/camps/json/',function(data){
		var myObj = JSON.parse(data);
		if (myObj.success == true) {
			//use get request to get a list of all camps
			var i = 0;
			for (i = 0; i < myObj.results.ids.length; i++) { 
				var lat = myObj.results.latitudes[i];
				var lon = myObj.results.longitudes[i];
				var id  = myObj.results.ids[i];
				if (lat && lon) {
					lat = parseFloat(lat);
					lon = parseFloat(lon);
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(lat, lon),
						map: map,
						url: BASE_URL + "/camps/" + id,
						icon: "https://maps.google.com/mapfiles/kml/shapes/ranger_station.png"
					});
					google.maps.event.addListener(marker, 'click', function() {
						window.location.href = this.url;
					});
				}
			}
			//for each camp, get the address (one string)		
				
			//end for loop	
		} else {
			alert("could not load map data2: " + myObj.error);
		}
	}).fail(function() {
		alert("Ajax get request failed in loading map data.")
	});
}