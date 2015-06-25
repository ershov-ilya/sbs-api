var docState={paginator:{page:1}, filter:{page:0}, filterURL:'', filter_enabled: false, more_block_content:'', ajax_lock: false};
// JavaScript Document
/* Carousel ----------------------------------------------------------------------------------------------------------*/
$(document).ready(function(){
    eventListeners();
    copyright();
    adaptLinks();
    divideWrap(3);
    divideWrap(3, '.divide-speakers-in-three', '.speakerblock', '<li><section class="speaker-list clearfix divide-item"></section></li>');
    activateAjaxButtons();
    activateFilters();
    activateDropFilters();
    activatePopup();
    placeFilterLetters();
    activateButtonsCollapsible();
    activateSbsTabs();
    activateMagnificPopup();
    checkIfInIFrame();
    activateBxSlider();
    WITCH.init();
    RequireRegistration();

    // Сохраняем исходное состояние кнопки
    docState.more_block_content =$('.more-block').html();
    setTimeout(hideServiceLogo, 2000);

    //intrusiveAuth();
});

function activateBxSlider(){
    $('.carousel').bxSlider({
        slideWidth: 940,
        minSlides: 1,
        maxSlides: 1,
        slideMargin: 0
    });

    /* Переключение отображения блоков  ----------------------------------------------------------------*/
    $(".column").click(function() {
        $('.item-list').removeClass("itemblock-h").addClass("itemblock-v");
        $('.column').addClass("current");
        $('.rows').removeClass("current");
        return false;
    });
    $(".rows").click(function() {
        $('.item-list').removeClass("itemblock-v").addClass("itemblock-h");
        $('.rows').addClass("current");
        $('.column').removeClass("current");
        return false;
    });

    $(".column").click(function() {
        $('.schedule-list').removeClass("schedule-h").addClass("schedule-v");
        $('.column').addClass("current");
        $('.rows').removeClass("current");
        return false;
    });
    $(".rows").click(function() {
        $('.schedule-list').removeClass("schedule-v").addClass("schedule-h");
        $('.rows').addClass("current");
        $('.column').removeClass("current");
        return false;
    })


}

/* Scroll to top ----------------------------------------------------------------------------------------------------------*/
$(function() {
    $.fn.scrollToTop=function() {
        $(this).hide().removeAttr("href");
        if($(window).scrollTop()!="0") {
            $(this).fadeIn("slow")
        }
        var scrollDiv=$(this);
        $(window).scroll(function() {
            if($(window).scrollTop()=="0") {
                $(scrollDiv).fadeOut("slow")
            } else {
                $(scrollDiv).fadeIn("slow")
            }
        });
        $(this).click(function() {
            $("html, body").animate({scrollTop:0},"slow")
        })
    }
});

$(function() {
    $(".totop").scrollToTop();
});
/* /Scroll to top ----------------------------------------------------------------------------------------------------------*/

function copyright(){
    console.log("Сайт развивается и обслуживается веб-разработчиком http://ershov.pw/");
}

function adaptLinks(){
    $('.link-email').attr('href','mailto:info@sbs.edu.ru');
    $('.share .support').remove();
}

var $new;
// 	divideWrap(3, '.divide-speakers-in-three', '.speakerblock');
function divideWrap(limit, selector, elements, wrap){
    // Значения по умолчанию
    if(!limit) limit=3;
    if(!selector) selector='.divide-in-three';
    if(!elements) elements='.itemblock';
    if(!wrap) wrap='<li><section class="item-list itemblock-v clearfix divide-item"></section></li>'; // must have .divide-item class

    $new = $('');
    $new.append(wrap);
    $new.append(wrap);

    $(selector).each(function(){
        var section = $(this);

        var arr=[];
        var $wrap;
        var slide=0;
        var num=0;
        //console.log(content.html());
        section.find(elements).each(function(){
            if(num==0) $wrap=$(wrap);
            $wrap.append($(this));
            //console.log($(this).html());
            if(!arr[slide]) arr[slide]=[];

            arr[slide].push($(this));
            num++;
            if(num>=limit) {
                num=0;
                slide++;
                $new.append($wrap);
            }
        });
        //console.log($new);

        section.html('');
        var sub_arr;
        var el;
        for(i in arr){
            sub_arr=arr[i];
            //console.log(sub_arr[0].html());
            el=$(wrap);
            el.find('.divide-item').append(sub_arr);
            section.append(el);
        }
    });
}

function activateAjaxButtons(){
    var page = getParameterByName('page');
    if(page>0) docState.paginator.page=page;

    var button=$('.ajax-page-load');
    docState.paginator.parents=button.first().data('parents');
    docState.paginator.template=button.first().data('template');
    docState.paginator.filtervalue=button.first().data('filtervalue');

    // ajaxRequest()
    $('.more-block').click('a',ajaxRequest);
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function hideJWlogo(){
    //console.log('hideJWlogo start');
    var element = $(".jwlogo");
    var size = element.size();
    if(!size) setTimeout(hideJWlogo, 100);
    else $(".jwlogo").remove();
}

function hideServiceLogo(){
    $('.hc_footer_logo').remove();
}

/* Фильтры на страницах ----------------------------------------------------------------*/
function activateFilters(){

    // Клик по фильтру сортировки
    $('.sorting-filter a').click(function(){
        //console.log($(this).get(0));
        $(this).closest('.sorting-filter').find('a').removeClass('current');
        $(this).addClass('current');
        var option  = $(this).data('option');
        if(docState.filter.sortby == option) // Повторное нажатие
        {
            if(docState.filter.sortdir) docState.filter.sortdir=0;
            else docState.filter.sortdir =1;
        }
        else // Новый выбор
        {
            docState.filter.sortby = $(this).data('option');
            docState.filter.sortdir = 1;
        }

        docState.filter_enabled=true;
        docState.filter.page=0;
        ajaxRequest();

        return false;
    });

}

/* Выпадающий список в фильтре ----------------------------------------------------------------*/
function activateDropFilters() {
    var state = false;

    // Открытие закрытие списка
    $('.drop-link').click(function(){
        if(!state){
            $(this).parents(".drop-filter").find("UL").fadeIn('fast');
        }
        else{
            $(this).parents(".drop-filter").find("UL").fadeOut('fast');
        }
        state = !state;
        return false;
    });

    // Обработка фильтра "выпадающий список"
    $(this).find('.drop-filter LI')
    $(".drop-filter LI").click(function(){
        var a = $(this).find('a');
        var option = a.data('option');

        var filterWrap = $(this).closest('.drop-filter');
        //console.log(filterWrap.get(0));
        if(filterWrap.hasClass('city-filter')) docState.filter.city=option;
        if(filterWrap.hasClass('themes-filter')) docState.filter.lecture_theme=option;
        if(filterWrap.hasClass('year-filter')) docState.filter.filter_year=option;
        if(filterWrap.hasClass('month-filter')) docState.filter.filter_month=option;


        //console.log(lecture_theme);
        var mycontent = $(this).html();
        $(this).parents('.drop-filter').find('.drop-link').html(mycontent);
        state = false;
        $(this).parents('.drop-filter').find("UL").fadeOut('fast');

        docState.filter_enabled=true;
        docState.filter.page=0;
        ajaxRequest();

        return false;
    });
};

function SetFilterConnectorURL(){
    if(docState.filterURL) return true;

    // Первое ajax обращение к фильтрам, проверка
    var template = $('body').data('template');
    switch(template)
    {
        case 'news':
            docState.filterURL = '/api/do/get-filter/news/';
            break;
        case 'articles':
            docState.filterURL = '/api/do/get-filter/articles/';
            break;
        case 'videos':
            docState.filterURL = '/api/do/get-filter/video/';
            break;
        case 'dispatch':
            docState.filterURL = '/api/do/get-filter/dispatch/';
            break;
        case 'schedule':
            docState.filterURL = '/api/do/get-filter/events/';
            break;
        case 'speakers':
            docState.filterURL = '/api/do/get-filter/speakers/';
            break;
    }
    if(!docState.filterURL)
    {
        console.error('Не найден путь к ajax-коннектору');
        return false;
    }

    $('.pagesblock').remove();
    docState.filter_enabled=true;
    return docState.filterURL;
}

/* AJAX ----------------------------------------------------------------*/
function ajaxRequest(){
    $('.more-block').html(docState.more_block_content);

    if(!docState.ajax_lock){
        docState.ajax_lock=true;
        if(docState.filter_enabled) ajaxFilterRequest();
        else ajaxDefaultRequest();
    }
    return false;
}

function ajaxFilterRequest(){
    if(!SetFilterConnectorURL()) return false;
    docState.ajax_lock=true;

    //console.log('ajaxFilterRequest() event');

    docState.filter.page++;

    var filter = jQuery.extend({}, docState.filter);

    console.log(filter);
    if(filter.page==1)
    {
        delete filter.page;
        $(".ajax-page-content").html('');
    }

    var temp=$("<div>");
    temp.hide();

    $('.more-block').addClass('pending');
    temp.load(docState.filterURL, filter, function(){
        var content=temp.html();
        $('.more-block').removeClass('pending');
        docState.ajax_lock=false;

        if(content=='') {$('.more-block').html('<p>Больше пока нет :(</p>')}
        else{
            if(filter.page) {
                var banner=$('.footer-banner .bannerItem').first().clone();
                temp.prepend(banner);
            }
            $(".ajax-page-content").append(temp);
            temp.slideDown();
            RequireRegistration();
        }
    });
    return false;
}

function ajaxDefaultRequest()
{
    //console.log('ajaxDefaultRequest() event');
    //$('.pagesblock').remove();
    var url = "/api/do/get-page/";
    docState.paginator.page++;

    var temp=$("<div>");
    temp.hide();
    $('.more-block').addClass('pending');
    temp.load(url, docState.paginator, function(){
        $('.more-block').removeClass('pending');
        docState.ajax_lock=false;

        var content=temp.html();
        if(content=='') {$('.more-block').html('<p>Больше пока нет :(</p>')}
        else{
            var banner=$('.footer-banner .bannerItem').first().clone();
            temp.prepend(banner);
            $(".ajax-page-content").append(temp);
            temp.slideDown();
            $('.pagesblock a.page-no-'+docState.paginator.page).addClass('active');
            RequireRegistration();
        }
    });
    return false;
}

function filterLettersClick(e){
    var option=e.target.innerHTML;
    if(option.length>1) option=0;
    //console.log(option);

    docState.filter.filter_letter=option;

    docState.filter_enabled=true;
    docState.filter.page=0;
    ajaxRequest();

    return false;
}

function placeFilterLetters(){
    var filter = $('.letters-filter');
    if(!filter.size()) {return false;}

    var res='', arr, sym;

    // А 1040  Я 1071  Ё 1025	Ъ 1066  Ы 1067  Ь 1068
    for(var i=1040; i<=1071; i++)
    {
        if(i==1066) continue;
        if(i==1067) continue;
        if(i==1068) continue;
        sym=String.fromCharCode(i);

        res+='<a href><span>'+sym+'</span></a>';
    }
    //res=res.replace('/[\s]*$/i','');
    //console.log(res+'|');

    filter.append(res);
    filter.click('a span', filterLettersClick);
    return true;
}



/* PopUp ----------------------------------------------------------------*/
function activatePopup(){
    $('.hidelayout').click(function(){
        closePopup();
    });
    // DEBUG
    /*
     $('.old-style-popup :submit').click(function(e){
     var target=$(e.target).closest('.regform');
     console.log(target.get(0));
     closePopup();
     return false;
     });
     */
}

function showPopup(id, type){
    // Поиск DOM элементов
    var source = $('.schedule-item-id-'+id);
    if(!source.size()) return false;
    var popup=$('.hidelayout, .old-style-popup');
    var regform=popup.find('.regform');
    var form=regform.find('form');
    var value,currency;

    // Подстановка данных
    form.attr('action','http://sbs.edu.ru/lander/ALM/lander.php?r=land/index');

    // Подстановка заголовка
    value=source.find('h4 a').text();
    if(value){
        regform.find('.traningname').text(value);
        form.data('landname', value);
    }
    // Подстановка ID
    value=source.data('id');
    form.data('version',value);
    // Подстановка даты
    value=source.data('date');
    regform.find('.info .date').text(value);
    // Подстановка стоимости
    value=source.data('cost');
    currency=source.data('currency');
    if(value) value+=' <span class="rubl">'+currency+'</span>';
    regform.find('.info .cost').html(value);

    popup.fadeIn(200);
}

function closePopup(){
    $('.hidelayout, .old-style-popup').fadeOut(200);
}

function submitPopup(){
    closePopup();
}

function activateButtonsCollapsible(){
    $('.btn-collapsible').click(function(){
        $(this).closest('.collapsible').toggleClass('collapsible-open');
    });
}

function visitSbsBase(){
    if(!localStorage['sbs_base_visited']) localStorage['sbs_base_visited']='1';
}

function activateMagnificPopup(){
    if($.magnificPopup){
        $('.ajax-popup-link').magnificPopup({
            type: 'iframe',
            height: '500px',
            callbacks: {
                close: function() {
                    $(window).trigger('magnificPopup.close');
                }
            }
        });
    }
}

function activateSbsTabs(){
    var $sbsTabs = $('.sbs-tabs');
    if(!$sbsTabs.size()) return true;

    $sbsTabs.find('.sbs-tab').not(':first-child').hide();
    $sbsTabs.find('.sbs-tab-link:first-child').addClass('active');

    $sbsTabs.find('.sbs-tab-link').each(function(i){
        $(this).addClass('sbs-tab-link-'+i);
        $(this).data('tab-index',i);
        //console.log($(this).text()+' '+$(this).data('tab-index'));
    });

    $sbsTabs.find('.sbs-tab').each(function(i){
        $(this).addClass('sbs-tab-'+i);
    });

    $sbsTabs.click('.sbs-tab-link', function(event){
        //console.log('handeled click');
        //event.preventDefault();
        var index=$(event.target).closest('.sbs-tab-link').data('tab-index');
        if(index===undefined) return true;
        //console.log(index);

        var $tabsSection=$(this).closest('.sbs-tabs');
        $tabsSection.find('.sbs-tab').slideUp(200);
        $tabsSection.find('.sbs-tab-'+index).slideDown(200);
        $tabsSection.find('.sbs-tab-link').removeClass('active');
        $tabsSection.find('.sbs-tab-link-'+index).addClass('active');
        //console.log(event.target);
        return false;
    })
}

function RequireRegistration(){
    if(document.auth) {return false;}
    if(!$('body').hasClass('require-registration')) {return false;}
    var selector=".itemblock, .dispatch-list>.dispatch";
    $(selector).addClass('require-registration-block').click('a', function(e){
        e.preventDefault();
        $('.register-popup').trigger('click');
        return false;
    });
    //console.log('Блокировка включена');
    return true;
}

function checkIfInIFrame(){
    var isInIFrame = (window.location != window.parent.location) ? true : false;
    if(!isInIFrame) return true;
    if(window.location.href == 'http://sbs.edu.ru/popup') return true;
    window.parent.location.replace(window.location.href)
    //if(isInIFrame) window.location.replace(window.parent.location.href);
}

function eventListeners(){
    $(window).on('office.register-link-sent',function(){
        //alert('office.register-link-sent');
        setTimeout(function(){ window.parent.location.replace('http://sbs.edu.ru/profile/check-email'); }, 1000);
    });
}