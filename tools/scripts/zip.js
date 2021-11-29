const fs = require( 'fs' );
const archiver = require( 'archiver' );
const log = require( 'log-beautify' );
const config = require( '../config' );

deployZip();

function deployZip() {
	const output = fs.createWriteStream( config.rootPath + '/' + config.name + '.zip' );
	const archive = archiver( 'zip', {});

	output.on( 'close', function() {
		console.log( '\n' );
		log.success_( '"' + config.name + '.zip" deployed to the root folder.' );
		console.log( '\n' )
	});

	archive.on( 'error', function( err ) {
		throw err;
	});

	archive.pipe( output );

	let directories = config.directories;
	for ( let i = 0; i < directories.length; i++ ) {
		archive.directory( config.rootPath + '/' + directories[i], config.name + '/' + directories[i], null );
	}

	let files = config.files;
	for ( let i = 0; i < files.length; i++ ) {
		archive.file( config.rootPath + '/' + files[i], { name: config.name + '/' + files[i] });
	}

	archive.finalize();
}
