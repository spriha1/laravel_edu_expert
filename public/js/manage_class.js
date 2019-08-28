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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/manage_class.js":
/*!**************************************!*\
  !*** ./resources/js/manage_class.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.get('display_class', function (result) {
    var response = JSON.parse(result);
    var length = response.length;

    for (var i = 0; i < length; i++) {
      var element = $(".clone").clone(true).css('display', 'block').removeClass('clone');
      element.find('.text').text(response[i]["class"]);
      element.attr('class_id', response[i]["class"]);
      element.appendTo('.append_class');
    }
  });
  $(".add_item").click(function (event) {
    event.preventDefault();
    $('#append_teacher').html("");
    $('.append_teacher #class').val("");
    $('.append_teacher .subject').val(''); // $('.append_teacher .subject').html('');

    $('.append_teacher .subject').select2('destroy').select2();
    $(".add_class").css("display", "block");
  });
  $('.subject').on('select2:select', function (e) {
    var data = e.params.data;
    var id = data.id; //value of options

    var text = data.text;
    $.post('fetch_teachers', {
      subject_id: id
    }, function (result) {
      var response = JSON.parse(result);
      var element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
      element.find('label').text(text);
      element.find('label').attr('for', id);
      element.find('select').attr('name', id);

      for (var i = 0; i < response.length; i++) {
        var element2 = $(".editable option").clone(true); // element.closest('select').removeClass('teacher');

        console.log(element);
        element2.attr('value', response[i].id);
        element2.html(response[i].firstname);
        element2.appendTo(element.find('select'));
      }

      element.appendTo('#append_teacher');
    });
  });
  $('.subject').on('select2:unselect', function (e) {
    var data = e.params.data;
    var id = data.id;
    $('.append_teacher label[for=' + id + ']').remove();
    $('.append_teacher select[name=' + id + ']').remove();
  });
  $("#add").click(function (event) {
    event.preventDefault();
    $(".add_class").css("display", "none");
    $.post('add_class', $('#add_class').serialize(), function (result) {
      var response = JSON.parse(result);
      console.log(response[0]["class"]);
      var element = $(".clone").clone(true).css('display', 'block').removeClass('clone');
      element.find('.text').text(response[0]["class"]);
      element.attr('class_id', response[0]["class"]);
      element.appendTo('.append_class');
    }); // $('.subject').text("");
    // $('#class').val("");
  });
  $(".remove").click(function (event) {
    //event.preventDefault();
    var class_id = $(this).closest('li').attr('class_id');
    $.post('remove_class', {
      class_id: class_id
    }, function () {
      $("ul li[class_id=" + class_id + "]").remove();
    });
  });
  $('.edit').click(function (event) {
    var class_id = $(this).closest('li').attr('class_id');
    $('._add_class').css('display', 'none');
    $('#edit_subject').css('display', 'none');
    $('#edit_subject select').val("");
    $("._add_class").find('form input').val(class_id);
    $.post('fetch_class_details', {
      "class": class_id
    }, function (result) {
      var response = JSON.parse(result);
      var length = response.length;
      console.log(response[0]["class"]);
      $('#view_subjects').html("");

      for (var i = 0; i < length; i++) {
        console.log(response[i].name);
        var element = $('.subjects_body').clone(true).css('display', 'table-row').removeClass('subjects_body');
        element.find('.subject_name').text(response[i].name);
        element.find('.teacher').text(response[i].firstname);
        element.attr('subject_id', response[i].subjectid);
        element.attr('class_id', response[i]["class"]);
        element.appendTo('#view_subjects');
      }
    });
  });
  $(".remove_subject").click(function (event) {
    //event.preventDefault();
    var class_id = $(this).closest('tr').attr('class_id');
    var subject_id = $(this).closest('tr').attr('subject_id');
    $.post('remove_class_subject', {
      class_id: class_id,
      subject_id: subject_id
    }, function () {
      $("table tr[subject_id=" + subject_id + "]").remove();
    });
  });
  $(".add_subject").click(function (event) {
    event.preventDefault();
    $('#_append_teacher').html("");
    $('._append_teacher ._subject').val(''); // $('._append_teacher ._subject').html('');

    $('._append_teacher ._subject').select2('destroy').select2();
    $("._add_class").css("display", "block");
  });
  $('._subject').on('select2:select', function (e) {
    var data = e.params.data;
    var id = data.id; //value of options

    var text = data.text;
    $.post('fetch_teachers', {
      subject_id: id
    }, function (result) {
      var response = JSON.parse(result);
      var element = $("._editable").clone(true).css('display', 'block').removeClass('_editable');
      element.find('label').text(text);
      element.find('label').attr('for', id);
      element.find('select').attr('name', id);

      for (var i = 0; i < response.length; i++) {
        var element2 = $("._editable option").clone(true); // element.closest('select').removeClass('teacher');

        console.log(element);
        element2.attr('value', response[i].id);
        element2.html(response[i].firstname);
        element2.appendTo(element.find('select'));
      }

      element.appendTo('#_append_teacher');
    });
  });
  $('._subject').on('select2:unselect', function (e) {
    var data = e.params.data;
    var id = data.id;
    $('._append_teacher label[for=' + id + ']').remove();
    $('._append_teacher select[name=' + id + ']').remove();
  });
  $("#_add").click(function (event) {
    event.preventDefault();
    $("._add_class").css("display", "none");
    console.log($('#_add_class').serialize());
    $.post('add_class_subject', $('#_add_class').serialize(), function (result) {
      var response = JSON.parse(result);
      var element = $('.subjects_body').clone(true).css('display', 'table-row').removeClass('subjects_body');
      element.find('.subject_name').text(response[0].name);
      element.find('.teacher').text(response[0].firstname);
      element.attr('subject_id', response[0].subjectid);
      element.attr('class_id', response[0]["class"]);
      element.appendTo('#view_subjects');
    }); // $('.subject').text("");
    // $('#class').val("");
  });
  $('.edit_subject').click(function () {
    $('#edit_subject').css('display', 'block');
    var subject_id = $(this).closest('tr').attr('subject_id');
    var class_id = $(this).closest('tr').attr('class_id');
    $('#edit_subject select').attr('subject_id', subject_id);
    $('#edit_subject select').attr('class_id', class_id);
    $('#edit_subject select').html("");
    $.post('fetch_teachers', {
      subject_id: subject_id
    }, function (result) {
      var response = JSON.parse(result);

      for (var i = 0; i < response.length; i++) {
        var element = $("#edit_subject ._clone").clone(true).removeClass('_clone'); // element.closest('select').removeClass('teacher');

        console.log(element);
        element.attr('value', response[i].id);
        element.html(response[i].firstname);
        element.appendTo('.teacher_');
      }
    });
  });
  $('#edit_subject button').click(function () {
    $('#edit_subject').css('display', 'none');
    var subject_id = $('#edit_subject select').attr('subject_id');
    var class_id = $('#edit_subject select').attr('class_id');
    var teacher_id = $('#edit_subject select').val();
    $.post('update_teacher', {
      subject_id: subject_id,
      class_id: class_id,
      teacher_id: teacher_id
    }, function (result) {
      var response = JSON.parse(result);
      $('.modal-body tr[subject_id=' + subject_id + '] .teacher').text(response[0].firstname);
    });
  });
}); // function load_display_data() {
// 	$.get('display_subjects.php', function(result) {
// 		var response = JSON.parse(result);
// 		var length = response.length;
// 		for (var i = 0; i < length; i++) {
// 			let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
// 			element.attr('subject_id', response[i].id);
// 			element.appendTo('.todo');
// 			subject_id = response[i].id;
// 			$("ul li[subject_id=" + subject_id + "] .text").html(response[i].name);
// 		}
// 	});
// }

/***/ }),

/***/ 8:
/*!********************************************!*\
  !*** multi ./resources/js/manage_class.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/spriha/Documents/laravel/laravel_edu_expert/resources/js/manage_class.js */"./resources/js/manage_class.js");


/***/ })

/******/ });