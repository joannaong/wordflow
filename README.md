Wordflow
========================================
Wordpress workflow that generates a static site


This workflow uses wordpress as a CMS and generates a static site which can be pushed up to S3.




Requirements
---------------------
Install Composer: https://getcomposer.org/doc/00-intro.md

```bash
curl -sS https://getcomposer.org/installer | php
```

Install NPM

```bash
curl http://npmjs.org/install.sh | sh
```




Structure
---------------------

```
PROJECT/
	|- composer.json        # list of php dependencies (composer packages)
	|- composer.lock        # lock file auto_produced when running 'composer install'
	|- Gruntfile.js         # Grunt tasks
	|- node_modules/        # list of npm dependencies used in Grunt (git ignored)
	|- package.json         # list of npm packages
	|- README.md
	|- src/                 # source code (themes and/or plugins)
	|  |- cms/
	|  |  |- plugins/
	|  |  |- themes/
	|  |  `- wp-config.php
	|  |- html/             # front-end files
	|- vendor/              # composer dependencies (git ignored)
	|- wordpress_module/    # wordpress (git ignored)
```





Usage
---------------------

### Install dependencies
Run 'composer install' in terminal to install wordpress and all dependent plugins and themes. Configuration can be edited inside composer.json. Refer to http://roots.io/using-composer-with-wordpress/ for more info on how it's set up.

```bash
composer install
```

Run 'npm install' in terminal to install node_modules/.

```bash
npm install
```

### Setup Wordpress
Setup your database table and fill in src/wp-config.php.

```php
/** The name of the database for WordPress */
define('DB_NAME', '*************');

/** MySQL database username */
define('DB_USER', '*************');

/** MySQL database password */
define('DB_PASSWORD', '*************');

/** MySQL hostname */
define('DB_HOST', '*************');
```

Setup your aws credentials (if applicable) src/wp-config.php

```php
define('AWS_ACCESS_KEY_ID', '*************');
define('AWS_SECRET_ACCESS_KEY', '*************');
```

### Build
Run 'grunt build:[your environment (local, dev, stage)]' to build.
- Wordpress core will be copied over to deploy/[env]/wp
- src/cms/wp-config.php will be copied over to deploy/[env]/wp/wp-config.php
- src/cms/plugins will be copied over to deploy/[env]/wp/wp-content/plugins

```bash
grunt build:local
```








Reference
---------------------
Tutorials: 
 - http://polycademy.com/blog/id/148/modern_wordpress_workflow_with_composer
 - http://roots.io/using-composer-with-wordpress/

Wordpress plugin list:
 - http://wpackagist.org/
 - http://plugins.svn.wordpress.org/
