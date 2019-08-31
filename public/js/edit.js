/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/edit.js":
/*!******************************!*\
  !*** ./resources/js/edit.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var lat, _long;

  mapboxgl.accessToken = 'pk.eyJ1Ijoic3ByaWhhMSIsImEiOiJjanp4dHk1ZnIwb2Q4M2NsYWJiZXFhajNzIn0.q_cDP5GyFAGrHm20NsVnbg';
  var map = new mapboxgl.Map({
    container: 'map',
    // Container ID
    style: 'mapbox://styles/mapbox/streets-v11',
    // Map style to use
    center: [85.8245, 20.2961],
    // Starting position [lng, lat]
    zoom: 12 // Starting zoom level

  });
  var marker = new mapboxgl.Marker() // Initialize a new marker
  .setLngLat([85.8245, 20.2961]) // Marker [lng, lat] coordinates
  .addTo(map); // Add the marker to the map

  var geocoder = new MapboxGeocoder({
    // Initialize the geocoder
    accessToken: mapboxgl.accessToken,
    // Set the access token
    marker: {
      color: 'orange'
    },
    mapboxgl: mapboxgl,
    // Set the mapbox-gl instance
    //marker: false, // Do not use the default marker style
    placeholder: 'Search for places',
    // Placeholder text for the search bar
    //bbox: [85.0985, 20.9517, 85.0985, 20.9517], // Boundary for Berkeley
    proximity: {
      longitude: 85.8245,
      latitude: 20.2961 // Coordinates of UC Berkeley

    }
  }); // Add the geocoder to the map

  map.addControl(geocoder);
  document.getElementById('geocoder').appendChild(geocoder.onAdd(map)); // After the map style has loaded on the page,
  // add a source layer and default styling for a single point

  map.on('load', function () {
    map.addSource('single-point', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: []
      }
    }); // Listen for the `result` event from the Geocoder
    // `result` event is triggered when a user makes a selection
    // Add a marker at the result's coordinates

    geocoder.on('result', function (ev) {
      console.log(ev);
      lat = ev.result.center[0];
      _long = ev.result.center[1];
      map.getSource('single-point').setData(ev.result.geometry);
    });
  });
  $("#registration").submit(function () {
    event.preventDefault();
    $.post('/update_profile', $('#registration').serialize(), function (result) {
      console.log($('#registration').serialize());
      var response = JSON.parse(result);

      if (response.email == 1) {
        $('#alert').text("Please verify it by clicking the activation link that has been send to your email.");
        $("#alert").css("display", "block");
      } else if (response.success == 1) {
        $('#alert').text("Updated successfully");
        $("#alert").css("display", "block");
      } else if (response.email == 0) {
        $('#alert').text("Invalid email format");
        $("#alert").css("display", "block");
      } else if (response.username == 0) {
        $('#alert').text("Invalid username format");
        $("#alert").css("display", "block");
      } else if (Object.keys(response).length === 0 && response.constructor === Object) {
        $('#alert').text("You need to fill in atleast one field to update");
        $("#alert").css("display", "block");
      } else {
        $('#alert').text("Error");
        $("#alert").css("display", "block");
      }
    });
  });
  $('body').click(function () {
    if (event.target.id === 'password' && event.target.closest("form").getAttribute("id") === 'registration') {
      var msg = "The password :<br> Must be a minimum of 8 characters<br>Must contain at least 1 number<br>Must contain at least one uppercase character<br>Must contain at least one lowercase character";
      $("#info").text(msg);
      $("#info").css("display", "block");
    }

    if (event.target.id === 'username' && event.target.closest("form").getAttribute("id") === 'registration') {
      var msg = "The username can contain letters, digits, @ and _";
      $("#info").text(msg);
      $("#info").css("display", "block");
    }
  });
  $('input').blur(function () {
    if (event.target.closest("form").getAttribute("id") === 'registration') {
      if (event.target.id === 'username') {
        var username_pattern = /^([a-zA-Z0-9@_]+)$/;
        var username = $('#username').val();
        $.get("fetch_info.php", {
          q1: "username",
          q2: username
        }, function (data) {
          if (Number(data) === 1) {
            $('#username').css("borderColor", "red");
            $("#alert").text("This username already exists");
            $("#alert").css("display", "block");
          }
        });

        if ($('#username').val() === "") {
          $('#username').css("borderColor", "rgba(0,0,0,.125)");
        } else if (!username_pattern.test($('#username').val())) {
          $('#username').css("borderColor", "red");
          $("#alert").text("Invalid username");
          $("#alert").css("display", "block");
        } else {
          $('#username').css("borderColor", "green");
        }
      } else if (event.target.id === 'password') {
        var password_pattern = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;

        if ($('#password').val() === "") {
          $('#password').css("borderColor", "rgba(0,0,0,.125)");
        } else if (!password_pattern.test($('#password').val())) {
          $('#password').css("borderColor", "red");
          $("#alert").text("Invalid password");
          $("#alert").css("display", "block");
        } else {
          $('#password').css("borderColor", "green");
        }
      } else if (event.target.id === "email") {
        var email = $('#email').val();
        $.get("fetch_info.php", {
          q1: "email",
          q2: email
        }, function (data) {
          if (Number(data) === 1) {
            $('#email').css("borderColor", "red");
            $("#alert").text("This email already exists");
            $("#alert").css("display", "block");
          }
        });

        if ($('#email').val() === "") {
          $('#email').css("borderColor", "rgba(0,0,0,.125)");
        } else if ($('#email').val().indexOf("@") < 0 || $('#email').val().indexOf(".") < 0) {
          $('#email').css("borderColor", "red");
          $("#alert").text("Invalid email");
          $("#alert").css("display", "block");
        } else {
          $('#email').css("borderColor", "green");
        }
      } else if (event.target.id === 'fname') {
        var name_pattern = /^([a-zA-Z]+)$/;

        if ($('#fname').val() === "") {
          $('#fname').css("borderColor", "rgba(0,0,0,.125)");
        } else if (!name_pattern.test($('#fname').val())) {
          $('#fname').css("borderColor", "red");
          $("#alert").text("Invalid first name");
          $("#alert").css("display", "block");
        } else {
          $('#fname').css("borderColor", "green");
        }
      } else if (event.target.id === 'lname') {
        var name_pattern = /^([a-zA-Z]+)$/;

        if ($('#lname').val() === "") {
          $('#lname').css("borderColor", "rgba(0,0,0,.125)");
        } else if (!name_pattern.test($('#lname').val())) {
          $('#lname').css("borderColor", "red");
          $("#alert").text("Invalid last name");
          $("#alert").css("display", "block");
        } else {
          $('#lname').css("borderColor", "green");
        }
      }
    }
  });
});

/***/ }),

/***/ 5:
/*!************************************!*\
  !*** multi ./resources/js/edit.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/edit.js */"./resources/js/edit.js");


/***/ })

/******/ });