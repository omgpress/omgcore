const fs = require( 'fs' );
const glob = require( 'glob' );
const log = require( 'log-beautify' );
const config = require( '../config' );
const state = require( './state' );

const PHPClassesRoot = 'includes';

setPHPClassName();

function setPHPClassName() {
	glob( config.rootPath + '/' + PHPClassesRoot + '/**/*.php', function( err, paths ) {
		if ( err ) {
			console.log( err );

		} else {
			const currentPHPClassName = state.currentPHPClassName;
			const newPHPClassName = 'WP_Titan_' + config.version.split( '.' ).join( '_' );

			paths.push( config.rootPath + '/index.php' );
			paths.push( config.rootPath + '/functions.php' );
			paths.push( config.rootPath + '/README.md' );

			paths.forEach( function( path ) {
				replaceFileText( path, currentPHPClassName, newPHPClassName, replaceDashClassName );
			});

			replaceFileText( config.rootPath + '/composer.json', currentPHPClassName, newPHPClassName, setPackageVersion );

			state.currentPHPClassName = newPHPClassName;

			fs.writeFile( './state.json', JSON.stringify( state, null, 2 ), 'utf8', function( err ) {
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

	return JSON.stringify( json, null, 2 );
}

function replaceDashClassName( data, needle, replace ) {
	return data.replace(
		needle.replace( /_/g, '-' ),
		replace.replace( /_/g, '-' )
	);
}

log.success_( 'Version changed to ' + config.version );
