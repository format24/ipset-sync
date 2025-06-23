<?php
function getua(): string {
    $urls = [
        'https://www.ipdeny.com/ipblocks/data/countries/ua.zone',
        // 'https://example.com/other.zone',
    ];

    $listname = pathinfo(__FILE__, PATHINFO_FILENAME);
    $output = "";

    foreach ($urls as $url) {
        $lines = @file($url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) continue;

        foreach ($lines as $cidr) {
            $output .= "add {$listname} {$cidr}\n";
        }
    }

    return $output;
}
