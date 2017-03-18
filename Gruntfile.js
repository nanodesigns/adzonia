/**
 * AdZonia Grunt Directives
 *
 * @package     adzonia
 * @version     2.0.0
 */

module.exports = function(grunt) {

    'use strict';

    // @Grunt: Get our configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /**
         * Validate files with JSHint
         * @url: https://github.com/gruntjs/grunt-contrib-jshint
         */
        jshint: {
            all: [
                'Gruntfile.js',
                'public/js/adzonia.js',
                'admin/js/adzonia-admin.js'
            ]
        },

        /**
         * Concatenate & Minify Javascript files
         * @url: https://github.com/gruntjs/grunt-contrib-uglify
         */
        uglify: {
            public: {
                options: {
                    sourceMap: false,
                    preserveComments: /^!/ // Preserve comments that start with a bang.
                },
                files: {
                    'public/js/adzonia.min.js': [ 'public/js/adzonia.js' ],
                    'admin/js/adzonia-admin.min.js': [ 'admin/js/adzonia-admin.js' ]
                },
            }
        },

        /**
         * Compile SCSS files into CSS
         * @url: https://github.com/sindresorhus/grunt-sass/
         */
        sass: {
            dist: {
                options: {
                    sourceMap: false
                },
                files: {
                    'public/css/adzonia.css': 'public/sass/adzonia.scss',
                    'admin/css/adzonia-admin.css': 'admin/sass/adzonia-admin.scss'
                }
            }
        },

        /**
         * Add vendor prefixes
         * @url: https://github.com/nDmitry/grunt-autoprefixer
         */
        autoprefixer: {
            options: {
                cascade: false
            },
            adzCSS: {
                src: 'public/css/adzonia.css'
            },
            adminCSS: {
                src: 'admin/css/adzonia-admin.css'
            }
        },

        /**
         * Minify Stylehseets for production
         * @url: https://github.com/gruntjs/grunt-contrib-cssmin
         */
        cssmin: {
            minify: {
                files: {
                    'public/css/adzonia.css': 'public/css/adzonia.css',
                    'admin/css/adzonia-admin.css': 'admin/css/adzonia-admin.css'
                },
                options: {
                    report: 'min',
                    keepSpecialComments: 0
                }
            }
        },


        /**
         * Updates the translation catalog
         * @url: https://www.npmjs.com/package/grunt-wp-i18n
         */
        makepot: {
            target: {
                options: {
                    domainPath: '/i18n/languages/',
                    exclude: [
                        'public/css/.*',
                        'public/sass/.*',
                        'admin/sass/.*',
                        'admin/css/.*',
                        'public/js/.*',
                        'admin/js/.*',
                        'node_modules/.*',
                        'vendor/.*',
                        'tests/.*'
                    ],
                    mainFile: 'adzonia.php',
                    potComments: 'Copyright (c) 2014-2017 nanodesigns',
                    potFilename: 'adzonia.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true,
                        'report-msgid-bugs-to': 'https://github.com/nanodesigns/adzonia/issues',
                        'last-translator': 'nanodesigns (http://nanodesignsbd.com/)',
                        'language-team': 'nanodesigns <info@nanodesignsbd.com>',
                        'language': 'en_US'
                    },
                    processPot: null,
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },


        /**
         * Check textdomain errors
         * @url: https://github.com/stephenharris/grunt-checktextdomain
         */
        checktextdomain: {
            options:{
                text_domain: 'adzonia',
                keywords: [
                    '__:1,2d',
                    '_e:1,2d',
                    '_x:1,2c,3d',
                    'esc_html__:1,2d',
                    'esc_html_e:1,2d',
                    'esc_html_x:1,2c,3d',
                    'esc_attr__:1,2d',
                    'esc_attr_e:1,2d',
                    'esc_attr_x:1,2c,3d',
                    '_ex:1,2c,3d',
                    '_n:1,2,4d',
                    '_nx:1,2,4c,5d',
                    '_n_noop:1,2,3d',
                    '_nx_noop:1,2,3c,4d'
                ]
            },
            files: {
                src:  [
                    '**/*.php',         // Include all files
                    '!node_modules/**', // Exclude node_modules/
                    '!vendor/**',       // Exclude vendor/
                    '!tests/**'         // Exclude tests/
                ],
                expand: true
            }
        },


        /**
         * Create a neat zip archive for distribution
         * @url: https://github.com/gruntjs/grunt-contrib-compress
         */
        compress: {
            main: {
                options: {
                    archive: 'adzonia-<%= pkg.version %>.zip',
                    mode: 'zip'
                },
                files: [{
                    src: [
                        '*',
                        '**',
                        '!node_modules/**',
                        '!vendor/**',
                        '!tests/**',
                        '!.gitignore',
                        '!.travis.yml',
                        '!composer.json',
                        '!composer.lock',
                        '!tests/**',
                        '!logs/**',
                        '!README.md',
                        '!contributing.md',
                        '!*.sublime-grunt.cache',
                        '!Gruntfile.js',
                        '!package.json',
                        '!*.sublime-workspace',
                        '!*.sublime-project',
                        '!adzonia-<%= pkg.version %>.zip'
                    ],
                    dest: 'adzonia/' // archive it in this directory
                }]
            }
        },


        /**
         * Watch for changes and do it
         * @url: https://github.com/gruntjs/grunt-contrib-watch
         */
        watch: {
            options: {
                livereload: {
                    port: 9000
                }
            },
            js: {
                files: [
                    'public/js/adzonia.js',
                    'admin/js/adzonia-admin.js'
                ],
                tasks: ['uglify']
            },
            css: {
                files: [
                    'public/sass/*.scss',
                    'admin/sass/*.scss'
                ],
                tasks: ['sass', 'autoprefixer', 'cssmin']
            }
        }

    });


    // @Grunt: we're using the following plugins
    require('load-grunt-tasks')(grunt);


    // @Grunt: do the following when we will type 'grunt <command>'
    grunt.registerTask('default', ['jshint', 'uglify', 'sass', 'autoprefixer', 'cssmin', 'watch']);
    grunt.registerTask('build', ['jshint', 'uglify', 'sass', 'autoprefixer', 'cssmin']);
    grunt.registerTask('translate', ['checktextdomain', 'makepot']);
    grunt.registerTask('release', ['translate', 'build', 'compress']);

};
