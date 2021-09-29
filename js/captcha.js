/**-------------------------------------------------
 * Simple Captcha System
 * @package Code Snippets
 * @link http://rhythmshahriar.com/codes/
 * @author Rhythm Shahriar <rhy@rhythmshahriar.com>
 * @link http://rhythmshahriar.com
 * @copyright Copyright © 2017, Rhythm Shahriar
 ---------------------------------------------------*/
 
//email verification
$('#email').on('change', function () {
    if(!validateEmail($(this).val())){
        $('#errEmail').html('<span style="color: red;"> รูปแบบที่อยู่อีเมลไม่ถูกต้อง </span>');
        $(this).val('');
    }else {
        $('#errEmail').html('');
    }
});

$('#usr').on('change', function () {
    if(!validateUsr($(this).val())){
        $('#errUsr').html('<span style="color: red;"> ชื่อผู้ใช้จะค้องประกอบด้วยตัวอักษร A-Z,a-z,0-9 เท่านั้น และมีความยาว 4 ตัวอักษรขึ้นไป </span>');
        $(this).val('');
    }else {
        $('#errUsr').html('');
    }
});
$('#pwd').on('change', function () {
    if(!validatePwd($(this).val())){
        $('#errPwd').html('<span style="color: red;"> รหัสผ่านจะค้องประกอบด้วยตัวอักษร A-Z,a-z,0-9 เท่านั้น และมีความยาว 6 ตัวอักษรขึ้นไป </span>');
        $(this).val('');
    }else {
        $('#errPwd').html('');
    }
});

//password verification
$('#cpwd').on('change', function () {
    if($(this).val() != $('#pwd').val() ){
        $('#errcPwd').html('<span style="color: red;"> รหัสผ่านไม่ตรงกัน </span>');
        $(this).val('');
    }else {
        $('#errcPwd').html('');
    }
});

$('#cid').on('change', function () {
    if(!checkCID($(this).val())){
        $('#errCID').html('<span style="color: red;"> เลขบัตรประจำตัวประชาชนไม่ถูกต้อง </span>');
        $(this).val('');
    }else {
        $('#errCID').html('');
    }
});

function validateUsr(usr){
    var re = new RegExp("^([A-Za-z0-9@-_.]{4,})$");
    return re.test(usr);
}

function validatePwd(pwd){
    var re = new RegExp("^([A-Za-z0-9@-_.]{6,})$");
    return re.test(pwd);
}

//email validation
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function checkCID(cid) {
	if(cid.length != 13) return false;
    for(i=0, sum=0; i < 12; i++)
    sum += parseFloat(cid.charAt(i))*(13-i); if((11-sum%11)%10!=parseFloat(cid.charAt(12)))
    return false; return true;
}

//allow only number input
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return (charCode > 47 && charCode < 58 || charCode == 8  || charCode == 9 || charCode == 46  || charCode >36 &&  charCode < 41);
}

/*function isUsrPwd(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return (charCode > 64 && charCode < 91 || charCode > 96 &&  charCode < 123 || charCode > 47 && charCode < 58 || charCode == 35 || charCode == 95 || charCode == 64 || charCode == 45 || charCode == 8 || charCode == 9 || charCode == 46  || charCode >36 &&  charCode < 41);
}*/

//allow only number input
function isAlpha(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return (charCode > 64 && charCode < 91 || charCode > 96 &&  charCode < 123 || charCode > 160 && charCode < 239 || charCode > 3584 && charCode < 3676 || charCode == 8  || charCode == 9 || charCode == 46  || charCode >36 &&  charCode < 41);
}

//generate captcha
function generateCaptcha(length, chars) {
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
}



//default captcha
$('.dynamic-code').text(generateCaptcha(6, '0123456789abcdefghijklmnopqrstuvwxyz'));

$('.captcha-reload').on('click', function () {
    $('.dynamic-code').text(generateCaptcha(6, '0123456789abcdefghijklmnopqrstuvwxyz'));
});

//check captcha
$('#captcha-input').on('change', function () {
    if($(this).val() != $('.dynamic-code').text()){
        $('#errCaptcha').html('<span style="color: red;"> ตัวอักษรไม่ถูกต้อง </span>');
        $(this).val('');
    }else {
        $('#errCaptcha').html('');
    }
});
