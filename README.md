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
Setup your database table and fill in src/cms/wp-config.php.

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

Set up path where Wordpress will be hosted
```php
define('WP_HOME','http://localhost/wordflow/deploy/local/wp');
define('WP_SITEURL','http://localhost/wordflow/deploy/local/wp');
```

Setup your aws credentials (if applicable)

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

### Activate Plugins
- Go to the deploy folder where Wordpress is copied over, ie. http://localhost/wordflow/deploy/local/wp/wp-admin and activate all plugins by going to 'Plugins' on the side dashboard.
- Go to 'Settings' > 'JSON API' and activate wordflow





Plugins
---------------------
##JSON API
##Custom Fields





Reference
---------------------
Tutorials: 
 - http://polycademy.com/blog/id/148/modern_wordpress_workflow_with_composer
 - http://roots.io/using-composer-with-wordpress/

Wordpress plugin list:
 - http://wpackagist.org/
 - http://plugins.svn.wordpress.org/


Notes
---------------------
MAMP is weird. If you're getting an error when hitting .sh script, fix mamp by opening /Applications/MAMP/Library/bin/envvars and comment out
```php
# DYLD_LIBRARY_PATH="/Applications/MAMP/Library/lib:$DYLD_LIBRARY_PATH"
# export DYLD_LIBRARY_PATH
```