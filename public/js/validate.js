!function(e){var t={};function r(a){if(t[a])return t[a].exports;var n=t[a]={i:a,l:!1,exports:{}};return e[a].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=t,r.d=function(e,t,a){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(r.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)r.d(a,n,function(t){return e[t]}.bind(null,n));return a},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=1)}({1:function(e,t,r){e.exports=r("qNtR")},qNtR:function(e,t){$(document).ready(function(){$("body").submit(function(e){e.preventDefault(),jQuery.validator.addMethod("onlyalpha",function(e,t){return this.optional(t)||/^([a-zA-Z]+)$/.test(e)},"Only alphabetic characters are allowed"),jQuery.validator.addMethod("username",function(e,t){return this.optional(t)||/^([a-zA-Z0-9@_]+)$/.test(e)},"Please enter a valid username"),jQuery.validator.addMethod("password",function(e,t){return this.optional(t)||/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/.test(e)},"Please enter a valid password"),$("#registration").validate({rules:{fname:{required:!0,onlyalpha:!0},lname:{required:!0,onlyalpha:!0},user_type:"required",email:{required:!0,email:!0},password:{required:!0,password:!0},username:{required:!0,username:!0}}}),$("#registration").valid()&&($("#spinner").css("display","block"),$.post("/register",$("#registration").serialize(),function(e){$("#spinner").css("display","none"),$("#alert").css("display","block"),$("#alert").text(e)}))}),$("body").click(function(){if("password"===event.target.id&&"registration"===event.target.closest("form").getAttribute("id")){var e="The password :<br> Must be a minimum of 8 characters<br>Must contain at least 1 number<br>Must contain at least one uppercase character<br>Must contain at least one lowercase character";$("#info_password").html(e),$("#info_password").css("display","block")}if("username"===event.target.id&&"registration"===event.target.closest("form").getAttribute("id")){e="The username can contain letters, digits, @ and _";$("#info_username").text(e),$("#info_username").css("display","block")}}),$("input").blur(function(){if("registration"===event.target.closest("form").getAttribute("id"))if("password"===event.target.id)$("#info_password").css("display","none");else if("username"===event.target.id){$("#info_username").css("display","none");var e=$("#username").val();$.get("/fetch_info",{q1:"username",q2:e},function(e){1===Number(e)&&($("#username").css("borderColor","red"),$("#alert").text("This username already exists"),$("#alert").css("display","block"))})}else if("email"===event.target.id){$("#info_email").css("display","none");var t=$("#email").val();$.get("/fetch_info",{q1:"email",q2:t},function(e){1===Number(e)&&($("#email").css("borderColor","red"),$("#alert").text("This email already exists"),$("#alert").css("display","block"))})}}),$("#user_type").change(function(e){"Teacher"===$("#user_type").val()&&$(".subject").closest("div").css("display","block"),"Student"===$("#user_type").val()&&$(".subject").closest("div").css("display","none")})})}});