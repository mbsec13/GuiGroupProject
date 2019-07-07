<div class="container" style="min-height: 100vh">
	<nav aria-label="breadcrumb">
		<ul class="breadcrumb bg-white">
			<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Map</li>
		</ul>
	</nav> <!-- End of breadcrumb -->
	<div id="map" class="w-100 h-400"></div>
	<script src="<?= BASE_URL ?>/public/js/mapscript.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx0BtA19HQ-qYKfn6c_RVW2H02xXv9nkk&callback=myMap"></script>
	<div class="mt-4">
		<h2 class="mb-2">What is this?</h2>
		<p>
			This is a Google Map that we provided for you to see all the Axis Prisoners of War camps we have on our website. Each camp marker's location on the map represents the camp's geographic location that we have. Clicking on a marker will redirect you to the camp's page, so you can read to your heart's desire!
			<br><br>
		</p>
		<h2 class="mb-2">Instructions</h2>
		<p>
			To <strong>zoom</strong> in and out on the map, ensure your mouse is on the map, then Ctrl+Scroll.<br>
			You can <strong>move</strong> the map around by clicking on the map then dragging the map.
		</p>
	</div>
</div> <!-- End of container -->
