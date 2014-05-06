# Wordpress Workflow

Wordpress workflow using composer and grunt


## Requirements
Install Composer: https://getcomposer.org/doc/00-intro.md
```
curl -sS https://getcomposer.org/installer | php
```
Install NPM
```
curl http://npmjs.org/install.sh | sh
```


## Usage (terminal)

run 'composer install' to install php dependencies including wordpress
```
composer install
```
run 'npm install' to install node_modules
```
npm install
```
run 'grunt' to build
```
grunt
```
Setup your database table and fill in src/wp-config.php
```
/** The name of the database for WordPress */
define('DB_NAME', '*************');

/** MySQL database username */
define('DB_USER', '*************');

/** MySQL database password */
define('DB_PASSWORD', '*************');

/** MySQL hostname */
define('DB_HOST', 'localhost');
```


## Structure
```
PROJECT/
	|_ composer.json     # list of php dependencies (composer packages)
	|_ composer.lock     # lock file auto_produced when running 'composer install'
	|_ Gruntfile.js      # Grunt tasks
	|_ node_modules/     # list of npm dependencies used in Grunt (git ignored)
	|_ package.json      # grunt packages
	|_ README.md
	|_ src/              # source code (themes and/or plugins)
	|_ vendor/           # composer dependencies (git ignored)
	|_ wp/               # wordpress (git ignored)
```

- Wordpress and all dependent plugins / themes are installed as dependencies.
- Wordpress is installed under the wp/ directory
- src/wp-config.php is then copied over to wp/wp-config.php
- src/plugins are copied over to wp/wp-content/plugins



## Reference
Tutorials: 
	- http://polycademy.com/blog/id/148/modern_wordpress_workflow_with_composer
	- http://roots.io/using-composer-with-wordpress/
Wordpress plugin list:
	- http://wpackagist.org/
	- http://plugins.svn.wordpress.org/