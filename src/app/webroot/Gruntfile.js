module.exports = function(grunt) {
  pkg: grunt.file.readJSON('package.json'),
  grunt.initConfig({

    // compass
    compass: {
      dist: {
        options: {
          require: 'susy',
          sassDir: 'css/sass',
          cssDir: 'css',
          require: 'susy'
        }
      }
    },

    csso: {
      dist: {
        options: {
          report: 'gzip',
        },
        files: {
          'css/style.min.css': ['css/style.css'],
        },
      },
    },

	ngAnnotate: {
		options:{

		},
		app_citicinemas: {
			files:[
				{
					expand: true,
					src: ['js/admin/projections.js'],
					ext: '.annotated.js',
					extDot:'last'
				},
				{
					expand: true,
					src: ['js/admin/sync.js'],
					ext: '.annotated.js',
					extDot:'last'
				}
			]
		}
	},

    uglify: {
      dist: {
        files: {
          'js/admin/projections.min.js': ['js/admin/projections.annotated.js'],
	      'js/admin/sync.min.js': ['js/admin/sync.annotated.js']
        }
      }
    },

    less:{
      dist:{
        options:{
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files:{
          //"less/l10ntools.less": "css/l10ntools.css"
          "../plugins/i18n/webroot/css/l10ntools.css":"../plugins/i18n/webroot/less/l10ntools.less"
        }
      }
    },

    notify: {
      css:{
        options: {
          title: "Css Files Build",
          message:'LESS / SASS task complete',
          duration: 3
        }
      },
      scripts:{
        options: {
          title: "JS Files Build",
          message:'Uglify task complete',
          duration: 3
        }
      }
    },

    // Watch files for changes
    watch: {
      css: {
        files: ['css/sass/**/**/*', 'css/sass/*','../plugins/i18n/webroot/less/*'],
        // Run compass, autoprefixer, and CSSO
        tasks: ['compass', 'csso','less','notify:css'],
        options: {
          interrupt: true,
          spawn: false,
          livereload: true,
        },
      },
      scripts: {
        files: ['js/admin/projections.js'],
        tasks: ['uglify','notify:scripts']
      }
    }

  });

  // Load tasks
  /*grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-csso');*/
  grunt.loadNpmTasks('grunt-notify');
  //grunt.loadNpmTasks('grunt-newer');
  //require('time-grunt')(grunt);
  require('jit-grunt')(grunt);

  // Register tasks
  grunt.registerTask('default', ['compass', 'csso', 'ngAnnotate' ,'uglify','less', 'watch']);
  grunt.task.run('notify_hooks');
};