    module.exports = function(grunt) {

        grunt.initConfig({

            less: {
                dev: {
                    files: {
                        "css/styles.css": "less/_styles.less"
                    }
                }
            },
            sass: {
                dev: {
                    files: {
                        'css/styles.css': 'scss/_styles.scss'
                    }
                }
            },
            watch: {
                styles_less: {
                    files: ['../*.less', 'less/*.less'], // which files to watch
                    tasks: ['less:dev'],
                    options: {
                        nospawn: true
                    }
                },
                styles_sass: {
                    files: ['../*.scss', 'scss/*.scss'], // which files to watch
                    tasks: ['sass:dev'],
                    options: {
                        nospawn: true
                    }
                }
            }
        });
        grunt.loadNpmTasks('grunt-contrib-less');
        grunt.loadNpmTasks('grunt-contrib-sass');
        grunt.loadNpmTasks('grunt-contrib-watch');
        grunt.registerTask('default', ['watch']);
    };
