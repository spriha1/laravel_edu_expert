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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/timetable.js":
/*!***********************************!*\
  !*** ./resources/js/timetable.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var user = $('#user_id').val();
  var date = new Date();
  $('.datepicker').datepicker('setDate', date);
  var date = $('#date').val();
  var user_id = $('#user_id').val();
  var user_type = $('#user_type').val();
  var date_format = $('#date_format').val();
  var year = parseInt(get_year(date, date_format));
  var d = format_date(date, date_format);
  var week = getNumberOfWeek(d);
  $.get('/fetch_request_status', {
    user_id: user_id,
    week: week,
    year: year
  }, function (result) {
    var response = JSON.parse(result);

    if (response[0]) {
      var status = response[0].name;
      $('.badge').text(status);

      if (status == 'Pending' || status == 'Approved') {
        $('button').css('display', 'none');
      } else {
        $('button').css('display', 'block');
      }
    } else {
      $('.badge').text('');
      $('button').css('display', 'block');
    }
  }).fail(function () {
    toastr.error('The request status cannot be determined');
  });
  load_display_data(date, user_id, user_type, date_format);
  $('.input').blur(function () {
    var time = $(this).val();
    time = time.split(":");
    time = parseInt(time[0], 10) * 3600 + parseInt(time[1], 10) * 60 + parseInt(time[2], 10);
    var date = $(this).closest('td').attr('date');
    var user_id = $('#user_id').val();
    var task_id = $(this).closest('tr').attr('task_id');
    var user_type = $('#user_type').val();
    $.post('/update_completion_time', {
      time: time,
      date: date,
      user_id: user_id,
      task_id: task_id,
      user_type: user_type
    }).fail(function () {
      toastr.error('The time could not be updated');
    });
  });
  $('.datepicker').datepicker().on('changeDate', function (e) {
    var date = e.format();
    var user_id = $('#user_id').val();
    var user_type = $('#user_type').val();
    var date_format = $('#date_format').val();
    var year = parseInt(get_year(date, date_format));
    var d = format_date(date, date_format);
    var week = getNumberOfWeek(d);
    $.get('/fetch_request_status', {
      user_id: user_id,
      week: week,
      year: year
    }, function (result) {
      var response = JSON.parse(result);

      if (response[0]) {
        var status = response[0].name;
        $('.badge').text(status);

        if (status == 'Pending' || status == 'Approved') {
          $('button').css('display', 'none');
        } else {
          $('button').css('display', 'block');
        }
      } else {
        $('.badge').text('');
        $('button').css('display', 'block');
      }
    }).fail(function () {
      toastr.error('The request status could not be determined');
    });
    $('.timetable').html("");
    load_display_data(date, user_id, user_type, date_format);
  });
  $('button').click(function () {
    var user_id = $('#user_id').val();
    var date_format = $('#date_format').val();
    var date = $('#date').val();
    var year = parseInt(get_year(date, date_format));
    var date = format_date(date, date_format);
    var week = getNumberOfWeek(date);
    $.post('/update_request_status', {
      user_id: user_id,
      week: week,
      status: "Pending",
      year: year,
      user: user
    }, function (result) {
      if (result == 'Pending') {
        $('button').css('display', 'none');
        $('.badge').text('Pending');
      }
    }).fail(function () {
      toastr.error('The request status could not be updated');
    });
  });
});

function format_date(date, date_format) {
  switch (date_format) {
    case "yyyy/mm/dd":
      date = date.split('/');
      date = new Date(date[0], date[1] - 1, date[2]);
      break;

    case "yyyy.mm.dd":
      date = date.split('.');
      date = new Date(date[0], date[1] - 1, date[2]);
      break;

    case "yyyy-mm-dd":
      date = date.split('-');
      date = new Date(date[0], date[1] - 1, date[2]);
      break;

    case "dd/mm/yyyy":
      date = date.split('/');
      date = new Date(date[2], date[1] - 1, date[0]);
      break;

    case "dd-mm-yyyy":
      date = date.split('-');
      date = new Date(date[2], date[1] - 1, date[0]);
      break;

    case "dd.mm.yyyy":
      date = date.split('.');
      date = new Date(date[2], date[1] - 1, date[0]);
      break;

    default:
      date = 0;
  }

  return date;
}

function get_year(date, date_format) {
  switch (date_format) {
    case "yyyy/mm/dd":
      date = date.split('/');
      var year = date[0];
      break;

    case "yyyy.mm.dd":
      date = date.split('.');
      var year = date[0];
      break;

    case "yyyy-mm-dd":
      date = date.split('-');
      var year = date[0];
      break;

    case "dd/mm/yyyy":
      date = date.split('/');
      var year = date[2];
      break;

    case "dd-mm-yyyy":
      date = date.split('-');
      var year = date[2];
      break;

    case "dd.mm.yyyy":
      date = date.split('.');
      var year = date[2];
      break;

    default:
      var year = 0;
  }

  return year;
}

function getNumberOfWeek(date) {
  var today = new Date(date);
  var firstDayOfYear = new Date(today.getFullYear(), 0, 1);
  var pastDaysOfYear = (today - firstDayOfYear) / 86400000;
  return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
}

function load_display_data(date, user_id, user_type, date_format) {
  $.post('/display_timetable', {
    date: date,
    user_id: user_id,
    user_type: user_type,
    date_format: date_format
  }, function (result) {
    var response = JSON.parse(result);
    var len = response['original_dates'].length;

    for (var index = 0; index < len; index++) {
      var date = new Date(response['original_dates'][index] * 1000);
      var year = date.getFullYear();
      var month = date.getMonth() + 1;
      var day = date.getDate();

      if (day < 10) {
        day = '0' + day;
      }

      if (month < 10) {
        month = '0' + month;
      }

      switch (date_format) {
        case "yyyy/mm/dd":
          date = year + '/' + month + '/' + day;
          break;

        case "yyyy.mm.dd":
          date = year + '.' + month + '.' + day;
          break;

        case "yyyy-mm-dd":
          date = year + '-' + month + '-' + day;
          break;

        case "dd/mm/yyyy":
          date = day + '/' + month + '/' + year;
          break;

        case "dd-mm-yyyy":
          date = day + '-' + month + '-' + year;
          break;

        case "dd.mm.yyyy":
          date = day + '.' + month + '.' + year;
          break;

        default:
          date = year + '-' + month + '-' + day;
      }

      $('table thead #' + index).text(date);
    }

    var tasks = response[0];
    var length = tasks.length;

    if (user_type === 'teacher') {
      for (var index = 0; index < length; index++) {
        var element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
        element.attr('task_id', tasks[index][0].task_id);
        element.appendTo('.timetable');
        var task_id = tasks[index][0].task_id;
        var len = response['dates'].length;

        for (var index2 = 0; index2 < len; index2++) {
          if (response['dates'][index2] != 0) {
            $("tbody tr[task_id=" + task_id + "] td[dow=" + index2 + "]").attr('date', response['dates'][index2]);
          }
        }

        var task = tasks[index][0].name + ' / ' + tasks[index][0]["class"];
        $("tbody tr[task_id=" + task_id + "] .task").text(task);
        var len = response[task_id].length;

        for (var index3 = 0; index3 < len; index3++) {
          if (response[task_id][index3].length != 0) {
            var seconds = response[task_id][index3][0].total_time;

            if (seconds != 0) {
              var hours = Math.floor(seconds / 3600);
              seconds = seconds - hours * 3600;
              var minutes = Math.floor(seconds / 60);
              seconds = seconds - minutes * 60;
              var time = hours + ':' + minutes + ':' + seconds;
            } else {
              var time = '0:0:0';
            }

            var task_id = response[task_id][index3][0].task_id;
            $("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][index3][0].on_date + "] input").val(time);
            $("tbody tr[task_id=" + task_id + "] td[dow=" + index3 + "] input").css('display', 'table-row');
          }
        }
      }
    } else if (user_type === 'student') {
      for (var index = 0; index < length; index++) {
        var _element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');

        _element.attr('task_id', tasks[index][0].task_id);

        _element.appendTo('.timetable');

        var task_id = tasks[index][0].task_id;
        var len = response['dates'].length;

        for (var index2 = 0; index2 < len; index2++) {
          if (response['dates'][index2] != 0) {
            $("tbody tr[task_id=" + task_id + "] td[dow=" + index2 + "]").attr('date', response['dates'][index2]);
          }
        }

        var task = tasks[index][0].name + ' / ' + tasks[index][0].firstname;
        $("tbody tr[task_id=" + task_id + "] .task").text(task);
        var len = response[task_id].length;

        for (var index3 = 0; index3 < len; index3++) {
          if (response[task_id][index3].length != 0) {
            var seconds = response[task_id][index3][0].total_time;

            if (seconds > 0) {
              var hours = Math.floor(seconds / 3600);
              seconds = seconds - hours * 3600;
              var minutes = Math.floor(seconds / 60);
              seconds = seconds - minutes * 60;
              var time = hours + ':' + minutes + ':' + seconds;
            } else {
              var time = '0:0:0';
            }

            var task_id = response[task_id][index3][0].task_id;
            $("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][index3][0].on_date + "] input").val(time);
            $("tbody tr[task_id=" + task_id + "] td[dow=" + index3 + "] input").css('display', 'table-row');
          }
        }
      }
    }
  }).fail(function () {
    toastr.error('The timetable could not be displayed');
  });
}

/***/ }),

/***/ 11:
/*!*****************************************!*\
  !*** multi ./resources/js/timetable.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/timetable.js */"./resources/js/timetable.js");


/***/ })

/******/ });