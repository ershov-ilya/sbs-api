<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 16.12.2014
 * Time: 16:54
 */

function parseContent($content,$id=0)
{
    $arr = explode("\n", $content);
    $output='';
    $i=0;
    $skip=0;
    foreach($arr as $key=>$el)
    {
        $str='';
        if($skip>0){$skip--; continue;}

        // Убираем html-комменты
        $el=preg_replace('/(<!\-\-)+.*(\-\->)+/Ui','',$el);


        // Пропускаем повторяющиеся блоки
        if(preg_match('/id="preview_text"/i', $el)) {$skip=2; continue;}
        if(preg_match('/Наш адрес/i', $el)) continue;
        if(preg_match('/Не отвечайте на это письмо/i', $el)) continue;
        if(preg_match('/Школы Бизнеса/i', $el)) continue;
        if(preg_match('/С уважением/i', $el)) continue;
        if(preg_match('/Если не видно картинок/i', $el)) continue;
        if(preg_match('/СИНЕРГИЯ/i', $el)) continue;
        if(preg_match('/<h3/i', $el)) continue;
        if(preg_match('/&gt;/i', $el)) continue;
        if(preg_match('/\+\s*7\s*\({0,1}[0-9]{3}\){0,1}\s*[0-9\-\s]{7,9}/i', $el)) continue;
        /**/

        $check=preg_match('/[а-яёА-ЯЁ]+/i', $el);
        //if(preg_match('/<a href/i', $el)) continue;

        $el=preg_replace('/<br>/Ui',' ',$el);
        $el=preg_replace('/<\/p>/Ui',' ',$el);

        // Вычищаем теги
        $el=preg_replace('/<[\/abdefhiklnoprstuv!]+.*>/Ui','',$el);

        // Вычищаем пробелы
        $el=preg_replace('/[\s]+/i',' ',$el);

        if($check)
        {
            // Если не осталось текста - не подходит
            if(!preg_match('/[а-яёА-ЯЁ]+/i', $el)) continue;

            // Совсем короткие строчки не подходят
            //if(strlen($el)<20) continue;

            $str.= $el." ";
            $i++;
        }
        //if($i>2) break;

        //if(strlen($output.$str)>500) continue;
        $output.=$str;

        // Вычищаем пробелы ещё раз
        $output=preg_replace('/[\s]+/i',' ',$output);
    }


    print $output;
    if(empty($output)) print ">>>>>>>>>>>> Пусто в id:$id\n";
    return $output;
}