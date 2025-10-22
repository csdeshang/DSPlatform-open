<?php

namespace AlibabaCloud\Dara;

use AlibabaCloud\Dara\Date;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Utils;
use AlibabaCloud\Dara\Exception\DaraException;

class File
{
    private $_path;
    private $_stat = null;
    private $_fd = false;
    private $_position = 0;

    public function __construct($path) {
        $this->_path = $path;
    }

    public function path() {
        return $this->_path;
    }

    public function createTime() {
        if (!$this->_stat) {
            $this->_stat = stat($this->_path);
        }
        return new Date($this->_stat['ctime']);
    }

    public function modifyTime() {
        if (!$this->_stat) {
            $this->_stat = stat($this->_path);
        }
        return new Date($this->_stat['mtime']);
    }

    public function length() {
        if (!$this->_stat) {
            $this->_stat = stat($this->_path);
        }
        return $this->_stat['size'];
    }

    public function read($size) {
        if (!$this->_fd) {
            $this->_fd = fopen($this->_path, 'a+');
        }
        $position = ftell($this->_fd);
        $position = ftell($this->_fd);
        fseek($this->_fd, $this->_position);
        $data = fread($this->_fd, $size);
        $bytesRead = strlen($data);
        if (!$bytesRead) {
            return null;
        }
        $this->_position += $bytesRead;
        return $data;
    }

    public function write($data) {
        if (!$this->_fd) {
            $this->_fd = fopen($this->_path, 'a+');
        }
        
        fwrite($this->_fd, $data);
        fflush($this->_fd);
        clearstatcache();
        $this->_stat = stat($this->_path);
    }

    public function close() {
        if ($this->_fd) {
            fclose($this->_fd);
            $this->_fd = false;
        }
    }

    /**
     *
     * @param string $path 
     * @return bool
     */
    public static function exists($path) {
        return file_exists($path);
    }

    /**
     *
     * @param string $path 
     * @return Stream
     */
    public static function createReadStream($path) {
        try {
            $stream = Utils::streamFor(fopen($path, 'r'));
            return $stream;
        } catch (Exception $e) {
            throw new DaraException([], "Unable to open file for reading: " . $e->getMessage());
        }
    }

    /**
     *
     * @param string $path
     * @return Stream
     */
    public static function createWriteStream($path) {
        try {
            $stream = Utils::streamFor(fopen($path, 'a+'));
            return $stream;
        } catch (Exception $e) {
            throw new DaraException([], "Unable to open file for writing: " . $e->getMessage());
        }
    }
}
