module.exports = function(grunt) {
	// Used for loading all of your grunt plugins
	require('load-grunt-tasks')(grunt);

	// Config variables for different deployment environments
	var config = {
		local: {
			options: {
			variables: {
			environment: {
				id: "local",
				host: "localhost",
				dest: "log/local/www/",
				backendDest: "deploy/local/",
				s3_preview: "preview.wordflow.dev.thesecretlocation.net",
				s3_final: "wordflow.dev.thesecretlocation.net",
				s3_asset: "asset.wordflow.dev.thesecretlocation.net",
				aws_accessKeyId: '***',
		    aws_secretAccessKey: '***'
			}
			}
			}
		}
	}

	//----------------
	//- Grunt
	//----------------
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
    config: config,

		// CMS data
		CMS_navData: grunt.file.exists('log/data_cms/nav.json') ? grunt.file.readJSON('log/data_cms/nav.json') : grunt.file.readJSON('src/html/data/nav.json'),
		CMS_mediaArchivesData: grunt.file.exists('log/data_cms/media.json') ? grunt.file.readJSON('log/data_cms/media.json') : grunt.file.readJSON('src/html/data/media.json'),
		CMS_privacyData: grunt.file.exists('log/data_cms/privacy.json') ? grunt.file.readJSON('log/data_cms/privacy.json') : grunt.file.readJSON('src/html/data/privacy.json'),

    aws_s3: {
		  options: {
		    accessKeyId: '<%= environment.aws_accessKeyId %>',
		    secretAccessKey: '<%= environment.aws_secretAccessKey %>'
		  },
		  preview_all: {
		  	options: {
		      bucket: '<%= environment.s3_preview %>',
		      differential: false
		    },
		    files: [{
	      	expand: true, 
	      	'action': 'upload', 
	      	cwd: '<%= environment.dest %>', 
	      	src: ["*.html", "css/*", "js/*", "assets/**/*"]
	      }]
		  },
		  preview_data: {
		  	options: {
		      bucket: '<%= environment.s3_preview %>',
		      differential: false
		    },
		    files: [{
	      	expand: true, 
	      	'action': 'upload', 
	      	cwd: '<%= environment.dest %>', 
	      	src: ["*.html"]
	      }]
		  },
		  publish_all: {
		    options: {
		      bucket: '<%= environment.s3_final %>',
		      differential: false // Only uploads the files that have changed
		    },
		    files: [{
	      	expand: true, 
	      	'action': 'upload', 
	      	cwd: '<%= environment.dest %>', 
	      	src: ["*.html", "css/*", "js/*", "assets/**/*"]
	      }]
		  },
		  publish_data: {
		    options: {
		      bucket: '<%= environment.s3_final %>',
		      differential: false
		    },
		    files: [{
	      	expand: true, 
	      	'action': 'upload', 
	      	cwd: '<%= environment.dest %>', 
	      	src: ["*.html"]
	      }]
		  }
		},

		// Delete all files in the distribution directory
		clean: {
			dist: {
				src: [ "<%= environment.dest %>", "<%= environment.backendDest %>" ]
			},
			cms: {
				src: [ "<%= environment.backendDest %>wp/wp-content/themes" ]
			},
			log: {
				src: [ "log" ]
			}
		},

		// Copy the _externalAssets directory
		copy: {
			wordpress_module: {
				files: [{
					expand: true,
					cwd: "wordpress_module/core",
					src: ["**"],
					dest: "<%= environment.backendDest %>wp/"
				},
				{
					expand: true,
					cwd: "wordpress_module/plugins/",
					src: ["**"],
					dest: "<%= environment.backendDest %>wp/wp-content/plugins/"
				}]
			},
			cms: {
				files: [{
					expand: true,
					cwd: "src/cms/plugins/",
					src: ["**"],
					dest: "<%= environment.backendDest %>wp/wp-content/plugins/"
				},
				{
					expand: true,
					cwd: "src/cms/themes/",
					src: ["**"],
					dest: "<%= environment.backendDest %>wp/wp-content/themes/"
				},
				{
					expand: true,
					cwd: "src/cms/uploads/",
					src: ["**"],
					dest: "<%= environment.backendDest %>wp/wp-content/uploads/"
				},
				{
					src: "src/cms/wp-config.php",
					dest: "<%= environment.backendDest %>wp/wp-config.php"
				}]
			},
			assets: {
				files: [{
					expand: true,
					cwd: "src/html/assets",
					src: ["**"],
					dest: "<%= environment.dest %>assets/"
				}]
			}
		},

		// Jade compilation
		jade: {
			cms: {
				options: {
					sourceMap: true,
					pretty: false,
					data: {
						environment: "<%= environment %>",
						srcDir: "src/html/",
						navData: "<%= CMS_navData %>",
						mediaArchivesData: "<%= CMS_mediaArchivesData %>",
						privacyData: "<%= CMS_privacyData %>",
					}
				},
				files: {
					"<%= environment.dest %>index.html" : "src/html/jade/index.jade"
				}
			}
		}
	});


	// Compile build for the different deployment environments
	grunt.registerTask('build', function(_environment) {
		if(!_environment) _environment = 'local';

		grunt.task.run([
			'config:'+_environment,
			'clean:dist',
			'copy:assets',
			'copy:wordpress_module',
			'clean:cms',
			'copy:cms',
			'jade:cms'
		]);
		
	});

	// CMS Task: preview
	grunt.registerTask('preview', function(_environment, _which) {
		if(!_environment) _environment = 'local';

		if (_environment == "local") {
			grunt.task.run([
				'config:'+_environment,
				'jade:cms'
			]);
		} else {
			grunt.task.run([
				'config:'+_environment,
				'clean:log',
				'jade:cms',
				'aws_s3:preview_'+_which
			]);
		}
		
	});

	// CMS Task: publish
	grunt.registerTask('publish', function(_environment, _which) {
		if(!_environment) _environment = 'local';

		if (_environment == "local") {
			grunt.task.run([
				'config:'+_environment,
				'jade:cms'
			]);
		} else {
			grunt.task.run([
				'config:'+_environment,
				'clean:log',
				'jade:cms',
				'aws_s3:publish_'+_which
			]);
		}

	});

	// DEPLOY
	grunt.registerTask('deploy', function(_environment, _which) {
		if(!_environment) _environment = 'local';

		grunt.task.run([
			'config:'+_environment,
			'clean:dist',
			'copy:assets',
			'copy:php',
			'copy:wordpress_module',
			'clean:cms',
			'copy:cms',
			'jade:cms',
			'aws_s3:preview_'+_which,
			'aws_s3:publish_'+_which
		]);
	});

};