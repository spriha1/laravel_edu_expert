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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/validate.js":
/*!**********************************!*\
  !*** ./resources/js/validate.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('body').submit(function (event) {
    //event.preventDefault();
    //if (event.target.id === 'login') {
    // var username = $('#username').val().trim();
    // var password = $('#password').val().trim();
    // if (username === "") {
    // 	$('#username').css("borderColor" , "red");
    // }
    // if (password === "") {
    // 	$('#password').css("borderColor" , "red");
    // }
    // if (username === "" || password === "") {
    // 	$("#alert").text("Please fill in the highlighted fields");
    // 	$("#alert").css("display" , "block");
    // }
    // else {
    // $.post('ajax_login.php', $('#login').serialize(), function(result) {
    // 	var response = JSON.parse(result)["msg"];
    // 	var newToken = JSON.parse(result)["token"];
    // 	$('#token').val(newToken);
    // 	console.log(response);
    // 	if (response === "Admin") {
    // 		window.location.href = 'admin_dashboard.php';
    // 	}
    // 	else if (response === "Student") {
    // 		window.location.href = 'student_dashboard.php';
    // 	}
    // 	else if (response === "Teacher") {
    // 		window.location.href = 'teacher_dashboard.php';
    // 	}
    // 	else {
    // 		$('#alert').text(response);
    // 		$("#alert").css("display" , "block");
    // 	}
    // });
    // }
    //}
    if (event.target.id === 'registration') {
      event.preventDefault();
      $.post('register', $('#registration').serialize(), function (result) {
        console.log('success');
      }).fail(function (result) {
        console.log(result.responseText);
        var res = JSON.parse(result.responseText);
        var errors = res.errors;
        $('#alert').html(errors.fname + '<br>' + errors.lname + '<br>' + errors.username + '<br>' + errors.email + '<br>' + errors.password);
        $("#alert").css("display", "block");
      });
    }
  });
  $('body').click(function () {
    if (event.target.id === 'password' && event.target.closest("form").getAttribute("id") === 'registration') {
      var msg = "The password :<br> Must be a minimum of 8 characters<br>Must contain at least 1 number<br>Must contain at least one uppercase character<br>Must contain at least one lowercase character";
      $("#info_password").html(msg);
      $("#info_password").css("display", "block");
    }

    if (event.target.id === 'username' && event.target.closest("form").getAttribute("id") === 'registration') {
      var msg = "The username can contain letters, digits, @ and _";
      $("#info_username").text(msg);
      $("#info_username").css("display", "block");
    }
  });
  $('input').blur(function () {
    if (event.target.closest("form").getAttribute("id") === 'registration') {
      if (event.target.id === 'password') {
        $("#info_password").css("display", "none");
      } else if (event.target.id === 'username') {
        $("#info_username").css("display", "none");
        var username_pattern = /^([a-zA-Z0-9@_]+)$/;
        var username = $('#username').val();
        $.get("fetch_info", {
          q1: "username",
          q2: username
        }, function (data) {
          if (Number(data) === 1) {
            $('#username').css("borderColor", "red");
            $("#alert").text("This username already exists");
            $("#alert").css("display", "block");
          }
        });
      }
    }
  }); // 		if (event.target.id === 'username') {
  // 			$("#info_username").css("display" , "none");
  // 			var username_pattern = /^([a-zA-Z0-9@_]+)$/;
  // 			var username = $('#username').val();
  // 			$.get("fetch_info.php" , {q1: "username", q2: username} , function(data) {
  // 				if (Number(data) === 1) {
  // 					$('#username').css("borderColor" , "red");
  // 					$("#alert").text("This username already exists");
  // 					$("#alert").css("display" , "block");
  // 				}
  // 			})
  // 			if ($('#username').val() === "") {
  // 				$('#username').css("borderColor" , "rgba(0,0,0,.125)");
  // 			}
  // 			else if (!username_pattern.test($('#username').val())) {
  // 				$('#username').css("borderColor" , "red");
  // 				$("#alert").text("Invalid username");
  // 				$("#alert").css("display" , "block");
  // 			}
  // 			else {
  // 				$('#username').css("borderColor" , "green");
  // 			}
  // 		}
  // 		else if (event.target.id === 'password') {
  // 			$("#info_password").css("display" , "none");
  // 			var password_pattern = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
  // 			if ($('#password').val() === "") {
  // 				$('#password').css("borderColor" , "rgba(0,0,0,.125)");
  // 			}
  // 			else if (!password_pattern.test($('#password').val())) {
  // 				$('#password').css("borderColor" , "red");
  // 				$("#alert").text("Invalid password");
  // 				$("#alert").css("display" , "block");
  // 			}
  // 			else {
  // 				$('#password').css("borderColor" , "green");
  // 			}
  // 		}
  // 		else if (event.target.id ==="email") {
  // 			var email = $('#email').val();
  // 			$.get("fetch_info.php", {q1: "email", q2: email}, function(data) {
  // 				if (Number(data) === 1) {
  // 					$('#email').css("borderColor" , "red");
  // 					$("#alert").text("This email already exists");
  // 					$("#alert").css("display" , "block");
  // 				}
  // 			})
  // 			if ($('#email').val() === "") {
  // 				$('#email').css("borderColor" , "rgba(0,0,0,.125)");
  // 			}
  // 			else if ($('#email').val().indexOf("@") < 0 || $('#email').val().indexOf(".") < 0) {
  // 				$('#email').css("borderColor" , "red");
  // 				$("#alert").text("Invalid email");
  // 				$("#alert").css("display" , "block");
  // 			}
  // 			else {
  // 				$('#email').css("borderColor" , "green");
  // 			}
  // 		}
  // 		else if (event.target.id === 'fname') {
  // 			var name_pattern = /^([a-zA-Z]+)$/;
  // 			if ($('#fname').val() === "") {
  // 				$('#fname').css("borderColor" , "rgba(0,0,0,.125)");
  // 			}
  // 			else if (!name_pattern.test($('#fname').val())) {
  // 				$('#fname').css("borderColor" , "red");
  // 				$("#alert").text("Invalid first name");
  // 				$("#alert").css("display" , "block");
  // 			}
  // 			else {
  // 				$('#fname').css("borderColor" , "green");
  // 			}
  // 		}
  // 		else if (event.target.id === 'lname') {
  // 			var name_pattern = /^([a-zA-Z]+)$/;
  // 			if ($('#lname').val() === "") {
  // 				$('#lname').css("borderColor" , "rgba(0,0,0,.125)");
  // 			}
  // 			else if (!name_pattern.test($('#lname').val())) {
  // 				$('#lname').css("borderColor" , "red");
  // 				$("#alert").text("Invalid last name");
  // 				$("#alert").css("display" , "block");
  // 			}
  // 			else {
  // 				$('#lname').css("borderColor" , "green");
  // 			}
  // 		}
  // 	}
  // });

  $('#user_type').change(function (event) {
    if ($('#user_type').val() === 'Teacher') {
      $('.subject').closest('div').css('display', 'block');
    }

    if ($('#user_type').val() === 'Student') {
      $('.subject').closest('div').css('display', 'none');
    }
  });
  $('.subject').change(function () {
    console.log($('.subject').val());
  });
});

/***/ }),

/***/ 1:
/*!****************************************!*\
  !*** multi ./resources/js/validate.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/validate.js */"./resources/js/validate.js");


/***/ })

/******/ });