module.exports = function( grunt ) {
	'use strict';

	grunt.initConfig({

		// Minify .js files.
		uglify: {
			options: {
				preserveComments: 'some'
			},
			main: {
				files: [{
					expand: true,
					cwd: 'js/',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: 'js/',
					ext: '.min.js'
				}]
			}
		},

		// Compile all .scss files.
		sass: {
			compile: {
				options: {
					sourcemap: 'none',
					loadPath: require( 'node-bourbon' ).includePaths
				},
				files: [{
					'style.css'    : 'style.scss',
					'css/demo.css' : 'sass/demo.scss'
				}]
			}
		},

		// Minify main .css file.
		cssmin: {
			css: {
				src:  'style.css',
				dest: 'style.min.css'
			}
		},

		// Watch changes for assets.
		watch: {
			css: {
				files: [
					'**/*.scss'
				],
				tasks: [
					'sass',
					'cssmin'
				]
			},
			js: {
				files: [
					'js/*js',
					'!js/*.min.js'
				],
				tasks: ['uglify']
			}
		},

		// Generate POT files.
		makepot: {
			options: {
				type: 'wp-theme',
				domainPath: 'languages',
				potHeaders: {
					'report-msgid-bugs-to'  : 'https://github.com/aprea/adoration/issues',
					'language-team'         : 'LANGUAGE <EMAIL@ADDRESS>'
				},
				exclude: [
					'node_modules/.*',  // Exclude node_modules/
					'library/.*',       // Exclude library/
					'admin/inc/cmb/.*'  // Exclude admin/inc/cmb/
				],
			},
			frontend: {
				options: {
					potFilename: 'adoration.pot',
					processPot: function ( pot ) {
						pot.headers['project-id-version'];
						return pot;
					}
				}
			}
		},

	});

	// Load NPM tasks to be used here
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	// Register tasks
	grunt.registerTask( 'default', [
		'css',
		'uglify'
	]);

	grunt.registerTask( 'css', [
		'sass',
		'cssmin'
	]);

	grunt.registerTask( 'dev', [
		'default',
		'makepot'
	]);
};