<?php

$data = json_decode(file_get_contents(__DIR__ . "/data.json"), true);

if (!$data || !count($data["gifts"]) || time() - filemtime(__DIR__ . "/data.json") > 10) {
    $data_source = json_decode(file_get_contents("https://api.darudar.org/geo/city/447159/gifts?p=1&pp=4"), true);
    if ($data_source && count($data_source["gifts"])) {
        file_put_contents(__DIR__ . "/data.json", json_encode($data_source));
        $data = $data_source;
    }
}

echo <<<EOF
<h1>Дары Москвы</h1>
EOF;

foreach ($data["gifts"] as $gift)
{
    $gift["preview"]["uri"] = htmlspecialchars($gift["preview"]["uri"]);
    $gift["pk_gift"] = htmlspecialchars($gift["pk_gift"]);
    $gift["name"] = htmlspecialchars($gift["name"]);

    echo <<<EOF
<div style="display: inline-block; width: 200px; text-align: center;">
<a href="https://app.darudar.org/gift/{$gift["pk_gift"]}"><img style="max-height: 100px;" src="{$gift["preview"]["uri"]}"/></a>
<br/><a href="https://app.darudar.org/gift/{$gift["pk_gift"]}">{$gift["name"]}</a>
</div>
EOF;
}