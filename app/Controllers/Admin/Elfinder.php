<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
class Elfinder extends BaseController
{
	protected $opt_elfinder=[];
    public function __construct()
    {
		$this->opt_elfinder = array(
			'roots' => array(
				// Items volume
				array(
					'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
					'path'          => 'ticofiles',                 // path to files (REQUIRED)
					'URL'           => base_url('ticofiles'), // URL to files (REQUIRED)
					'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
					'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
					'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
					'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'video/mp4'), // Mimetype `image` and `text/plain` allowed to upload
					'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
					'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
				),
				array(
					'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
					'path'          => 'uploads',                 // path to files (REQUIRED)
					'URL'           => base_url('uploads'), // URL to files (REQUIRED)
					'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
					'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
					'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
					'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'video/mp4'), // Mimetype `image` and `text/plain` allowed to upload
					'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
					'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
				),
				// Trash volume
				array(
					'id'            => '1',
					'driver'        => 'Trash',
					'path'          => 'uploads/.trash/',
					'tmbURL'        => 'uploads/.trash/.tmb/',
					'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
					'uploadDeny'    => array('all'),                // Recomend the same settings as the original volume that uses the trash
					'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'), // Same as above
					'uploadOrder'   => array('deny', 'allow'),      // Same as above
					'accessControl' => 'access',                    // Same as above
				),
			)
		);
        $this->data['currentAdminMenu'] = 'user-role';
        $this->data['currentAdminSubMenu'] = 'elfinder';
    }
	
    public function index()
    {
        return view('admin/elfinder/index', $this->data);
    }
	public function explorer()
    {
        return view('admin/elfinder/explorer', $this->data);
    }
	public function connector()
    {
		$connector = new \elFinderConnector(new \elFinder($this->opt_elfinder));
		$connector->run();
    }
}
?>