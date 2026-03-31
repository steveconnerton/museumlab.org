<?php

/*
 * adds custom css styles for wysiwyg editor
 */
function add_custom_wysiwyg_styles()
{
  add_editor_style('css/admin/custom-wysiwyg-styles.css');
}

add_action('admin_init', 'add_custom_wysiwyg_styles');


/*
 * adds Formats dropdown to basic and full wysiwyg toolbars
 */
function custom_toolbar($toolbars)
{

  //add stylselect option to toolbars
  array_unshift($toolbars['Basic'][1], 'styleselect');
  array_unshift($toolbars['Full'][1], 'styleselect');

  return $toolbars;
}

add_filter('acf/fields/wysiwyg/toolbars', 'custom_toolbar');


/*
 * defines custom format options
 */
function custom_format_styles($config)
{
  $temp_array = array(
    array(
      'title' => 'Headings',
      'items' => array(
        array(
          'title' => 'Format',
          'items' => array(
            array(
              'title' => 'Headline 2',
              'format' => 'h2',
              'block' => 'h2',
            ),
            array(
              'title' => 'Headline 3',
              'format' => 'h3',
              'block' => 'h3',
            ),
            array(
              'title' => 'Headline 4',
              'format' => 'h4',
              'block' => 'h4',
            ),
            array(
              'title' => 'Headline 5',
              'format' => 'h5',
              'block' => 'h5',
            ),
            array(
              'title' => 'Headline 6',
              'format' => 'h6',
              'block' => 'h6',
            ),
          ),
        ),
        array(
          'title' => 'Sizes',
          'items' => array(
            array(
              'title' => 'X-Small',
              'selector' => 'h2, h3, h4, h5, h6',
              'inline' => 'span',
              'attributes' => array(
                'class' => 'font-size-xsmall'
              )
            ),
            array(
              'title' => 'Small',
              'selector' => 'h2, h3, h4, h5, h6',
              'inline' => 'span',
              'attributes' => array(
                'class' => 'font-size-small'
              )
            ),
            array(
              'title' => 'Normal',
              'selector' => 'h2, h3, h4, h5, h6',
              'inline' => 'span',
              'attributes' => array(
                'class' => 'font-size-normal'
              )
            ),
            array(
              'title' => 'Large',
              'selector' => 'h2, h3, h4, h5, h6',
              'inline' => 'span',
              'attributes' => array(
                'class' => 'font-size-large'
              )
            ),
            array(
              'title' => 'X-Large',
              'selector' => 'h2, h3, h4, h5, h6',
              'inline' => 'span',
              'attributes' => array(
                'class' => 'font-size-xlarge'
              )
            ),
          )
        )
      ),
    ),
    array(
      'title' => 'Text Colors',
      'items' => array(
        array(
          'title' => 'Black',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-black'
          )
        ),
        array(
          'title' => 'Dark Gray',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-gray-dark'
          )
        ),
        array(
          'title' => 'Gray',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-gray'
          )
        ),
        array(
          'title' => 'Light Gray',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-gray-light'
          )
        ),
        array(
          'title' => 'White',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-white'
          )
        ),
        array(
          'title' => 'Green',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-green'
          )
        ),
        array(
          'title' => 'Light Green',
          'inline' => 'span',
          'attributes' => array(
            'class' => 'text-green-light'
          )
        )
      )
    ),
    array(
      'title' => 'Text Styles',
      'items' => array(
        array(
          'title' => 'Uppercase',
          'inline' => 'span',
          'classes' => 'text-uppercase'
        ),
        array(
          'title' => 'Thin Weight',
          'inline' => 'span',
          'classes' => 'text-thin'
        ),
        array(
          'title' => 'Address',
          'inline' => 'span',
          'classes' => 'text-address'
        )
      )
    ),
    array(
      'title' => 'Buttons',
      'items' => array(
        array(
          'title' => 'Button Styles',
          'items' => array(
            array(
              'title' => 'Default Button',
              'selector' => 'a',
              'classes' => 'btn--default'
            ),
            array(
              'title' => 'Primary Button',
              'selector' => 'a',
              'classes' => 'btn--primary'
            ),
            array(
              'title' => 'Secondary Button',
              'selector' => 'a',
              'classes' => 'btn--secondary'
            ),
            array(
              'title' => 'Link Button',
              'selector' => 'a',
              'classes' => 'btn--link'
            )
          )
        ),
        array(
          'title' => 'Button Sizes',
          'items' => array(
            array(
              'title' => 'Small Button',
              'selector' => 'a',
              'classes' => 'btn--small'
            ),
            array(
              'title' => 'Medium (default) Button',
              'selector' => 'a',
              'classes' => 'btn--medium'
            ),
            array(
              'title' => 'Large Button',
              'selector' => 'a',
              'classes' => 'btn--large'
            )
          )
        )
      )
    )
  );
  $config['style_formats'] = json_encode($temp_array);
  return $config;
}

add_filter('tiny_mce_before_init', 'custom_format_styles');

