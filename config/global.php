<?php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $link = "https";
} else {
    $link = "http";
}
$link .= "://" . $_SERVER['HTTP_HOST'];
return [
    'project_name' => 'Umeed-e-Nau',
    'project_shortname' => 'UeN Endline',
    'asset_path' => $link . '/dashboards_public_asset/laravel',
]

?>
