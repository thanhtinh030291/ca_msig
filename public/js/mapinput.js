function initialize() {
    $('form').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");
    var show = document.getElementsByClassName("show");
    var markersArray = [];
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || 35.68060344070658;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 139.7680153656312;

        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: {
                lat: latitude,
                lng: longitude
            },
            zoom: 13
        });
        //default marker
        markersArray.push(new google.maps.Marker({
            map: map,
            position: {
                lat: latitude,
                lng: longitude
            },
        }));
        //check editable and add new marker on click
        if (show.length != 0) {
            return;
        } else {
            function placeMarker(location) {
                if (markersArray) {
                    markersArray[0].setPosition(location);
                } else {
                    markersArray.length = 0;
                    markersArray.push(new google.maps.Marker({
                        position: location,
                        map: map
                    }));
                }
            }
            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
                var lat = event.latLng.lat();
                var lng = event.latLng.lng();
                setLocationCoordinates(lat, lng);
            });
        }

        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                // Create a marker for new place.
                placeMarker(place.geometry.location);
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                setLocationCoordinates(lat, lng);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                // check editable and create new marker on click
                if (show.length != 0) {
                    return;
                } else {
                    google.maps.event.addListener(map, 'click', function (event) {
                        placeMarker(event.latLng);
                        var lat = event.latLng.lat();
                        var lng = event.latLng.lng();
                        setLocationCoordinates(lat, lng);
                    });
                }
            });
            map.fitBounds(bounds);
        });
    }
}

function setLocationCoordinates(lat, lng) {
    const latitudeField = document.getElementById("address-latitude");
    const longitudeField = document.getElementById("address-longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}

