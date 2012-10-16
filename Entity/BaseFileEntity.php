<?php
namespace Iphp\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseFileEntity
{
    /**
     * @var string $oath
     */
    protected $path;


    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;


    abstract function getFilesPath();


    public function setPath($path)
    {
       // $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getUrl()
    {
        return null === $this->path ? null : $this->getFilesPath() . $this->path;
    }


    protected function getUploadDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web' . $this->getFilesPath();
    }


    protected function preUpload()
    {
        if (null === $this->file) return;
        $this->path = uniqid() . '.' . $this->file->guessExtension();

    }

    protected function postUpload()
    {
        if ($this->file === null) return;
        $this->file->move($this->getUploadDir(), $this->path);
        $this->file = null;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }


    function preUpdate()
    {
        $this->preUpload();
    }

    function postUpdate()
    {
        $this->postUpload();
    }
}