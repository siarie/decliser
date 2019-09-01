<?php
class Listmydir {
	
	public $rootDir   = '.';
	public $currentDir= NULL;

	public $directoryUrl    = 'http://cdn.local.dep/';

	public $openNewTab  = TRUE;
		// File names to block from showing in the directory listing
	public $ignoreFiles = array(
		'.htaccess',
		'.DS_Store',
		'Thumbs.db',
		'index.php',
		'Listmydir.php'
	);
		// File extensions to block from showing in the directory listing
	public $ignoredFileExtensions = array(
		'ini',
	);
	
	// Directories to block from showing in the directory listing
	public $ignoredDirectories = array(
	);

	private $__fileList = [];

	public function index()
	{
		$this->currentDir = $this->rootDir;

		if (isset($_GET['url'])) {
			$url = $_GET['url'];
			$this->currentDir = $url.'/';
		}

		return $this->LoadDir($this->currentDir);
	}
	
	public function LoadDir($path)
	{
		$files = $this->_scanDir($path);
		if (!empty($files)) {
			$files = $this->_ignored($files);

			foreach ($files as $file) {
				$filePath= realpath($this->currentDir.'/'.$file);
				$fileUrl = $this->directoryUrl;
				
				if ($this->rootDir != '' && $this->currentDir != '.') {
					if (is_dir($filePath)) {
						$fileUrl .= '?url=' . $this->currentDir . $file;
					}
					else {
						$fileUrl .= $this->currentDir . $file;
					}
				}
				else {
					if (is_dir($filePath)) {
						$fileUrl .= '?url=' . $file;
					}
					else{
						$fileUrl .= $file;
					}
				}

				$type = (is_dir($filePath)) ? 'directory' : 'file';
				
				$this->__fileList[$file] = [
					'name'  => $file,
					'type'  => $type,
					'path'  => $filePath,
					'url'   => $fileUrl,
					'target'=> '_self'
				];
				
				if (!is_dir($filePath) && $this->openNewTab === TRUE) {
					$this->__fileList[$file]['target'] = '_blank';
				}
			}
		}
		$data = [
			'curentPath'    => $this->currentDir,
			'breadcrumb'    => $this->_breadcrumb(),
			'files'         => $this->__fileList
		];

		return $data;
	}

	function _scanDir($dir){

		if (strstr($dir,'../')) return false;

		if ($dir == '/' || $dir == '') {
			$dir = $this->rootDir;
			$this->currentDir = $dir;
		}
		if (in_array($dir, $this->ignoredDirectories)) {
			return false;
		}
		return scandir($dir);
	}

	private function _ignored($files){
		$this->ignoredDirectories[] = '.';
		$this->ignoredDirectories[] = '..';
		foreach ($files as $key => $file) {
			if (in_array($file, $this->ignoredDirectories)) {
				unset($files[$key]);
			}
			if (in_array($file, $this->ignoreFiles)) unset($files[$key]);
		}

		return $files;
	}

	private function _breadcrumb(){
		$dir    = $this->currentDir;
		$breadcrumb = [];
		$breadcrumb['./'] = 'home';
		if (substr_count($dir,'/') >= 0) {
			$items = explode('/', $dir);
			$items = array_filter($items);
			$path = '';
			foreach ($items as $item) {
				if ($item == '.' || $item == '..') {
					continue;
				}

				$path .= rawurlencode($item) . '/';
				$breadcrumb[$path] = $item;
			}
			$breadcrumb = array_filter($breadcrumb);
			return $breadcrumb;
		}
	}
}

?>