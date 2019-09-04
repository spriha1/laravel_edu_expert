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
  var user_id;
  var date = new Date();
  $('.datepicker').datepicker('setDate', date);
  var date = $('#date').val(); // var user_id = $('#user_id').val();

  var user_type = $('#user_type').val();
  var date_format = $('#date_format').val();
  $('#search').change(function () {
    user_id = $('#search').val();
    load_display_data(date, user_id, user_type, date_format);
  }); // $('.input').blur(function() {
  // 	var time = $(this).val();
  // 	time = time.split(":");
  // 	time = parseInt(time[0], 10)*3600 + parseInt(time[1], 10)*60 + parseInt(time[2], 10);
  // 	var date = $(this).closest('td').attr('date');
  // 	var user_id = $('#user_id').val();
  // 	var task_id = $(this).closest('tr').attr('task_id');
  // 	var user_type = $('#user_type').val();
  // 	$.post('/update_completion_time', {time: time, date: date, user_id: user_id, task_id: task_id, user_type: user_type});
  // })

  $('.datepicker').datepicker().on('changeDate', function (e) {
    var date = e.format(); // var user_id = $('#user_id').val();

    var user_type = $('#user_type').val();
    var date_format = $('#date_format').val();
    $('.timetable').html("");
    load_display_data(date, user_id, user_type, date_format);
  });
});

function load_display_data(date, user_id, user_type, date_format) {
  $.post('/display_timetable', {
    date: date,
    user_id: user_id,
    user_type: user_type,
    date_format: date_format
  }, function (result) {
    var response = JSON.parse(result);
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

    if (user_type === 'teacher') {
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
            $("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "] input").val(time);
            $("tbody tr[task_id=" + task_id + "] td[dow=" + j + "] input").css('display', 'table-row');
          }
        }
      }
    } else if (user_type === 'student') {
      for (var i = 0; i < length; i++) {
        var _element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');

        _element.attr('task_id', tasks[i][0].task_id);

        _element.appendTo('.timetable');

        var task_id = tasks[i][0].task_id;
        var len = response['dates'].length;

        for (var k = 0; k < len; k++) {
          if (response['dates'][k] != 0) {
            $("tbody tr[task_id=" + task_id + "] td[dow=" + k + "]").attr('date', response['dates'][k]);
          }
        }

        var task = tasks[i][0].name + ' / ' + tasks[i][0].firstname;
        $("tbody tr[task_id=" + task_id + "] .task").text(task);
        var len = response[task_id].length;

        for (var j = 0; j < len; j++) {
          if (response[task_id][j].length != 0) {
            var seconds = response[task_id][j][0].total_time;

            if (seconds > 0) {
              var hours = Math.floor(seconds / 3600);
              seconds = seconds - hours * 3600;
              var minutes = Math.floor(seconds / 60);
              seconds = seconds - minutes * 60;
              var time = hours + ':' + minutes + ':' + seconds;
            }

            var task_id = response[task_id][j][0].task_id;
            $("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "] input").val(time);
            $("tbody tr[task_id=" + task_id + "] td[dow=" + j + "] input").css('display', 'table-row');
          }
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