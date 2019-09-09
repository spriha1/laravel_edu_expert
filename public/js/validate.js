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
  function validation() {
    jQuery.validator.addMethod("onlyalpha", function (value, element) {
      return this.optional(element) || /^([a-zA-Z]+)$/.test(value);
    }, 'Only alphabetic characters are allowed');
    jQuery.validator.addMethod("username", function (value, element) {
      return this.optional(element) || /^([a-zA-Z0-9@_]+)$/.test(value);
    }, 'Please enter a valid username');
    jQuery.validator.addMethod("password", function (value, element) {
      return this.optional(element) || /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/.test(value);
    }, 'Please enter a valid password');
    $("#registration").validate({
      rules: {
        'fname': {
          'required': true,
          'onlyalpha': true
        },
        'lname': {
          'required': true,
          'onlyalpha': true
        },
        'user_type': 'required',
        'email': {
          'required': true,
          'email': true
        },
        'password': {
          'required': true,
          'password': true
        },
        'username': {
          'required': true,
          'username': true
        }
      }
    });
  }

  $('body').submit(function (event) {
    event.preventDefault();
    validation();

    if ($('#registration').valid()) {
      $("#spinner").css('display', 'block');
      $.post('/register', $('#registration').serialize(), function (result) {
        $('#spinner').css('display', 'none');
        $("#alert").css("display", "block");
        $("#alert").text(result);
      });
    } // if (event.target.id === 'registration') {
    // 	var check,c = 0,size = 0;
    // 	$('#registration input').not('.select2-search__field').each(function(){
    // 	    check = $(this).val().trim();
    // 		if (check === "") {
    // 			$(this).css("borderColor" , "red");
    // 			c++;
    // 			console.log(this);
    // 		}
    // 	});
    // 	$('#registration').find('select').each(function(){
    // 	    check = $(this).val();
    // 	    console.log(check);
    // 		// if (check === "") {
    // 		// 	$(this).css("borderColor" , "red");
    // 		// 	c++;
    // 		// 	console.log(this);
    // 		// }
    // 	});
    // 	if (c > 0) {
    // 		$("#alert").text("Please fill in the highlighted fields");
    // 		$("#alert").css("display" , "block");
    // 		event.preventDefault();
    // 	}
    // 	else {
    // 		$.post('register' , $('#registration').serialize() , function(result){
    // 			$("#alert").css("display" , "block");
    // 			$("#alert").text(result);
    // 		})
    // 		.fail(function(result) {
    // 			console.log(result.responseText);
    // 		    var res = JSON.parse(result.responseText);
    // 		    var errors = res.errors;
    // 			// $('#alert').html(errors.fname + '<br>' + errors.lname + '<br>' + errors.username + '<br>' + errors.email + '<br>' + errors.password);
    // 			$("#alert").css("display" , "block");
    // 		});
    // 	}
    // }

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
        var username = $('#username').val();
        $.get("/fetch_info", {
          q1: "username",
          q2: username
        }, function (data) {
          if (Number(data) === 1) {
            $('#username').css("borderColor", "red");
            $("#alert").text("This username already exists");
            $("#alert").css("display", "block");
          }
        });
      } else if (event.target.id === 'email') {
        $("#info_email").css("display", "none");
        var email = $('#email').val();
        $.get("/fetch_info", {
          q1: "email",
          q2: email
        }, function (data) {
          if (Number(data) === 1) {
            $('#email').css("borderColor", "red");
            $("#alert").text("This email already exists");
            $("#alert").css("display", "block");
          }
        });
      }
    }
  });
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