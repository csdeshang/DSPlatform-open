<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi\Models;
use AlibabaCloud\Dara\Model;
use AlibabaCloud\Credentials\Credential;
use Darabonba\OpenApi\Models\GlobalParameters;
use AlibabaCloud\Dara\RetryPolicy\RetryOptions;
/**
 * @remarks
 * Model for initing client
 */
class Config extends Model {
  /**
   * @var string
   */
  public $accessKeyId;
  /**
   * @var string
   */
  public $accessKeySecret;
  /**
   * @var string
   */
  public $securityToken;
  /**
   * @var string
   */
  public $bearerToken;
  /**
   * @var string
   */
  public $protocol;
  /**
   * @var string
   */
  public $method;
  /**
   * @var string
   */
  public $regionId;
  /**
   * @var int
   */
  public $readTimeout;
  /**
   * @var int
   */
  public $connectTimeout;
  /**
   * @var string
   */
  public $httpProxy;
  /**
   * @var string
   */
  public $httpsProxy;
  /**
   * @var Credential
   */
  public $credential;
  /**
   * @var string
   */
  public $endpoint;
  /**
   * @var string
   */
  public $noProxy;
  /**
   * @var int
   */
  public $maxIdleConns;
  /**
   * @var string
   */
  public $network;
  /**
   * @var string
   */
  public $userAgent;
  /**
   * @var string
   */
  public $suffix;
  /**
   * @var string
   */
  public $socks5Proxy;
  /**
   * @var string
   */
  public $socks5NetWork;
  /**
   * @var string
   */
  public $endpointType;
  /**
   * @var string
   */
  public $openPlatformEndpoint;
  /**
   * @var string
   */
  public $type;
  /**
   * @var string
   */
  public $signatureVersion;
  /**
   * @var string
   */
  public $signatureAlgorithm;
  /**
   * @var GlobalParameters
   */
  public $globalParameters;
  /**
   * @var string
   */
  public $key;
  /**
   * @var string
   */
  public $cert;
  /**
   * @var string
   */
  public $ca;
  /**
   * @var boolean
   */
  public $disableHttp2;
  /**
   * @var RetryOptions
   */
  public $retryOptions;
  protected $_name = [
      'accessKeyId' => 'accessKeyId',
      'accessKeySecret' => 'accessKeySecret',
      'securityToken' => 'securityToken',
      'bearerToken' => 'bearerToken',
      'protocol' => 'protocol',
      'method' => 'method',
      'regionId' => 'regionId',
      'readTimeout' => 'readTimeout',
      'connectTimeout' => 'connectTimeout',
      'httpProxy' => 'httpProxy',
      'httpsProxy' => 'httpsProxy',
      'credential' => 'credential',
      'endpoint' => 'endpoint',
      'noProxy' => 'noProxy',
      'maxIdleConns' => 'maxIdleConns',
      'network' => 'network',
      'userAgent' => 'userAgent',
      'suffix' => 'suffix',
      'socks5Proxy' => 'socks5Proxy',
      'socks5NetWork' => 'socks5NetWork',
      'endpointType' => 'endpointType',
      'openPlatformEndpoint' => 'openPlatformEndpoint',
      'type' => 'type',
      'signatureVersion' => 'signatureVersion',
      'signatureAlgorithm' => 'signatureAlgorithm',
      'globalParameters' => 'globalParameters',
      'key' => 'key',
      'cert' => 'cert',
      'ca' => 'ca',
      'disableHttp2' => 'disableHttp2',
      'retryOptions' => 'retryOptions',
  ];

  public function validate()
  {
    if(null !== $this->credential) {
      $this->credential->validate();
    }
    if(null !== $this->globalParameters) {
      $this->globalParameters->validate();
    }
    parent::validate();
  }

  public function toArray($noStream = false)
  {
    $res = [];
    if (null !== $this->accessKeyId) {
      $res['accessKeyId'] = $this->accessKeyId;
    }

    if (null !== $this->accessKeySecret) {
      $res['accessKeySecret'] = $this->accessKeySecret;
    }

    if (null !== $this->securityToken) {
      $res['securityToken'] = $this->securityToken;
    }

    if (null !== $this->bearerToken) {
      $res['bearerToken'] = $this->bearerToken;
    }

    if (null !== $this->protocol) {
      $res['protocol'] = $this->protocol;
    }

    if (null !== $this->method) {
      $res['method'] = $this->method;
    }

    if (null !== $this->regionId) {
      $res['regionId'] = $this->regionId;
    }

    if (null !== $this->readTimeout) {
      $res['readTimeout'] = $this->readTimeout;
    }

    if (null !== $this->connectTimeout) {
      $res['connectTimeout'] = $this->connectTimeout;
    }

    if (null !== $this->httpProxy) {
      $res['httpProxy'] = $this->httpProxy;
    }

    if (null !== $this->httpsProxy) {
      $res['httpsProxy'] = $this->httpsProxy;
    }

    if (null !== $this->credential) {
      $res['credential'] = $this->credential;
    }

    if (null !== $this->endpoint) {
      $res['endpoint'] = $this->endpoint;
    }

    if (null !== $this->noProxy) {
      $res['noProxy'] = $this->noProxy;
    }

    if (null !== $this->maxIdleConns) {
      $res['maxIdleConns'] = $this->maxIdleConns;
    }

    if (null !== $this->network) {
      $res['network'] = $this->network;
    }

    if (null !== $this->userAgent) {
      $res['userAgent'] = $this->userAgent;
    }

    if (null !== $this->suffix) {
      $res['suffix'] = $this->suffix;
    }

    if (null !== $this->socks5Proxy) {
      $res['socks5Proxy'] = $this->socks5Proxy;
    }

    if (null !== $this->socks5NetWork) {
      $res['socks5NetWork'] = $this->socks5NetWork;
    }

    if (null !== $this->endpointType) {
      $res['endpointType'] = $this->endpointType;
    }

    if (null !== $this->openPlatformEndpoint) {
      $res['openPlatformEndpoint'] = $this->openPlatformEndpoint;
    }

    if (null !== $this->type) {
      $res['type'] = $this->type;
    }

    if (null !== $this->signatureVersion) {
      $res['signatureVersion'] = $this->signatureVersion;
    }

    if (null !== $this->signatureAlgorithm) {
      $res['signatureAlgorithm'] = $this->signatureAlgorithm;
    }

    if (null !== $this->globalParameters) {
      $res['globalParameters'] = null !== $this->globalParameters ? $this->globalParameters->toArray($noStream) : $this->globalParameters;
    }

    if (null !== $this->key) {
      $res['key'] = $this->key;
    }

    if (null !== $this->cert) {
      $res['cert'] = $this->cert;
    }

    if (null !== $this->ca) {
      $res['ca'] = $this->ca;
    }

    if (null !== $this->disableHttp2) {
      $res['disableHttp2'] = $this->disableHttp2;
    }

    if (null !== $this->retryOptions) {
      $res['retryOptions'] = null !== $this->retryOptions ? $this->retryOptions->toArray($noStream) : $this->retryOptions;
    }

    return $res;
  }

  public function toMap($noStream = false)
  {
    return $this->toArray($noStream);
  }

  public static function fromMap($map = [])
  {
    $model = new self();
    if (isset($map['accessKeyId'])) {
      $model->accessKeyId = $map['accessKeyId'];
    }

    if (isset($map['accessKeySecret'])) {
      $model->accessKeySecret = $map['accessKeySecret'];
    }

    if (isset($map['securityToken'])) {
      $model->securityToken = $map['securityToken'];
    }

    if (isset($map['bearerToken'])) {
      $model->bearerToken = $map['bearerToken'];
    }

    if (isset($map['protocol'])) {
      $model->protocol = $map['protocol'];
    }

    if (isset($map['method'])) {
      $model->method = $map['method'];
    }

    if (isset($map['regionId'])) {
      $model->regionId = $map['regionId'];
    }

    if (isset($map['readTimeout'])) {
      $model->readTimeout = $map['readTimeout'];
    }

    if (isset($map['connectTimeout'])) {
      $model->connectTimeout = $map['connectTimeout'];
    }

    if (isset($map['httpProxy'])) {
      $model->httpProxy = $map['httpProxy'];
    }

    if (isset($map['httpsProxy'])) {
      $model->httpsProxy = $map['httpsProxy'];
    }

    if (isset($map['credential'])) {
      $model->credential = $map['credential'];
    }

    if (isset($map['endpoint'])) {
      $model->endpoint = $map['endpoint'];
    }

    if (isset($map['noProxy'])) {
      $model->noProxy = $map['noProxy'];
    }

    if (isset($map['maxIdleConns'])) {
      $model->maxIdleConns = $map['maxIdleConns'];
    }

    if (isset($map['network'])) {
      $model->network = $map['network'];
    }

    if (isset($map['userAgent'])) {
      $model->userAgent = $map['userAgent'];
    }

    if (isset($map['suffix'])) {
      $model->suffix = $map['suffix'];
    }

    if (isset($map['socks5Proxy'])) {
      $model->socks5Proxy = $map['socks5Proxy'];
    }

    if (isset($map['socks5NetWork'])) {
      $model->socks5NetWork = $map['socks5NetWork'];
    }

    if (isset($map['endpointType'])) {
      $model->endpointType = $map['endpointType'];
    }

    if (isset($map['openPlatformEndpoint'])) {
      $model->openPlatformEndpoint = $map['openPlatformEndpoint'];
    }

    if (isset($map['type'])) {
      $model->type = $map['type'];
    }

    if (isset($map['signatureVersion'])) {
      $model->signatureVersion = $map['signatureVersion'];
    }

    if (isset($map['signatureAlgorithm'])) {
      $model->signatureAlgorithm = $map['signatureAlgorithm'];
    }

    if (isset($map['globalParameters'])) {
      $model->globalParameters = GlobalParameters::fromMap($map['globalParameters']);
    }

    if (isset($map['key'])) {
      $model->key = $map['key'];
    }

    if (isset($map['cert'])) {
      $model->cert = $map['cert'];
    }

    if (isset($map['ca'])) {
      $model->ca = $map['ca'];
    }

    if (isset($map['disableHttp2'])) {
      $model->disableHttp2 = $map['disableHttp2'];
    }

    if (isset($map['retryOptions'])) {
      $model->retryOptions = RetryOptions::fromMap($map['retryOptions']);
    }

    return $model;
  }


}

