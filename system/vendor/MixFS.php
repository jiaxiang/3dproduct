<?php

/**
 * Class for accessing the MogileFS file system
 * Allows creation of classes, retrieval and storage of files, querying
 * existence of a file, etc.
 * from  mediawiki
 */

class MixFSException extends Exception {}
class MixFS {
	
	/**
	 * Constructor
	 * 
	 * TODO
	 */
	public static function factory( $baseset='',$reqinstkey='', $domain = null, $hosts = null, $root = '' )
	{
	    return new MixFS($baseset,$reqinstkey, $domain, $hosts , $root);
	}

    public function __construct($baseset='',$reqinstkey='', $domain = null, $hosts = null, $root = ''){
        $this->baseset = rtrim($baseset,'/').'/';
        $this->reqinstkey = $reqinstkey;
        $this->domain = $domain;
        $this->hosts  = $hosts;
        $this->root   = $root;
        $this->error  = '';
        $this->basepath  = $this->baseset.$this->domain.'/'.$this->reqinstkey.'/';
        return $this->connect()?$this:FALSE;
    }

	function connect()
	{
		if(is_dir($this->baseset)){
		    @$this->_mkpath($this->baseset,$this->domain.'/'.$this->reqinstkey.'/');
		    if(!is_dir($this->basepath)){
		        throw new MixFSException('base path not exists.',404);
		        return FALSE;
		    }
		    return $this->baseset;
		}else{
		    throw new MixFSException('base dir not exists.',404);
		    return FALSE;
		}
	}

	function _buildkey($key) {
		return substr(chunk_split(md5($key),3,'/'),0,-1);
	}


	/**
	 * Save a file to the MogileFS
	 * TODO
	 */
	function putFile( $key, $filename, $class='')
	{

		$curkeypath=$this->_buildkey($key);
		$thispath=$this->basepath.$curkeypath;
		@$this->_mkpath($this->basepath,substr($curkeypath,0,-2));
		if(@copy($filename,$thispath))
		{
			return $thispath;
		}
		else {
		return false;
		}
	}

    function putFileData($key,$filecontent, $class=''){
        $curkeypath=$this->_buildkey($key);
        $thispath=$this->basepath.$curkeypath;

        @$this->_mkpath($this->basepath,substr($curkeypath,0,-2));
        return @file_put_contents($thispath,$filecontent);
    }

	/**
	 * Get a file from the file service and return it as a string
	 * TODO
	 */
	function getFileData( $key )
	{
		$curkeypath=$this->_buildkey($key);
		$thispath=$this->basepath.$curkeypath;
		return @file_get_contents($thispath);
	}

	/** 
	 * Delete a file from system
	 */
	function delete ( $key )
	{
		$curkeypath=$this->_buildkey($key);
		$thispath=$this->basepath.$curkeypath;
		if(@unlink($thispath)){
			// 尝试删除子路径
			@$this->_rmpath($this->basepath,substr($curkeypath,0,-2));
			return true;
		}else{
			return false;
		}
	}


	/**
	 * 函数说明: 构建目录树
	 * 
	 * @author 樊振兴(nick)<nickfan81@gmail.com> 
	 * @history 2006-08-25 樊振兴 添加了本方法
	 * @param string baseDir 基路径需带尾部/
	 * @param string path 需要添加的子路径串（如: subdir/subsubdir/subsubsubdir/）
	 * @param string chmod 默认chmod为0777
	 * @return void 
	 */
	function _mkpath($baseDir, $path, $chmod = null) {
				$ch = !is_null($chmod)?$chmod:0777;
				$parts = explode("/", $path);
				$subDir = "";

				foreach($parts as $part){
								if(!is_dir($baseDir . $subDir . $part))
												mkdir($baseDir . $subDir . $part, $ch);
												@chmod($baseDir . $subDir . $part, $ch);
								$subDir .= $part . "/";
				}
	}


	/**
	 * 函数说明: 构建目录树
	 * 
	 * @author 樊振兴(nick)<nickfan81@gmail.com> 
	 * @history 2006-08-25 樊振兴 添加了本方法
	 * @param string baseDir 基路径需带尾部/
	 * @param string path 需要添加的子路径串（如: subdir/subsubdir/subsubsubdir/）
	 * @param string chmod 默认chmod为0777
	 * @return void 
	 */
	function _rmpath($baseDir, $path){
		$path=rtrim($path,'/');
		$baselen=strlen($baseDir);
		$thispath=$baseDir.$path;
		$rmstat=true;
		while($rmstat==true && strlen($thispath)>$baselen){
			$rmstat=@rmdir($thispath);
			$thispath=substr($thispath,0,strrpos($thispath,'/'));
		}
	}

	/**
	 * Get an array of paths
	 */
	function getPaths( $key )
	{
		return array($this->basepath.$this->_buildkey($key),);
	}

}
####
####
####         T E S T 
####
####
#$mfs = MogileFS::NewMogileFS( 'testdomain', array('192.168.1.22:6001'));
#$mfs->getFileDataAndSend( 'abc' );
#if($mfs->saveFile( 'abc', 'testclass', 'abc.txt' ))
#	echo "ok";
#else
#	echo "error";
#
