<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => array(
        
        "font_dir" => public_path('admin_assets/fonts/'), // advised by dompdf (https://github.com/dompdf/dompdf/pull/782)
        "font_cache" => storage_path('fonts'),
        "temp_dir" => sys_get_temp_dir(),
        "chroot" => realpath(base_path()),
        'allowed_protocols' => [
            "file://" => ["rules" => []],
            "http://" => ["rules" => []],
            "https://" => ["rules" => []]
        ],

        'log_output_file' => null,
        "enable_font_subsetting" => false,
        "pdf_backend" => "CPDF",
        "default_media_type" => "screen",
        "default_paper_size" => "a4",
        'default_paper_orientation' => "portrait",
        "default_font" => "SutonnyOMJ",
        "dpi" => 96,
        "enable_php" => false,
        "enable_javascript" => true,
        "enable_remote" => true,
        "font_height_ratio" => 1.1,
        "enable_html5_parser" => true,
    ),

    'font_date' => [
        'SutonnyOMJ' => [
            'R'  => 'SutonnyOMJ.ttf',    // Regular
            'B'  => 'SutonnyMJ-Bold.ttf',       // Bold
            'I'  => 'SutonnyMJ-Italic.ttf',     // Italic
            'BI' => 'SutonnyMJ-BoldItalic.ttf', // Bold Italic
        ]
    ],

);
