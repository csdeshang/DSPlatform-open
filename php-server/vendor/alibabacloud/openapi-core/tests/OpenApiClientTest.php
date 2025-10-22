<?php

namespace Darabonba\OpenApi\Tests;

use Darabonba\OpenApi\OpenApiClient;
use Darabonba\OpenApi\Utils;

use Darabonba\OpenApi\Models\Config;
use Darabonba\OpenApi\Models\Params;
use Darabonba\OpenApi\Models\GlobalParameters;
use AlibabaCloud\Dara\Models\RuntimeOptions;
use AlibabaCloud\Dara\Models\ExtendsParameters;
use Darabonba\OpenApi\Models\OpenApiRequest;
use AlibabaCloud\Credentials\Credential;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class OpenApiClientTest extends TestCase
{

    /**
     * @var resource
     */
    private $pid = 0;

    /**
     * @before
     */
    protected function initialize()
    {
        // $server = dirname(__DIR__). \DIRECTORY_SEPARATOR . 'tests' . \DIRECTORY_SEPARATOR . 'Mock' . \DIRECTORY_SEPARATOR . 'MockServer.php';
        // $command = "php $server > /dev/null 2>&1 & echo $!";
        // // $command = "php -S localhost:8000 $server";
        // $output = shell_exec($command);
        // $this->pid = (int)trim($output);
        // sleep(2);
    }
    
    /**
     * @after
     */
    protected function cleanup()
    {
        // shell_exec('kill '.$this->pid);
    }

    public function testConfig(){
        $globalParameters = new GlobalParameters([
            "headers" => [
                "global-key" => "global-value"
            ],
            "queries" => [
                "global-query" => "global-value"
            ]
        ]);
        $config = new Config([
            "endpoint" => "config.endpoint",
            "endpointType" => "regional",
            "network" => "config.network",
            "suffix" => "config.suffix",
            "protocol" => "config.protocol",
            "method" => "config.method",
            "regionId" => "config.regionId",
            "userAgent" => "config.userAgent",
            "readTimeout" => 3000,
            "connectTimeout" => 3000,
            "httpProxy" => "config.httpProxy",
            "httpsProxy" => "config.httpsProxy",
            "noProxy" => "config.noProxy",
            "socks5Proxy" => "config.socks5Proxy",
            "socks5NetWork" => "config.socks5NetWork",
            "maxIdleConns" => 128,
            "signatureVersion" => "config.signatureVersion",
            "signatureAlgorithm" => "config.signatureAlgorithm",
            "globalParameters" => $globalParameters
        ]);
        $creConfig = new \AlibabaCloud\Credentials\Credential\Config([
            "accessKeyId" => "accessKeyId",
            "accessKeySecret" => "accessKeySecret",
            "securityToken" => "securityToken",
            "type" => "sts"
        ]);
        $credential = new Credential($creConfig);
        $config->credential = $credential;
        $client = new OpenApiClient($config);
        $config->accessKeyId = "ak";
        $config->accessKeySecret = "secret";
        $config->securityToken = "token";
        $config->type = "sts";
        $client = new OpenApiClient($config);
    }

    /**
     * @return Config
     */
    public static function createConfig(){
        $globalParameters = new GlobalParameters([
            "headers" => [
                "global-key" => "global-value"
            ],
            "queries" => [
                "global-query" => "global-value"
            ]
        ]);
        $config = new Config([
            "accessKeyId" => "ak",
            "accessKeySecret" => "secret",
            "securityToken" => "token",
            "type" => "sts",
            "userAgent" => "config.userAgent",
            "readTimeout" => 3000,
            "connectTimeout" => 3000,
            "maxIdleConns" => 128,
            "signatureVersion" => "config.signatureVersion",
            "signatureAlgorithm" => "ACS3-HMAC-SHA256",
            "globalParameters" => $globalParameters
        ]);
        return $config;
    }

    /**
     * @return RuntimeOptions
     */
    public static function createRuntimeOptions(){
        $runtime = new RuntimeOptions([
            "readTimeout" => 4000,
            "connectTimeout" => 4000,
            "maxIdleConns" => 100,
            "autoretry" => true,
            "maxAttempts" => 1,
            "backoffPolicy" => "no",
            "backoffPeriod" => 1,
            "ignoreSSL" => true,
            "extendsParameters" => new ExtendsParameters([
                "headers" => [
                    "extends-key" => "extends-value"
                ],
            ])
        ]);
        return $runtime;
    }

    /**
     * @return OpenApiRequest
     */
    public static function createOpenApiRequest(){
        $query = [];
        $query["key1"] = "value";
        $query["key2"] = 1;
        $query["key3"] = true;
        $body = [];
        $body["key1"] = "value";
        $body["key2"] = 1;
        $body["key3"] = true;
        $headers = [
            "for-test" => "sdk"
        ];
        $req = new OpenApiRequest([
            "headers" => $headers,
            "query" => Utils::query($query),
            "body" => Utils::parseToMap($body)
        ]);
        return $req;
    }

    public function testCallApiForRPCWithV2Sign_AK_Form(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->signatureAlgorithm = "v2";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/",
            "method" => "POST",
            "authType" => "AK",
            "style" => "RPC",
            "reqBodyType" => "formData",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForRPCWithV2Sign_Anonymous_JSON(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->signatureAlgorithm = "v2";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/",
            "method" => "POST",
            "authType" => "Anonymous",
            "style" => "RPC",
            "reqBodyType" => "json",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForROAWithV2Sign_HTTPS_AK_Form(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->signatureAlgorithm = "v2";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/test",
            "method" => "POST",
            "authType" => "AK",
            "style" => "ROA",
            "reqBodyType" => "formData",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForROAWithV2Sign_Anonymous_JSON(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->signatureAlgorithm = "v2";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/test",
            "method" => "POST",
            "authType" => "Anonymous",
            "style" => "ROA",
            "reqBodyType" => "json",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForRPCWithV3Sign_AK_Form(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/",
            "method" => "POST",
            "authType" => "AK",
            "style" => "RPC",
            "reqBodyType" => "formData",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForRPCWithV3Sign_Anonymous_JSON(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/",
            "method" => "POST",
            "authType" => "Anonymous",
            "style" => "RPC",
            "reqBodyType" => "json",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForROAWithV3Sign_AK_Form(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/test",
            "method" => "POST",
            "authType" => "AK",
            "style" => "ROA",
            "reqBodyType" => "formData",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testCallApiForROAWithV3Sign_Anonymous_JSON(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/test",
            "method" => "POST",
            "authType" => "Anonymous",
            "style" => "ROA",
            "reqBodyType" => "json",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
    }

    public function testResponseBodyType(){
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "test.aliyuncs.com";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/test",
            "method" => "POST",
            "authType" => "AK",
            "style" => "ROA",
            "reqBodyType" => "formData",
            "bodyType" => "json"
        ]);
        $client->callApi($params, $request, $runtime);
        $params->bodyType = "array";
        $client->callApi($params, $request, $runtime);
        $params->bodyType = "string";
        $client->callApi($params, $request, $runtime);
        $params->bodyType = "byte";
        $client->callApi($params, $request, $runtime);
    }

    public function testCallSSEApiWithSignV3()
    {
        $config = self::createConfig();
        $runtime = self::createRuntimeOptions();
        $config->protocol = "HTTP";
        $config->endpoint = "127.0.0.1:8000";
        $client = new OpenApiClient($config);
        $request = self::createOpenApiRequest();
        $params = new Params([
            "action" => "TestAPI",
            "version" => "2022-06-01",
            "protocol" => "HTTPS",
            "pathname" => "/sse",
            "method" => "POST",
            "authType" => "AK",
            "style" => "ROA",
            "reqBodyType" => "json",
            "bodyType" => "sse"
        ]);
        $response = $client->callSSEApi($params, $request, $runtime);

       
        // Add more assertions as needed
        $events = [];
        
        // SSE events are typically separated by double newline
        foreach ($response as $event) {
            $this->assertEquals(200, $event->statusCode);
            $headers = $event->headers;
            $this->assertEquals('text/event-stream;charset=UTF-8', $headers['Content-Type'][0]);
            $this->assertEquals('sdk', $headers['for-test'][0]);
            $userAgentArray = explode(' ', $headers['user-agent'][0]);
            $this->assertEquals('config.userAgent', end($userAgentArray));
            $this->assertEquals('global-value', $headers['global-key'][0]);
            $this->assertEquals('extends-value', $headers['extends-key'][0]);
            $this->assertNotEmpty($headers['x-acs-signature-nonce'][0]);
            $this->assertNotEmpty($headers['x-acs-date'][0]);
            $this->assertEquals('application/json', $headers['accept'][0]);
            $event = $event->event->toArray();
            // var_dump($event);
            $events[] = json_decode($event['data'], true);
        }
        $expectedEvents = [
            ['count' => 0],
            ['count' => 1],
            ['count' => 2],
            ['count' => 3],
            ['count' => 4],
        ];
        $this->assertCount(5, $events);
        $this->assertEquals($expectedEvents, $events);
    }
}
