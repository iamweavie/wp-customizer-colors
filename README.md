# WP Customizer Colors

This theme extension helps you manage custom colors in a WordPress theme using the Customizer. It works with classic or hybrid themes — that is, themes that use the WP Customizer interface rather than relying solely on full site editing.

It provides:
- A pluggable function for defining your default color palette
- Integration with the WordPress Iris color picker in the Customizer
- A dedicated Customizer tab for your theme colors
- CSS root variables and utility classes added to the DOM
- (Coming soon) Support for Gutenberg editor color palette integration

## Usage

1. **Create a `src/` directory** in the root of your child theme (if it doesn't exist already).
2. **Clone or copy this repo** into the `src/` directory.
3. **Include and initialize the class** from your theme’s `functions.php` file:

```php
// Load Dependencies.
require_once __DIR__ . '/src/ThemeColors/ThemeColors.php';


// Initialize Theme Colors.
new ThemeColors();
```
