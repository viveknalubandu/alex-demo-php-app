<?php

function siteName()
{
    echo config('name');
}

function siteVersion()
{
    echo config('version');
}

function navMenu($sep = ' | ')
{
    $nav_menu = '';

    foreach (config('nav_menu') as $uri => $name) {
        $nav_menu .= '<a href="/'.(config('pretty_uri') || $uri == '' ? '' : '?page=').$uri.'">'.$name.'</a>'.$sep;
    }

    echo trim($nav_menu, $sep);
}

function pageTitle()
{
    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Home';

    echo ucwords(str_replace('-', ' ', $page));
}

function pageContent()
{
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    $path = getcwd().'/'.config('content_path').'/'.$page.'.php';

    if (file_exists(filter_var($path, FILTER_SANITIZE_URL))) {
        include $path;
    } else {
        include config('content_path').'/404.php';
    }
}

function run()
{
    include config('template_path').'/template.php';
}
