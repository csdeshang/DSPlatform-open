<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi;
use AlibabaCloud\Dara\Dara;
use AlibabaCloud\Credentials\Credential;
use Darabonba\GatewaySpi\Client;
use Darabonba\OpenApi\Models\GlobalParameters;
use AlibabaCloud\Dara\RetryPolicy\RetryOptions;
use Darabonba\OpenApi\Exceptions\ClientException;
use AlibabaCloud\Credentials\Credential\Config;
use Darabonba\OpenApi\Models\OpenApiRequest;
use AlibabaCloud\Dara\Models\RuntimeOptions;
use AlibabaCloud\Dara\RetryPolicy\RetryPolicyContext;
use AlibabaCloud\Dara\Request;
use Darabonba\OpenApi\Utils;
use AlibabaCloud\Dara\Util\FormUtil;
use AlibabaCloud\Dara\Util\StreamUtil;
use Darabonba\OpenApi\Exceptions\ThrottlingException;
use Darabonba\OpenApi\Exceptions\ServerException;
use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\Exception\DaraUnableRetryException;
use Darabonba\OpenApi\Models\Params;
use AlibabaCloud\Dara\Util\BytesUtil;
use AlibabaCloud\Dara\Util\StringUtil;
use AlibabaCloud\Dara\Util\XML;
use Darabonba\GatewaySpi\Models\InterceptorContext\configuration;
use Darabonba\GatewaySpi\Models\InterceptorContext;
use Darabonba\GatewaySpi\Models\AttributeMap;
use Darabonba\GatewaySpi\Models\InterceptorContext\response;
use Darabonba\OpenApi\Models\SSEResponse;
/**
 * @remarks
 * This is for OpenApi SDK
 */
class OpenApiClient {
  /**
   * @var string
   */
  protected $_endpoint;

  /**
   * @var string
   */
  protected $_regionId;

  /**
   * @var string
   */
  protected $_protocol;

  /**
   * @var string
   */
  protected $_method;

  /**
   * @var string
   */
  protected $_userAgent;

  /**
   * @var string
   */
  protected $_endpointRule;

  /**
   * @var string[]
   */
  protected $_endpointMap;

  /**
   * @var string
   */
  protected $_suffix;

  /**
   * @var int
   */
  protected $_readTimeout;

  /**
   * @var int
   */
  protected $_connectTimeout;

  /**
   * @var string
   */
  protected $_httpProxy;

  /**
   * @var string
   */
  protected $_httpsProxy;

  /**
   * @var string
   */
  protected $_socks5Proxy;

  /**
   * @var string
   */
  protected $_socks5NetWork;

  /**
   * @var string
   */
  protected $_noProxy;

  /**
   * @var string
   */
  protected $_network;

  /**
   * @var string
   */
  protected $_productId;

  /**
   * @var int
   */
  protected $_maxIdleConns;

  /**
   * @var string
   */
  protected $_endpointType;

  /**
   * @var string
   */
  protected $_openPlatformEndpoint;

  /**
   * @var Credential
   */
  protected $_credential;

  /**
   * @var string
   */
  protected $_signatureVersion;

  /**
   * @var string
   */
  protected $_signatureAlgorithm;

  /**
   * @var string[]
   */
  protected $_headers;

  /**
   * @var Client
   */
  protected $_spi;

  /**
   * @var GlobalParameters
   */
  protected $_globalParameters;

  /**
   * @var string
   */
  protected $_key;

  /**
   * @var string
   */
  protected $_cert;

  /**
   * @var string
   */
  protected $_ca;

  /**
   * @var boolean
   */
  protected $_disableHttp2;

  /**
   * @var RetryOptions
   */
  protected $_retryOptions;


  /**
   * @remarks
   * Init client with Config
   * 
   * @param config - config contains the necessary information to create a client
   */
  public function __construct($config)
  {
    if (is_null($config)) {
      throw new ClientException([
        'code' => 'ParameterMissing',
        'message' => '\'config\' can not be unset',
      ]);
    }

    if ((!is_null($config->accessKeyId) && $config->accessKeyId != '') && (!is_null($config->accessKeySecret) && $config->accessKeySecret != '')) {
      if (!is_null($config->securityToken) && $config->securityToken != '') {
        $config->type = 'sts';
      } else {
        $config->type = 'access_key';
      }

      $credentialConfig = new Config([
        'accessKeyId' => $config->accessKeyId,
        'type' => $config->type,
        'accessKeySecret' => $config->accessKeySecret,
      ]);
      $credentialConfig->securityToken = $config->securityToken;
      $this->_credential = new Credential($credentialConfig);
    } else if (!is_null($config->bearerToken) && $config->bearerToken != '') {
      $cc = new Config([
        'type' => 'bearer',
        'bearerToken' => $config->bearerToken,
      ]);
      $this->_credential = new Credential($cc);
    } else if (!is_null($config->credential)) {
      $this->_credential = $config->credential;
    }

    $this->_endpoint = $config->endpoint;
    $this->_endpointType = $config->endpointType;
    $this->_network = $config->network;
    $this->_suffix = $config->suffix;
    $this->_protocol = $config->protocol;
    $this->_method = $config->method;
    $this->_regionId = $config->regionId;
    $this->_userAgent = $config->userAgent;
    $this->_readTimeout = $config->readTimeout;
    $this->_connectTimeout = $config->connectTimeout;
    $this->_httpProxy = $config->httpProxy;
    $this->_httpsProxy = $config->httpsProxy;
    $this->_noProxy = $config->noProxy;
    $this->_socks5Proxy = $config->socks5Proxy;
    $this->_socks5NetWork = $config->socks5NetWork;
    $this->_maxIdleConns = $config->maxIdleConns;
    $this->_signatureVersion = $config->signatureVersion;
    $this->_signatureAlgorithm = $config->signatureAlgorithm;
    $this->_globalParameters = $config->globalParameters;
    $this->_key = $config->key;
    $this->_cert = $config->cert;
    $this->_ca = $config->ca;
    $this->_disableHttp2 = $config->disableHttp2;
    $this->_retryOptions = $config->retryOptions;
  }

  /**
   * @remarks
   * Encapsulate the request and invoke the network
   * 
   * @param action - api name
   * @param version - product version
   * @param protocol - http or https
   * @param method - e.g. GET
   * @param authType - authorization type e.g. AK
   * @param bodyType - response body type e.g. String
   * @param request - object of OpenApiRequest
   * @param runtime - which controls some details of call api, such as retry times
   * @returns the response
   * @param string $action
   * @param string $version
   * @param string $protocol
   * @param string $method
   * @param string $authType
   * @param string $bodyType
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function doRPCRequest($action, $version, $protocol, $method, $authType, $bodyType, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        $_request->protocol = ''.($this->_protocol ? $this->_protocol : $protocol);
        $_request->method = $method;
        $_request->pathname = '/';
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $_request->query = Dara::merge([
          'Action' => $action,
          'Format' => 'json',
          'Version' => $version,
          'Timestamp' => Utils::getTimestamp(),
          'SignatureNonce' => Utils::getNonce(),
        ], $globalQueries, $extendsQueries, $request->query);
        $headers = $this->getRpcHeaders();
        if (is_null($headers)) {
          // endpoint is setted in product client
          $_request->headers = Dara::merge([
            'host' => $this->_endpoint,
            'x-acs-version' => $version,
            'x-acs-action' => $action,
            'user-agent' => Utils::getUserAgent($this->_userAgent),
          ], $globalHeaders, $extendsHeaders);
        } else {
          $_request->headers = Dara::merge([
            'host' => $this->_endpoint,
            'x-acs-version' => $version,
            'x-acs-action' => $action,
            'user-agent' => Utils::getUserAgent($this->_userAgent),
          ], $globalHeaders, $extendsHeaders, $headers);
        }

        if (!is_null($request->body)) {
          $m = $request->body;
          $tmp = Utils::query($m);
          $_request->body = FormUtil::toFormString($tmp);
          @$_request->headers['content-type'] = 'application/x-www-form-urlencoded';
        }

        if ($authType != 'Anonymous') {
          if (is_null($this->_credential)) {
            throw new ClientException([
              'code' => 'InvalidCredentials',
              'message' => 'Please set up the credentials correctly. If you are setting them through environment variables, please ensure that ALIBABA_CLOUD_ACCESS_KEY_ID and ALIBABA_CLOUD_ACCESS_KEY_SECRET are set correctly. See https://help.aliyun.com/zh/sdk/developer-reference/configure-the-alibaba-cloud-accesskey-environment-variable-on-linux-macos-and-windows-systems for more details.',
            ]);
          }

          $credentialModel = $this->_credential->getCredential();
          $credentialType = $credentialModel->type;
          if ($credentialType == 'bearer') {
            $bearerToken = $credentialModel->bearerToken;
            @$_request->query['BearerToken'] = $bearerToken;
            @$_request->query['SignatureType'] = 'BEARERTOKEN';
          } else {
            $accessKeyId = $credentialModel->accessKeyId;
            $accessKeySecret = $credentialModel->accessKeySecret;
            $securityToken = $credentialModel->securityToken;
            if (!is_null($securityToken) && $securityToken != '') {
              @$_request->query['SecurityToken'] = $securityToken;
            }

            @$_request->query['SignatureMethod'] = 'HMAC-SHA1';
            @$_request->query['SignatureVersion'] = '1.0';
            @$_request->query['AccessKeyId'] = $accessKeyId;
            $t = null;
            if (!is_null($request->body)) {
              $t = $request->body;
            }

            $signedParam = Dara::merge([
            ], $_request->query, Utils::query($t));
            @$_request->query['Signature'] = Utils::getRPCSignature($signedParam, $_request->method, $accessKeySecret);
          }

        }

        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        if (($_response->statusCode >= 400) && ($_response->statusCode < 600)) {
          $_res = StreamUtil::readAsJSON($_response->body);
          $err = $_res;
          $requestId = (@$err['RequestId'] ? @$err['RequestId'] : @$err['requestId']);
          $code = (@$err['Code'] ? @$err['Code'] : @$err['code']);
          if (('' . (string)$code . '' == 'Throttling') || ('' . (string)$code . '' == 'Throttling.User') || ('' . (string)$code . '' == 'Throttling.Api')) {
            throw new ThrottlingException([
              'statusCode' => $_response->statusCode,
              'code' => '' . (string)$code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . (string)$requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'retryAfter' => Utils::getThrottlingTimeLeft($_response->headers),
              'requestId' => '' . (string)$requestId . '',
            ]);
          } else if (($_response->statusCode >= 400) && ($_response->statusCode < 500)) {
            throw new ClientException([
              'statusCode' => $_response->statusCode,
              'code' => '' . (string)$code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . (string)$requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'accessDeniedDetail' => $this->getAccessDeniedDetail($err),
              'requestId' => '' . (string)$requestId . '',
            ]);
          } else {
            throw new ServerException([
              'statusCode' => $_response->statusCode,
              'code' => '' . (string)$code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . (string)$requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'requestId' => '' . (string)$requestId . '',
            ]);
          }

        }

        if ($bodyType == 'binary') {
          $resp = [
            'body' => $_response->body,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
          return $resp;
        } else if ($bodyType == 'byte') {
          $byt = StreamUtil::readAsBytes($_response->body);
          return [
            'body' => $byt,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'string') {
          $str = StreamUtil::readAsString($_response->body);
          return [
            'body' => $str,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'json') {
          $obj = StreamUtil::readAsJSON($_response->body);
          $res = $obj;
          return [
            'body' => $res,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'array') {
          $arr = StreamUtil::readAsJSON($_response->body);
          return [
            'body' => $arr,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else {
          return [
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        }

      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @remarks
   * Encapsulate the request and invoke the network
   * 
   * @param action - api name
   * @param version - product version
   * @param protocol - http or https
   * @param method - e.g. GET
   * @param authType - authorization type e.g. AK
   * @param pathname - pathname of every api
   * @param bodyType - response body type e.g. String
   * @param request - object of OpenApiRequest
   * @param runtime - which controls some details of call api, such as retry times
   * @returns the response
   * @param string $action
   * @param string $version
   * @param string $protocol
   * @param string $method
   * @param string $authType
   * @param string $pathname
   * @param string $bodyType
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function doROARequest($action, $version, $protocol, $method, $authType, $pathname, $bodyType, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        $_request->protocol = ''.($this->_protocol ? $this->_protocol : $protocol);
        $_request->method = $method;
        $_request->pathname = $pathname;
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $_request->headers = Dara::merge([
          'date' => Utils::getDateUTCString(),
          'host' => $this->_endpoint,
          'accept' => 'application/json',
          'x-acs-signature-nonce' => Utils::getNonce(),
          'x-acs-signature-method' => 'HMAC-SHA1',
          'x-acs-signature-version' => '1.0',
          'x-acs-version' => $version,
          'x-acs-action' => $action,
          'user-agent' => Utils::getUserAgent($this->_userAgent),
        ], $globalHeaders, $extendsHeaders, $request->headers);
        if (!is_null($request->body)) {
          $_request->body = json_encode($request->body, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
          @$_request->headers['content-type'] = 'application/json; charset=utf-8';
        }

        $_request->query = Dara::merge([
        ], $globalQueries, $extendsQueries);
        if (!is_null($request->query)) {
          $_request->query = Dara::merge([
          ], $_request->query, $request->query);
        }

        if ($authType != 'Anonymous') {
          if (is_null($this->_credential)) {
            throw new ClientException([
              'code' => 'InvalidCredentials',
              'message' => 'Please set up the credentials correctly. If you are setting them through environment variables, please ensure that ALIBABA_CLOUD_ACCESS_KEY_ID and ALIBABA_CLOUD_ACCESS_KEY_SECRET are set correctly. See https://help.aliyun.com/zh/sdk/developer-reference/configure-the-alibaba-cloud-accesskey-environment-variable-on-linux-macos-and-windows-systems for more details.',
            ]);
          }

          $credentialModel = $this->_credential->getCredential();
          $credentialType = $credentialModel->type;
          if ($credentialType == 'bearer') {
            $bearerToken = $credentialModel->bearerToken;
            @$_request->headers['x-acs-bearer-token'] = $bearerToken;
            @$_request->headers['x-acs-signature-type'] = 'BEARERTOKEN';
          } else {
            $accessKeyId = $credentialModel->accessKeyId;
            $accessKeySecret = $credentialModel->accessKeySecret;
            $securityToken = $credentialModel->securityToken;
            if (!is_null($securityToken) && $securityToken != '') {
              @$_request->headers['x-acs-accesskey-id'] = $accessKeyId;
              @$_request->headers['x-acs-security-token'] = $securityToken;
            }

            $stringToSign = Utils::getStringToSign($_request);
            @$_request->headers['authorization'] = 'acs ' . $accessKeyId . ':' . Utils::getROASignature($stringToSign, $accessKeySecret) . '';
          }

        }

        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        if ($_response->statusCode == 204) {
          return [
            'headers' => $_response->headers,
          ];
        }

        if (($_response->statusCode >= 400) && ($_response->statusCode < 600)) {
          $_res = StreamUtil::readAsJSON($_response->body);
          $err = $_res;
          $requestId = ''.(@$err['RequestId'] ? @$err['RequestId'] : @$err['requestId']);
          $requestId = ''.($requestId ? $requestId : @$err['requestid']);
          $code = ''.(@$err['Code'] ? @$err['Code'] : @$err['code']);
          if (('' . $code . '' == 'Throttling') || ('' . $code . '' == 'Throttling.User') || ('' . $code . '' == 'Throttling.Api')) {
            throw new ThrottlingException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'retryAfter' => Utils::getThrottlingTimeLeft($_response->headers),
              'requestId' => '' . $requestId . '',
            ]);
          } else if (($_response->statusCode >= 400) && ($_response->statusCode < 500)) {
            throw new ClientException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'accessDeniedDetail' => $this->getAccessDeniedDetail($err),
              'requestId' => '' . $requestId . '',
            ]);
          } else {
            throw new ServerException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'requestId' => '' . $requestId . '',
            ]);
          }

        }

        if ($bodyType == 'binary') {
          $resp = [
            'body' => $_response->body,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
          return $resp;
        } else if ($bodyType == 'byte') {
          $byt = StreamUtil::readAsBytes($_response->body);
          return [
            'body' => $byt,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'string') {
          $str = StreamUtil::readAsString($_response->body);
          return [
            'body' => $str,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'json') {
          $obj = StreamUtil::readAsJSON($_response->body);
          $res = $obj;
          return [
            'body' => $res,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'array') {
          $arr = StreamUtil::readAsJSON($_response->body);
          return [
            'body' => $arr,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else {
          return [
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        }

      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @remarks
   * Encapsulate the request and invoke the network with form body
   * 
   * @param action - api name
   * @param version - product version
   * @param protocol - http or https
   * @param method - e.g. GET
   * @param authType - authorization type e.g. AK
   * @param pathname - pathname of every api
   * @param bodyType - response body type e.g. String
   * @param request - object of OpenApiRequest
   * @param runtime - which controls some details of call api, such as retry times
   * @returns the response
   * @param string $action
   * @param string $version
   * @param string $protocol
   * @param string $method
   * @param string $authType
   * @param string $pathname
   * @param string $bodyType
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function doROARequestWithForm($action, $version, $protocol, $method, $authType, $pathname, $bodyType, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        $_request->protocol = ''.($this->_protocol ? $this->_protocol : $protocol);
        $_request->method = $method;
        $_request->pathname = $pathname;
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $_request->headers = Dara::merge([
          'date' => Utils::getDateUTCString(),
          'host' => $this->_endpoint,
          'accept' => 'application/json',
          'x-acs-signature-nonce' => Utils::getNonce(),
          'x-acs-signature-method' => 'HMAC-SHA1',
          'x-acs-signature-version' => '1.0',
          'x-acs-version' => $version,
          'x-acs-action' => $action,
          'user-agent' => Utils::getUserAgent($this->_userAgent),
        ], $globalHeaders, $extendsHeaders, $request->headers);
        if (!is_null($request->body)) {
          $m = $request->body;
          $_request->body = Utils::toForm($m);
          @$_request->headers['content-type'] = 'application/x-www-form-urlencoded';
        }

        $_request->query = Dara::merge([
        ], $globalQueries, $extendsQueries);
        if (!is_null($request->query)) {
          $_request->query = Dara::merge([
          ], $_request->query, $request->query);
        }

        if ($authType != 'Anonymous') {
          if (is_null($this->_credential)) {
            throw new ClientException([
              'code' => 'InvalidCredentials',
              'message' => 'Please set up the credentials correctly. If you are setting them through environment variables, please ensure that ALIBABA_CLOUD_ACCESS_KEY_ID and ALIBABA_CLOUD_ACCESS_KEY_SECRET are set correctly. See https://help.aliyun.com/zh/sdk/developer-reference/configure-the-alibaba-cloud-accesskey-environment-variable-on-linux-macos-and-windows-systems for more details.',
            ]);
          }

          $credentialModel = $this->_credential->getCredential();
          $credentialType = $credentialModel->type;
          if ($credentialType == 'bearer') {
            $bearerToken = $credentialModel->bearerToken;
            @$_request->headers['x-acs-bearer-token'] = $bearerToken;
            @$_request->headers['x-acs-signature-type'] = 'BEARERTOKEN';
          } else {
            $accessKeyId = $credentialModel->accessKeyId;
            $accessKeySecret = $credentialModel->accessKeySecret;
            $securityToken = $credentialModel->securityToken;
            if (!is_null($securityToken) && $securityToken != '') {
              @$_request->headers['x-acs-accesskey-id'] = $accessKeyId;
              @$_request->headers['x-acs-security-token'] = $securityToken;
            }

            $stringToSign = Utils::getStringToSign($_request);
            @$_request->headers['authorization'] = 'acs ' . $accessKeyId . ':' . Utils::getROASignature($stringToSign, $accessKeySecret) . '';
          }

        }

        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        if ($_response->statusCode == 204) {
          return [
            'headers' => $_response->headers,
          ];
        }

        if (($_response->statusCode >= 400) && ($_response->statusCode < 600)) {
          $_res = StreamUtil::readAsJSON($_response->body);
          $err = $_res;
          $requestId = ''.(@$err['RequestId'] ? @$err['RequestId'] : @$err['requestId']);
          $code = ''.(@$err['Code'] ? @$err['Code'] : @$err['code']);
          if (('' . $code . '' == 'Throttling') || ('' . $code . '' == 'Throttling.User') || ('' . $code . '' == 'Throttling.Api')) {
            throw new ThrottlingException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'retryAfter' => Utils::getThrottlingTimeLeft($_response->headers),
              'requestId' => '' . $requestId . '',
            ]);
          } else if (($_response->statusCode >= 400) && ($_response->statusCode < 500)) {
            throw new ClientException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'accessDeniedDetail' => $this->getAccessDeniedDetail($err),
              'requestId' => '' . $requestId . '',
            ]);
          } else {
            throw new ServerException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'requestId' => '' . $requestId . '',
            ]);
          }

        }

        if ($bodyType == 'binary') {
          $resp = [
            'body' => $_response->body,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
          return $resp;
        } else if ($bodyType == 'byte') {
          $byt = StreamUtil::readAsBytes($_response->body);
          return [
            'body' => $byt,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'string') {
          $str = StreamUtil::readAsString($_response->body);
          return [
            'body' => $str,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'json') {
          $obj = StreamUtil::readAsJSON($_response->body);
          $res = $obj;
          return [
            'body' => $res,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($bodyType == 'array') {
          $arr = StreamUtil::readAsJSON($_response->body);
          return [
            'body' => $arr,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else {
          return [
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        }

      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @remarks
   * Encapsulate the request and invoke the network
   * 
   * @param action - api name
   * @param version - product version
   * @param protocol - http or https
   * @param method - e.g. GET
   * @param authType - authorization type e.g. AK
   * @param bodyType - response body type e.g. String
   * @param request - object of OpenApiRequest
   * @param runtime - which controls some details of call api, such as retry times
   * @returns the response
   * @param Params $params
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function doRequest($params, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        $_request->protocol = ''.($this->_protocol ? $this->_protocol : $params->protocol);
        $_request->method = $params->method;
        $_request->pathname = $params->pathname;
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $_request->query = Dara::merge([
        ], $globalQueries, $extendsQueries, $request->query);
        // endpoint is setted in product client
        $_request->headers = Dara::merge([
          'host' => $this->_endpoint,
          'x-acs-version' => $params->version,
          'x-acs-action' => $params->action,
          'user-agent' => Utils::getUserAgent($this->_userAgent),
          'x-acs-date' => Utils::getTimestamp(),
          'x-acs-signature-nonce' => Utils::getNonce(),
          'accept' => 'application/json',
        ], $globalHeaders, $extendsHeaders, $request->headers);
        if ($params->style == 'RPC') {
          $headers = $this->getRpcHeaders();
          if (!is_null($headers)) {
            $_request->headers = Dara::merge([
            ], $_request->headers, $headers);
          }

        }

        $signatureAlgorithm = ''.($this->_signatureAlgorithm ? $this->_signatureAlgorithm : 'ACS3-HMAC-SHA256');
        $hashedRequestPayload = Utils::hash(BytesUtil::from('', 'utf-8'), $signatureAlgorithm);
        if (!is_null($request->stream)) {
          $tmp = StreamUtil::readAsBytes($request->stream);
          $hashedRequestPayload = Utils::hash($tmp, $signatureAlgorithm);
          $_request->body = $tmp;
          @$_request->headers['content-type'] = 'application/octet-stream';
        } else {
          if (!is_null($request->body)) {
            if ($params->reqBodyType == 'byte') {
              $byteObj = unpack('C*', $request->body);
              $hashedRequestPayload = Utils::hash($byteObj, $signatureAlgorithm);
              $_request->body = $byteObj;
            } else if ($params->reqBodyType == 'json') {
              $jsonObj = json_encode($request->body, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
              $hashedRequestPayload = Utils::hash(StringUtil::toBytes($jsonObj, 'utf8'), $signatureAlgorithm);
              $_request->body = $jsonObj;
              @$_request->headers['content-type'] = 'application/json; charset=utf-8';
            } else {
              $m = $request->body;
              $formObj = Utils::toForm($m);
              $hashedRequestPayload = Utils::hash(StringUtil::toBytes($formObj, 'utf8'), $signatureAlgorithm);
              $_request->body = $formObj;
              @$_request->headers['content-type'] = 'application/x-www-form-urlencoded';
            }

          }

        }

        @$_request->headers['x-acs-content-sha256'] = bin2hex(BytesUtil::toString($hashedRequestPayload));
        if ($params->authType != 'Anonymous') {
          if (is_null($this->_credential)) {
            throw new ClientException([
              'code' => 'InvalidCredentials',
              'message' => 'Please set up the credentials correctly. If you are setting them through environment variables, please ensure that ALIBABA_CLOUD_ACCESS_KEY_ID and ALIBABA_CLOUD_ACCESS_KEY_SECRET are set correctly. See https://help.aliyun.com/zh/sdk/developer-reference/configure-the-alibaba-cloud-accesskey-environment-variable-on-linux-macos-and-windows-systems for more details.',
            ]);
          }

          $credentialModel = $this->_credential->getCredential();
          $authType = $credentialModel->type;
          if ($authType == 'bearer') {
            $bearerToken = $credentialModel->bearerToken;
            @$_request->headers['x-acs-bearer-token'] = $bearerToken;
            if ($params->style == 'RPC') {
              @$_request->query['SignatureType'] = 'BEARERTOKEN';
            } else {
              @$_request->headers['x-acs-signature-type'] = 'BEARERTOKEN';
            }

          } else {
            $accessKeyId = $credentialModel->accessKeyId;
            $accessKeySecret = $credentialModel->accessKeySecret;
            $securityToken = $credentialModel->securityToken;
            if (!is_null($securityToken) && $securityToken != '') {
              @$_request->headers['x-acs-accesskey-id'] = $accessKeyId;
              @$_request->headers['x-acs-security-token'] = $securityToken;
            }

            @$_request->headers['Authorization'] = Utils::getAuthorization($_request, $signatureAlgorithm, bin2hex(BytesUtil::toString($hashedRequestPayload)), $accessKeyId, $accessKeySecret);
          }

        }

        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        if (($_response->statusCode >= 400) && ($_response->statusCode < 600)) {
          $err = [ ];
          if (!is_null(@$_response->headers['content-type']) && @$_response->headers['content-type'] == 'text/xml;charset=utf-8') {
            $_str = StreamUtil::readAsString($_response->body);
            $respMap = XML::parseXml($_str, null);
            $err = @$respMap['Error'];
          } else {
            $_res = StreamUtil::readAsJSON($_response->body);
            $err = $_res;
          }

          $requestId = ''.(@$err['RequestId'] ? @$err['RequestId'] : @$err['requestId']);
          $code = ''.(@$err['Code'] ? @$err['Code'] : @$err['code']);
          if (('' . $code . '' == 'Throttling') || ('' . $code . '' == 'Throttling.User') || ('' . $code . '' == 'Throttling.Api')) {
            throw new ThrottlingException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'retryAfter' => Utils::getThrottlingTimeLeft($_response->headers),
              'requestId' => '' . $requestId . '',
            ]);
          } else if (($_response->statusCode >= 400) && ($_response->statusCode < 500)) {
            throw new ClientException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'accessDeniedDetail' => $this->getAccessDeniedDetail($err),
              'requestId' => '' . $requestId . '',
            ]);
          } else {
            throw new ServerException([
              'statusCode' => $_response->statusCode,
              'code' => '' . $code . '',
              'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . $requestId . '',
              'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
              'requestId' => '' . $requestId . '',
            ]);
          }

        }

        if ($params->bodyType == 'binary') {
          $resp = [
            'body' => $_response->body,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
          return $resp;
        } else if ($params->bodyType == 'byte') {
          $byt = StreamUtil::readAsBytes($_response->body);
          return [
            'body' => $byt,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($params->bodyType == 'string') {
          $str = StreamUtil::readAsString($_response->body);
          return [
            'body' => $str,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($params->bodyType == 'json') {
          $obj = StreamUtil::readAsJSON($_response->body);
          $res = $obj;
          return [
            'body' => $res,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else if ($params->bodyType == 'array') {
          $arr = StreamUtil::readAsJSON($_response->body);
          return [
            'body' => $arr,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        } else {
          $anything = StreamUtil::readAsString($_response->body);
          return [
            'body' => $anything,
            'headers' => $_response->headers,
            'statusCode' => $_response->statusCode,
          ];
        }

      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @remarks
   * Encapsulate the request and invoke the network
   * 
   * @param action - api name
   * @param version - product version
   * @param protocol - http or https
   * @param method - e.g. GET
   * @param authType - authorization type e.g. AK
   * @param bodyType - response body type e.g. String
   * @param request - object of OpenApiRequest
   * @param runtime - which controls some details of call api, such as retry times
   * @returns the response
   * @param Params $params
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function execute($params, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
      'disableHttp2' => boolval(($this->_disableHttp2 ? $this->_disableHttp2 : false)),
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        // spi = new Gateway();//Gateway implements SPI，这一步在产品 SDK 中实例化
        $headers = $this->getRpcHeaders();
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $requestContext = new \Darabonba\GatewaySpi\Models\InterceptorContext\request([
          'headers' => Dara::merge([
          ], $globalHeaders, $extendsHeaders, $request->headers, $headers),
          'query' => Dara::merge([
          ], $globalQueries, $extendsQueries, $request->query),
          'body' => $request->body,
          'stream' => $request->stream,
          'hostMap' => $request->hostMap,
          'pathname' => $params->pathname,
          'productId' => $this->_productId,
          'action' => $params->action,
          'version' => $params->version,
          'protocol' => ''.($this->_protocol ? $this->_protocol : $params->protocol),
          'method' => ''.($this->_method ? $this->_method : $params->method),
          'authType' => $params->authType,
          'bodyType' => $params->bodyType,
          'reqBodyType' => $params->reqBodyType,
          'style' => $params->style,
          'credential' => $this->_credential,
          'signatureVersion' => $this->_signatureVersion,
          'signatureAlgorithm' => $this->_signatureAlgorithm,
          'userAgent' => Utils::getUserAgent($this->_userAgent),
        ]);
        $configurationContext = new configuration([
          'regionId' => $this->_regionId,
          'endpoint' => ''.($request->endpointOverride ? $request->endpointOverride : $this->_endpoint),
          'endpointRule' => $this->_endpointRule,
          'endpointMap' => $this->_endpointMap,
          'endpointType' => $this->_endpointType,
          'network' => $this->_network,
          'suffix' => $this->_suffix,
        ]);
        $interceptorContext = new InterceptorContext([
          'request' => $requestContext,
          'configuration' => $configurationContext,
        ]);
        $attributeMap = new AttributeMap([ ]);
        // 1. spi.modifyConfiguration(context: SPI.InterceptorContext, attributeMap: SPI.AttributeMap);
        $this->_spi->modifyConfiguration($interceptorContext, $attributeMap);
        // 2. spi.modifyRequest(context: SPI.InterceptorContext, attributeMap: SPI.AttributeMap);
        $this->_spi->modifyRequest($interceptorContext, $attributeMap);
        $_request->protocol = $interceptorContext->request->protocol;
        $_request->method = $interceptorContext->request->method;
        $_request->pathname = $interceptorContext->request->pathname;
        $_request->query = $interceptorContext->request->query;
        $_request->body = $interceptorContext->request->stream;
        $_request->headers = $interceptorContext->request->headers;
        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        $responseContext = new response([
          'statusCode' => $_response->statusCode,
          'headers' => $_response->headers,
          'body' => $_response->body,
        ]);
        $interceptorContext->response = $responseContext;
        // 3. spi.modifyResponse(context: SPI.InterceptorContext, attributeMap: SPI.AttributeMap);
        $this->_spi->modifyResponse($interceptorContext, $attributeMap);
        return [
          'headers' => $interceptorContext->response->headers,
          'statusCode' => $interceptorContext->response->statusCode,
          'body' => $interceptorContext->response->deserializedBody,
        ];
      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @param Params $params
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return SSEResponse
   */
  public function callSSEApi($params, $request, $runtime)
  {
    $_runtime = [
      'key' => ''.($runtime->key ? $runtime->key : $this->_key),
      'cert' => ''.($runtime->cert ? $runtime->cert : $this->_cert),
      'ca' => ''.($runtime->ca ? $runtime->ca : $this->_ca),
      'readTimeout' => (($runtime->readTimeout ? $runtime->readTimeout : $this->_readTimeout) + 0),
      'connectTimeout' => (($runtime->connectTimeout ? $runtime->connectTimeout : $this->_connectTimeout) + 0),
      'httpProxy' => ''.($runtime->httpProxy ? $runtime->httpProxy : $this->_httpProxy),
      'httpsProxy' => ''.($runtime->httpsProxy ? $runtime->httpsProxy : $this->_httpsProxy),
      'noProxy' => ''.($runtime->noProxy ? $runtime->noProxy : $this->_noProxy),
      'socks5Proxy' => ''.($runtime->socks5Proxy ? $runtime->socks5Proxy : $this->_socks5Proxy),
      'socks5NetWork' => ''.($runtime->socks5NetWork ? $runtime->socks5NetWork : $this->_socks5NetWork),
      'maxIdleConns' => (($runtime->maxIdleConns ? $runtime->maxIdleConns : $this->_maxIdleConns) + 0),
      'retryOptions' => $this->_retryOptions,
      'ignoreSSL' => $runtime->ignoreSSL,
    ];

    $_retriesAttempted = 0;
    $_lastRequest = null;
    $_lastResponse = null;
    $_context = new RetryPolicyContext([
      'retriesAttempted' => $_retriesAttempted,
    ]);
    while (Dara::shouldRetry($_runtime['retryOptions'], $_context)) {
      if ($_retriesAttempted > 0) {
        $_backoffTime = Dara::getBackoffDelay($_runtime['retryOptions'], $_context);
        if ($_backoffTime > 0) {
          Dara::sleep($_backoffTime);
        }
      }

      $_retriesAttempted++;
      try {
        $_request = new Request();
        $_request->protocol = ''.($this->_protocol ? $this->_protocol : $params->protocol);
        $_request->method = $params->method;
        $_request->pathname = $params->pathname;
        $globalQueries = [ ];
        $globalHeaders = [ ];
        if (!is_null($this->_globalParameters)) {
          $globalParams = $this->_globalParameters;
          if (!is_null($globalParams->queries)) {
            $globalQueries = $globalParams->queries;
          }

          if (!is_null($globalParams->headers)) {
            $globalHeaders = $globalParams->headers;
          }

        }

        $extendsHeaders = [ ];
        $extendsQueries = [ ];
        if (!is_null($runtime->extendsParameters)) {
          $extendsParameters = $runtime->extendsParameters;
          if (!is_null($extendsParameters->headers)) {
            $extendsHeaders = $extendsParameters->headers;
          }

          if (!is_null($extendsParameters->queries)) {
            $extendsQueries = $extendsParameters->queries;
          }

        }

        $_request->query = Dara::merge([
        ], $globalQueries, $extendsQueries, $request->query);
        // endpoint is setted in product client
        $_request->headers = Dara::merge([
          'host' => $this->_endpoint,
          'x-acs-version' => $params->version,
          'x-acs-action' => $params->action,
          'user-agent' => Utils::getUserAgent($this->_userAgent),
          'x-acs-date' => Utils::getTimestamp(),
          'x-acs-signature-nonce' => Utils::getNonce(),
          'accept' => 'application/json',
        ], $extendsHeaders, $globalHeaders, $request->headers);
        if ($params->style == 'RPC') {
          $headers = $this->getRpcHeaders();
          if (!is_null($headers)) {
            $_request->headers = Dara::merge([
            ], $_request->headers, $headers);
          }

        }

        $signatureAlgorithm = ''.($this->_signatureAlgorithm ? $this->_signatureAlgorithm : 'ACS3-HMAC-SHA256');
        $hashedRequestPayload = Utils::hash(BytesUtil::from('', 'utf-8'), $signatureAlgorithm);
        if (!is_null($request->stream)) {
          $tmp = StreamUtil::readAsBytes($request->stream);
          $hashedRequestPayload = Utils::hash($tmp, $signatureAlgorithm);
          $_request->body = $tmp;
          @$_request->headers['content-type'] = 'application/octet-stream';
        } else {
          if (!is_null($request->body)) {
            if ($params->reqBodyType == 'byte') {
              $byteObj = unpack('C*', $request->body);
              $hashedRequestPayload = Utils::hash($byteObj, $signatureAlgorithm);
              $_request->body = $byteObj;
            } else if ($params->reqBodyType == 'json') {
              $jsonObj = json_encode($request->body, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
              $hashedRequestPayload = Utils::hash(StringUtil::toBytes($jsonObj, 'utf8'), $signatureAlgorithm);
              $_request->body = $jsonObj;
              @$_request->headers['content-type'] = 'application/json; charset=utf-8';
            } else {
              $m = $request->body;
              $formObj = Utils::toForm($m);
              $hashedRequestPayload = Utils::hash(StringUtil::toBytes($formObj, 'utf8'), $signatureAlgorithm);
              $_request->body = $formObj;
              @$_request->headers['content-type'] = 'application/x-www-form-urlencoded';
            }

          }

        }

        @$_request->headers['x-acs-content-sha256'] = bin2hex(BytesUtil::toString($hashedRequestPayload));
        if ($params->authType != 'Anonymous') {
          $credentialModel = $this->_credential->getCredential();
          $authType = $credentialModel->type;
          if ($authType == 'bearer') {
            $bearerToken = $credentialModel->bearerToken;
            @$_request->headers['x-acs-bearer-token'] = $bearerToken;
          } else {
            $accessKeyId = $credentialModel->accessKeyId;
            $accessKeySecret = $credentialModel->accessKeySecret;
            $securityToken = $credentialModel->securityToken;
            if (!is_null($securityToken) && $securityToken != '') {
              @$_request->headers['x-acs-accesskey-id'] = $accessKeyId;
              @$_request->headers['x-acs-security-token'] = $securityToken;
            }

            @$_request->headers['Authorization'] = Utils::getAuthorization($_request, $signatureAlgorithm, bin2hex(BytesUtil::toString($hashedRequestPayload)), $accessKeyId, $accessKeySecret);
          }

        }

        $_runtime['stream'] = true;
        $_response = Dara::send($_request, $_runtime);
        $_lastRequest = $_request;
        $_lastResponse = $_response;

        if (($_response->statusCode >= 400) && ($_response->statusCode < 600)) {
          $err = [ ];
          if (!is_null(@$_response->headers['content-type']) && @$_response->headers['content-type'] == 'text/xml;charset=utf-8') {
            $_str = StreamUtil::readAsString($_response->body);
            $respMap = XML::parseXml($_str, null);
            $err = @$respMap['Error'];
          } else {
            $_res = StreamUtil::readAsJSON($_response->body);
            $err = $_res;
          }

          @$err['statusCode'] = $_response->statusCode;
          throw new DaraException([
            'code' => '' . (string)(@$err['Code'] ? @$err['Code'] : @$err['code']) . '',
            'message' => 'code: ' . (string)$_response->statusCode . ', ' . (string)(@$err['Message'] ? @$err['Message'] : @$err['message']) . ' request id: ' . (string)(@$err['RequestId'] ? @$err['RequestId'] : @$err['requestId']) . '',
            'data' => $err,
            'description' => '' . (string)(@$err['Description'] ? @$err['Description'] : @$err['description']) . '',
            'accessDeniedDetail' => (@$err['AccessDeniedDetail'] ? @$err['AccessDeniedDetail'] : @$err['accessDeniedDetail']),
          ]);
        }

        $events = StreamUtil::readAsSSE($_response->body);

        foreach($events as $event) {
          yield new SSEResponse([
            'statusCode' => $_response->statusCode,
            'headers' => $_response->headers,
            'event' => $event,
          ]);
        }
        return null;
      } catch (DaraException $e) {
        $_context = new RetryPolicyContext([
          'retriesAttempted' => $_retriesAttempted,
          'lastRequest' => $_lastRequest,
          'lastResponse' => $_lastResponse,
          'exception' => $e,
        ]);
        continue;
      }
    }

    throw new DaraUnableRetryException($_context);
  }

  /**
   * @param Params $params
   * @param OpenApiRequest $request
   * @param RuntimeOptions $runtime
   * @return mixed[]
   */
  public function callApi($params, $request, $runtime)
  {
    if (is_null($params)) {
      throw new ClientException([
        'code' => 'ParameterMissing',
        'message' => '\'params\' can not be unset',
      ]);
    }

    if (is_null($this->_signatureAlgorithm) || $this->_signatureAlgorithm != 'v2') {
      return $this->doRequest($params, $request, $runtime);
    } else if (($params->style == 'ROA') && ($params->reqBodyType == 'json')) {
      return $this->doROARequest($params->action, $params->version, $params->protocol, $params->method, $params->authType, $params->pathname, $params->bodyType, $request, $runtime);
    } else if ($params->style == 'ROA') {
      return $this->doROARequestWithForm($params->action, $params->version, $params->protocol, $params->method, $params->authType, $params->pathname, $params->bodyType, $request, $runtime);
    } else {
      return $this->doRPCRequest($params->action, $params->version, $params->protocol, $params->method, $params->authType, $params->bodyType, $request, $runtime);
    }

  }

  /**
   * @remarks
   * Get accesskey id by using credential
   * @returns accesskey id
   * @return string
   */
  public function getAccessKeyId()
  {
    if (is_null($this->_credential)) {
      return '';
    }

    $accessKeyId = $this->_credential->getAccessKeyId();
    return $accessKeyId;
  }

  /**
   * @remarks
   * Get accesskey secret by using credential
   * @returns accesskey secret
   * @return string
   */
  public function getAccessKeySecret()
  {
    if (is_null($this->_credential)) {
      return '';
    }

    $secret = $this->_credential->getAccessKeySecret();
    return $secret;
  }

  /**
   * @remarks
   * Get security token by using credential
   * @returns security token
   * @return string
   */
  public function getSecurityToken()
  {
    if (is_null($this->_credential)) {
      return '';
    }

    $token = $this->_credential->getSecurityToken();
    return $token;
  }

  /**
   * @remarks
   * Get bearer token by credential
   * @returns bearer token
   * @return string
   */
  public function getBearerToken()
  {
    if (is_null($this->_credential)) {
      return '';
    }

    $token = $this->_credential->getBearerToken();
    return $token;
  }

  /**
   * @remarks
   * Get credential type by credential
   * @returns credential type e.g. access_key
   * @return string
   */
  public function getType()
  {
    if (is_null($this->_credential)) {
      return '';
    }

    $authType = $this->_credential->getType();
    return $authType;
  }

  /**
   * @remarks
   * If the endpointRule and config.endpoint are empty, throw error
   * 
   * @param config - config contains the necessary information to create a client
   * @param \Darabonba\OpenApi\Models\Config $config
   * @return void
   */
  public function checkConfig($config)
  {
    if (is_null($this->_endpointRule) && is_null($config->endpoint)) {
      throw new ClientException([
        'code' => 'ParameterMissing',
        'message' => '\'config.endpoint\' can not be empty',
      ]);
    }

  }

  /**
   * @remarks
   * set gateway client
   * 
   * @param spi - .
   * @param Client $spi
   * @return void
   */
  public function setGatewayClient($spi)
  {
    $this->_spi = $spi;
  }

  /**
   * @remarks
   * set RPC header for debug
   * 
   * @param headers - headers for debug, this header can be used only once.
   * @param string[] $headers
   * @return void
   */
  public function setRpcHeaders($headers)
  {
    $this->_headers = $headers;
  }

  /**
   * @remarks
   * get RPC header for debug
   * @return string[]
   */
  public function getRpcHeaders()
  {
    $headers = $this->_headers;
    $this->_headers = null;
    return $headers;
  }

  /**
   * @param mixed[] $err
   * @return mixed[]
   */
  public function getAccessDeniedDetail($err)
  {
    $accessDeniedDetail = null;
    if (!is_null(@$err['AccessDeniedDetail'])) {
      $detail1 = @$err['AccessDeniedDetail'];
      $accessDeniedDetail = $detail1;
    } else if (!is_null(@$err['accessDeniedDetail'])) {
      $detail2 = @$err['accessDeniedDetail'];
      $accessDeniedDetail = $detail2;
    }

    return $accessDeniedDetail;
  }

}
