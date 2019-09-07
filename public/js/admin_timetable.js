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
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin_timetable.js":
/*!*****************************************!*\
  !*** ./resources/js/admin_timetable.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var user_id, user_type;
  var date = new Date();
  $('.datepicker').datepicker('setDate', date);
  var date = $('#date').val();
  var date_format = $('#date_format').val();
  $('#search').change(function () {
    user_id = $('#search').val();
    user_type = $('option:selected').attr('usertype');
    date = $('#date').val();
    date_format = $('#date_format').val();
    var d = format_date(date, date_format);
    var week = getNumberOfWeek(d);
    $.get('/fetch_request_status', {
      user_id: user_id,
      week: week
    }, function (result) {
      var response = JSON.parse(result);

      if (response[0]) {
        var status = response[0].name;

        if (status == 'Pending') {
          $('#accept').css('display', 'inline');
          $('#reject').css('display', 'inline');
        } else if (status == 'Approved') {
          $('#accept').css('display', 'none');
          $('#reject').css('display', 'inline');
          $('.badge').text('Approved');
        } else if (status == 'Rejected') {
          $('.badge').text('Rejected');
          $('#accept').css('display', 'inline');
          $('#reject').css('display', 'none');
        }
      } else {
        $('.badge').text('');
        $('#accept').css('display', 'none');
        $('#reject').css('display', 'none');
      }
    });
    load_display_data(date, user_id, date_format, user_type);
  });
  $('#accept').click(function () {
    user_id = $('#search').val();
    date = $('#date').val();
    date_format = $('#date_format').val();
    var d = format_date(date, date_format);
    var week = getNumberOfWeek(d);
    $.post('/update_request_status', {
      user_id: user_id,
      week: week,
      status: "Approved"
    }, function (result) {
      if (result) {
        $('#accept').css('display', 'none');
        $('#reject').css('display', 'inline');
        $('.badge').text('Approved');
      }
    });
  });
  $('#reject').click(function () {
    user_id = $('#search').val();
    date = $('#date').val();
    date_format = $('#date_format').val();
    var d = format_date(date, date_format);
    var week = getNumberOfWeek(d);
    $.post('/update_request_status', {
      user_id: user_id,
      week: week,
      status: "Rejected"
    }, function (result) {
      if (result) {
        $('.badge').text('Rejected');
        $('#accept').css('display', 'inline');
        $('#reject').css('display', 'none');
      }
    });
  });
  $('.datepicker').datepicker().on('changeDate', function (e) {
    var date = e.format();
    user_id = $('#search').val();
    var date_format = $('#date_format').val();
    var d = format_date(date, date_format);
    var week = getNumberOfWeek(d);
    $.get('/fetch_request_status', {
      user_id: user_id,
      week: week
    }, function (result) {
      var response = JSON.parse(result);

      if (response[0]) {
        var status = response[0].name;

        if (status == 'Pending') {
          $('#accept').css('display', 'inline');
          $('#reject').css('display', 'inline');
        } else if (status == 'Approved') {
          $('#accept').css('display', 'none');
          $('#reject').css('display', 'inline');
          $('.badge').text('Approved');
        } else if (status == 'Rejected') {
          $('.badge').text('Rejected');
          $('#accept').css('display', 'inline');
          $('#reject').css('display', 'none');
        }
      } else {
        $('.badge').text('');
        $('#accept').css('display', 'none');
        $('#reject').css('display', 'none');
      }
    });
    $('.timetable').html("");
    load_display_data(date, user_id, date_format, user_type);
  });
});

function format_date(date, date_format) {
  if (date_format === "yyyy/mm/dd") {
    date = date.split('/');
    date = new Date(date[0], date[1] - 1, date[2]);
  } else if (date_format === "yyyy.mm.dd") {
    date = date.split('.');
    date = new Date(date[0], date[1] - 1, date[2]);
  } else if (date_format === "yyyy-mm-dd") {
    date = date.split('-');
    date = new Date(date[0], date[1] - 1, date[2]);
  } else if (date_format === "dd/mm/yyyy") {
    date = date.split('/');
    date = new Date(date[2], date[1] - 1, date[0]);
  } else if (date_format === "dd-mm-yyyy") {
    date = date.split('-');
    date = new Date(date[2], date[1] - 1, date[0]);
  } else if (date_format === "dd.mm.yyyy") {
    date = date.split('.');
    date = new Date(date[2], date[1] - 1, date[0]);
  }

  return date;
}

function getNumberOfWeek(date) {
  var today = new Date(date);
  var firstDayOfYear = new Date(today.getFullYear(), 0, 1);
  var pastDaysOfYear = (today - firstDayOfYear) / 86400000;
  return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
}

function load_display_data(date, user_id, date_format, user_type) {
  $.post('/post_timesheets', {
    date: date,
    user_id: user_id,
    date_format: date_format,
    user_type: user_type
  }, function (result) {
    var response = JSON.parse(result);
    console.log(response);
    $('.timetable').html("");
    var len = response['original_dates'].length;

    for (var i = 0; i < len; i++) {
      var date = new Date(response['original_dates'][i] * 1000);
      var year = date.getFullYear();
      var month = date.getMonth() + 1;
      var day = date.getDate();

      if (day < 10) {
        day = '0' + day;
      }

      if (month < 10) {
        month = '0' + month;
      }

      if (date_format === "yyyy/mm/dd") {
        date = year + '/' + month + '/' + day;
      } else if (date_format === "yyyy.mm.dd") {
        date = year + '.' + month + '.' + day;
      } else if (date_format === "yyyy-mm-dd") {
        date = year + '-' + month + '-' + day;
      } else if (date_format === "dd/mm/yyyy") {
        date = day + '/' + month + '/' + year;
      } else if (date_format === "dd-mm-yyyy") {
        date = day + '-' + month + '-' + year;
      } else if (date_format === "dd.mm.yyyy") {
        date = day + '.' + month + '.' + year;
      }

      $('table thead #' + i).text(date);
    }

    var tasks = response[0];
    var length = tasks.length;

    for (var i = 0; i < length; i++) {
      var element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
      element.attr('task_id', tasks[i][0].task_id);
      element.appendTo('.timetable');
      var task_id = tasks[i][0].task_id;
      var len = response['dates'].length;

      for (var k = 0; k < len; k++) {
        if (response['dates'][k] != 0) {
          $("tbody tr[task_id=" + task_id + "] td[dow=" + k + "]").attr('date', response['dates'][k]);
        }
      }

      var task = tasks[i][0].name + ' / ' + tasks[i][0]["class"];
      $("tbody tr[task_id=" + task_id + "] .task").text(task);
      var len = response[task_id].length;

      for (var j = 0; j < len; j++) {
        if (response[task_id][j].length != 0) {
          var seconds = response[task_id][j][0].total_time;

          if (seconds != 0) {
            var hours = Math.floor(seconds / 3600);
            seconds = seconds - hours * 3600;
            var minutes = Math.floor(seconds / 60);
            seconds = seconds - minutes * 60;
            var time = hours + ':' + minutes + ':' + seconds;
          } else {
            var time = '0:0:0';
          }

          var task_id = response[task_id][j][0].task_id;
          $("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "]").text(time);
        }
      }
    }
  });
}

/***/ }),

/***/ 12:
/*!***********************************************!*\
  !*** multi ./resources/js/admin_timetable.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/admin_timetable.js */"./resources/js/admin_timetable.js");


/***/ })

/******/ });