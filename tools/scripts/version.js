const fs = require( 'fs' );
const glob = require( 'glob' );
const log = require( 'log-beautify' );
const config = require( '../config' );
const cachePath = '../cache';
const cache = require( cachePath );
const PHPClassesRoot = 'includes';
const newPHPClassName = 'WP_Titan_' + config.version.split( '.' ).join( '_' );

setPHPClassName();

function setPHPClassName() {
	glob( config.rootPath + '/' + PHPClassesRoot + '/**/*.php', function( err, paths ) {
		if ( err ) {
			console.log( err );

		} else {
			paths.push( config.rootPath + '/index.php' );
			paths.push( config.rootPath + '/README.md' );

			paths.forEach( function( path ) {
				replaceFileText( path, cache.currentPHPClassName, newPHPClassName, replaceDashClassName );
			});

			replaceFileText( config.rootPath + '/composer.json', cache.currentPHPClassName, newPHPClassName, setPackageVersion );

			cache.currentPHPClassName = newPHPClassName;

			fs.writeFile( cachePath + '.json', JSON.stringify( cache, null, 2 ), 'utf8', function( err ) {
				if ( err ) {
					return console.error( err );
				}
			});
		}
	});
}

function replaceFileText( path, needle, replace, modifyResult = null ) {
	fs.readFile( path, 'utf8', function( err, data ) {
		if ( err ) {
			return console.error( err );
		}

		let result = data.replace( new RegExp( needle, 'g' ), replace );

		if ( modifyResult ) {
			result = modifyResult( result, needle, replace );
		}

		fs.writeFile( path, result, 'utf8', function( err ) {
			if ( err ) {
				return console.error( err );
			}
		});
	});
}

function setPackageVersion( data ) {
	const json = JSON.parse( data );
	json.version = config.version;
	json.config['autoloader-suffix'] = '_' + newPHPClassName;

	return JSON.stringify( json, null, 2 );
}

function replaceDashClassName( data, needle, replace ) {
	return data.replace(
		new RegExp( needle.replace( /_/g, '-' ), 'g' ),
		replace.replace( /_/g, '-' )
	);
}

log.success_( 'Version changed to ' + config.version );
