<?php

function config($key = '')
{
    $config = [
        'name' => 'Enterprise DevOps Sample PHP Website',
        'nav_menu' => [
            '' => 'Home',
            'products' => 'Products',
            'contact' => 'Contact',
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'pretty_uri' => false,
        'version' => 'v1.0',
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
