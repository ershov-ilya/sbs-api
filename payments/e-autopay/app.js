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
    var match = data.name.match(/^([a-zа-яё]+)(\s[a-zа-яё]+){0,1}(\s[a-zа-яё]+){0,1}/i);
    delete match.index;
    delete match.input;
    delete match[0];
    for(key in match){
        if(match[key]===undefined) delete match[key];
    }
    console.log(match);
    var name=[];
    if(match[3]){
        console.log('match 3');
        name['fam']=match[1];
        name['name']=match[2];
        name['otch']=match[3];
    }else if(match[2]){
        console.log('match 2');
        name['name']=match[1];
        name['fam']=match[2];
    }else if(match[1]){
        console.log('match 1');
        name['name']=match[1];
    }

    if(name['name'])  ptr.find('input[name="name"]').val(name['name']);
    if(name['fam'])  ptr.find('input[name="fam"]').val(name['fam']);
    if(name['otch'])  ptr.find('input[name="otch"]').val(name['otch']);

    ptr.find('.ea_email').val(data.email);
    ptr.find('.ea_phone').val(data.phone);
    ptr.find('[name="additional_field1"]').val(data.program);
    ptr.find('[name="additional_field2"]').val(data.speaker);
    ptr.find('[name="additional_field3"]').val(data.land);
}
