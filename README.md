# base wp

Wordpress workflow using composer and grunt


## Requirements
- Install Composer: https://getcomposer.org/doc/00-intro.md
```
curl -sS https://getcomposer.org/installer | php
```
- Install NPM
```
curl http://npmjs.org/install.sh | sh
```


## Usage (terminal)
```
composer install
npm install
grunt
```
- run 'composer install' to install php dependencies including wordpress
- run 'npm install' to install node_modules
- run 'grunt' to build


## Structure
```
PROJECT
|-- composer.json # list of php dependencies (composer packages)
|-- composer.lock # lock file auto-produced when running 'composer install'
|-- Gruntfile.js 	# Grunt tasks
|-- node_modules  # list of npm dependencies used in Grunt (git ignored)
|-- package.json 	# grunt packages
|-- README.md
|-- src 					# source code (themes and/or plugins)
|-- vendor				# composer dependencies (git ignored)
|-- wp 						# wordpress (git ignored)
```

- Wordpress and all dependent plugins / themes are installed as dependencies.
- Wordpress is installed under the wp/ directory
- src/wp-config.php is then copied over to wp/wp-config.php
- src/plugins are copied over to wp/wp-content/plugins



## Reference
- Tutorials:
-- http://polycademy.com/blog/id/148/modern_wordpress_workflow_with_composer
-- http://roots.io/using-composer-with-wordpress/

- Wordpress plugin list:
-- http://wpackagist.org/
-- http://plugins.svn.wordpress.org/