module.exports = function(grunt) {
  pkg: grunt.file.readJSON('package.json'),
  grunt.initConfig({

    // compass
    compass: {
      dist: {
        options: {
          sassDir: 'css/sass',
          cssDir: 'css'
        }
      }
    },

    // Auto-prefix CSS properties using Can I Use?
    autoprefixer: {
      options: {
        // Last 2 versions of all browsers, plus IE7/8, BB10 (LOL), and Android 3+
        browsers: ['last 2 versions', 'ie 8', 'ie 7', 'bb 10', 'android 3']
      },
      no_dest: {
        // File to output
        src: 'css/style.css'
      },
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

    // Watch files for changes
    watch: {
      css: {
        files: ['css/sass/**/**/*', 'css/sass/*'],
        // Run compass, autoprefixer, and CSSO
        tasks: ['compass', 'autoprefixer', 'csso'],
        options: {
          interrupt: true,
          spawn: false,
          livereload: true,
        },
      },
    }

  });

  // Load tasks
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-csso');
  grunt.loadNpmTasks('grunt-notify');

  // Register tasks
  grunt.registerTask('default', ['compass', 'autoprefixer', 'csso', 'watch']);
};