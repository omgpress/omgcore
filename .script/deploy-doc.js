const dotenv = require( 'dotenv' ).config();
const FtpDeploy = require( 'ftp-deploy' );
const path = require( 'path' );
const ftp = new FtpDeploy();
const log = require( 'log-beautify' );

const directories = [
	'classes',
	'css',
	'files',
	'graphs',
	'indices',
	'js',
	'namespaces',
	'packages',
	'reports'
];
const files = [
	'index.html'
];

ftp
	.deploy( {
		host: process.env.DEV_FTP_HOST,
		port: 21,
		user: process.env.DEV_FTP_NAME,
		password: process.env.DEV_FTP_PWD,
		localRoot: path.dirname(__dirname),
		remoteRoot: process.env.DEV_FTP_DIR,
		include: directories.map(
			( dir ) => 'doc/api/' + dir + '/**'
		).concat(files.map(
			( file ) => 'doc/api/' + file
		)),
		exclude: [ 'node_modules/**', 'src/**' ],
		deleteRemote: false,
		forcePasv: true,
		sftp: false,
	} )
	.then( ( res ) => log.success_( 'Doc deployment is complete: ' + res ) )
	.catch( ( err ) => log.error_( err ) );
