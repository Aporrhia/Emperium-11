<!-- resources/views/partials/sell-map.blade.php -->
<div id="map" class="w-full h-[600px] rounded-lg"></div>

<script>
// Leaflet Map Setup
document.addEventListener('DOMContentLoaded', function () {
    // Define the bounds of the map image (0,0 is bottom-left, 2000,2000 is top-right)
    var bounds = [[0, 0], [2000, 2000]];

    // Initialize Leaflet map with a custom image overlay
    var map = L.map('map', {
        crs: L.CRS.Simple,
        minZoom: -1,
        maxZoom: 2,
        maxBounds: bounds,
        maxBoundsViscosity: 1.0,
        attributionControl: false
    });

    // Use the GTA V satellite map image from storage
    var imageUrl = '{{ asset('storage/map/GTAV_satellite_map.jpg') }}';
    L.imageOverlay(imageUrl, bounds).addTo(map);

    // Calculate the zoom level to fit the image exactly
    map.fitBounds(bounds);

    // Adjust minZoom to prevent zooming out beyond the image
    var minZoom = map.getBoundsZoom(bounds, true);
    map.setMinZoom(minZoom);

    // Center the map
    map.setView([1000, 1000], minZoom);

    // Create a custom control to display coordinates
    var CoordinatesControl = L.Control.extend({
        onAdd: function(map) {
            var container = L.DomUtil.create('div', 'leaflet-control-coordinates leaflet-bar leaflet-control');
            container.style.background = 'rgba(255, 255, 255, 0.8)';
            container.style.padding = '5px 10px';
            container.style.borderRadius = '5px';
            container.style.fontSize = '12px';
            container.innerHTML = 'Lat: 0, Lng: 0 (Scaled: 0, 0)';
            return container;
        }
    });

    var coordinatesControl = new CoordinatesControl({ position: 'topright' });
    coordinatesControl.addTo(map);

    // Hidden form fields for coordinates
    var latitudeInput = document.getElementById('latitude');
    var longitudeInput = document.getElementById('longitude');

    // Variable to hold the current marker
    var currentMarker = null;

    // Update coordinates on mouse move
    map.on('mousemove', function(e) {
        var lat = e.latlng.lat.toFixed(2);
        var lng = e.latlng.lng.toFixed(2);
        var scaledLat = (lat / 2000 * 100).toFixed(2);
        var scaledLng = (lng / 2000 * 100).toFixed(2);
        coordinatesControl.getContainer().innerHTML = `Lat: ${lat}, Lng: ${lng} (Scaled: ${scaledLat}, ${scaledLng})`;
    });

    // Clear coordinates when mouse leaves the map
    map.on('mouseout', function() {
        if (!latitudeInput.value && !longitudeInput.value) {
            coordinatesControl.getContainer().innerHTML = 'Lat: 0, Lng: 0 (Scaled: 0, 0)';
        }
    });

    // Add a marker and save coordinates on click
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(2);
        var lng = e.latlng.lng.toFixed(2);
        var scaledLat = (lat / 2000 * 100).toFixed(2);
        var scaledLng = (lng / 2000 * 100).toFixed(2);

        // Update hidden form fields
        latitudeInput.value = lat;
        longitudeInput.value = lng;

        // Update coordinates control
        coordinatesControl.getContainer().innerHTML = `Lat: ${lat}, Lng: ${lng} (Scaled: ${scaledLat}, ${scaledLng})`;

        // Remove existing marker if any
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Add new marker at clicked location
        currentMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'custom-marker',
                html: `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="32" height="32">
                        <path fill="#4682B4" d="M32 5a21 21 0 0 0-21 21c0 17 21 33 21 33s21-16 21-33A21 21 0 0 0 32 5zm0 31a10 10 0 1 1 10-10 10 10 0 0 1-10 10z"/>
                    </svg>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            })
        }).addTo(map);

        // Center the map on the marker
        map.setView([lat, lng], 1);
    });
});
</script>