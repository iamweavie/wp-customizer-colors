(function($) {
    if (wp.customize) {
        wp.customize.bind('ready', function () {
            // Update the Iris color palette
            $('.wp-picker-container').iris({palettes: customColors});
        });
    }
    if (acf) {
        acf.add_filter('color_picker_args', function( args, $field ){
            args.palettes = customColors;
            return args;
        });
    }
})(jQuery);
