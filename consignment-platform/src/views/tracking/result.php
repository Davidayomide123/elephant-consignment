<!-- src/views/tracking/result.php -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAR1a5EBP_8Tsbcxy7IDgNV2hm83otzO74&callback=initMap" async defer></script>
<script>
    function initMap() {
        const origin = { lat: <?= (float)$shipment['origin_lat'] ?: 0 ?>, lng: <?= (float)$shipment['origin_lng'] ?: 0 ?> };
        const dest = { lat: <?= (float)$shipment['destination_lat'] ?: 0 ?>, lng: <?= (float)$shipment['destination_lng'] ?: 0 ?> };
        
        const currentLat = <?= !empty($logs[0]['location_lat']) ? (float)$logs[0]['location_lat'] : (float)$shipment['origin_lat'] ?>;
        const currentLng = <?= !empty($logs[0]['location_lng']) ? (float)$logs[0]['location_lng'] : (float)$shipment['origin_lng'] ?>;
        const currentLoc = { lat: currentLat, lng: currentLng };

        const map = new google.maps.Map(document.getElementById("map-canvas"), {
            zoom: 2,
            center: { lat: (origin.lat + dest.lat) / 2, lng: (origin.lng + dest.lng) / 2 },
            mapTypeControl: false,
            streetViewControl: false,
            styles: [
                { "featureType": "all", "elementType": "labels.text.fill", "stylers": [{ "color": "#7c7c7c" }] },
                { "featureType": "water", "elementType": "geometry.fill", "stylers": [{ "color": "#d6d1c7" }] },
                { "featureType": "landscape", "elementType": "geometry.fill", "stylers": [{ "color": "#f5f4f1" }] }
            ]
        });

        const path = new google.maps.Polyline({
            path: [origin, currentLoc, dest],
            geodesic: true,
            strokeColor: "#6f8569",
            strokeOpacity: 0.8,
            strokeWeight: 3,
            icons: [{ icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW }, offset: '50%' }]
        });
        path.setMap(map);

        new google.maps.Marker({ position: origin, map: map, title: "Origin", label: "Origin", icon: "http://maps.google.com/mapfiles/ms/icons/grey-dot.png" });
        new google.maps.Marker({ position: dest, map: map, title: "Destination", label: "Destination", icon: "http://maps.google.com/mapfiles/ms/icons/grey-dot.png" });
        new google.maps.Marker({ position: currentLoc, map: map, title: "Current Location", icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png" });
    }
</script>

<section class="max-w-4xl mx-auto px-6 pt-16 pb-24">
    <div class="text-center mb-12">
        <h1 class="text-4xl mb-2">Journey Details</h1>
        <p class="text-elephant-500 font-mono badge-breath"><?= e($trackingCode) ?></p>
    </div>

    <!-- GOOGLE MAP -->
    <div class="card-breath p-4 md:p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-xs text-elephant-400 uppercase tracking-wider mb-1">Origin</p>
                <p class="font-medium text-lg text-elephant-800"><?= e($shipment['origin_port']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-elephant-400 uppercase tracking-wider mb-1">Destination</p>
                <p class="font-medium text-lg text-elephant-800"><?= e($shipment['destination_port']) ?></p>
            </div>
        </div>
        
        <!-- Map Canvas -->
        <div id="map-canvas" class="w-full h-64 md:h-96 rounded-xl border border-elephant-200 bg-elephant-100"></div>
    </div>

    <!-- Timeline -->
    <div class="card-breath p-8 md:p-12">
        <h2 class="text-2xl font-display mb-8">Tracking History</h2>
        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-sage-400 before:via-elephant-200 before:to-transparent">
            
            <?php foreach ($logs as $index => $log): ?>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-sage-100 text-sage-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                        <?php if($index === 0): ?>
                            <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sage-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-sage-500"></span></span>
                        <?php else: ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <?php endif; ?>
                    </div>
                    
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-xl border border-elephant-200 bg-elephant-50 shadow-sm">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-bold text-elephant-800"><?= e($log['location']) ?></p>
                            <time class="text-xs text-elephant-400"><?= date('M d, H:i', strtotime($log['created_at'])) ?></time>
                        </div>
                        
                        <p class="text-sm text-elephant-600"><?= e($log['status_message']) ?></p>
                        
                        <!-- Show Image if one exists -->
                        <?php if (!empty($log['image_path'])): ?>
                            <div class="mt-3 rounded-lg overflow-hidden border border-elephant-200">
                                <a href="<?= e($log['image_path']) ?>" target="_blank" title="Click to view full image">
                                    <img src="<?= e($log['image_path']) ?>" alt="Package at <?= e($log['location']) ?>" class="w-full h-48 object-cover hover:opacity-90 transition-opacity">
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($log['is_alert']): ?>
                            <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-lg">
                                <p class="text-sm font-medium text-red-700 mb-2">Action required at this port.</p>
                                <a href="https://your-external-support-link.com" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-bold text-red-600 hover:text-red-700">Contact Support Now →</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>