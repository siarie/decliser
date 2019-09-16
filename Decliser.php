<?php
class Decliser {
	
	public $config;
	public $currentDir= NULL;
	public $files = [];
	public $breadcrumb;

	public function __construct($config) {
		$this->config = $config;
		$this->currentDir = $this->config['root_directory'];
		if (isset($_GET['url'])) {
			$url = $_GET['url'];
			$this->currentDir = $url;
		}
		
		return $this->LoadDir($this->currentDir);
	}
	
	public function LoadDir($path)
	{
		$files = $this->__scanDir($path);
		if (!empty($files)) {
			$files = $this->__ignored($files);

			foreach ($files as $file) {
				$filePath= realpath($this->currentDir.'/'.$file);
				$fileUrl = $this->config['directory_url'];
				
				if ($this->config['root_directory'] != '' && $this->currentDir != '.' && $this->currentDir != './') {
					if (is_dir($filePath)) {
						$fileUrl .= '?url=' . $this->currentDir . '/' . $file;
					}
					else {
						$fileUrl .= $this->currentDir . '/' . $file;
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
				$fileExt = ( is_file($filePath) ) ? $this->__getFileExt( $file ) : 'dir';
				
				$type = (is_dir($filePath)) ? 'directory' : 'file';

				$target = (!is_dir($filePath) && $this->config['open_new_tab'] === TRUE) ? '_blank' : '_self';	

				$fileList[$file] = [
					'name'  => $file,
					'type'  => $type,
					'path'  => $filePath,
					'url'   => $fileUrl,
					'target'=> $target,
					'ext'	=> $fileExt
				];

				
			}
		}
		$this->files = @$fileList;
		$this->breadcrumb = $this->breadcrumb();
	}

	protected function __scanDir($dir){

		if (strstr($dir,'../')) return false;

		if ($dir == '/' || $dir == '') {
			$dir = $this->config['root_directory'];
			$this->currentDir = $dir;
		}
		if (in_array($dir, $this->config['hide_directories'])) return false;
		// if (in_array($stripped, $this->config['hide_directories'])) return;
		// if (! file_exists($dir) || !is_dir($dir)) {
		// 	return false;
		// }
		return scandir($dir);
	}

	protected function __ignored($files){
		foreach ($files as $key => $file) {
			if (is_dir(realpath($file)) && in_array($file, $this->config['hide_directories']))
				unset($files[$key]);

			if ($this->config['hide_dotdir'] === TRUE) {
				if ( is_dir(realpath($file)) && substr($file, 0, 1) == '.' ) {
					unset($files[$key]);
				}
			}

			if (in_array($file, $this->config['hide_files'])) unset($files[$key]);
			
			if ( $this->config['hide_dotfiles'] === TRUE) {
				if ( is_file(realpath($file)) && substr($file, 0, 1) == '.' ) {
					unset($files[$key]);
				}
			}

			if ( ! is_dir( realpath( $file ) ) ) {
				$file_ext = $this->__getFileExt($file);
				if (in_array($file_ext, $this->config['hide_by_ext'])) unset($files[$key]);
			}
		}

		return $files;
	}

	private function breadcrumb(){
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

	protected function __getFileExt($filename)
	{
		return substr( strrchr( $filename,'.' ),1 );
	}
}