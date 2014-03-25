<?php

namespace Sopinet\Bundle\UploadMagicBundle\Model;

/**
 * UploadMagic trait.
 *
 * Should be used inside entity, that needs to be one UploadMagic.
 */
trait UploadMagic
{
	/**
	 * @ORM\Column(name="title", type="string", length=255, nullable=true)
	 */
	protected $title;	
	
	/**
	 * @ORM\Column(name="path", type="string", length=255, nullable=true)
	 */
	protected $path;
	
	/**
	 * @ORM\Column(name="mimeType", type="string", length=50, nullable=true)
	 */
	protected $mimeType;
	
	/**
	 * @ORM\Column(name="dir", type="string", length=50, nullable=true)
	 */
	protected $dir;
	
	protected $filesize;
	
	protected $url;
	
	protected $pathonlyname;
	
	/**
	 * Set path
	 *
	 * @param string $path
	 *
	 * @return Image
	 */
	public function setPath($path)
	{
		$this->path = $path;
	
		return $this;
	}
	
	/**
	 * Get path
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}
	
	public function getPathOnlyName()
	{
		$temp = explode("/", $this->getPath());
		return $temp[count($temp) - 1];
	}
	
	public function setUrl($url){
		$this->url = $url;
	}
	
	/*public function initUrl($host = null){
		$url = $host . "uploads/" . $this->dir . "/" . $this->getPathOnlyName();
		$this->setUrl($url);
	}*/
	
	public function getUrl(){
		return $this->url;
	}
	
	public function getPathRelative(){
		return "uploads/" . $this->dir . "/" . $this->getPathOnlyName();
	}
	
	public function setDir($dir){
		$this->dir = $dir;
	}
	
	public function getDir(){
		return $this->dir;
	}

	public function getFilesize(){
		if ($this->getPathOnlyName() == "") $this->filesize = 0;
		else if (!file_exists($this->path)) $this->filesize = 0;
		else $this->filesize = filesize($this->path);
		 
		return $this->filesize;
	}
	
	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return File
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	
		return $this;
	}
	
	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	
	public function __toString() {
		return $this->getPathOnlyName();
	}
}

