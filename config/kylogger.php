<?php

// config file to store the specific kylogger app settings

return [
    'companies_type' => [
        '1' => 'Suppliers',
        '2' => 'Clients',
        '3' => 'Clients & Suppliers'
    ],
    'package_types' => [
        [
            'text' => 'All',
            'color' => 'grey-salsa'
        ],
        [
            'text' => 'Quarantine',
            'color' => 'blue-steel'
        ],
        [
            'text' => 'Litigation',
            'color' => 'yellow-lemon'
        ],
        [
            'text' => 'Invalid',
            'color' => 'red-thunderbird'
        ],
        [
            'text' => 'OK',
            'color' => 'green-steel'
        ]
    ],
    'reception_states' => [
        '1' => [
            'text' => 'In Transit',
            'color' => 'yellow-lemon'
        ],
        '2' => [
            'text' => 'Received',
            'color' => 'blue-steel'
        ]
    ],
    'quantities' => [
        ''
    ],
    'destinations' => [
        '1' => [
            'text' => 'Export',
            'color' => 'yellow-lemon'
        ],
        '2' => [
            'text' => 'Import',
            'color' => 'blue-steel'
        ],
        '3' => [
            'text' => 'Local',
            'color' => 'green-jungle'
        ]
    ],
    'values_intervals' => [
        '1' => '0-99',
        '2' => '100-999',
        '3' => '1000-9999',
        '4' => '10000-99999'
    ],
    'net_weight_intervals' => [
        '1' => '0-99',
        '2' => '100-999',
        '3' => '1000-9999'
    ],
    'delivery_outside_working_hours' => [
        '0' => [
            'text' => 'NO',
            'color' => 'red-thunderbird'
        ],
        '1' => [
            'text' => 'YES',
            'color' => 'green-steel'
        ]
    ],
    'value_not_defined' => 'Not Specified',
    'stat_period' => [
        '1' => '1 month ago',
        '2' => '3 months ago',
        '3' => '6 months ago',
        '4' => '1 year ago',
        '5' => 'all'
    ]
];
