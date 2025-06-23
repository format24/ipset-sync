<?php
function getgooglebots(): string {
    $urls = [
        'https://developers.google.com/search/apis/ipranges/googlebot.json',
	'https://developers.google.com/static/search/apis/ipranges/special-crawlers.json',
	'https://developers.google.com/static/search/apis/ipranges/user-triggered-fetchers.json',
	'https://developers.google.com/static/search/apis/ipranges/user-triggered-fetchers-google.json'
    ];

    $export = '';
    foreach ($urls as $url) {
        $jsonData = @file_get_contents($url);
        if (!$jsonData) continue;

        $data = json_decode($jsonData, true);
        if (!isset($data['prefixes'])) continue;

        foreach ($data['prefixes'] as $prefix) {
            if (isset($prefix['ipv4Prefix'])) {
                $export .='add '. pathinfo(__FILE__, PATHINFO_FILENAME) . ' ' . $prefix['ipv4Prefix'] . PHP_EOL;
            }
        }
    }

    return $export;
}
