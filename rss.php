<?php

$posts =[];

$zenn =  simplexml_load_string(file_get_contents('https://zenn.dev/mikkame/feed'));

foreach ($zenn->channel->item as $item) {
    $posts[]  = [
        'title' => $item->title.'',
        'date' => date('Y-m-d H:i:s', strtotime($item->pubDate.'')),
        'type' => 'zenn',
        'url' => $item->link.''
    ];
}

$qiita = simplexml_load_string(file_get_contents('https://qiita.com/mikkame/feed.atom'));

foreach ($qiita->entry as $entry) {
    $posts[]  = [
        'title' => $entry->title.'',
        'date' => date('Y-m-d H:i:s', strtotime($entry->published.'')),
        'type' => 'qiita',
        'url' => $entry->url.''
    ];
}


$sort_arr = array_map( "strtotime", array_column($posts, "date") );
array_multisort($sort_arr, SORT_DESC, $posts ) ;

$dist_md = '';

foreach (array_splice($posts, 0, 5) as $post) {
    $dist_md.= "- ![](img/${post['type']}.png) [${post['title']}](${post['url']})\n";
}

file_put_contents('README.md', preg_replace('/<!--\[START POSTS\]-->.*<!--\[END POSTS\]-->/s', "<!--[START POSTS]-->\n${dist_md}<!--[END POSTS]-->", file_get_contents('README.md')));
