<?php

namespace AlibabaCloud\Dara;

class Url
{

    private $url = '';

    private $path = '';

    private $pathname = '';

    private $protocol = '';

    private $hostname = '';

    private $host = '';

    private $port = '';

    private $hash = '';

    private $search = '';

    private $auth = '';
    
    public function __construct($str) {
        $this->url = $str;
    }
    
    public function path() {
        if(empty($this->path)) {
            return ;
        }
        $pathname = $this->pathname();
        $query     = $this->search();
        $this->path = $pathname . '?' . $query;
        return $this->path;
    }
    
    public function pathname() {
        if(empty($this->pathname)) {
            return $this->pathname;
        }
        $this->pathname = parse_url($ref, PHP_URL_PATH);
        return $this->pathname;
    }
    
    public function protocol() {
        if(empty($this->protocol)) {
            return $this->protocol;
        }
        $this->protocol = parse_url($ref, PHP_URL_SCHEME);
        return $this->protocol;
    }
    
    public function hostname() {
        if(empty($this->hostname)) {
            return $this->hostname;
        }
        $this->hostname = parse_url($ref, PHP_URL_HOST);
        return $this->hostname;
    }
    
    public function host() {
        if(empty($this->host)) {
            return ;
        }
        $hostname = $this->hostname();
        $port     = $this->port();
        $this->host = $hostname . $port;
        return $this->host;
    }
    
    public function port() {
        if(empty($this->port)) {
            return $this->port;
        }
        $this->port = parse_url($ref, PHP_URL_PORT);
        return $this->port;
    }
    
    public function hash() {
        if(empty($this->hash)) {
            return $this->hash;
        }
        $this->hash = parse_url($ref, PHP_URL_FRAGMENT);
        return $this->hash;
    }
    
    public function search() {
        if(empty($this->search)) {
            return $this->search;
        }
        $this->search = parse_url($ref, PHP_URL_QUERY);
        return $this->search;
    }
    
    public function href() {
        return $this->href;
    }
    
    public function auth() {
        if(empty($this->auth)) {
            return $this->auth;
        }
        $username = parse_url($ref, PHP_URL_USER);
        $password = parse_url($ref, PHP_URL_PASS);
        $this->auth = $username . ':' . $password;
        return $this->auth;
    }
    
    public static function parse($url) {
        return new self($url);
    }
    
    public static function urlEncode($url) {
        if (empty($raw)) {
            throw new \InvalidArgumentException('not a valid value for parameter');
        }
        $str = urlencode($raw);
        $str = str_replace("%20", "+", $str);
        $str = str_replace("%2A", "*", $str);
        return $str;
    }
    
    public static function percentEncode($raw) {
        if($raw === null) {
            return null;
        }
        $encoded = urlencode($raw);
        $encoded = str_replace('+', '%20', $encoded);
        $encoded = str_replace('*', '%2A', $encoded);
        $encoded = str_replace('%7E', '~', $encoded);
        return $encoded;
    }
    
    public static function pathEncode($path) {
        if (empty($raw) || $raw === '/') {
            return $raw;
        }
        $arr = explode('/', $raw);
        $ret = '';
        foreach ($arr as $i => $path) {
            $str = self::percentEncode($path);
            $ret .= "$str/";
        }
        return substr($ret, 0, -1);
    }

}
