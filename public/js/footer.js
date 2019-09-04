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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/footer.js":
/*!********************************!*\
  !*** ./resources/js/footer.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  var url = window.location.pathname;
  $('.main-sidebar .sidebar ul li a[href="' + url + '"]').parent().addClass('active'); // $(".chosen").select2({
  // 	matcher: matchCustom
  // });

  var footer = document.getElementById("footer").getAttribute("footer");

  if (footer === "footer") {
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
        /* optional */

      });
      $('.subject').select2({
        width: 'resolve'
      });
    });
  } else if (footer === "dashboard_footer") {
    $.widget.bridge('uibutton', $.ui.button);
    $('.datepicker').datepicker({
      format: $('#date_format').val()
    });
    $('.subject').select2({
      width: 'resolve'
    });
    $('._subject').select2({
      width: 'resolve'
    });
  } else if (footer === "profile_footer") {
    $.widget.bridge('uibutton', $.ui.button);
    $('#edit').click(function () {
      event.preventDefault();
      $(":input").attr("readonly", false);
      $("#date_format").attr("disabled", false);
      $("#edit").css('display', 'none');
      $("#update").css('display', 'block');
    });
    $('#change').click(function () {
      event.preventDefault();
      $("#pass").css('display', 'block');
      $("#edit").css('display', 'none');
      $("#update").css('display', 'block');
    });
    $('.start').click(function () {
      var task_id = $(this).closest('tr').attr('task_id');
      $(this).closest('tr').find('.timer').timer({
        seconds: 0,
        hidden: false
      });
      $('tbody tr[task_id=' + task_id + '] .start').css('display', 'none');
      $('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
      $('tbody tr[task_id=' + task_id + '] .pause').css('display', 'table-row');
    });
    $('.stop').click(function () {
      var task_id = $(this).closest('tr').attr('task_id');
      var date = $('#date').val();
      var date_format = $('#date_format').val();
      $('tbody tr[task_id=' + task_id + '] .start').css('display', 'none');
      $('tbody tr[task_id=' + task_id + '] .stop').css('display', 'none');
      $('tbody tr[task_id=' + task_id + '] .pause').css('display', 'none');
      var time = $(this).closest('tr').find('.timer').data('seconds');
      $('tbody tr[task_id=' + task_id + '] .timer').timer('remove');
      var task_id = $(this).attr('task_id');
      var user_id = $('#user_id').val();
      var user_type = $('#user_type').val();
      $.post('/add_completion_time', {
        task_id: task_id,
        user_id: user_id,
        time: time,
        user_type: user_type,
        date: date,
        date_format: date_format
      });
    });
    $('.resume').click(function () {
      var task_id = $(this).closest('tr').attr('task_id');
      $('tbody tr[task_id=' + task_id + '] .resume').css('display', 'none');
      $('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
      $('tbody tr[task_id=' + task_id + '] .pause').css('display', 'table-row');
      $(this).closest('tr').find('.timer').timer('resume');
    });
    $('.pause').click(function () {
      var task_id = $(this).closest('tr').attr('task_id');
      $('tbody tr[task_id=' + task_id + '] .pause').css('display', 'none');
      $('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
      $('tbody tr[task_id=' + task_id + '] .resume').css('display', 'table-row');
      $(this).closest('tr').find('.timer').timer('pause');
    });
    $('.datepicker').datepicker({
      format: $('#date_format').val()
    });
    $('.subject').select2({
      width: 'resolve'
    });
    $('.day').select2({
      width: 'resolve'
    });
  } else if (footer === "timesheet_footer") {
    $.widget.bridge('uibutton', $.ui.button);
  } else if (footer === "forgot_password_footer") {
    $(document).ready(function () {
      $('button').click(function () {
        event.preventDefault();

        if ($('#username').val().trim() === "") {
          $('#username').css("borderColor", "red");
          $("#alert").text("Please enter a username");
          $("#alert").css("display", "block");
        } else {
          // console.log('hi');
          var username = $('#username').val();
          $.get("/fetch_info", {
            q1: "username",
            q2: username
          }, function (data) {
            if (Number(data) === 0) {
              $('#username').css("borderColor", "red");
              $("#alert").text("This username does not exist");
              $("#alert").css("display", "block");
            } else {
              $('#login').submit();
            }
          });
        }
      });
    });
  }
});

/***/ }),

/***/ 2:
/*!**************************************!*\
  !*** multi ./resources/js/footer.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/footer.js */"./resources/js/footer.js");


/***/ })

/******/ });