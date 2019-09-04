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
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/daily_timetable.js":
/*!*****************************************!*\
  !*** ./resources/js/daily_timetable.js ***!
  \*****************************************/
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
  var date = $('#date').val();
  var user_id = $('#user_id').val();
  var user_type = $('#user_type').val();
  var date_format = $('#date_format').val();
  load_display_data(date, user_id, user_type, date_format);
  $('#share').click(function (event) {
    event.preventDefault();
    var user_id = $("#user_id").val();
    var date_format = $('#date_format').val();
    var date = $("#date").val();
    $.post('/add_shared_timesheets', {
      user_id: user_id,
      date: date,
      date_format: date_format
    });
  });
  $('.datepicker').datepicker().on('changeDate', function (e) {
    var date = e.format();
    var user_id = $('#user_id').val();
    var user_type = $('#user_type').val();
    var date_format = $('#date_format').val();
    $('.timetable').html("");
    load_display_data(date, user_id, user_type, date_format);
  });
});

function load_display_data(date, user_id, user_type, date_format) {
  $.post('/display_daily_timetable', {
    date: date,
    user_id: user_id,
    user_type: user_type,
    date_format: date_format
  }, function (result) {
    // console.log(result);
    var response = JSON.parse(result);

    if (date_format === "yyyy/mm/dd") {
      date = date.split('/');
      date = new Date(date[0], date[1] - 1, date[2]).getTime();
    } else if (date_format === "yyyy.mm.dd") {
      date = date.split('.');
      date = new Date(date[0], date[1] - 1, date[2]).getTime();
    } else if (date_format === "yyyy-mm-dd") {
      date = date.split('-');
      date = new Date(date[0], date[1] - 1, date[2]).getTime();
    } else if (date_format === "dd/mm/yyyy") {
      date = date.split('/');
      date = new Date(date[2], date[1] - 1, date[0]).getTime();
    } else if (date_format === "dd-mm-yyyy") {
      date = date.split('-');
      date = new Date(date[2], date[1] - 1, date[0]).getTime();
    } else if (date_format === "dd.mm.yyyy") {
      date = date.split('.');
      date = new Date(date[2], date[1] - 1, date[0]).getTime();
    }

    date = date / 1000;
    var length = response.length;

    if (user_type === 'teacher') {
      for (var i = 0; i < length; i++) {
        var element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
        element.attr('task_id', response[i].task_id);
        element.appendTo('.timetable');
        var task_id = response[i].task_id;
        var seconds = response[i].total_time;

        if (seconds > 0) {
          var hours = Math.floor(seconds / 3600);
          seconds = seconds - hours * 3600;
          var minutes = Math.floor(seconds / 60);
          seconds = seconds - minutes * 60;
          var time = hours + ':' + minutes + ':' + seconds;

          var _date = new Date(date * 1000);

          _date = _date.getDate() + '/' + (_date.getMonth() + 1) + '/' + _date.getFullYear();
          var _on_date = response[i].on_date;
          _on_date = new Date(_on_date * 1000);
          _on_date = _on_date.getDate() + '/' + (_on_date.getMonth() + 1) + '/' + _on_date.getFullYear();

          if (_date === _on_date) {
            $("tbody tr[task_id=" + task_id + "] .timer").text(time);
          }
        }

        $("tbody tr[task_id=" + task_id + "] .name").text(response[i].name);
        $("tbody tr[task_id=" + task_id + "] .class").text(response[i]["class"]);
        $("tbody tr[task_id=" + task_id + "] .stop").attr('task_id', response[i].task_id);
      }
    } else if (user_type === 'student') {
      for (var i = 0; i < length; i++) {
        var _element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');

        _element.attr('task_id', response[i].task_id);

        _element.appendTo('.timetable');

        var task_id = response[i].task_id;
        var seconds = response[i].total_time;

        if (seconds > 0) {
          var hours = Math.floor(seconds / 3600);
          seconds = seconds - hours * 3600;
          var minutes = Math.floor(seconds / 60);
          seconds = seconds - minutes * 60;
          var time = hours + ':' + minutes + ':' + seconds;

          var _date = new Date(date * 1000);

          _date = _date.getDate() + '/' + (_date.getMonth() + 1) + '/' + _date.getFullYear();
          var _on_date = response[i].on_date;
          _on_date = new Date(_on_date * 1000);
          _on_date = _on_date.getDate() + '/' + (_on_date.getMonth() + 1) + '/' + _on_date.getFullYear(); // console.log(_date)
          // console.log(_on_date)

          if (_date == _on_date) {
            $("tbody tr[task_id=" + task_id + "] .timer").text(time);
          }
        }

        $("tbody tr[task_id=" + task_id + "] .name").text(response[i].name);
        $("tbody tr[task_id=" + task_id + "] .teacher").text(response[i].firstname);
        $("tbody tr[task_id=" + task_id + "] .stop").attr('task_id', response[i].task_id);
      }
    }
  });
}

/***/ }),

/***/ 10:
/*!***********************************************!*\
  !*** multi ./resources/js/daily_timetable.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/daily_timetable.js */"./resources/js/daily_timetable.js");


/***/ })

/******/ });