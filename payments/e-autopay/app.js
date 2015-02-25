/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 25.02.2015
 * Time: 13:29
 */
var docState = {};

$(document).ready(function(){
    console.log(docState.data);
    fillForm(docState.data);
});

function fillForm(data){
    var ptr=$('.ea_form');

    // ФИО

    ptr.find('.ea_email').val(data.email);
    ptr.find('.ea_phone').val(data.phone);
}
