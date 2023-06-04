<?php

require_once "simple_html_dom.php";

$url = 'https://small.kz/ru/pavlodar';

// $data = file_get_contents($url);
// $html = str_get_html($data);

// preg_match_all('/8\s\d{3}\s\d{3}\s\d{4}/um', $data, $matches);
// print_r($matches);

// preg_match_all('/\<div\s+class\s*\=\s*\"companyBin\"\s*\>([^<]*)\<br\>([^<]*)\<\/\s*div\>/um', $data, $matches);
// print_r($matches);

// $html = file_get_html($url);

// $elements = $html->find('.companyBin');
// $bin = $elements[0]->plaintext;
// print_r($bin);

$page = '/news';

$html = file_get_html($url . $page);

$news = $html->find('.newsItem');

foreach($news as $new) {
    echo '<a href="'. $new->href .'">' . $new->find('h2')[0]->plaintext . "</a><br>";
    $html2 = file_get_html($new->href);
    echo $text = $html2->find('.newsSingleText')[0]->outertext;
    echo '<small>' . $new->find('p')[0]->plaintext . "</small><br>";
}
