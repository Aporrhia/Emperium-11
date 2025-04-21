<div id="map" class="w-full h-[600px] rounded-lg"></div>

<script>
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
            coordinatesControl.getContainer().innerHTML = 'Lat: 0, Lng: 0 (Scaled: 0, 0)';
        });

        // Add markers for each property
        var properties = @json($properties);
        var selectedId = {{ request('id') ? request('id') : 'null' }};
        var selectedType = '{{ request('type') ?? null }}';
        var propertyArray = Object.values(properties);

        propertyArray.forEach(function(property) {
            if (property.latitude && property.longitude) {
                console.log(`Property: ${property.title}, Coordinates: [${property.latitude}, ${property.longitude}]`);
                var isSelected = property.id == selectedId && property.type === selectedType;
                var markerColor = isSelected ? '#4682B4' : '#87CEEB';

                var marker = L.marker([property.latitude, property.longitude], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="32" height="32">
                                <path fill="${markerColor}" d="M32 5a21 21 0 0 0-21 21c0 17 21 33 21 33s21-16 21-33A21 21 0 0 0 32 5zm0 31a10 10 0 1 1 10-10 10 10 0 0 1-10 10z"/>
                            </svg>
                        `,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    })
                }).addTo(map);

                marker.bindPopup(`
                    <b>${property.title}</b><br>
                    ${property.location}<br>
                    $${new Intl.NumberFormat().format(property.price)}<br>
                    <a href="${window.location.pathname}?id=${property.id}&type=${property.type}">View Details</a>
                `, {
                    offset: [0, 0]
                });

                if (isSelected) {
                    marker.openPopup();
                    map.setView([property.latitude, property.longitude], 1);
                }

                marker.on('click', function() {
                    map.setView([property.latitude, property.longitude], 1);
                });
            } else {
                console.log(`Property ${property.title} missing coordinates`);
            }
        });

        // Center the map if no property is selected
        if (!selectedId) {
            map.setView([1000, 1000], minZoom);
        }
    });
</script>