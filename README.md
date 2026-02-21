# WP Sermons

A comprehensive WordPress plugin for managing and displaying church sermons.

## Features

- **Custom Post Type**: Dedicated sermon post type with full WordPress editor support
- **Taxonomies**: Organize sermons by Series, Speaker, and Topic
- **Shortcodes**: Easy-to-use shortcodes for displaying sermons anywhere
- **REST API**: Full REST API support for headless implementations
- **Settings Page**: Configurable options for archive display
- **Responsive Design**: Mobile-friendly default styling

## Installation

1. Upload the `wp-sermons` folder to `/wp-content/plugins/`
2. Activate the plugin through WordPress admin
3. Navigate to Sermons > Settings to configure
4. Start adding sermons!

## Usage

### Shortcodes

**Display Sermons:**
```
[sermons number="10" series="romans" speaker="john-doe"]
```

**Display Series:**
```
[sermon_series number="5"]
```

**Display Recent Sermons:**
```
[recent_sermons number="5"]
```

### REST API

**Get all sermons:**
```
GET /wp-json/wp-sermons/v1/sermons
```

**Get single sermon:**
```
GET /wp-json/wp-sermons/v1/sermons/{id}
```

**Get all series:**
```
GET /wp-json/wp-sermons/v1/series
```

**Get all speakers:**
```
GET /wp-json/wp-sermons/v1/speakers
```

## File Structure

```
wp-sermons/
├── admin/
│   ├── css/
│   │   └── wp-sermons-admin.css
│   ├── js/
│   │   └── wp-sermons-admin.js
│   ├── class-wp-sermons-admin.php
│   └── class-wp-sermons-settings.php
├── includes/
│   ├── class-wp-sermons.php
│   ├── class-wp-sermons-activator.php
│   ├── class-wp-sermons-deactivator.php
│   ├── class-wp-sermons-post-type.php
│   ├── class-wp-sermons-shortcodes.php
│   └── class-wp-sermons-rest-api.php
├── public/
│   ├── css/
│   │   └── wp-sermons-public.css
│   ├── js/
│   │   └── wp-sermons-public.js
│   └── class-wp-sermons-public.php
├── wp-sermons.php
└── readme.txt
```

## Development

### Requirements

- PHP 7.4 or higher
- WordPress 5.0 or higher

### Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

GPL-2.0+

## Author

**CGH Web**

## Version

0.1.0
