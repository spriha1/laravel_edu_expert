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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/goals.js":
/*!*******************************!*\
  !*** ./resources/js/goals.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var date = new Date();
  $('.datepicker').datepicker('setDate', date);
  date = $('#date').val();
  date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
  date = new Date(date);
  date = date.getTime() / 1000;
  var user_id = $('#user_id').val();
  var total_time = 0;
  load_display_data(date, user_id);
  $(".add_item").click(function (event) {
    event.preventDefault();
    $("#goal").css("display", "block");
    $(".add").css("display", "block");
    $(".add_item").css("display", "none");
  });
  $('#activate').click(function () {
    event.preventDefault();
    $.get('/connect');
  });
  $(".add").click(function (event) {
    event.preventDefault();
    $("#goal").css("display", "none");
    $(".add").css("display", "none");
    $(".add_item").css("display", "block");
    date = $('#date').val();
    date_format = $('#date_format').val();
    date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
    date = new Date(date);
    on_date = date.getTime() / 1000;
    var goal = $("textarea").val();
    var user_id = $(".add").attr("user_id");
    $.post('add_goals', {
      goal: goal,
      user_id: user_id,
      on_date: on_date
    }, function (result) {
      var response = JSON.parse(result);
      var element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
      element.find('.text').html(response[0].goal);
      element.find('.remove').attr('goal_id', response[0].id);
      ;
      element.attr('goal_id', response[0].id);
      element.appendTo('.todo');
    });
    $("textarea").val("");
  });
  $(".check_goal").change(function (event) {
    event.preventDefault();
    var goal_id = $(this).closest('[goal_id]').attr("goal_id");
    $.post('update_goals', {
      goal_id: goal_id
    }, function (result) {
      var response = JSON.parse(result);
      var total_time = response[0].total_time;
      var time = new Date(null);
      time.setSeconds(response[0].total_time);
      var total_time = time.toISOString().substr(11, 8);
      $("ul li[goal_id=" + goal_id + "]").find('.time').css('visibility', 'visible');
      $("ul li[goal_id=" + goal_id + "]").find('.total_time').text(total_time);
    });
  });
  $(".remove").click(function (event) {
    var goal_id = $(this).attr('goal_id');
    $.post('remove_goals', {
      goal_id: goal_id
    }, function () {
      $("ul li[goal_id=" + goal_id + "]").remove();
    });
  });
  $('.datepicker').datepicker().on('changeDate', function (e) {
    var date = e.format();
    date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
    date = new Date(date);
    date = date.getTime() / 1000;
    var user_id = $('#user_id').val();
    $('.todo').html("");
    load_display_data(date, user_id);
  });
});

function load_display_data(date, user_id) {
  $.post('display_goals', {
    date: date,
    user_id: user_id
  }, function (result) {
    var response = JSON.parse(result);
    var length = response.length;

    for (var index = 0; index < length; index++) {
      var element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
      element.attr('goal_id', response[index].id);
      element.appendTo('.todo');
      goal_id = response[index].id;
      $("ul li[goal_id=" + goal_id + "] .text").html(response[index].goal);
      $("ul li[goal_id=" + goal_id + "] .remove").attr('goal_id', response[index].id);
      $("ul li[goal_id=" + goal_id + "] .time").attr('id', response[index].id);

      if (response[index].check_status == 1) {
        $("ul li[goal_id=" + goal_id + "] .check_goal").attr('checked', true);
        var time = new Date(null);
        time.setSeconds(response[index].total_time);
        total_time = time.toISOString().substr(11, 8);
        $("ul li[goal_id=" + goal_id + "] .time").css('visibility', 'visible');
        $("ul li[goal_id=" + goal_id + "] .time .total_time").text(total_time);
      }
    }
  });
}

/***/ }),

/***/ 3:
/*!*************************************!*\
  !*** multi ./resources/js/goals.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/goals.js */"./resources/js/goals.js");


/***/ })

/******/ });