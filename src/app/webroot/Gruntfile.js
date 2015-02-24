module.exports = function(grunt) {
  pkg: grunt.file.readJSON('package.json'),
  grunt.initConfig({

    // compass
    compass: {
      dist: {
        options: {
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

    // Watch files for changes
    watch: {
      css: {
        files: ['css/sass/**/**/*', 'css/sass/*'],
        // Run compass, autoprefixer, and CSSO
        tasks: ['compass', 'csso'],
        options: {
          interrupt: true,
          spawn: false,
          livereload: true,
        },
      },
    }

  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-csso');
  grunt.loadNpmTasks('grunt-notify');

  // Register tasks
  grunt.registerTask('default', ['compass', 'csso', 'watch']);
};