<?php
/**
 * Config-file for navigation bar.
 *
 */

$loggedin = $this->di->session->has('user');

if($loggedin) {
    $user = $this->di->session->get('user');
    $username = $user['acronym'];
    $userid = $user['id'];
}
    // om man kunde låta den här menyn vara till höger på sidan och inte en del av den vanliga menyn, så vore det trevligt.
    $userpanel = $loggedin ? 
    [
        'text' => $username,
        'url' => 'users/profile/'.$username, 
        'title' => 'Gå till din profil',

        'submenu' => [
            'items' => [
                'item1' => [
                    'url' => 'users/profile/'.$username,
                    'text' => 'Din profil',
                    'title' => 'Gå till din profil'
                ],
                'item2' => [
                    'url' => 'questions/new', 
                    'text' =>'Skapa fråga',
                    'title' => 'Skapa en ny fråga'
                ],
                'item3' => [
                    'url' => 'users/logout',
                    'text' => 'Logga ut',
                    'title' => 'Logga ut från sidan'
                ]
            ]
        ]
    ] : // else
    [
        'url' => 'users/login',
        'text' => 'Logga in', 
        'title' => 'Logga in på sidan'
    ]
    ;

    $create = $loggedin ? 
        null
         : 
        [
        'url' => 'users/create',
                'title' => 'Skapa användare',
                'text' => 'Skapa användare'
        ]
    ;

return [
    // Use for styling the menu (css)
    'class' => 'navbar',
 
    // Here comes the menu structure
    'items' => [
        // This is a menu item
        'home'  => [
            'text'  => 'Hem',
            'url'   => '',
            'title' => 'Förstasidan'
        ],
        'questions' => [
            'text' => 'Frågor', 
            'url' => 'questions',
            'title' => 'Gå till Frågor'
        ],
        'users' => [
            'text' => 'Användare', 
            'url' => 'users', 
            'title' => 'Gå till Användare'
        ],
        'about' => [
            'text'  =>'Om oss',
            'url'   => 'about',
            'title' => 'Om vår verksamhet'
        ],
        'panel' => $userpanel,
        // 'reset' => [
        //     'text' => 'reset db (dev)',
        //     'url' => 'reset', 
        //     'title' => 'återställ hela db:n'
        // ],
        'create' => $create,
    ],
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getRoute()) {
                return true;
        }
    },

    // Callback to create the urls
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
];
