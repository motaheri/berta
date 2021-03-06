<?php

$fontOptions = [
    'Arial, sans-serif' => 'Arial, sans-serif',
    'Helvetica, Arial, sans-serif' => 'Helvetica, Arial, sans-serif',
    '"Helvetica Neue", Helvetica, Arial, sans-serif' => 'Helvetica Neue, Helvetica, Arial, sans-serif',
    '"Arial Black", Gadget, sans-serif' => 'Arial Black, Gadget',
    '"Comic Sans MS", cursive' => 'Comic Sans MS',
    '"Courier New", Courier, monospace' => 'Courier New, Courier',
    'Georgia, "Times New Roman", Times, serif' => 'Georgia, Times New Roman, Times',
    'Impact, Charcoal, sans-serif' => 'Impact, Charcoal',
    '"Lucida Console", Monaco, monospace' => 'Lucida Console, Monaco',
    '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode, Lucida Grande',
    '"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino Linotype, Book Antiqua, Palatino',
    'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva',
    '"Times New Roman", Times, serif' => 'Times New Roman, Times',
    '"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS, Helvetica',
    'Verdana, Geneva, sans-serif' => 'Verdana, Geneva'
];
$fontOptionsWithInherit = array_merge(['inherit' => '(inherit from general-font-settings)'], $fontOptions);

$sectionTypes = [
    'default' => ['title' => 'Default', 'params' => [
        'columns' => ['format' => 'select', 'default' => '1', 'values' => ['1', '2', '3', '4'], 'html_before' => '<div class="label">' . I18n::_('Columns') . ':</div>'],
        'entryMaxWidth' => ['format' => 'text', 'css_units' => true, 'default' => '', 'html_before' => '<div class="label">' . I18n::_('Entry max width') . ':</div>'],
        'entryPadding' => ['format' => 'text', 'default' => '0 10px 20px 10px', 'html_before' => '<div class="label">' . I18n::_('Entry padding') . ':</div>'],
        'backgroundVideoEmbed' => ['format' => 'text', 'default' => '', 'html_entities' => true, 'html_before' => '<div class="label">' . I18n::_('Background video (embed)') . ':</div>'],
        'backgroundVideoRatio' => ['format' => 'select', 'default' => 'fillWindow', 'values' => ['fillWindow' => I18n::_('Fill window'), 'keepRatio' => I18n::_('Keep ratio')], 'html_before' => '<div class="label">' . I18n::_('Background video ratio') . ':</div>'],
    ]],
    'external_link' => ['title' => 'External link', 'params' => [
        'link' => ['format' => 'text',	'default' => '', 'link' => true, 'html_before' => '<div class="label">' . I18n::_('Link address') . ':</div>'],
        'target' => ['format' => 'select', 'values' => ['_self' => 'Same window', '_blank' => 'New window'], 'default' => '_blank', 'html_before' => '<div class="label">' . I18n::_('Opens in') . ':</div>']
    ]],
    'grid' => ['title' => 'Thumbnails enabled'],
    'portfolio' => ['title' => 'Portfolio']
];

$templateConf = [
    'generalFontSettings' => [
        '_' => ['title' => I18n::_('General font settings')],
        'color' => ['format' => 'color',		'default' => '#363636', 							                'title' => I18n::_('Color'),         'description' => ''],
        'fontFamily' => ['format' => 'fontselect',	'values' => $fontOptions, 'default' => 'Arial, sans-serif', 		'title' => I18n::_('Font face'),     'description' => ''],
        'googleFont' => ['format' => 'text',		'default' => '',	'html_entities' => true,						'title' => 'Google web fonts',       'description' => I18n::_('googleFont_description')],
        'fontSize' => ['format' => 'text', 'css_units' => true, 'default' => '12px', 								                'title' => I18n::_('Font size'),     'description' => ''],
        'fontWeight' => ['format' => 'select',		'values' => ['normal', 'bold'], 'default' => 'normal', 		'title' => I18n::_('Font weight'),   'description' => ''],
        'fontStyle' => ['format' => 'select',		'values' => ['normal', 'italic'], 'default' => 'normal', 		'title' => I18n::_('Font style'),    'description' => ''],
        'fontVariant' => ['format' => 'select',		'values' => ['normal', 'small-caps'], 'default' => 'normal', 	'title' => I18n::_('Font variant'),  'description' => ''],
        'lineHeight' => ['format' => 'text', 'css_units' => true, 'default' => '18px', 								                'title' => I18n::_('Line height'),   'description' => I18n::_('Height of text line. Use em, px or % values or the default value "normal"')]
    ],

    'background' => [
        '_' => ['title' => I18n::_('Background')],
        'backgroundColor' => ['format' => 'color',		'default' => '#FFFFFF',									                                                                                                                                    'title' => I18n::_('Background color'),                      'description' => I18n::_('IMPORTANT! These settings will be overwritten, if you are using background gallery feature. You access it by clicking "edit background gallery" button in each section.')],
        'backgroundImageEnabled' => ['format' => 'select',		'values' => ['yes', 'no'], 'default' => 'no', 				                                                                                                                            'title' => I18n::_('Is background image enabled?'),          'description' => ''],
        'backgroundImage' => ['format' => 'image',		'default' => '', 'min_width' => 1, 'min_height' => 1, 'max_width' => 3000, 'max_height' => 3000, 	 	                                                                                    'title' => I18n::_('Background image'),                      'description' => I18n::_('Picture to use for page background.')],
        'backgroundRepeat' => ['format' => 'select',		'values' => ['repeat' => 'tile vertically and horizontally', 'repeat-x' => 'tile horizontally', 'repeat-y' => 'tile vertically', 'no-repeat' => 'no tiling'], 'default' => 'repeat', 		'title' => I18n::_('Background tiling'),                     'description' => I18n::_('How the background fills the screen?')],
        'backgroundPosition' => ['format' => 'select',		'values' => ['top left', 'top center', 'top right', 'center left', 'center', 'center right', 'bottom left', 'bottom center', 'bottom right'], 'default' => 'top left', 	            'title' => I18n::_('Background alignment'),                  'description' => I18n::_('Where the background image is positioned?')],
        'backgroundAttachment' => ['format' => 'select',		'values' => ['fixed' => 'Fixed to browser window', 'fill' => 'Filled in browser window', 'scroll' => 'No stretch, scroll along with content'], 'default' => 'scroll', 		            'title' => I18n::_('Background position'),                   'description' => I18n::_('Sets how background behaves in relation with the browser window.')]
    ],

    'pageLayout' => [
        '_' => ['title' => I18n::_('Page layout')],
        'bgButtonType' => ['format' => 'select', 'default' => 'dark', 'values' => ['dark', 'bright'],	'title' => I18n::_('Background button type'),	'description' => I18n::_('Select type for background gallery buttons.')],
        'centered' => ['format' => 'select', 'default' => 'no', 'values' => ['yes', 'no'], 'title' => I18n::_('Centered layout'), 'description' => I18n::_('Sets whether layout should be centered or not.')],
        'centeredWidth' => ['format' => 'text',	'default' => '960px',	'css_units' => true,	'title' => I18n::_('Centered content width'),	'description' => I18n::_('Content width if layout is centered.')],
        'centeringGuidesColor' => ['format' => 'select', 'default' => 'dark', 'values' => ['dark', 'bright'],	'title' => I18n::_('Centering guides color tone'),	'description' => I18n::_('Color tone for centering guides (dark for bright background colors, bright for dark background colors).')],

        'group_responsive' => ['format' => false, 'default' => false, 'title' => I18n::_('Responsive design')],
        'autoResponsive' => ['format' => 'select', 'default' => 'yes', 'values' => ['yes', 'no'], 'title' => I18n::_('Responsive layout'), 'description' => I18n::_('Enables responsive layout for mobile devices.')],
        'responsive' => ['format' => 'select', 'default' => 'no', 'values' => ['no', 'yes'], 'title' => I18n::_('Grid layout'), 'description' => I18n::_('Entries are organised in columns. Column count can be changed under section settings.')],
        'centeredContents' => ['format' => 'select', 'default' => 'no', 'values' => ['no', 'yes'], 'title' => I18n::_('Centered contents'), 'description' => I18n::_('Page heading, menu items and section entries is horizontally centered to window.')],
        'headingMargin' => ['format' => 'text', 'default' => '20px 10px',	'title' => I18n::_('Heading margin'),	'description' => I18n::_('Margin around page heading or logo. Please see the short CSS guide at the bottom of this page.')],
        'menuMargin' => ['format' => 'text', 'default' => '20px 10px',	'title' => I18n::_('Menu margin'),	'description' => I18n::_('Margin around menu. Please see the short CSS guide at the bottom of this page.')],
    ],

    'entryHeading' => [
        '_' => ['title' => I18n::_('Entry heading')],
        'color' => ['format' => 'color',		'default' => '#363636', 					                                    'title' => I18n::_('Color'),             'description' => ''],
        'fontFamily' => ['format' => 'fontselect',	'values' => $fontOptions, 'default' => 'Arial, sans-serif', 			        'title' => I18n::_('Font face'),         'description' => ''],
        'googleFont' => ['format' => 'text',		'default' => '', 	'html_entities' => true,									'title' => 'Google web fonts',         'description' => I18n::_('googleFont_description')],
        'fontSize' => ['format' => 'text', 'css_units' => true, 'default' => '1.8em', 					                                        'title' => I18n::_('Font size'),         'description' => ''],
        'fontWeight' => ['format' => 'select',		'values' => ['normal', 'bold'], 'default' => 'normal', 			        'title' => I18n::_('Font weight'),       'description' => ''],
        'fontStyle' => ['format' => 'select',		'values' => ['normal', 'italic'], 'default' => 'normal', 			        'title' => I18n::_('Font style'),        'description' => ''],
        'fontVariant' => ['format' => 'select',		'values' => ['normal', 'small-caps'], 'default' => 'normal', 		        'title' => I18n::_('Font variant'),      'description' => ''],
        'lineHeight' => ['format' => 'text', 'css_units' => true, 'default' => 'normal', 					                                        'title' => I18n::_('Line height'),       'description' => I18n::_('Height of text line. Use em, px or % values or the default value "normal"')],
        'margin' => ['format' => 'text',		'default' => '15px 0 15px 0',													'title' => I18n::_('Margins'),           'description' => I18n::_('How far the entry heading is form other elements in page. Please see the short CSS guide at the bottom of this page.')],
    ],

    'entryLayout' => [
        '_' => ['title' => I18n::_('Entry layout')],
        'contentWidth' => ['format' => 'text',	'default' => '400px',	'css_units' => true,                                'title' => I18n::_('Entry text max width'),                             'description' => ''],
        'defaultGalleryType' => ['format' => 'select',		'values' => ['slideshow', 'row'], 'default' => 'slideshow',    'title' => I18n::_('Default gallery type'),                         'description' => I18n::_('Slideshow means that an image menu plus only one image is visible at a time. Row means that all images are visible.')],
        'spaceBetweenImages' => ['format' => 'text',		'default' => '1em', 'css_units' => true,                                'title' => I18n::_('Space between images in row and column'),       'description' => I18n::_('Horizontal/vertical space between images when gallery is in "row"/"column" mode')],
        'galleryNavMargin' => ['format' => 'text',		'default' => '0', 'css_units' => true,					                'title' => I18n::_('Space between images and image navigation'), 	'description' => I18n::_('Vertical space between images and image navigation (the digits below the image) when gallery is in "slideshow" mode')],
        'galleryMargin' => ['format' => 'text',		'default' => '0', 'css_units' => true,				                    'title' => I18n::_('Empty space below gallery'), 	                'description' => I18n::_('Distance between the gallery and the content below')],
        'displayTags' => ['format' => 'select',	'values' => ['yes', 'no'], 'default' => 'no', 	                    'title' => I18n::_('Display tags by each entry'),                   'description' => I18n::_('This determines whether people will see tags you set for each entry. Regardless of this settting, tags still will make up the main menu.')]
    ],

    'heading' => [
        '_' => ['title' => I18n::_('Page heading')],
        'position' => ['format' => 'select',		'values' => ['fixed', 'absolute'], 'default' => 'absolute', 		                        'title' => I18n::_('Heading position'),           'description' => I18n::_('description_heading_position')],
        'image' => ['format' => 'image',		'default' => '', 'min_width' => 1, 'min_height' => 1, 'max_width' => 140, 'max_height' => 400, 	 	'title' => I18n::_('Logo image'),    'description' => '<span class="warning">' . I18n::_('Displayed image will be half of the original size, full size will be used for hi-res displays.') . '</span>'],
        'color' => ['format' => 'color',		'default' => '#000000',                                                                             'title' => I18n::_('Color'),         'description' => ''],
        'fontFamily' => ['format' => 'fontselect',	'values' => $fontOptionsWithInherit, 'default' => '"Arial black", Gadget', 			    'title' => I18n::_('Font face'),     'description' => ''],
        'googleFont' => ['format' => 'text',		'default' => '', 'html_entities' => true,														'title' => 'Google web fonts',         'description' => I18n::_('googleFont_description')],
        'fontSize' => ['format' => 'text', 'css_units' => true, 'default' => '30px',                                                                                'title' => I18n::_('Font size'),     'description' => ''],
        'fontWeight' => ['format' => 'select',		'values' => ['normal', 'bold'], 'default' => 'bold',                                           'title' => I18n::_('Font weight'),   'description' => ''],
        'fontStyle' => ['format' => 'select',		'values' => ['normal', 'italic'], 'default' => 'normal',                                       'title' => I18n::_('Font style'),    'description' => ''],
        'fontVariant' => ['format' => 'select',		'values' => ['normal', 'small-caps'], 'default' => 'normal',                                   'title' => I18n::_('Font variant'),  'description' => ''],
        'lineHeight' => ['format' => 'text', 'css_units' => true,	'default' => '1em',                                                                                 'title' => I18n::_('Line height'),   'description' => I18n::_('Height of text line. Use em, px or % values or the default value "normal"')]
    ],

    'menu' => [
        '_' => ['title' => I18n::_('Main menu')],
        'position' => ['format' => 'select',		'values' => ['fixed', 'absolute'], 'default' => 'absolute', 		                        'title' => I18n::_('Menu position'),           'description' => I18n::_('description_menu_position')],
        'fontFamily' => ['format' => 'fontselect',	'values' => $fontOptionsWithInherit, 'default' => '"Arial black", Gadget',  'title' => I18n::_('Font face'),             'description' => ''],
        'googleFont' => ['format' => 'text',		'default' => '', 'html_entities' => true,											'title' => 'Google web fonts',         'description' => I18n::_('googleFont_description')],
        'fontSize' => ['format' => 'text', 'css_units' => true, 'default' => '20px', 								                                    'title' => I18n::_('Font size'),             'description' => ''],
        'fontWeight' => ['format' => 'select',		'values' => ['normal', 'bold'], 'default' => 'bold', 		                        'title' => I18n::_('Font weight'),           'description' => ''],
        'fontStyle' => ['format' => 'select',		'values' => ['normal', 'italic'], 'default' => 'normal', 		                    'title' => I18n::_('Font style'),            'description' => ''],
        'lineHeight' => ['format' => 'text', 'css_units' => true, 'default' => '1em', 								                                    'title' => I18n::_('Line height'),           'description' => I18n::_('Height of text line. Use em, px or % values or the default value "normal"')],
        'colorLink' => ['format' => 'color',		'default' => '#000000', 	                                                            'title' => I18n::_('Color'),                 'description' => ''],
        'colorHover' => ['format' => 'color',		'default' => '#000000', 	                                                            'title' => I18n::_('Color when hovered'),    'description' => I18n::_('Color of the element under mouse cursor')],
        'colorActive' => ['format' => 'color',		'default' => '#000000', 	                                                            'title' => I18n::_('Color when selected'),   'description' => I18n::_('Color of the element of the currently opened section')],
        'textDecorationLink' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'], 	'default' => 'none', 		    'title' => I18n::_('Decoration'),                'description' => ''],
        'textDecorationHover' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'underline', 		'title' => I18n::_('Decoration when hovered'),   'description' => ''],
        'textDecorationActive' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'line-through',    'title' => I18n::_('Decoration when selected'),    'description' => '']
    ],

    'tagsMenu' => [
        '_' => ['title' => I18n::_('Submenu')],
        'fontFamily' => ['format' => 'fontselect',	'values' => $fontOptionsWithInherit, 'default' => '"Arial black", Gadget',  'title' => I18n::_('Font face'),             'description' => ''],
        'googleFont' => ['format' => 'text',		'default' => '', 'html_entities' => true,											'title' => 'Google web fonts',         'description' => I18n::_('googleFont_description')],
        'fontSize' => ['format' => 'text', 'css_units' => true, 'default' => '16px', 								                                    'title' => I18n::_('Font size'),             'description' => ''],
        'fontWeight' => ['format' => 'select',		'values' => ['normal', 'bold'], 'default' => 'normal',                             'title' => I18n::_('Font weight'),           'description' => ''],
        'fontStyle' => ['format' => 'select',		'values' => ['normal', 'italic'], 'default' => 'normal',                           'title' => I18n::_('Font style'),            'description' => ''],
        'lineHeight' => ['format' => 'text', 'css_units' => true, 'default' => '1.5em',                                                                    'title' => I18n::_('Line height'),           'description' => I18n::_('Height of text line. Use em, px or % values or the default value "normal"')],
        'colorLink' => ['format' => 'color',			'default' => '#000000',                                                             'title' => I18n::_('Color'),                 'description' => ''],
        'colorHover' => ['format' => 'color',		'default' => '#000000',                                                                 'title' => I18n::_('Color when hovered'),    'description' => ''],
        'colorActive' => ['format' => 'color',		'default' => '#000000',                                                                 'title' => I18n::_('Color when selected'),   'description' => ''],
        'textDecorationLink' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'], 	'default' => 'none', 		'title' => I18n::_('Decoration'),                'description' => ''],
        'textDecorationHover' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'underline', 	'title' => I18n::_('Decoration when hovered'),   'description' => ''],
        'textDecorationActive' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'underline', 	'title' => I18n::_('Decoration when selected'),    'description' => ''],
        'x' => ['format' => 'text', 'css_units' => true, 'default' => '0', 								                                    'title' => I18n::_('Positon X'),             'description' => I18n::_('description_tagsMenu_x')],
        'y' => ['format' => 'text', 'css_units' => true, 'default' => '0', 								                                    'title' => I18n::_('Positon Y'),             'description' => I18n::_('description_tagsMenu_y')],
        'alwaysOpen' => ['format' => 'select',	'values' => ['yes', 'no'], 'default' => 'yes', 	                    'title' => I18n::_('Submenu is allways open'),                   'description' => I18n::_('description_submenu_alwaysopen')],
        'hidden' => ['format' => 'select',     'values' => ['yes', 'no'], 'default' => 'no',                         'title' => I18n::_('Submenu is hidden'),        'description' => ''],
    ],

    'links' => [
        '_' => ['title' => I18n::_('Hyperlinks')],
        'colorLink' => ['format' => 'color',		'default' => '#000000', 	    'title' => I18n::_('Link color'),                'description' => ''],
        'colorVisited' => ['format' => 'color',		'default' => '#000000', 	'title' => I18n::_('Visited link color'),        'description' => ''],
        'colorHover' => ['format' => 'color',		'default' => '#000000', 	'title' => I18n::_('Link color when hovered'),   'description' => ''],
        'colorActive' => ['format' => 'color',		'default' => '#000000', 	'title' => I18n::_('Link color when clicked'),   'description' => ''],
        'textDecorationLink' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'], 	'default' => 'none', 		'title' => I18n::_('Link decoration'),               'description' => ''],
        'textDecorationVisited' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'none', 		'title' => I18n::_('Visited link decoration'),       'description' => ''],
        'textDecorationHover' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'underline', 	'title' => I18n::_('Link decoration when hovered'),  'description' => ''],
        'textDecorationActive' => ['format' => 'select',		'values' => ['none', 'underline', 'overline', 'line-through'],		'default' => 'underline', 	'title' => I18n::_('Link decoration when clicked'),  'description' => '']
    ],

    'grid' => [
        '_' => ['title' => I18n::_('Thumbnails')],
        'whatAreThumbnails' => ['format' => '', 'default' => '', 'title' => I18n::_('Thumbnails can be turned on by setting the section type to "Thumbnails enabled" & adding more than 1 images to background gallery.')],
        'contentWidth' => ['format' => 'text',	'default' => '60%',	'title' => I18n::_('Thumbnail container width'),	'description' => I18n::_('IMPORTANT! This must be set as percentage. i.e. 60%')],
    ],

    'css' => [
        '_' => ['title' => I18n::_('Custom CSS')],
        'customCSS' => ['format' => 'longtext',	'allow_blank' => true,	'default' => '',	'html_entities' => true,	'title' => I18n::_('Custom CSS'), 'description' => I18n::_('description_custom_css')]
    ]
];

if (@file_exists('../_plugin_shop/template.conf.php')) {
    include '../_plugin_shop/template.conf.php';
}

return [$sectionTypes, $templateConf];
