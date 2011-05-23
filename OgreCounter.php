<?php
/**
 * 男と生まれたからには
 * 誰でも一生のうち一度は夢見る
 * 「地上最強のサクセスカウンター」
 * 
 * オーガカウンタークラス
 * 
 * @author Daisuke Sakata
 * @link http://lab.websegment.net/oc/example/oc.html
 * 
 * @example
 * <pre>
 * $oc = new OgreCounter('/path/to/file.txt');
 * $oc->countUp($_GET['dispcount']);
 * $oc->displayCount();
 * </pre>
 */
class OgreCounter{
	/**
	 * @access private
	 * @var array
	 */
	private $putStatus = array();
	/**
	 * @access private
	 * @var string
	 */
	private $file = '';
	/**
	 * @access public
	 * @see $this->initialize()
	 * @param string $file ローカルファイルパス
	 * @return OgreCounter
	 */
	public function __construct($file){
		$this->file = $file;
		if(!$this->isFile()){
			$this->initialize();
		}
		return $this;
	}
	/**
	 * ファイルが存在しない場合、ファイルを生成
	 * 
	 * @access private
	 */
	private function initialize(){
		if($fp = fopen($this->file, 'w')){
			flock($fp, 2);
			fputs($fp, "0");
			fclose($fp);
		}else{
			$this->putStatus['error'] = 
				'Begin file write mode permission denied.';
		}
	}
	/**
	 * ファイルの存在を確かめる
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isFile(){
		return file_exists($this->file);
	}
	/**
	 * @access public
	 * @see $this->displayCount()
	 * @param int $displayCount 現在画面に表示しているカウンタ。
	 * @return OgreCounter
	 */
	public function countUp($displayCount = null){
		if(!$this->isFile()){
			$this->putStatus['error'] = 'file not found.';
		}else{
			$fp = fopen($this->file, 'r+');
			flock($fp, 2);
			$fileCount = fgets($fp);
			if($displayCount != $fileCount){
				$fileCount++;
				fseek($fp, 0);
				fputs($fp, $fileCount);
			}
			fclose($fp);
			$this->putStatus['ok'] = 'count up successfull.';
			$this->putStatus['counter'] = $fileCount;
		}
		return $this;
	}
	/**
	 * JSON形式のファイルを表示
	 * 
	 * @access public
	 */
	public function displayCount(){
		if(!isset($this->putStatus['error'])){
			foreach($this->putStatus as $key => $value){
				$data[] = sprintf('"%s":"%s"', $key, $value);
			}
		}else{
			$data[] = sprintf('"error":"%s"', $this->putStatus['error']);
		}
		header('Content-Type:text/javascript');
		echo '{' . implode(',', $data) . '}';
	}
}