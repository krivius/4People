<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use simple_html_dom;
use App\Models\News;

class NewsController extends Controller
{

    private function grabb($url){  //лезем CURL`ом по ссылке и выдераем оттуда всю страницу
        $agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';  //прикидываемся браузером

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


        $response = curl_exec($ch);

        if ($response === false){
            $response = curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }

    public function newsList(){

        include('..\vendor\simplehtmldom\simplehtmldom\simple_html_dom.php');
        $html = new simple_html_dom();
        $html->load($this->grabb("https://www.rbc.ru/"));
        $collection = $html->find('a.js-news-feed-item'); //дергаем список ссылок на новости

        $links = [];
        $titles = [];
        $news = [];
        $images = [];

        foreach($collection as $collectionItem)
        {
            if( (!str_contains($collectionItem->attr['href'], 'adv.rbc')) &&
                (!str_contains($collectionItem->attr['href'], 'traffic.rbc'))) {  //избавляемся от рекламы

                $newsPage = new simple_html_dom();
                $newsPage->load($this->grabb($collectionItem->attr['href']));  //дергаем полную новость в сыром виде
                $header = $newsPage->find("h1", 0);  //дергаем заголовок новости
                $title = str_get_html($header)->plaintext;

                $img = $newsPage->find("img.article__main-image__image", 0);



                $fullStory ='';
                $text = $newsPage->find("p");
                foreach ($text as $p){  //формируем полный текст новости
                    $fullStory .= $p->plaintext;
                }


                $article = new news();
                $article->title = $title;
                $article->article = $fullStory;
                $article->link = $collectionItem->attr['href'];
                if($img){
                    $article->img = $img->getAttribute("src");

                }else{
                    $article->img = '';
                }

                $article->save();
            }
        }


        $newsList = News::latest()->take(15)->get();

        return view('/documents/showList', ["news"=>$newsList]);
    }


    public function fullStory($id)
    {
        $article = News::find($id);

        return view('/documents/showFull', ["article"=>$article]);
    }
}
