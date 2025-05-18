<?php

/**
 * Add the Color Options Function
 * - This function is pluggable
 * - It can be overridden if you would like to add to or update the color options in the child theme
 * - @return array
 */
if (!function_exists('theme_color_options')) {
    function theme_color_options() : array
    {
        return array(
            'primary' => '#005e54',
            'secondary' => '#de3e27',
            'accent' => '#c2bb00',
            'light' => '#e0f7ff',
            'dark' => '#003547',
            'neutral' => '#f5f5f5',
            'white' => '#ffffff',
            'black' => '#000000',
        );
    }
}

/**
 * Add the Color Options Class
 * - // This class adds theme-defined color options to the WordPress Customizer, editor, and front-end CSS
 */
class ThemeColors
{
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_color_options_palette'));
        add_action('wp_enqueue_scripts', array($this, 'theme_color_options_css'));
        add_action('customize_register', array($this, 'theme_colors_customize_register'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_iris_palette_script'));
        add_action('after_setup_theme', array($this, 'add_editor_palette_support'));
    }

    /**
     * Add the Color Options Palette for the Editor
     * @return array
     */
    public function theme_color_options_palette() : array
    {
        $colors = theme_color_options();
        $palette = array();

        foreach ($colors as $color => $hex) {
            $option_name = 'theme_color_' . $color;
            $palette[] = array(
                'name' => ucwords($color),
                'slug' => $color,
                'color' => get_option('theme_color_' . $color, $hex),
            );
        }

        return $palette;
    }

    /**
     * Theme Color Options Css
     * - This function adds the color options to the root element
     * @return void
     */
    public function theme_color_options_css() :void
    {
        $colors = theme_color_options();

        $css = '';

        foreach ($colors as $color => $hex) {
            $option_name = 'theme_color_' . $color;
            $option_value = get_option($option_name) ?: $hex;
            $css .= "--color-$color: $option_value;";
        }

        wp_add_inline_style('theme-css', ':root{' . $css . '}');
    }

    /**
     * Add color options to the Customizer
     * @param WP_Customize_Manager $wp_customize
     */
    public function theme_colors_customize_register(WP_Customize_Manager $wp_customize) : void
    {
        $colors = theme_color_options();
        foreach ($colors as $color => $hex) {
            // Add the setting
            $wp_customize->add_setting('theme_color_' . $color, array(
                'default' => $hex,
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            ));

            // Add the color control
            $wp_customize->add_control(new WP_Customize_Color_Control(
                $wp_customize,
                'theme_color_' . $color,
                array(
                    'label' => ucfirst($color),
                    'section' => 'colors',
                    'settings' => 'theme_color_' . $color,
                )
            ));
        }
    }

    /**
     * Enqueue the Iris Palette Script
     * @return void
     */
    public function enqueue_iris_palette_script(): void
    {
        wp_enqueue_script('update-iris-palette', get_stylesheet_directory_uri() . '/src/ThemeColors/ThemeColors.js', array('jquery', 'wp-color-picker'), null, true);
        wp_localize_script('update-iris-palette', 'customColors', array_values(theme_color_options()) );
    }

    /**
     * Add Editor Palette Support
     * - This function adds the color options to the editor palette
     * @return void
     */
    public function add_editor_palette_support(): void
    {
        add_theme_support('editor-color-palette', $this->theme_color_options_palette());
        add_theme_support('disable-custom-colors');
    }
}
