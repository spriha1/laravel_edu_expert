$(document).ready(function() {

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	var lat, long;

	lat = $('#lat').val();
	long = $('#long').val();
	address = $('#address').val();
	if ((lat == 0) && (long == 0)) {
		lat = 20.2961;
		long = 85.8245;
	}

	mapboxgl.accessToken = 'pk.eyJ1Ijoic3ByaWhhMSIsImEiOiJjanp4dTByZ28wbHp6M2RvNHdua3g0MWQ1In0.pNLPJ7GyhwFcegQMZhfXJA';
	var map = new mapboxgl.Map({
		container: 'map', // Container ID
		style: 'mapbox://styles/mapbox/streets-v11', // Map style to use
		//center: [85.8245, 20.2961], // Starting position [lng, lat]
		center: [long, lat],
		zoom: 12, // Starting zoom level
	});

	var marker = new mapboxgl.Marker() // Initialize a new marker
	.setLngLat([long, lat]) // Marker [lng, lat] coordinates
	.addTo(map); // Add the marker to the map
	
		// var map = new mapboxgl.Map({
		// 	container: 'map', // Container ID
		// 	style: 'mapbox://styles/mapbox/streets-v11', // Map style to use
		// 	//center: [85.8245, 20.2961], // Starting position [lng, lat]
		// 	center: [85.8245, 20.2961],
		// 	zoom: 12, // Starting zoom level
		// });
		// var marker = new mapboxgl.Marker() // Initialize a new marker
		// .setLngLat([85.8245, 20.2961]) // Marker [lng, lat] coordinates
		// .addTo(map); // Add the marker to the map

	var geocoder = new MapboxGeocoder({ // Initialize the geocoder
		accessToken: mapboxgl.accessToken, // Set the access token
		marker: {
			color: 'orange'
		},
		mapboxgl: mapboxgl, // Set the mapbox-gl instance
		//marker: false, // Do not use the default marker style
		placeholder: 'Search for places', // Placeholder text for the search bar
		//bbox: [85.0985, 20.9517, 85.0985, 20.9517], // Boundary for Berkeley
		proximity: {
			longitude: long,
			latitude: lat
		} // Coordinates of UC Berkeley
	});

	// Add the geocoder to the map
	// map.addControl(geocoder);
	// $('.mapboxgl-ctrl-geocoder--input').appendTo('#geo')
	document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
	$('.mapboxgl-ctrl-geocoder--input').val(address);

	// After the map style has loaded on the page,
	// add a source layer and default styling for a single point
	map.on('load', function() {

		// map.addSource('single-point', {
		// 	type: 'geojson',
		// 	data: {
		// 		type: 'FeatureCollection',
		// 		features: []
		// 	}
		// });
		
		// Listen for the `result` event from the Geocoder
		// `result` event is triggered when a user makes a selection
		// Add a marker at the result's coordinates
		geocoder.on('result', function(ev) {
			console.log(ev);
			long = ev.result.center[0];
			lat = ev.result.center[1];
			address = ev.result.place_name;
			$('#lat').val(lat);
			$('#long').val(long);
			$('#address').val(address);

			map.getSource('single-point').setData(ev.result.geometry);
		});

	});


	$("#registration").submit(function() {
		event.preventDefault();
		$("#spinner").css('display','block');
		$.post('/update_profile', $('#registration').serialize() , function(result) {
			$('#spinner').css('display', 'none');
			var response = JSON.parse(result);
			if (response.email == 1) {
				$('#alert').text("Please verify it by clicking the activation link that has been send to your email.");
				$("#alert").css("display" , "block");
			}
			else if (response.success == 1) {
				$('#info').text("Updated successfully");
				$("#info").css("display" , "block");
			}
			else if (response.email == 0) {
				$('#alert').text("Invalid email format");
				$("#alert").css("display" , "block");
			}
			else if (response.username == 0) {
				$('#alert').text("Invalid username format");
				$("#alert").css("display" , "block");
			}
			else if (Object.keys(response).length === 0 && response.constructor === Object) {
				$('#alert').text("You need to fill in atleast one field to update");
				$("#alert").css("display" , "block");
			}
			else {
				$('#alert').text("Error");
				$("#alert").css("display" , "block");
			}
		})
	});

	$('#currency').change(function() {
		var currency = $(this).val();
		$.post('/update_currency', {currency: currency}, function (result) {
			$('#info').text("Currency Updated successfully");
			$("#info").css("display" , "block");
		})
	});

	$('body').click(function() {
		if (event.target.id === 'password' && event.target.closest("form").getAttribute("id") === 'registration') {
			var msg = "The password :<br> Must be a minimum of 8 characters<br>Must contain at least 1 number<br>Must contain at least one uppercase character<br>Must contain at least one lowercase character";
			$("#info_password").html(msg);
			$("#info_password").css("display" , "block");
		}

		if (event.target.id === 'username' && event.target.closest("form").getAttribute("id") === 'registration') {
			var msg = "The username can contain letters, digits, @ and _";
			$("#info_username").html(msg);
			$("#info_username").css("display" , "block");
		}
	});


	$('input').blur(function() {
		if (event.target.closest("form").getAttribute("id") === 'registration') {

			if (event.target.id === 'username') {
				$("#info_username").css("display" , "none");
				var username_pattern = /^([a-zA-Z0-9@_]+)$/;
				var username = $('#username').val();
				$.get("/fetch_info" , {q1: "username", q2: username} , function(data) {
					if (Number(data) === 1) {
						$('#username').css("borderColor" , "red");
						$("#alert").text("This username already exists");
						$("#alert").css("display" , "block");
					}
				})

				if ($('#username').val() === "") {
					$('#username').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!username_pattern.test($('#username').val())) {
					$('#username').css("borderColor" , "red");
					$("#alert").text("Invalid username");
					$("#alert").css("display" , "block");
				}

				else {
					$('#username').css("borderColor" , "green");
				}
			}

			else if (event.target.id === 'password') {
				$("#info_password").css("display" , "none");
				var password_pattern = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
				if ($('#password').val() === "") {
					$('#password').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!password_pattern.test($('#password').val())) {
					$('#password').css("borderColor" , "red");
					$("#alert").text("Invalid password");
					$("#alert").css("display" , "block");
				}

				else {
					$('#password').css("borderColor" , "green");
				}
			}

			else if (event.target.id ==="email") {
				var email = $('#email').val();
				$.get("/fetch_info", {q1: "email", q2: email}, function(data) {
					if (Number(data) === 1) {
						$('#email').css("borderColor" , "red");
						$("#alert").text("This email already exists");
						$("#alert").css("display" , "block");
					}
				})

				if ($('#email').val() === "") {
					$('#email').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if ($('#email').val().indexOf("@") < 0 || $('#email').val().indexOf(".") < 0) {
					$('#email').css("borderColor" , "red");
					$("#alert").text("Invalid email");
					$("#alert").css("display" , "block");
				}

				else {
					$('#email').css("borderColor" , "green");
				}
			}

			else if (event.target.id === 'fname') {
				var name_pattern = /^([a-zA-Z]+)$/;
				if ($('#fname').val() === "") {
					$('#fname').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!name_pattern.test($('#fname').val())) {
					$('#fname').css("borderColor" , "red");
					$("#alert").text("Invalid first name");
					$("#alert").css("display" , "block");
				}

				else {
					$('#fname').css("borderColor" , "green");
				}
			}

			else if (event.target.id === 'lname') {
				var name_pattern = /^([a-zA-Z]+)$/;
				if ($('#lname').val() === "") {
					$('#lname').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!name_pattern.test($('#lname').val())) {
					$('#lname').css("borderColor" , "red");
					$("#alert").text("Invalid last name");
					$("#alert").css("display" , "block");
				}

				else {
					$('#lname').css("borderColor" , "green");
				}
			}
		}
	});

})





