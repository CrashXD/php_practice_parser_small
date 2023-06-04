<?php

require_once "simple_html_dom.php";
require_once "connection.php";

$host = 'https://small.kz';
$language = 'ru';
$city = 'pavlodar';

$baseurl = $host . '/' . $language . '/' . $city;

$page = "catalog-goods";

$html = file_get_html($baseurl . '/' . $page);

// находим категории
$categories = [];
$filter = $html->find('.goodsFilter', 0);
if (!is_null($filter)) {
    $inputs = $filter->find('.filter-checkbox');
    foreach ($inputs as $input) {
        $element = $input->find('input', 0);
        if (!is_null($element)) {
            $id = $element->value;
        }
        $element = $input->find('.checkbox-text', 0);
        if (!is_null($element)) {
            $title = $element->plaintext;
        }
        if (isset($id) && $id && isset($title) && $title) {
            $categories[$id] = trim($title);
        }
    }
}

$category = 3;
$page_number = 1;

$url = $baseurl . '/' . $page . '?category[]=' . $category . '&page=' . $page_number;
$html = file_get_html($url);

// сколько страниц в категории
$max_page = 1;

$page_link = $html->find('.page-item', -2);
if (!is_null($page_link)) {
    $max_page = $page_link->plaintext;
}

$products = [];

for ($i=1; $i<=$max_page; $i++) {
    if ($i != 1) {
        $page_number = $i;
        $url = $baseurl . '/' . $page . '?category[]=' . $category . '&page=' . $page_number;
        $html = file_get_html($url);
    }

    $goods = $html->find('.good');

    foreach ($goods as $good) {
        $element = $good->find('.goodInfo p', 0); 
        if (!is_null($element)) {
            $title = $element->innertext;
        }
        $element = $good->find('.activePrice', 0); 
        if (!is_null($element)) {
            $price = $element->plaintext;
        }
        $element = $good->find('.oldPrice span', 0); 
        if (!is_null($element)) {
            $old_price = $element->plaintext;
        }
        $element = $good->find('.salePercent', 0); 
        if (!is_null($element)) {
            $stock = $element->plaintext;
        }
        $element = $good->find('.goodImage', 0); 
        if (!is_null($element)) {
            $image_name = $element->src;
            $image_name = $host . preg_replace('/\s\./', '.', $image_name);
            $image = 'images/'.basename($image_name); 
            if (!file_exists($image)) {
                $image_data = file_get_contents($image_name);
                file_put_contents($image, $image_data);
            }
        }
        if (isset($title)) {
            $products[] = [
                'title' => trim($title),
                'price' => isset($price) ? (int)$price : 0,
                'old_price' => isset($old_price) ? (int)$old_price : 0,
                'stock' => isset($stock) ? trim($stock) : '',
                'image' => isset($image) ? trim($image) : '',
                'category' => $categories[$category],
            ];
        }
    }
}

$sql = 'INSERT INTO products(title, price, old_price, stock, image, category) VALUES';
foreach ($products as $key => $product) {
    if ($key) {
        $sql .= ", ";
    }
    $sql .= "('{$product['title']}', {$product['price']}, {$product['old_price']}, '{$product['stock']}', '{$product['image']}', '{$product['category']}')";
}
$database->query($sql);