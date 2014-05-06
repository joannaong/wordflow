'use strict';

module.exports = function(grunt) {
	// Used for loading all of your grunt plugins
	require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // copy wp-config for wordpress
    copy:{
    	wpConfig: {
		    src: 'src/wp-config.php',
		    dest: 'wp/wp-config.php',
		  },
		  wpPlugins: {
		    src: 'src/plugins/',
		    dest: 'wp/wp-content/plugins/',
		  }
    }

  });

  // Default task(s).
  grunt.registerTask('default', ["copy"]);

};


