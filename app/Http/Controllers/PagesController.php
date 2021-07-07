<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use simple_html_dom;


class PagesController extends Controller
{
    public function index()
    {


        $url = "https://www.rbc.ru/";
        $agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);

        if ($response === false){
            $response = curl_error($ch);
        }

        curl_close($ch);

        include('..\vendor\simplehtmldom\simplehtmldom\simple_html_dom.php');
        $html = new simple_html_dom();
        $html->load($response);
        $collection = $html->find('#js_col_left a');
        $articles=[];
        foreach($collection as $collectionItem)
        {
            if(!str_contains($collectionItem->attr['href'], 'adv.rbc')) {  //избавляемся от рекламы
                $articles[] = $collectionItem->attr['href'];   //массив всех атрибутов href
            }
        }

//        dd(count($articles));



        return view('layout', ["data"=>$collection]);
    }




}
