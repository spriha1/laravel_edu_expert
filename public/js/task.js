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
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/task.js":
/*!******************************!*\
  !*** ./resources/js/task.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $('#task').submit(function (event) {
    event.preventDefault();
    $("#spinner").css('display', 'block');
    $.post('/add_timetable', $('#task').serialize(), function (result) {
      $('#spinner').css('display', 'none');
      $('#alert').text(result).css('display', 'block');
      $('.datepicker').val('');
      $('.subject').val('');
      $('.subject').html('');
      $('.subject').select2('destroy').select2();
      $('#class').val('');
    }).fail(function () {
      toastr.error('The task could not be added');
    });
  });
  $('#teacher').change(function () {
    var teacher_id = $(this).val();
    $('.class').val('');
    $('.class').html('');
    $.post('/fetch_teacher_class', {
      teacher_id: teacher_id
    }, function (result) {
      var response = JSON.parse(result);
      var length = response.length;

      for (var index = 0; index < length; index++) {
        var element = $('.clone_').clone(true).removeClass('clone_');
        element.attr('value', response[index]["class"]);
        element.text(response[index]["class"]);
        element.appendTo('.class');
      }

      $('#class').trigger('change');
    }).fail(function () {
      toastr.error('The information corresponding to the teacher could not be fetched');
    });
  }).trigger('change');
  $('#class').change(function () {
    var class_id = $(this).val();
    var teacher_id = $('#teacher').val();
    $('.subject').val('');
    $('.subject').html('');
    $('.subject').select2('destroy').select2();
    $.post('/fetch_teacher_class_subjects', {
      class_id: class_id,
      teacher_id: teacher_id
    }, function (result) {
      var response = JSON.parse(result);
      var length = response.length;

      for (var index = 0; index < length; index++) {
        var element = $('.clone').clone(true).removeClass('clone');
        element.attr('value', response[index].id);
        element.text(response[index].name);
        element.appendTo('.subject');
      }
    }).fail(function () {
      toastr.error('The required information could not be fetched');
    });
  });
});

function format_date(date) {
  var today = new Date(date);
  var year = today.getFullYear();
  var month = today.getMonth() + 1;
  var date = today.getDate();

  if (month < 10 && date < 10) {
    var date = year + '-0' + month + '-0' + date;
  } else if (month < 10) {
    var date = year + '-0' + month + '-' + date;
  } else if (date < 10) {
    var date = year + '-' + month + '-0' + date;
  }

  return date;
}

/***/ }),

/***/ 9:
/*!************************************!*\
  !*** multi ./resources/js/task.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/task.js */"./resources/js/task.js");


/***/ })

/******/ });