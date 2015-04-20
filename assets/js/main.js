var docState={paginator:{page:1}, filter:{page:0}, filterURL:'', filter_enabled: false, more_block_content:''};
// JavaScript Document
/* Carousel ----------------------------------------------------------------------------------------------------------*/
$(document).ready(function(){
	copyright();
	adaptLinks();
	divideWrap(3);
	divideWrap(3, '.divide-speakers-in-three', '.speakerblock', '<li><section class="speaker-list clearfix divide-item"></section></li>');
	activateAjaxButtons();
	activateFilters();
	activateDropFilters();
	activatePopup()
	// Сохраняем исходное состояние кнопки
	docState.more_block_content =$('.more-block').html();
	setTimeout(hideServiceLogo, 2000);

	
  $('.carousel').bxSlider({
    slideWidth: 940,
    minSlides: 1,
    maxSlides: 1,
    slideMargin: 0
  });
})


/* Переключение отображения блоков  ----------------------------------------------------------------*/
$(document).ready(function(){
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
});

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

// 	divideWrap(3, '.divide-speakers-in-three', '.speakerblock');
function divideWrap(limit, selector, elements, wrap){
	if(!limit) limit=3;
	if(!selector) selector='.divide-in-three';
	if(!elements) elements='.itemblock';
	if(!wrap) wrap='<li><section class="item-list itemblock-v clearfix divide-item"></section></li>'; // must have .divide-item class
	
	$(selector).each(function(){
		var section = $(this);
		
		var arr=[];
		var slide=0;
		var num=0;		
		section.find(elements).each(function(){
			if(arr[slide]===undefined) arr[slide]=[];
			arr[slide][num]=$(this);
			num++;
			if(num>=limit) {num=0; slide++;}
		});
		
		section.html('');
		for(i in arr){
			var sub_arr=arr[i];
			var el=$(wrap);
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
	}
	if(!docState.filterURL)
	{
		console.error('Не найден путь к ajax-коннектору');
		return false;
	}
	
	$('.pagesblock').remove();
	docState.filter_enabled=true;
	return true;
}

/* AJAX ----------------------------------------------------------------*/
function ajaxRequest(){
	$('.more-block').html(docState.more_block_content);
	
	if(docState.filter_enabled) ajaxFilterRequest();
	else ajaxDefaultRequest();
	return false;
}

function ajaxFilterRequest(){
	if(!SetFilterConnectorURL()) return false;
	
	console.log('ajaxFilterRequest() event');
	
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
		if(content=='') {$('.more-block').html('<p>Больше пока нет :(</p>')}
		else{
			if(filter.page) {
				var banner=$('.footer-banner .bannerItem').first().clone();
				temp.prepend(banner);
			}
			$(".ajax-page-content").append(temp);
			temp.slideDown();
		}
	});
	return false;
}

function ajaxDefaultRequest()
{
	console.log('ajaxDefaultRequest() event');
	//$('.pagesblock').remove();
	var url = "/api/do/get-page/";
	docState.paginator.page++;

	var temp=$("<div>");
	temp.hide();
	$('.more-block').addClass('pending');
	temp.load(url, docState.paginator, function(){
		$('.more-block').removeClass('pending');
		var content=temp.html();
		if(content=='') {$('.more-block').html('<p>Больше пока нет :(</p>')}
		else{
			var banner=$('.footer-banner .bannerItem').first().clone();
			temp.prepend(banner);
			$(".ajax-page-content").append(temp);
			temp.slideDown();
			$('.pagesblock a.page-no-'+docState.paginator.page).addClass('active');
		}
	});
	return false;
}

function activatePopup(){
	$('.hidelayout').click(function(){
		$('.hidelayout, .old-style-popup').fadeOut(200);
	});
}

function showPopup(id, type){
	$('.hidelayout, .old-style-popup').fadeIn(200);
	
}

