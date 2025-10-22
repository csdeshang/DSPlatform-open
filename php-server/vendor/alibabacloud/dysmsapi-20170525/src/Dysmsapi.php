<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525;

use AlibabaCloud\Dara\Models\RuntimeOptions;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddExtCodeSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddExtCodeSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CheckMobilesCardSupportRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CheckMobilesCardSupportResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ConversionDataIntlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ConversionDataIntlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateCardSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateCardSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateCardSmsTemplateShrinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmartShortUrlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmartShortUrlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsSignShrinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CreateSmsTemplateShrinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteExtCodeSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteExtCodeSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteShortUrlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteShortUrlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetCardSmsDetailsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetCardSmsDetailsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetCardSmsLinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetCardSmsLinkResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetMediaResourceIdRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetMediaResourceIdResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetOSSInfoForCardTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetOSSInfoForUploadFileRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetOSSInfoForUploadFileResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ListTagResourcesRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ListTagResourcesResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryCardSmsTemplateReportRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryCardSmsTemplateReportResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryCardSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryCardSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryExtCodeSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryExtCodeSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportShrinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryPageSmartShortUrlLogRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryPageSmartShortUrlLogResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendStatisticsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendStatisticsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryShortUrlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryShortUrlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignListRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignListResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchCardSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchCardSmsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchSmsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendCardSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendCardSmsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SmsConversionIntlRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SmsConversionIntlResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\TagResourcesRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\TagResourcesResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UntagResourcesRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UntagResourcesResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateExtCodeSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateExtCodeSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsSignResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsSignShrinkRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsTemplateResponse;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\UpdateSmsTemplateShrinkRequest;
use Darabonba\OpenApi\Models\OpenApiRequest;
use Darabonba\OpenApi\Models\Params;
use Darabonba\OpenApi\OpenApiClient;
use Darabonba\OpenApi\Utils;

class Dysmsapi extends OpenApiClient
{
    public function __construct($config)
    {
        parent::__construct($config);
        $this->_endpointRule = 'central';
        $this->_endpointMap = [
            'ap-southeast-1' => 'dysmsapi.ap-southeast-1.aliyuncs.com',
            'ap-southeast-5' => 'dysmsapi.ap-southeast-5.aliyuncs.com',
            'cn-beijing' => 'dysmsapi-proxy.cn-beijing.aliyuncs.com',
            'cn-hongkong' => 'dysmsapi-xman.cn-hongkong.aliyuncs.com',
            'eu-central-1' => 'dysmsapi.eu-central-1.aliyuncs.com',
            'us-east-1' => 'dysmsapi.us-east-1.aliyuncs.com',
        ];
        $this->checkConfig($config);
        $this->_endpoint = $this->getEndpoint('dysmsapi', $this->_regionId, $this->_endpointRule, $this->_network, $this->_suffix, $this->_endpointMap, $this->_endpoint);
    }

    /**
     * @param string   $productId
     * @param string   $regionId
     * @param string   $endpointRule
     * @param string   $network
     * @param string   $suffix
     * @param string[] $endpointMap
     * @param string   $endpoint
     *
     * @return string
     */
    public function getEndpoint($productId, $regionId, $endpointRule, $network, $suffix, $endpointMap, $endpoint)
    {
        if (null !== $endpoint) {
            return $endpoint;
        }

        if (null !== $endpointMap && null !== @$endpointMap[$regionId]) {
            return @$endpointMap[$regionId];
        }

        return Utils::getEndpointRules($productId, $regionId, $endpointRule, $network, $suffix);
    }

    /**
     * 添加验证码签名信息.
     *
     * @param request - AddExtCodeSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns AddExtCodeSignResponse
     *
     * @param AddExtCodeSignRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return AddExtCodeSignResponse
     */
    public function addExtCodeSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->extCode) {
            @$query['ExtCode'] = $request->extCode;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'AddExtCodeSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return AddExtCodeSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return AddExtCodeSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 添加验证码签名信息.
     *
     * @param request - AddExtCodeSignRequest
     *
     * @returns AddExtCodeSignResponse
     *
     * @param AddExtCodeSignRequest $request
     *
     * @return AddExtCodeSignResponse
     */
    public function addExtCodeSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->addExtCodeSignWithOptions($request, $runtime);
    }

    /**
     * Creates a short URL.
     *
     * @remarks
     *   Before you call this operation, you must register the primary domain name of the source URL in the Short Message Service (SMS) console. After the domain name is registered, you can call this operation to create a short URL. For more information, see [Domain name registration](https://help.aliyun.com/document_detail/302325.html#title-mau-zdh-hd0).
     * *   You can create up to 3,000 short URLs within a natural day.
     * *   After a short URL is generated, a security review is required. Generally, the review takes 10 minutes to 2 hours to complete. Before the security review is passed, the short URL cannot be directly accessed.
     *
     * @param request - AddShortUrlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns AddShortUrlResponse
     *
     * @param AddShortUrlRequest $request
     * @param RuntimeOptions     $runtime
     *
     * @return AddShortUrlResponse
     */
    public function addShortUrlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $body = [];
        if (null !== $request->effectiveDays) {
            @$body['EffectiveDays'] = $request->effectiveDays;
        }

        if (null !== $request->shortUrlName) {
            @$body['ShortUrlName'] = $request->shortUrlName;
        }

        if (null !== $request->sourceUrl) {
            @$body['SourceUrl'] = $request->sourceUrl;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'AddShortUrl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return AddShortUrlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return AddShortUrlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Creates a short URL.
     *
     * @remarks
     *   Before you call this operation, you must register the primary domain name of the source URL in the Short Message Service (SMS) console. After the domain name is registered, you can call this operation to create a short URL. For more information, see [Domain name registration](https://help.aliyun.com/document_detail/302325.html#title-mau-zdh-hd0).
     * *   You can create up to 3,000 short URLs within a natural day.
     * *   After a short URL is generated, a security review is required. Generally, the review takes 10 minutes to 2 hours to complete. Before the security review is passed, the short URL cannot be directly accessed.
     *
     * @param request - AddShortUrlRequest
     *
     * @returns AddShortUrlResponse
     *
     * @param AddShortUrlRequest $request
     *
     * @return AddShortUrlResponse
     */
    public function addShortUrl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->addShortUrlWithOptions($request, $runtime);
    }

    /**
     * Creates a signature.
     *
     * @remarks
     * You can call the AddSmsSign operation or use the [Short Message Service (SMS) console](https://dysms.console.aliyun.com/dysms.htm#/overview) to create an SMS signature. The signature must comply with the [SMS signature specifications](https://help.aliyun.com/document_detail/108076.html). You can call the QuerySmsSign operation or use the SMS console to query the review status of the signature.
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limit
     * You can call this operation only once per second. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     * >
     * *   You cannot cancel the review of a signature.
     * *   Individual users can create only one verification code signature, and can create only one general-purpose signature within a natural day. If you need to apply for multiple signatures, we recommend that you upgrade your account to an enterprise user.
     * *   If you need to use the same signature for messages sent to recipients both in and outside the Chinese mainland, the signature must be a general-purpose signature.
     * *   If you apply for a signature or message template, you must specify the signature scenario or template type. You must also provide the information of your services, such as a website URL, a domain name with an ICP filing, an application download URL, or the name of your WeChat official account or mini program. For sign-in scenarios, you must also provide an account and password for tests. A detailed description can improve the review efficiency of signatures and templates.
     * *   An SMS signature must undergo a thorough review process before it can be approved for use.
     *
     * @param request - AddSmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns AddSmsSignResponse
     *
     * @param AddSmsSignRequest $request
     * @param RuntimeOptions    $runtime
     *
     * @return AddSmsSignResponse
     */
    public function addSmsSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->signSource) {
            @$query['SignSource'] = $request->signSource;
        }

        if (null !== $request->signType) {
            @$query['SignType'] = $request->signType;
        }

        $body = [];
        if (null !== $request->signFileList) {
            @$body['SignFileList'] = $request->signFileList;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'AddSmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return AddSmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return AddSmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Creates a signature.
     *
     * @remarks
     * You can call the AddSmsSign operation or use the [Short Message Service (SMS) console](https://dysms.console.aliyun.com/dysms.htm#/overview) to create an SMS signature. The signature must comply with the [SMS signature specifications](https://help.aliyun.com/document_detail/108076.html). You can call the QuerySmsSign operation or use the SMS console to query the review status of the signature.
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limit
     * You can call this operation only once per second. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     * >
     * *   You cannot cancel the review of a signature.
     * *   Individual users can create only one verification code signature, and can create only one general-purpose signature within a natural day. If you need to apply for multiple signatures, we recommend that you upgrade your account to an enterprise user.
     * *   If you need to use the same signature for messages sent to recipients both in and outside the Chinese mainland, the signature must be a general-purpose signature.
     * *   If you apply for a signature or message template, you must specify the signature scenario or template type. You must also provide the information of your services, such as a website URL, a domain name with an ICP filing, an application download URL, or the name of your WeChat official account or mini program. For sign-in scenarios, you must also provide an account and password for tests. A detailed description can improve the review efficiency of signatures and templates.
     * *   An SMS signature must undergo a thorough review process before it can be approved for use.
     *
     * @param request - AddSmsSignRequest
     *
     * @returns AddSmsSignResponse
     *
     * @param AddSmsSignRequest $request
     *
     * @return AddSmsSignResponse
     */
    public function addSmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->addSmsSignWithOptions($request, $runtime);
    }

    // Deprecated
    /**
     * Creates a message template.
     *
     * @remarks
     * You can call the operation or use the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview) to apply for a message template. The template must comply with the [message template specifications](https://help.aliyun.com/document_detail/108253.html). You can call the [QuerySmsTemplate](https://help.aliyun.com/document_detail/419289.html) operation or use the Alibaba Cloud SMS console to check whether the message template is approved.
     * >
     * *   Message templates pending approval can be withdrawn. You can withdraw a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   Message templates that have been approved can be deleted, and cannot be modified. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   If you call the AddSmsTemplate operation, you can apply for a maximum of 100 message templates in a calendar day. After you apply for a message template, we recommend that you wait for at least 30 seconds before you apply for another one. If you use the Alibaba Cloud SMS console, you can apply for an unlimited number of message templates.
     * *   Messages sent to the Chinese mainland and messages sent to countries or regions outside the Chinese mainland use separate message templates. Create message templates based on your needs.
     * *   If you apply for a signature or message template, you must specify the signature scenario or template type. You must also provide the information of your services, such as a website URL, a domain name with an ICP filing, an application download URL, or the name of your WeChat official account or mini program. For sign-in scenarios, you must also provide an account and password for tests. A detailed description can improve the review efficiency of signatures and templates.
     * *   A signature must undergo a thorough review process before it can be approved for use. For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI AddSmsTemplate is deprecated, please use Dysmsapi::2017-05-25::CreateSmsTemplate instead
     *
     * @param request - AddSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns AddSmsTemplateResponse
     *
     * @param AddSmsTemplateRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return AddSmsTemplateResponse
     */
    public function addSmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateContent) {
            @$query['TemplateContent'] = $request->templateContent;
        }

        if (null !== $request->templateName) {
            @$query['TemplateName'] = $request->templateName;
        }

        if (null !== $request->templateType) {
            @$query['TemplateType'] = $request->templateType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'AddSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return AddSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return AddSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    // Deprecated
    /**
     * Creates a message template.
     *
     * @remarks
     * You can call the operation or use the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview) to apply for a message template. The template must comply with the [message template specifications](https://help.aliyun.com/document_detail/108253.html). You can call the [QuerySmsTemplate](https://help.aliyun.com/document_detail/419289.html) operation or use the Alibaba Cloud SMS console to check whether the message template is approved.
     * >
     * *   Message templates pending approval can be withdrawn. You can withdraw a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   Message templates that have been approved can be deleted, and cannot be modified. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   If you call the AddSmsTemplate operation, you can apply for a maximum of 100 message templates in a calendar day. After you apply for a message template, we recommend that you wait for at least 30 seconds before you apply for another one. If you use the Alibaba Cloud SMS console, you can apply for an unlimited number of message templates.
     * *   Messages sent to the Chinese mainland and messages sent to countries or regions outside the Chinese mainland use separate message templates. Create message templates based on your needs.
     * *   If you apply for a signature or message template, you must specify the signature scenario or template type. You must also provide the information of your services, such as a website URL, a domain name with an ICP filing, an application download URL, or the name of your WeChat official account or mini program. For sign-in scenarios, you must also provide an account and password for tests. A detailed description can improve the review efficiency of signatures and templates.
     * *   A signature must undergo a thorough review process before it can be approved for use. For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI AddSmsTemplate is deprecated, please use Dysmsapi::2017-05-25::CreateSmsTemplate instead
     *
     * @param request - AddSmsTemplateRequest
     *
     * @returns AddSmsTemplateResponse
     *
     * @param AddSmsTemplateRequest $request
     *
     * @return AddSmsTemplateResponse
     */
    public function addSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->addSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Checks whether a mobile phone number can receive card messages.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 2,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - CheckMobilesCardSupportRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns CheckMobilesCardSupportResponse
     *
     * @param CheckMobilesCardSupportRequest $request
     * @param RuntimeOptions                 $runtime
     *
     * @return CheckMobilesCardSupportResponse
     */
    public function checkMobilesCardSupportWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->mobiles) {
            @$query['Mobiles'] = $request->mobiles;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'CheckMobilesCardSupport',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return CheckMobilesCardSupportResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return CheckMobilesCardSupportResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Checks whether a mobile phone number can receive card messages.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 2,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - CheckMobilesCardSupportRequest
     *
     * @returns CheckMobilesCardSupportResponse
     *
     * @param CheckMobilesCardSupportRequest $request
     *
     * @return CheckMobilesCardSupportResponse
     */
    public function checkMobilesCardSupport($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->checkMobilesCardSupportWithOptions($request, $runtime);
    }

    /**
     * Sends conversion rate information to Alibaba Cloud SMS.
     *
     * @param request - ConversionDataIntlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns ConversionDataIntlResponse
     *
     * @param ConversionDataIntlRequest $request
     * @param RuntimeOptions            $runtime
     *
     * @return ConversionDataIntlResponse
     */
    public function conversionDataIntlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->conversionRate) {
            @$query['ConversionRate'] = $request->conversionRate;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->reportTime) {
            @$query['ReportTime'] = $request->reportTime;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'ConversionDataIntl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return ConversionDataIntlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return ConversionDataIntlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Sends conversion rate information to Alibaba Cloud SMS.
     *
     * @param request - ConversionDataIntlRequest
     *
     * @returns ConversionDataIntlResponse
     *
     * @param ConversionDataIntlRequest $request
     *
     * @return ConversionDataIntlResponse
     */
    public function conversionDataIntl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->conversionDataIntlWithOptions($request, $runtime);
    }

    /**
     * Creates a card message template.
     *
     * @remarks
     *   The CreateCardSmsTemplate operation saves the card message template information, submits it to the mobile phone manufacturer for approval, and returns the message template ID.
     * *   If the type of the message template is not supported or events that are not supported by the mobile phone manufacturer are specified, the template is not submitted. For more information, see [Supported message templates](https://help.aliyun.com/document_detail/434611.html).
     * *   For information about sample card message templates, see [Sample card message templates](https://help.aliyun.com/document_detail/435361.html).
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param tmpReq - CreateCardSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns CreateCardSmsTemplateResponse
     *
     * @param CreateCardSmsTemplateRequest $tmpReq
     * @param RuntimeOptions               $runtime
     *
     * @return CreateCardSmsTemplateResponse
     */
    public function createCardSmsTemplateWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new CreateCardSmsTemplateShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->template) {
            $request->templateShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->template, 'Template', 'json');
        }

        $query = [];
        if (null !== $request->factorys) {
            @$query['Factorys'] = $request->factorys;
        }

        if (null !== $request->memo) {
            @$query['Memo'] = $request->memo;
        }

        if (null !== $request->templateShrink) {
            @$query['Template'] = $request->templateShrink;
        }

        if (null !== $request->templateName) {
            @$query['TemplateName'] = $request->templateName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'CreateCardSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return CreateCardSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return CreateCardSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Creates a card message template.
     *
     * @remarks
     *   The CreateCardSmsTemplate operation saves the card message template information, submits it to the mobile phone manufacturer for approval, and returns the message template ID.
     * *   If the type of the message template is not supported or events that are not supported by the mobile phone manufacturer are specified, the template is not submitted. For more information, see [Supported message templates](https://help.aliyun.com/document_detail/434611.html).
     * *   For information about sample card message templates, see [Sample card message templates](https://help.aliyun.com/document_detail/435361.html).
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - CreateCardSmsTemplateRequest
     *
     * @returns CreateCardSmsTemplateResponse
     *
     * @param CreateCardSmsTemplateRequest $request
     *
     * @return CreateCardSmsTemplateResponse
     */
    public function createCardSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->createCardSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * 创建短链.
     *
     * @param request - CreateSmartShortUrlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns CreateSmartShortUrlResponse
     *
     * @param CreateSmartShortUrlRequest $request
     * @param RuntimeOptions             $runtime
     *
     * @return CreateSmartShortUrlResponse
     */
    public function createSmartShortUrlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->phoneNumbers) {
            @$query['PhoneNumbers'] = $request->phoneNumbers;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->sourceUrl) {
            @$query['SourceUrl'] = $request->sourceUrl;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'CreateSmartShortUrl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return CreateSmartShortUrlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return CreateSmartShortUrlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 创建短链.
     *
     * @param request - CreateSmartShortUrlRequest
     *
     * @returns CreateSmartShortUrlResponse
     *
     * @param CreateSmartShortUrlRequest $request
     *
     * @return CreateSmartShortUrlResponse
     */
    public function createSmartShortUrl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->createSmartShortUrlWithOptions($request, $runtime);
    }

    /**
     * Create SMS Signature.
     *
     * @remarks
     * - For details about the announcement of changes to the new and original interfaces, see [Announcement on Updates to SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Individual authenticated users can apply for one formal signature per natural day under the same Alibaba Cloud account, while enterprise authenticated users have no current restrictions. For details on the differences in rights between individual and enterprise users, please refer to [User Guide](https://help.aliyun.com/zh/sms/user-guide/usage-notes?spm).
     * - Signature information applied through the interface will be synchronized in the SMS service console. For operations related to signatures in the console, see [SMS Signatures](https://help.aliyun.com/zh/sms/user-guide/create-signatures?spm).
     * - After submitting the signature application, you can query the signature review status and details via the [GetSmsSign](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-getsmssign?spm) interface. You can also [Configure Receipt Messages](https://help.aliyun.com/zh/sms/developer-reference/configure-delivery-receipts-1?spm) and obtain signature review status messages through SignSmsReport.
     *
     * @param tmpReq - CreateSmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns CreateSmsSignResponse
     *
     * @param CreateSmsSignRequest $tmpReq
     * @param RuntimeOptions       $runtime
     *
     * @return CreateSmsSignResponse
     */
    public function createSmsSignWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new CreateSmsSignShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->moreData) {
            $request->moreDataShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->moreData, 'MoreData', 'json');
        }

        $query = [];
        if (null !== $request->applySceneContent) {
            @$query['ApplySceneContent'] = $request->applySceneContent;
        }

        if (null !== $request->moreDataShrink) {
            @$query['MoreData'] = $request->moreDataShrink;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->qualificationId) {
            @$query['QualificationId'] = $request->qualificationId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->signSource) {
            @$query['SignSource'] = $request->signSource;
        }

        if (null !== $request->signType) {
            @$query['SignType'] = $request->signType;
        }

        if (null !== $request->thirdParty) {
            @$query['ThirdParty'] = $request->thirdParty;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'CreateSmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return CreateSmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return CreateSmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Create SMS Signature.
     *
     * @remarks
     * - For details about the announcement of changes to the new and original interfaces, see [Announcement on Updates to SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Individual authenticated users can apply for one formal signature per natural day under the same Alibaba Cloud account, while enterprise authenticated users have no current restrictions. For details on the differences in rights between individual and enterprise users, please refer to [User Guide](https://help.aliyun.com/zh/sms/user-guide/usage-notes?spm).
     * - Signature information applied through the interface will be synchronized in the SMS service console. For operations related to signatures in the console, see [SMS Signatures](https://help.aliyun.com/zh/sms/user-guide/create-signatures?spm).
     * - After submitting the signature application, you can query the signature review status and details via the [GetSmsSign](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-getsmssign?spm) interface. You can also [Configure Receipt Messages](https://help.aliyun.com/zh/sms/developer-reference/configure-delivery-receipts-1?spm) and obtain signature review status messages through SignSmsReport.
     *
     * @param request - CreateSmsSignRequest
     *
     * @returns CreateSmsSignResponse
     *
     * @param CreateSmsSignRequest $request
     *
     * @return CreateSmsSignResponse
     */
    public function createSmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->createSmsSignWithOptions($request, $runtime);
    }

    /**
     * Create SMS Template.
     *
     * @remarks
     * - For details about the changes of this new interface compared to the original one, please refer to [Announcement on the Update of SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - It is recommended to apply for SMS templates via the API with at least a 30-second interval between each request.
     * - The template information applied through the API will be synchronized in the SMS service console. For operations related to templates in the console, please refer to SMS Templates.
     * - After submitting the template application, you can query the audit status and details using the GetSmsTemplate interface. You can also configure delivery receipts to obtain the audit status messages via TemplateSmsReport.
     * - Domestic SMS templates are not interchangeable with international/Hong Kong, Macao, and Taiwan SMS templates. Please apply for templates based on your business scenario.
     * - Only enterprise-verified users can apply for promotional messages and international/Hong Kong, Macao, and Taiwan messages. For differences in rights between personal and enterprise users, please refer to Usage Instructions.
     *
     * @param tmpReq - CreateSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns CreateSmsTemplateResponse
     *
     * @param CreateSmsTemplateRequest $tmpReq
     * @param RuntimeOptions           $runtime
     *
     * @return CreateSmsTemplateResponse
     */
    public function createSmsTemplateWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new CreateSmsTemplateShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->moreData) {
            $request->moreDataShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->moreData, 'MoreData', 'json');
        }

        $query = [];
        if (null !== $request->applySceneContent) {
            @$query['ApplySceneContent'] = $request->applySceneContent;
        }

        if (null !== $request->intlType) {
            @$query['IntlType'] = $request->intlType;
        }

        if (null !== $request->moreDataShrink) {
            @$query['MoreData'] = $request->moreDataShrink;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->relatedSignName) {
            @$query['RelatedSignName'] = $request->relatedSignName;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateContent) {
            @$query['TemplateContent'] = $request->templateContent;
        }

        if (null !== $request->templateName) {
            @$query['TemplateName'] = $request->templateName;
        }

        if (null !== $request->templateRule) {
            @$query['TemplateRule'] = $request->templateRule;
        }

        if (null !== $request->templateType) {
            @$query['TemplateType'] = $request->templateType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'CreateSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return CreateSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return CreateSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Create SMS Template.
     *
     * @remarks
     * - For details about the changes of this new interface compared to the original one, please refer to [Announcement on the Update of SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - It is recommended to apply for SMS templates via the API with at least a 30-second interval between each request.
     * - The template information applied through the API will be synchronized in the SMS service console. For operations related to templates in the console, please refer to SMS Templates.
     * - After submitting the template application, you can query the audit status and details using the GetSmsTemplate interface. You can also configure delivery receipts to obtain the audit status messages via TemplateSmsReport.
     * - Domestic SMS templates are not interchangeable with international/Hong Kong, Macao, and Taiwan SMS templates. Please apply for templates based on your business scenario.
     * - Only enterprise-verified users can apply for promotional messages and international/Hong Kong, Macao, and Taiwan messages. For differences in rights between personal and enterprise users, please refer to Usage Instructions.
     *
     * @param request - CreateSmsTemplateRequest
     *
     * @returns CreateSmsTemplateResponse
     *
     * @param CreateSmsTemplateRequest $request
     *
     * @return CreateSmsTemplateResponse
     */
    public function createSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->createSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * 删除验证码签名.
     *
     * @param request - DeleteExtCodeSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns DeleteExtCodeSignResponse
     *
     * @param DeleteExtCodeSignRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return DeleteExtCodeSignResponse
     */
    public function deleteExtCodeSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->extCode) {
            @$query['ExtCode'] = $request->extCode;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'DeleteExtCodeSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return DeleteExtCodeSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return DeleteExtCodeSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 删除验证码签名.
     *
     * @param request - DeleteExtCodeSignRequest
     *
     * @returns DeleteExtCodeSignResponse
     *
     * @param DeleteExtCodeSignRequest $request
     *
     * @return DeleteExtCodeSignResponse
     */
    public function deleteExtCodeSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->deleteExtCodeSignWithOptions($request, $runtime);
    }

    /**
     * Deletes a short URL. After you delete a short URL, it cannot be changed to its original state.
     *
     * @remarks
     * ### QPS limits
     * You can call this operation up to 100 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteShortUrlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns DeleteShortUrlResponse
     *
     * @param DeleteShortUrlRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return DeleteShortUrlResponse
     */
    public function deleteShortUrlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $body = [];
        if (null !== $request->sourceUrl) {
            @$body['SourceUrl'] = $request->sourceUrl;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'DeleteShortUrl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return DeleteShortUrlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return DeleteShortUrlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Deletes a short URL. After you delete a short URL, it cannot be changed to its original state.
     *
     * @remarks
     * ### QPS limits
     * You can call this operation up to 100 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteShortUrlRequest
     *
     * @returns DeleteShortUrlResponse
     *
     * @param DeleteShortUrlRequest $request
     *
     * @return DeleteShortUrlResponse
     */
    public function deleteShortUrl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->deleteShortUrlWithOptions($request, $runtime);
    }

    /**
     * Deletes a signature.
     *
     * @remarks
     *   You cannot delete a signature that has not been approved.
     * *   After you delete a signature, you cannot recover it. Proceed with caution.
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteSmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns DeleteSmsSignResponse
     *
     * @param DeleteSmsSignRequest $request
     * @param RuntimeOptions       $runtime
     *
     * @return DeleteSmsSignResponse
     */
    public function deleteSmsSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'DeleteSmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return DeleteSmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return DeleteSmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Deletes a signature.
     *
     * @remarks
     *   You cannot delete a signature that has not been approved.
     * *   After you delete a signature, you cannot recover it. Proceed with caution.
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteSmsSignRequest
     *
     * @returns DeleteSmsSignResponse
     *
     * @param DeleteSmsSignRequest $request
     *
     * @return DeleteSmsSignResponse
     */
    public function deleteSmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->deleteSmsSignWithOptions($request, $runtime);
    }

    /**
     * Deletes a message template.
     *
     * @remarks
     *   Message templates pending approval can be withdrawn. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   Message templates that have been approved can be deleted, and cannot be modified. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   You cannot recover deleted message templates. Proceed with caution.
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns DeleteSmsTemplateResponse
     *
     * @param DeleteSmsTemplateRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return DeleteSmsTemplateResponse
     */
    public function deleteSmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'DeleteSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return DeleteSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return DeleteSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Deletes a message template.
     *
     * @remarks
     *   Message templates pending approval can be withdrawn. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   Message templates that have been approved can be deleted, and cannot be modified. You can delete a message template pending approval on the Message Templates tab in the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview).
     * *   You cannot recover deleted message templates. Proceed with caution.
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - DeleteSmsTemplateRequest
     *
     * @returns DeleteSmsTemplateResponse
     *
     * @param DeleteSmsTemplateRequest $request
     *
     * @return DeleteSmsTemplateResponse
     */
    public function deleteSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->deleteSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Query card sending details.
     *
     * @param request - GetCardSmsDetailsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetCardSmsDetailsResponse
     *
     * @param GetCardSmsDetailsRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return GetCardSmsDetailsResponse
     */
    public function getCardSmsDetailsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->bizCardId) {
            @$query['BizCardId'] = $request->bizCardId;
        }

        if (null !== $request->bizDigitId) {
            @$query['BizDigitId'] = $request->bizDigitId;
        }

        if (null !== $request->bizSmsId) {
            @$query['BizSmsId'] = $request->bizSmsId;
        }

        if (null !== $request->currentPage) {
            @$query['CurrentPage'] = $request->currentPage;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->phoneNumber) {
            @$query['PhoneNumber'] = $request->phoneNumber;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->sendDate) {
            @$query['SendDate'] = $request->sendDate;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetCardSmsDetails',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetCardSmsDetailsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetCardSmsDetailsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Query card sending details.
     *
     * @param request - GetCardSmsDetailsRequest
     *
     * @returns GetCardSmsDetailsResponse
     *
     * @param GetCardSmsDetailsRequest $request
     *
     * @return GetCardSmsDetailsResponse
     */
    public function getCardSmsDetails($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getCardSmsDetailsWithOptions($request, $runtime);
    }

    /**
     * Queries the short URLs of a card messages template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - GetCardSmsLinkRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetCardSmsLinkResponse
     *
     * @param GetCardSmsLinkRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return GetCardSmsLinkResponse
     */
    public function getCardSmsLinkWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->cardCodeType) {
            @$query['CardCodeType'] = $request->cardCodeType;
        }

        if (null !== $request->cardLinkType) {
            @$query['CardLinkType'] = $request->cardLinkType;
        }

        if (null !== $request->cardTemplateCode) {
            @$query['CardTemplateCode'] = $request->cardTemplateCode;
        }

        if (null !== $request->cardTemplateParamJson) {
            @$query['CardTemplateParamJson'] = $request->cardTemplateParamJson;
        }

        if (null !== $request->customShortCodeJson) {
            @$query['CustomShortCodeJson'] = $request->customShortCodeJson;
        }

        if (null !== $request->domain) {
            @$query['Domain'] = $request->domain;
        }

        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->phoneNumberJson) {
            @$query['PhoneNumberJson'] = $request->phoneNumberJson;
        }

        if (null !== $request->signNameJson) {
            @$query['SignNameJson'] = $request->signNameJson;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetCardSmsLink',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetCardSmsLinkResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetCardSmsLinkResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the short URLs of a card messages template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - GetCardSmsLinkRequest
     *
     * @returns GetCardSmsLinkResponse
     *
     * @param GetCardSmsLinkRequest $request
     *
     * @return GetCardSmsLinkResponse
     */
    public function getCardSmsLink($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getCardSmsLinkWithOptions($request, $runtime);
    }

    /**
     * Converts a resource uploaded to the specified Object Storage Service (OSS) bucket for unified management. Then, a resource ID is returned. You can manage the resource based on the ID.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - GetMediaResourceIdRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetMediaResourceIdResponse
     *
     * @param GetMediaResourceIdRequest $request
     * @param RuntimeOptions            $runtime
     *
     * @return GetMediaResourceIdResponse
     */
    public function getMediaResourceIdWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->extendInfo) {
            @$query['ExtendInfo'] = $request->extendInfo;
        }

        if (null !== $request->fileSize) {
            @$query['FileSize'] = $request->fileSize;
        }

        if (null !== $request->memo) {
            @$query['Memo'] = $request->memo;
        }

        if (null !== $request->ossKey) {
            @$query['OssKey'] = $request->ossKey;
        }

        if (null !== $request->resourceType) {
            @$query['ResourceType'] = $request->resourceType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetMediaResourceId',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetMediaResourceIdResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetMediaResourceIdResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Converts a resource uploaded to the specified Object Storage Service (OSS) bucket for unified management. Then, a resource ID is returned. You can manage the resource based on the ID.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - GetMediaResourceIdRequest
     *
     * @returns GetMediaResourceIdResponse
     *
     * @param GetMediaResourceIdRequest $request
     *
     * @return GetMediaResourceIdResponse
     */
    public function getMediaResourceId($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getMediaResourceIdWithOptions($request, $runtime);
    }

    /**
     * Queries the OSS configuration information about card messages.
     *
     * @remarks
     * Resources such as images and videos used for card message templates can be uploaded to Object Storage Service (OSS) buckets for storage. For more information, see [Upload files to OSS](https://help.aliyun.com/document_detail/437303.html).
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - GetOSSInfoForCardTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetOSSInfoForCardTemplateResponse
     *
     * @param RuntimeOptions $runtime
     *
     * @return GetOSSInfoForCardTemplateResponse
     */
    public function getOSSInfoForCardTemplateWithOptions($runtime)
    {
        $req = new OpenApiRequest([]);
        $params = new Params([
            'action' => 'GetOSSInfoForCardTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetOSSInfoForCardTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetOSSInfoForCardTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the OSS configuration information about card messages.
     *
     * @remarks
     * Resources such as images and videos used for card message templates can be uploaded to Object Storage Service (OSS) buckets for storage. For more information, see [Upload files to OSS](https://help.aliyun.com/document_detail/437303.html).
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @returns GetOSSInfoForCardTemplateResponse
     *
     * @return GetOSSInfoForCardTemplateResponse
     */
    public function getOSSInfoForCardTemplate()
    {
        $runtime = new RuntimeOptions([]);

        return $this->getOSSInfoForCardTemplateWithOptions($runtime);
    }

    /**
     * SMS File Upload, Get Authorization Info.
     *
     * @remarks
     * - When creating signatures or templates, you can upload materials such as login pages with links, backend page screenshots, software copyrights, supplementary agreements, etc. This helps the review personnel understand your business details. If there are multiple materials, they can be combined into one file, supporting png, jpg, jpeg, doc, docx, pdf formats.
     * - For additional materials needed when creating signatures or templates, you can upload them to the OSS file system for storage. For file upload operations, refer to [OSS File Upload](https://help.aliyun.com/zh/sms/upload-files-through-oss).
     *
     * @param request - GetOSSInfoForUploadFileRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetOSSInfoForUploadFileResponse
     *
     * @param GetOSSInfoForUploadFileRequest $request
     * @param RuntimeOptions                 $runtime
     *
     * @return GetOSSInfoForUploadFileResponse
     */
    public function getOSSInfoForUploadFileWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->bizType) {
            @$query['BizType'] = $request->bizType;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetOSSInfoForUploadFile',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetOSSInfoForUploadFileResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetOSSInfoForUploadFileResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * SMS File Upload, Get Authorization Info.
     *
     * @remarks
     * - When creating signatures or templates, you can upload materials such as login pages with links, backend page screenshots, software copyrights, supplementary agreements, etc. This helps the review personnel understand your business details. If there are multiple materials, they can be combined into one file, supporting png, jpg, jpeg, doc, docx, pdf formats.
     * - For additional materials needed when creating signatures or templates, you can upload them to the OSS file system for storage. For file upload operations, refer to [OSS File Upload](https://help.aliyun.com/zh/sms/upload-files-through-oss).
     *
     * @param request - GetOSSInfoForUploadFileRequest
     *
     * @returns GetOSSInfoForUploadFileResponse
     *
     * @param GetOSSInfoForUploadFileRequest $request
     *
     * @return GetOSSInfoForUploadFileResponse
     */
    public function getOSSInfoForUploadFile($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getOSSInfoForUploadFileWithOptions($request, $runtime);
    }

    /**
     * Query SMS Signature Details.
     *
     * @remarks
     * - For details about the changes of this new interface and the original one, please refer to [Announcement on the Update of SMS Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Review Time: Generally, after submitting the signature, Alibaba Cloud expects to complete the review within 2 hours (Review Business Hours: Monday to Sunday 9:00~21:00, with legal holidays postponed). It is recommended to submit your application before 18:00.
     * - If the signature fails the review, the reason for the failure will be returned. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm), invoke the [UpdateSmsSign](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-updatesmssign?spm) API, or modify the unapproved SMS signature on the [Signature Management](https://dysms.console.aliyun.com/domestic/text/sign) page.
     *
     * @param request - GetSmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetSmsSignResponse
     *
     * @param GetSmsSignRequest $request
     * @param RuntimeOptions    $runtime
     *
     * @return GetSmsSignResponse
     */
    public function getSmsSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetSmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetSmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetSmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Query SMS Signature Details.
     *
     * @remarks
     * - For details about the changes of this new interface and the original one, please refer to [Announcement on the Update of SMS Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Review Time: Generally, after submitting the signature, Alibaba Cloud expects to complete the review within 2 hours (Review Business Hours: Monday to Sunday 9:00~21:00, with legal holidays postponed). It is recommended to submit your application before 18:00.
     * - If the signature fails the review, the reason for the failure will be returned. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm), invoke the [UpdateSmsSign](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-updatesmssign?spm) API, or modify the unapproved SMS signature on the [Signature Management](https://dysms.console.aliyun.com/domestic/text/sign) page.
     *
     * @param request - GetSmsSignRequest
     *
     * @returns GetSmsSignResponse
     *
     * @param GetSmsSignRequest $request
     *
     * @return GetSmsSignResponse
     */
    public function getSmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getSmsSignWithOptions($request, $runtime);
    }

    /**
     * Query Text SMS Template Details.
     *
     * @remarks
     * - For details about the announcement of changes to the new and original interfaces, see [Announcement on Updates to SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Review Time: Under normal circumstances, Alibaba Cloud expects to complete the review within 2 hours after template submission (review working hours: Monday to Sunday 9:00~21:00, with statutory holidays postponed). It is recommended to submit your application before 18:00.
     * - If the template fails the review, the reason for the failure will be returned. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm=a2c4g.11186623.0.0.41fd339f3bPSCQ), invoke the [ModifySmsTemplate](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-modifysmstemplate?spm=a2c4g.11186623.0.0.5b1f6e8bQloFit) API or modify the SMS template on the [Template Management](https://dysms.console.aliyun.com/domestic/text/template) page.
     * - The current QuerySmsTemplate interface queries the audit details of a single template by template code. The [QuerySmsTemplateList](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-querysmstemplatelist?spm=a2c4g.11186623.0.0.24086e8bO8cFn4) interface can query the template details of all templates under your current account.
     *
     * @param request - GetSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns GetSmsTemplateResponse
     *
     * @param GetSmsTemplateRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return GetSmsTemplateResponse
     */
    public function getSmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'GetSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return GetSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return GetSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Query Text SMS Template Details.
     *
     * @remarks
     * - For details about the announcement of changes to the new and original interfaces, see [Announcement on Updates to SMS Service Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Review Time: Under normal circumstances, Alibaba Cloud expects to complete the review within 2 hours after template submission (review working hours: Monday to Sunday 9:00~21:00, with statutory holidays postponed). It is recommended to submit your application before 18:00.
     * - If the template fails the review, the reason for the failure will be returned. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm=a2c4g.11186623.0.0.41fd339f3bPSCQ), invoke the [ModifySmsTemplate](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-modifysmstemplate?spm=a2c4g.11186623.0.0.5b1f6e8bQloFit) API or modify the SMS template on the [Template Management](https://dysms.console.aliyun.com/domestic/text/template) page.
     * - The current QuerySmsTemplate interface queries the audit details of a single template by template code. The [QuerySmsTemplateList](https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-querysmstemplatelist?spm=a2c4g.11186623.0.0.24086e8bO8cFn4) interface can query the template details of all templates under your current account.
     *
     * @param request - GetSmsTemplateRequest
     *
     * @returns GetSmsTemplateResponse
     *
     * @param GetSmsTemplateRequest $request
     *
     * @return GetSmsTemplateResponse
     */
    public function getSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->getSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Queries the tags of a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - ListTagResourcesRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns ListTagResourcesResponse
     *
     * @param ListTagResourcesRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return ListTagResourcesResponse
     */
    public function listTagResourcesWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->nextToken) {
            @$query['NextToken'] = $request->nextToken;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->prodCode) {
            @$query['ProdCode'] = $request->prodCode;
        }

        if (null !== $request->regionId) {
            @$query['RegionId'] = $request->regionId;
        }

        if (null !== $request->resourceId) {
            @$query['ResourceId'] = $request->resourceId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->resourceType) {
            @$query['ResourceType'] = $request->resourceType;
        }

        if (null !== $request->tag) {
            @$query['Tag'] = $request->tag;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'ListTagResources',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return ListTagResourcesResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return ListTagResourcesResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the tags of a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - ListTagResourcesRequest
     *
     * @returns ListTagResourcesResponse
     *
     * @param ListTagResourcesRequest $request
     *
     * @return ListTagResourcesResponse
     */
    public function listTagResources($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->listTagResourcesWithOptions($request, $runtime);
    }

    /**
     * Modifies a rejected signature and submit it for approval. Signatures that are pending approval or have been approved cannot be modified.
     *
     * @remarks
     * You can call the operation or use the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview) to modify an existing signature and submit the signature for approval. The signature must comply with the [signature specifications](https://help.aliyun.com/document_detail/108076.html).
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     * >
     * *   Signatures pending approval cannot be modified.
     * *   You cannot modify a signature after it is approved. If you no longer need the signature, you can delete it.
     * *   If you are an individual user, you cannot apply for a new signature on the same day that your signature is rejected or deleted. We recommend that you modify the rejected signature and submit it again.
     *
     * @param request - ModifySmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns ModifySmsSignResponse
     *
     * @param ModifySmsSignRequest $request
     * @param RuntimeOptions       $runtime
     *
     * @return ModifySmsSignResponse
     */
    public function modifySmsSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->signSource) {
            @$query['SignSource'] = $request->signSource;
        }

        if (null !== $request->signType) {
            @$query['SignType'] = $request->signType;
        }

        $body = [];
        if (null !== $request->signFileList) {
            @$body['SignFileList'] = $request->signFileList;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'ModifySmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return ModifySmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return ModifySmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Modifies a rejected signature and submit it for approval. Signatures that are pending approval or have been approved cannot be modified.
     *
     * @remarks
     * You can call the operation or use the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm#/overview) to modify an existing signature and submit the signature for approval. The signature must comply with the [signature specifications](https://help.aliyun.com/document_detail/108076.html).
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limits
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     * >
     * *   Signatures pending approval cannot be modified.
     * *   You cannot modify a signature after it is approved. If you no longer need the signature, you can delete it.
     * *   If you are an individual user, you cannot apply for a new signature on the same day that your signature is rejected or deleted. We recommend that you modify the rejected signature and submit it again.
     *
     * @param request - ModifySmsSignRequest
     *
     * @returns ModifySmsSignResponse
     *
     * @param ModifySmsSignRequest $request
     *
     * @return ModifySmsSignResponse
     */
    public function modifySmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->modifySmsSignWithOptions($request, $runtime);
    }

    // Deprecated
    /**
     * Modifies the information of an unapproved message template and submits it for review again.
     *
     * @remarks
     * After you apply for a message template, if the template fails to pass the review, you can call this operation to modify the template and submit the template again. You can call this operation to modify only a template for a specific message type.
     * The template content must comply with the [SMS template specifications](https://help.aliyun.com/document_detail/108253.html).
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI ModifySmsTemplate is deprecated, please use Dysmsapi::2017-05-25::UpdateSmsTemplate instead
     *
     * @param request - ModifySmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns ModifySmsTemplateResponse
     *
     * @param ModifySmsTemplateRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return ModifySmsTemplateResponse
     */
    public function modifySmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        if (null !== $request->templateContent) {
            @$query['TemplateContent'] = $request->templateContent;
        }

        if (null !== $request->templateName) {
            @$query['TemplateName'] = $request->templateName;
        }

        if (null !== $request->templateType) {
            @$query['TemplateType'] = $request->templateType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'ModifySmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return ModifySmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return ModifySmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    // Deprecated
    /**
     * Modifies the information of an unapproved message template and submits it for review again.
     *
     * @remarks
     * After you apply for a message template, if the template fails to pass the review, you can call this operation to modify the template and submit the template again. You can call this operation to modify only a template for a specific message type.
     * The template content must comply with the [SMS template specifications](https://help.aliyun.com/document_detail/108253.html).
     * For more information, see [Usage notes](https://help.aliyun.com/document_detail/55324.html).
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI ModifySmsTemplate is deprecated, please use Dysmsapi::2017-05-25::UpdateSmsTemplate instead
     *
     * @param request - ModifySmsTemplateRequest
     *
     * @returns ModifySmsTemplateResponse
     *
     * @param ModifySmsTemplateRequest $request
     *
     * @return ModifySmsTemplateResponse
     */
    public function modifySmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->modifySmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Queries the review status of a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryCardSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryCardSmsTemplateResponse
     *
     * @param QueryCardSmsTemplateRequest $request
     * @param RuntimeOptions              $runtime
     *
     * @return QueryCardSmsTemplateResponse
     */
    public function queryCardSmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QueryCardSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryCardSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryCardSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the review status of a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryCardSmsTemplateRequest
     *
     * @returns QueryCardSmsTemplateResponse
     *
     * @param QueryCardSmsTemplateRequest $request
     *
     * @return QueryCardSmsTemplateResponse
     */
    public function queryCardSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryCardSmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Queries sent card messages.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryCardSmsTemplateReportRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryCardSmsTemplateReportResponse
     *
     * @param QueryCardSmsTemplateReportRequest $request
     * @param RuntimeOptions                    $runtime
     *
     * @return QueryCardSmsTemplateReportResponse
     */
    public function queryCardSmsTemplateReportWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->endDate) {
            @$query['EndDate'] = $request->endDate;
        }

        if (null !== $request->startDate) {
            @$query['StartDate'] = $request->startDate;
        }

        if (null !== $request->templateCodes) {
            @$query['TemplateCodes'] = $request->templateCodes;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QueryCardSmsTemplateReport',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryCardSmsTemplateReportResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryCardSmsTemplateReportResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries sent card messages.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 300 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryCardSmsTemplateReportRequest
     *
     * @returns QueryCardSmsTemplateReportResponse
     *
     * @param QueryCardSmsTemplateReportRequest $request
     *
     * @return QueryCardSmsTemplateReportResponse
     */
    public function queryCardSmsTemplateReport($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryCardSmsTemplateReportWithOptions($request, $runtime);
    }

    /**
     * 查询验证码签名.
     *
     * @param request - QueryExtCodeSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryExtCodeSignResponse
     *
     * @param QueryExtCodeSignRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return QueryExtCodeSignResponse
     */
    public function queryExtCodeSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->extCode) {
            @$query['ExtCode'] = $request->extCode;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageNo) {
            @$query['PageNo'] = $request->pageNo;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QueryExtCodeSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryExtCodeSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryExtCodeSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 查询验证码签名.
     *
     * @param request - QueryExtCodeSignRequest
     *
     * @returns QueryExtCodeSignResponse
     *
     * @param QueryExtCodeSignRequest $request
     *
     * @return QueryExtCodeSignResponse
     */
    public function queryExtCodeSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryExtCodeSignWithOptions($request, $runtime);
    }

    /**
     * Checks whether a mobile phone number can receive card messages.
     *
     * @param tmpReq - QueryMobilesCardSupportRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryMobilesCardSupportResponse
     *
     * @param QueryMobilesCardSupportRequest $tmpReq
     * @param RuntimeOptions                 $runtime
     *
     * @return QueryMobilesCardSupportResponse
     */
    public function queryMobilesCardSupportWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new QueryMobilesCardSupportShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->mobiles) {
            $request->mobilesShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->mobiles, 'Mobiles', 'json');
        }

        $query = [];
        if (null !== $request->mobilesShrink) {
            @$query['Mobiles'] = $request->mobilesShrink;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QueryMobilesCardSupport',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryMobilesCardSupportResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryMobilesCardSupportResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Checks whether a mobile phone number can receive card messages.
     *
     * @param request - QueryMobilesCardSupportRequest
     *
     * @returns QueryMobilesCardSupportResponse
     *
     * @param QueryMobilesCardSupportRequest $request
     *
     * @return QueryMobilesCardSupportResponse
     */
    public function queryMobilesCardSupport($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryMobilesCardSupportWithOptions($request, $runtime);
    }

    /**
     * 点击明细查询.
     *
     * @param request - QueryPageSmartShortUrlLogRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryPageSmartShortUrlLogResponse
     *
     * @param QueryPageSmartShortUrlLogRequest $request
     * @param RuntimeOptions                   $runtime
     *
     * @return QueryPageSmartShortUrlLogResponse
     */
    public function queryPageSmartShortUrlLogWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->createDateEnd) {
            @$query['CreateDateEnd'] = $request->createDateEnd;
        }

        if (null !== $request->createDateStart) {
            @$query['CreateDateStart'] = $request->createDateStart;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageNo) {
            @$query['PageNo'] = $request->pageNo;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->phoneNumber) {
            @$query['PhoneNumber'] = $request->phoneNumber;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->shortUrl) {
            @$query['ShortUrl'] = $request->shortUrl;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QueryPageSmartShortUrlLog',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryPageSmartShortUrlLogResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryPageSmartShortUrlLogResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 点击明细查询.
     *
     * @param request - QueryPageSmartShortUrlLogRequest
     *
     * @returns QueryPageSmartShortUrlLogResponse
     *
     * @param QueryPageSmartShortUrlLogRequest $request
     *
     * @return QueryPageSmartShortUrlLogResponse
     */
    public function queryPageSmartShortUrlLog($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryPageSmartShortUrlLogWithOptions($request, $runtime);
    }

    /**
     * Queries the information about a message.
     *
     * @param request - QuerySendDetailsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySendDetailsResponse
     *
     * @param QuerySendDetailsRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return QuerySendDetailsResponse
     */
    public function querySendDetailsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->bizId) {
            @$query['BizId'] = $request->bizId;
        }

        if (null !== $request->currentPage) {
            @$query['CurrentPage'] = $request->currentPage;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->phoneNumber) {
            @$query['PhoneNumber'] = $request->phoneNumber;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->sendDate) {
            @$query['SendDate'] = $request->sendDate;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySendDetails',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySendDetailsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySendDetailsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the information about a message.
     *
     * @param request - QuerySendDetailsRequest
     *
     * @returns QuerySendDetailsResponse
     *
     * @param QuerySendDetailsRequest $request
     *
     * @return QuerySendDetailsResponse
     */
    public function querySendDetails($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySendDetailsWithOptions($request, $runtime);
    }

    /**
     * Queries message delivery details.
     *
     * @remarks
     * You can call the operation to query message delivery details, including the number of delivered messages, the number of messages with delivery receipts, and the time that a message is sent. If a large number of messages are sent on the specified date, you can specify the number of items displayed on each page and the number of pages to view the details by page.
     * ### QPS limits
     * You can call this operation up to 20 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySendStatisticsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySendStatisticsResponse
     *
     * @param QuerySendStatisticsRequest $request
     * @param RuntimeOptions             $runtime
     *
     * @return QuerySendStatisticsResponse
     */
    public function querySendStatisticsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->endDate) {
            @$query['EndDate'] = $request->endDate;
        }

        if (null !== $request->isGlobe) {
            @$query['IsGlobe'] = $request->isGlobe;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageIndex) {
            @$query['PageIndex'] = $request->pageIndex;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->startDate) {
            @$query['StartDate'] = $request->startDate;
        }

        if (null !== $request->templateType) {
            @$query['TemplateType'] = $request->templateType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySendStatistics',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySendStatisticsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySendStatisticsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries message delivery details.
     *
     * @remarks
     * You can call the operation to query message delivery details, including the number of delivered messages, the number of messages with delivery receipts, and the time that a message is sent. If a large number of messages are sent on the specified date, you can specify the number of items displayed on each page and the number of pages to view the details by page.
     * ### QPS limits
     * You can call this operation up to 20 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySendStatisticsRequest
     *
     * @returns QuerySendStatisticsResponse
     *
     * @param QuerySendStatisticsRequest $request
     *
     * @return QuerySendStatisticsResponse
     */
    public function querySendStatistics($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySendStatisticsWithOptions($request, $runtime);
    }

    /**
     * Queries the status of a short URL.
     *
     * @remarks
     * ### QPS limits
     * You can call this operation up to 20 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryShortUrlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QueryShortUrlResponse
     *
     * @param QueryShortUrlRequest $request
     * @param RuntimeOptions       $runtime
     *
     * @return QueryShortUrlResponse
     */
    public function queryShortUrlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $body = [];
        if (null !== $request->shortUrl) {
            @$body['ShortUrl'] = $request->shortUrl;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'QueryShortUrl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QueryShortUrlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QueryShortUrlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the status of a short URL.
     *
     * @remarks
     * ### QPS limits
     * You can call this operation up to 20 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QueryShortUrlRequest
     *
     * @returns QueryShortUrlResponse
     *
     * @param QueryShortUrlRequest $request
     *
     * @return QueryShortUrlResponse
     */
    public function queryShortUrl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->queryShortUrlWithOptions($request, $runtime);
    }

    /**
     * Queries the status of a signature.
     *
     * @remarks
     * After you apply for an SMS signature, you can query its status by using the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm) or calling the operation. If the signature is rejected, you can modify the signature based on the reason why it is rejected.
     * ### QPS limits
     * You can call this API operation up to 500 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySmsSignResponse
     *
     * @param QuerySmsSignRequest $request
     * @param RuntimeOptions      $runtime
     *
     * @return QuerySmsSignResponse
     */
    public function querySmsSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries the status of a signature.
     *
     * @remarks
     * After you apply for an SMS signature, you can query its status by using the [Alibaba Cloud SMS console](https://dysms.console.aliyun.com/dysms.htm) or calling the operation. If the signature is rejected, you can modify the signature based on the reason why it is rejected.
     * ### QPS limits
     * You can call this API operation up to 500 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsSignRequest
     *
     * @returns QuerySmsSignResponse
     *
     * @param QuerySmsSignRequest $request
     *
     * @return QuerySmsSignResponse
     */
    public function querySmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySmsSignWithOptions($request, $runtime);
    }

    /**
     * Queries message signatures by page.
     *
     * @remarks
     * You can call this operation to query the details of message signatures, including the name, creation time, and approval status of each signature. If a message template is rejected, the reason is returned. Modify the message signature based on the reason.
     * ### QPS limit
     * You can call this operation up to 10 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsSignListRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySmsSignListResponse
     *
     * @param QuerySmsSignListRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return QuerySmsSignListResponse
     */
    public function querySmsSignListWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageIndex) {
            @$query['PageIndex'] = $request->pageIndex;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySmsSignList',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySmsSignListResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySmsSignListResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries message signatures by page.
     *
     * @remarks
     * You can call this operation to query the details of message signatures, including the name, creation time, and approval status of each signature. If a message template is rejected, the reason is returned. Modify the message signature based on the reason.
     * ### QPS limit
     * You can call this operation up to 10 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsSignListRequest
     *
     * @returns QuerySmsSignListResponse
     *
     * @param QuerySmsSignListRequest $request
     *
     * @return QuerySmsSignListResponse
     */
    public function querySmsSignList($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySmsSignListWithOptions($request, $runtime);
    }

    // Deprecated
    /**
     * Queries the approval status of a message template.
     *
     * @remarks
     * After you create a message template, you can call this operation to query the approval status of the template. If a message template is rejected, the reason is returned. Modify the message template based on the reason.
     * ### QPS limit
     * You can call this operation up to 5,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI QuerySmsTemplate is deprecated, please use Dysmsapi::2017-05-25::GetSmsTemplate instead
     *
     * @param request - QuerySmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySmsTemplateResponse
     *
     * @param QuerySmsTemplateRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return QuerySmsTemplateResponse
     */
    public function querySmsTemplateWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    // Deprecated
    /**
     * Queries the approval status of a message template.
     *
     * @remarks
     * After you create a message template, you can call this operation to query the approval status of the template. If a message template is rejected, the reason is returned. Modify the message template based on the reason.
     * ### QPS limit
     * You can call this operation up to 5,000 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @deprecated openAPI QuerySmsTemplate is deprecated, please use Dysmsapi::2017-05-25::GetSmsTemplate instead
     *
     * @param request - QuerySmsTemplateRequest
     *
     * @returns QuerySmsTemplateResponse
     *
     * @param QuerySmsTemplateRequest $request
     *
     * @return QuerySmsTemplateResponse
     */
    public function querySmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySmsTemplateWithOptions($request, $runtime);
    }

    /**
     * Queries message templates.
     *
     * @remarks
     * You can call this operation to query the details of message templates, including the name, creation time, and approval status of each template. If a message template is rejected, the reason is returned. Modify the message template based on the reason.
     * ### QPS limit
     * You can call this operation up to 10 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsTemplateListRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns QuerySmsTemplateListResponse
     *
     * @param QuerySmsTemplateListRequest $request
     * @param RuntimeOptions              $runtime
     *
     * @return QuerySmsTemplateListResponse
     */
    public function querySmsTemplateListWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->pageIndex) {
            @$query['PageIndex'] = $request->pageIndex;
        }

        if (null !== $request->pageSize) {
            @$query['PageSize'] = $request->pageSize;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'QuerySmsTemplateList',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return QuerySmsTemplateListResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return QuerySmsTemplateListResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Queries message templates.
     *
     * @remarks
     * You can call this operation to query the details of message templates, including the name, creation time, and approval status of each template. If a message template is rejected, the reason is returned. Modify the message template based on the reason.
     * ### QPS limit
     * You can call this operation up to 10 times per second per account. If the number of calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - QuerySmsTemplateListRequest
     *
     * @returns QuerySmsTemplateListResponse
     *
     * @param QuerySmsTemplateListRequest $request
     *
     * @return QuerySmsTemplateListResponse
     */
    public function querySmsTemplateList($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->querySmsTemplateListWithOptions($request, $runtime);
    }

    /**
     * Sends multiple card messages at a time.
     *
     * @remarks
     * You can call the operation to send multiple card messages to a maximum of mobile phone numbers at a time. Different signatures and rollback settings can be specified for the mobile phone numbers.
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - SendBatchCardSmsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns SendBatchCardSmsResponse
     *
     * @param SendBatchCardSmsRequest $request
     * @param RuntimeOptions          $runtime
     *
     * @return SendBatchCardSmsResponse
     */
    public function sendBatchCardSmsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->cardTemplateCode) {
            @$query['CardTemplateCode'] = $request->cardTemplateCode;
        }

        if (null !== $request->cardTemplateParamJson) {
            @$query['CardTemplateParamJson'] = $request->cardTemplateParamJson;
        }

        if (null !== $request->digitalTemplateCode) {
            @$query['DigitalTemplateCode'] = $request->digitalTemplateCode;
        }

        if (null !== $request->digitalTemplateParamJson) {
            @$query['DigitalTemplateParamJson'] = $request->digitalTemplateParamJson;
        }

        if (null !== $request->fallbackType) {
            @$query['FallbackType'] = $request->fallbackType;
        }

        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->phoneNumberJson) {
            @$query['PhoneNumberJson'] = $request->phoneNumberJson;
        }

        if (null !== $request->signNameJson) {
            @$query['SignNameJson'] = $request->signNameJson;
        }

        if (null !== $request->smsTemplateCode) {
            @$query['SmsTemplateCode'] = $request->smsTemplateCode;
        }

        if (null !== $request->smsTemplateParamJson) {
            @$query['SmsTemplateParamJson'] = $request->smsTemplateParamJson;
        }

        if (null !== $request->smsUpExtendCodeJson) {
            @$query['SmsUpExtendCodeJson'] = $request->smsUpExtendCodeJson;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        if (null !== $request->templateParamJson) {
            @$query['TemplateParamJson'] = $request->templateParamJson;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'SendBatchCardSms',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return SendBatchCardSmsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return SendBatchCardSmsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Sends multiple card messages at a time.
     *
     * @remarks
     * You can call the operation to send multiple card messages to a maximum of mobile phone numbers at a time. Different signatures and rollback settings can be specified for the mobile phone numbers.
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - SendBatchCardSmsRequest
     *
     * @returns SendBatchCardSmsResponse
     *
     * @param SendBatchCardSmsRequest $request
     *
     * @return SendBatchCardSmsResponse
     */
    public function sendBatchCardSms($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->sendBatchCardSmsWithOptions($request, $runtime);
    }

    /**
     * Uses a single message template and multiple signatures to send messages to multiple recipients.
     *
     * @remarks
     * You can call the operation to send messages to a maximum of 100 recipients at a time.
     *
     * @param request - SendBatchSmsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns SendBatchSmsResponse
     *
     * @param SendBatchSmsRequest $request
     * @param RuntimeOptions      $runtime
     *
     * @return SendBatchSmsResponse
     */
    public function sendBatchSmsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        $body = [];
        if (null !== $request->phoneNumberJson) {
            @$body['PhoneNumberJson'] = $request->phoneNumberJson;
        }

        if (null !== $request->signNameJson) {
            @$body['SignNameJson'] = $request->signNameJson;
        }

        if (null !== $request->smsUpExtendCodeJson) {
            @$body['SmsUpExtendCodeJson'] = $request->smsUpExtendCodeJson;
        }

        if (null !== $request->templateParamJson) {
            @$body['TemplateParamJson'] = $request->templateParamJson;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
            'body' => Utils::parseToMap($body),
        ]);
        $params = new Params([
            'action' => 'SendBatchSms',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return SendBatchSmsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return SendBatchSmsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Uses a single message template and multiple signatures to send messages to multiple recipients.
     *
     * @remarks
     * You can call the operation to send messages to a maximum of 100 recipients at a time.
     *
     * @param request - SendBatchSmsRequest
     *
     * @returns SendBatchSmsResponse
     *
     * @param SendBatchSmsRequest $request
     *
     * @return SendBatchSmsResponse
     */
    public function sendBatchSms($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->sendBatchSmsWithOptions($request, $runtime);
    }

    /**
     * Sends a card message.
     *
     * @remarks
     *   Make sure that the message template that you want to use has been approved. If the mobile phone number of a recipient does not support card messages, the SendCardSms operation allows the rollback feature to ensure successful delivery.
     * *   When you call the SendCardSms operation to send card messages, the operation checks whether the mobile phone numbers of the recipients support card messages. If the mobile phone numbers do not support card messages, you can specify whether to enable rollback. Otherwise, the card message cannot be delivered.
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - SendCardSmsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns SendCardSmsResponse
     *
     * @param SendCardSmsRequest $request
     * @param RuntimeOptions     $runtime
     *
     * @return SendCardSmsResponse
     */
    public function sendCardSmsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->cardObjects) {
            @$query['CardObjects'] = $request->cardObjects;
        }

        if (null !== $request->cardTemplateCode) {
            @$query['CardTemplateCode'] = $request->cardTemplateCode;
        }

        if (null !== $request->digitalTemplateCode) {
            @$query['DigitalTemplateCode'] = $request->digitalTemplateCode;
        }

        if (null !== $request->digitalTemplateParam) {
            @$query['DigitalTemplateParam'] = $request->digitalTemplateParam;
        }

        if (null !== $request->fallbackType) {
            @$query['FallbackType'] = $request->fallbackType;
        }

        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->smsTemplateCode) {
            @$query['SmsTemplateCode'] = $request->smsTemplateCode;
        }

        if (null !== $request->smsTemplateParam) {
            @$query['SmsTemplateParam'] = $request->smsTemplateParam;
        }

        if (null !== $request->smsUpExtendCode) {
            @$query['SmsUpExtendCode'] = $request->smsUpExtendCode;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        if (null !== $request->templateParam) {
            @$query['TemplateParam'] = $request->templateParam;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'SendCardSms',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return SendCardSmsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return SendCardSmsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Sends a card message.
     *
     * @remarks
     *   Make sure that the message template that you want to use has been approved. If the mobile phone number of a recipient does not support card messages, the SendCardSms operation allows the rollback feature to ensure successful delivery.
     * *   When you call the SendCardSms operation to send card messages, the operation checks whether the mobile phone numbers of the recipients support card messages. If the mobile phone numbers do not support card messages, you can specify whether to enable rollback. Otherwise, the card message cannot be delivered.
     * ### QPS limit
     * You can call this operation up to 1,000 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - SendCardSmsRequest
     *
     * @returns SendCardSmsResponse
     *
     * @param SendCardSmsRequest $request
     *
     * @return SendCardSmsResponse
     */
    public function sendCardSms($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->sendCardSmsWithOptions($request, $runtime);
    }

    /**
     * Sends a message. Before you call this operation, submit a message signature and message template, and make sure that the signature and template are approved.
     *
     * @remarks
     *   This operation is mainly used to send a single message. In special scenarios, you can send multiple messages with the same content to a maximum of 1,000 mobile numbers. Note that group sending may be delayed.
     * *   To send messages with different signatures and template content to multiple mobile numbers in a single request, call the [SendBatchSms](https://help.aliyun.com/document_detail/102364.html) operation.
     * *   You are charged for using Alibaba Cloud Short Message Service (SMS) based on the amount of messages sent. For more information, see [Pricing](https://www.aliyun.com/price/product#/sms/detail).
     * *   If your verification code signature and general-purpose signature have the same name, the system uses the general-purpose signature to send messages by default.
     *
     * @param request - SendSmsRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns SendSmsResponse
     *
     * @param SendSmsRequest $request
     * @param RuntimeOptions $runtime
     *
     * @return SendSmsResponse
     */
    public function sendSmsWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->outId) {
            @$query['OutId'] = $request->outId;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->phoneNumbers) {
            @$query['PhoneNumbers'] = $request->phoneNumbers;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->smsUpExtendCode) {
            @$query['SmsUpExtendCode'] = $request->smsUpExtendCode;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        if (null !== $request->templateParam) {
            @$query['TemplateParam'] = $request->templateParam;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'SendSms',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return SendSmsResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return SendSmsResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Sends a message. Before you call this operation, submit a message signature and message template, and make sure that the signature and template are approved.
     *
     * @remarks
     *   This operation is mainly used to send a single message. In special scenarios, you can send multiple messages with the same content to a maximum of 1,000 mobile numbers. Note that group sending may be delayed.
     * *   To send messages with different signatures and template content to multiple mobile numbers in a single request, call the [SendBatchSms](https://help.aliyun.com/document_detail/102364.html) operation.
     * *   You are charged for using Alibaba Cloud Short Message Service (SMS) based on the amount of messages sent. For more information, see [Pricing](https://www.aliyun.com/price/product#/sms/detail).
     * *   If your verification code signature and general-purpose signature have the same name, the system uses the general-purpose signature to send messages by default.
     *
     * @param request - SendSmsRequest
     *
     * @returns SendSmsResponse
     *
     * @param SendSmsRequest $request
     *
     * @return SendSmsResponse
     */
    public function sendSms($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->sendSmsWithOptions($request, $runtime);
    }

    /**
     * Reports the status of an OTP message to Alibaba Cloud SMS.
     *
     * @remarks
     * Metrics:
     * *   Requested OTP messages
     * *   Verified OTP messages
     * An OTP conversion rate is calculated based on the following formula: OTP conversion rate = Number of verified OTP messages/Number of requested OTP messages.
     * > If you call the SmsConversion operation to query OTP conversion rates, your business may be affected. We recommend that you perform the following operations: 1. Call the SmsConversion operation in an asynchronous manner by configuring queues or events. 2. Manually degrade your services or use a circuit breaker to automatically degrade services.
     *
     * @param request - SmsConversionIntlRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns SmsConversionIntlResponse
     *
     * @param SmsConversionIntlRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return SmsConversionIntlResponse
     */
    public function smsConversionIntlWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->conversionTime) {
            @$query['ConversionTime'] = $request->conversionTime;
        }

        if (null !== $request->delivered) {
            @$query['Delivered'] = $request->delivered;
        }

        if (null !== $request->messageId) {
            @$query['MessageId'] = $request->messageId;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'SmsConversionIntl',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return SmsConversionIntlResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return SmsConversionIntlResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Reports the status of an OTP message to Alibaba Cloud SMS.
     *
     * @remarks
     * Metrics:
     * *   Requested OTP messages
     * *   Verified OTP messages
     * An OTP conversion rate is calculated based on the following formula: OTP conversion rate = Number of verified OTP messages/Number of requested OTP messages.
     * > If you call the SmsConversion operation to query OTP conversion rates, your business may be affected. We recommend that you perform the following operations: 1. Call the SmsConversion operation in an asynchronous manner by configuring queues or events. 2. Manually degrade your services or use a circuit breaker to automatically degrade services.
     *
     * @param request - SmsConversionIntlRequest
     *
     * @returns SmsConversionIntlResponse
     *
     * @param SmsConversionIntlRequest $request
     *
     * @return SmsConversionIntlResponse
     */
    public function smsConversionIntl($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->smsConversionIntlWithOptions($request, $runtime);
    }

    /**
     * Attaches tags to a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - TagResourcesRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns TagResourcesResponse
     *
     * @param TagResourcesRequest $request
     * @param RuntimeOptions      $runtime
     *
     * @return TagResourcesResponse
     */
    public function tagResourcesWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->prodCode) {
            @$query['ProdCode'] = $request->prodCode;
        }

        if (null !== $request->regionId) {
            @$query['RegionId'] = $request->regionId;
        }

        if (null !== $request->resourceId) {
            @$query['ResourceId'] = $request->resourceId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->resourceType) {
            @$query['ResourceType'] = $request->resourceType;
        }

        if (null !== $request->tag) {
            @$query['Tag'] = $request->tag;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'TagResources',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return TagResourcesResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return TagResourcesResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Attaches tags to a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - TagResourcesRequest
     *
     * @returns TagResourcesResponse
     *
     * @param TagResourcesRequest $request
     *
     * @return TagResourcesResponse
     */
    public function tagResources($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->tagResourcesWithOptions($request, $runtime);
    }

    /**
     * Deletes tags from a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - UntagResourcesRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns UntagResourcesResponse
     *
     * @param UntagResourcesRequest $request
     * @param RuntimeOptions        $runtime
     *
     * @return UntagResourcesResponse
     */
    public function untagResourcesWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->all) {
            @$query['All'] = $request->all;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->prodCode) {
            @$query['ProdCode'] = $request->prodCode;
        }

        if (null !== $request->regionId) {
            @$query['RegionId'] = $request->regionId;
        }

        if (null !== $request->resourceId) {
            @$query['ResourceId'] = $request->resourceId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->resourceType) {
            @$query['ResourceType'] = $request->resourceType;
        }

        if (null !== $request->tagKey) {
            @$query['TagKey'] = $request->tagKey;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'UntagResources',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return UntagResourcesResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return UntagResourcesResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Deletes tags from a message template.
     *
     * @remarks
     * ### QPS limit
     * You can call this operation up to 50 times per second per account. If the number of the calls per second exceeds the limit, throttling is triggered. As a result, your business may be affected. We recommend that you take note of the limit when you call this operation.
     *
     * @param request - UntagResourcesRequest
     *
     * @returns UntagResourcesResponse
     *
     * @param UntagResourcesRequest $request
     *
     * @return UntagResourcesResponse
     */
    public function untagResources($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->untagResourcesWithOptions($request, $runtime);
    }

    /**
     * 修改验证码签名.
     *
     * @param request - UpdateExtCodeSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns UpdateExtCodeSignResponse
     *
     * @param UpdateExtCodeSignRequest $request
     * @param RuntimeOptions           $runtime
     *
     * @return UpdateExtCodeSignResponse
     */
    public function updateExtCodeSignWithOptions($request, $runtime)
    {
        $request->validate();
        $query = [];
        if (null !== $request->existExtCode) {
            @$query['ExistExtCode'] = $request->existExtCode;
        }

        if (null !== $request->newExtCode) {
            @$query['NewExtCode'] = $request->newExtCode;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'UpdateExtCodeSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return UpdateExtCodeSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return UpdateExtCodeSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * 修改验证码签名.
     *
     * @param request - UpdateExtCodeSignRequest
     *
     * @returns UpdateExtCodeSignResponse
     *
     * @param UpdateExtCodeSignRequest $request
     *
     * @return UpdateExtCodeSignResponse
     */
    public function updateExtCodeSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->updateExtCodeSignWithOptions($request, $runtime);
    }

    /**
     * Update Text SMS Signature.
     *
     * @remarks
     * - For details about the changes of this new interface and the original one, please refer to [Announcement on the Update of SMS Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Only signatures that have not passed the review can be modified. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm) and call this interface to modify and resubmit for review after modification.
     * - Signature information applied through the interface will be synchronized in the SMS service console. For operations related to signatures in the console, please see [SMS Signatures](https://help.aliyun.com/zh/sms/user-guide/create-signatures?spm).
     *
     * @param tmpReq - UpdateSmsSignRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns UpdateSmsSignResponse
     *
     * @param UpdateSmsSignRequest $tmpReq
     * @param RuntimeOptions       $runtime
     *
     * @return UpdateSmsSignResponse
     */
    public function updateSmsSignWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new UpdateSmsSignShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->moreData) {
            $request->moreDataShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->moreData, 'MoreData', 'json');
        }

        $query = [];
        if (null !== $request->applySceneContent) {
            @$query['ApplySceneContent'] = $request->applySceneContent;
        }

        if (null !== $request->moreDataShrink) {
            @$query['MoreData'] = $request->moreDataShrink;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->qualificationId) {
            @$query['QualificationId'] = $request->qualificationId;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->signName) {
            @$query['SignName'] = $request->signName;
        }

        if (null !== $request->signSource) {
            @$query['SignSource'] = $request->signSource;
        }

        if (null !== $request->signType) {
            @$query['SignType'] = $request->signType;
        }

        if (null !== $request->thirdParty) {
            @$query['ThirdParty'] = $request->thirdParty;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'UpdateSmsSign',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return UpdateSmsSignResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return UpdateSmsSignResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Update Text SMS Signature.
     *
     * @remarks
     * - For details about the changes of this new interface and the original one, please refer to [Announcement on the Update of SMS Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Only signatures that have not passed the review can be modified. Please refer to [Handling Suggestions for Failed SMS Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm) and call this interface to modify and resubmit for review after modification.
     * - Signature information applied through the interface will be synchronized in the SMS service console. For operations related to signatures in the console, please see [SMS Signatures](https://help.aliyun.com/zh/sms/user-guide/create-signatures?spm).
     *
     * @param request - UpdateSmsSignRequest
     *
     * @returns UpdateSmsSignResponse
     *
     * @param UpdateSmsSignRequest $request
     *
     * @return UpdateSmsSignResponse
     */
    public function updateSmsSign($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->updateSmsSignWithOptions($request, $runtime);
    }

    /**
     * Update Text SMS Template.
     *
     * @remarks
     * - For details about the changes of this new interface compared to the original one, please refer to [Announcement on SMS Service Update: Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Only templates that have not passed the review can be modified. Please refer to [Handling Suggestions for Failed SMS Template Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm=a2c4g.11186623.0.0.4bf5561ajcFtMQ) and call this interface to modify and resubmit for review.
     * - Modifications made through the interface will be synchronized in the SMS service console. For related operations on templates in the console, see [SMS Templates](https://help.aliyun.com/zh/sms/user-guide/message-templates/?spm=a2c4g.11186623.0.0.35a947464Itaxp).
     * ### QPS Limit
     * The single-user QPS limit for this interface is 1000 times/second. Exceeding this limit will result in API throttling, which may impact your business. Please make calls reasonably.
     *
     * @param tmpReq - UpdateSmsTemplateRequest
     * @param runtime - runtime options for this request RuntimeOptions
     *
     * @returns UpdateSmsTemplateResponse
     *
     * @param UpdateSmsTemplateRequest $tmpReq
     * @param RuntimeOptions           $runtime
     *
     * @return UpdateSmsTemplateResponse
     */
    public function updateSmsTemplateWithOptions($tmpReq, $runtime)
    {
        $tmpReq->validate();
        $request = new UpdateSmsTemplateShrinkRequest([]);
        Utils::convert($tmpReq, $request);
        if (null !== $tmpReq->moreData) {
            $request->moreDataShrink = Utils::arrayToStringWithSpecifiedStyle($tmpReq->moreData, 'MoreData', 'json');
        }

        $query = [];
        if (null !== $request->applySceneContent) {
            @$query['ApplySceneContent'] = $request->applySceneContent;
        }

        if (null !== $request->intlType) {
            @$query['IntlType'] = $request->intlType;
        }

        if (null !== $request->moreDataShrink) {
            @$query['MoreData'] = $request->moreDataShrink;
        }

        if (null !== $request->ownerId) {
            @$query['OwnerId'] = $request->ownerId;
        }

        if (null !== $request->relatedSignName) {
            @$query['RelatedSignName'] = $request->relatedSignName;
        }

        if (null !== $request->remark) {
            @$query['Remark'] = $request->remark;
        }

        if (null !== $request->resourceOwnerAccount) {
            @$query['ResourceOwnerAccount'] = $request->resourceOwnerAccount;
        }

        if (null !== $request->resourceOwnerId) {
            @$query['ResourceOwnerId'] = $request->resourceOwnerId;
        }

        if (null !== $request->templateCode) {
            @$query['TemplateCode'] = $request->templateCode;
        }

        if (null !== $request->templateContent) {
            @$query['TemplateContent'] = $request->templateContent;
        }

        if (null !== $request->templateName) {
            @$query['TemplateName'] = $request->templateName;
        }

        if (null !== $request->templateRule) {
            @$query['TemplateRule'] = $request->templateRule;
        }

        if (null !== $request->templateType) {
            @$query['TemplateType'] = $request->templateType;
        }

        $req = new OpenApiRequest([
            'query' => Utils::query($query),
        ]);
        $params = new Params([
            'action' => 'UpdateSmsTemplate',
            'version' => '2017-05-25',
            'protocol' => 'HTTPS',
            'pathname' => '/',
            'method' => 'POST',
            'authType' => 'AK',
            'style' => 'RPC',
            'reqBodyType' => 'formData',
            'bodyType' => 'json',
        ]);
        if (null === $this->_signatureVersion || 'v4' != $this->_signatureVersion) {
            return UpdateSmsTemplateResponse::fromMap($this->callApi($params, $req, $runtime));
        }

        return UpdateSmsTemplateResponse::fromMap($this->execute($params, $req, $runtime));
    }

    /**
     * Update Text SMS Template.
     *
     * @remarks
     * - For details about the changes of this new interface compared to the original one, please refer to [Announcement on SMS Service Update: Signature & Template Interfaces](https://help.aliyun.com/zh/sms/product-overview/announcement-on-sms-service-update-signature-template-interface).
     * - Only templates that have not passed the review can be modified. Please refer to [Handling Suggestions for Failed SMS Template Reviews](https://help.aliyun.com/zh/sms/user-guide/causes-of-application-failures-and-suggestions?spm=a2c4g.11186623.0.0.4bf5561ajcFtMQ) and call this interface to modify and resubmit for review.
     * - Modifications made through the interface will be synchronized in the SMS service console. For related operations on templates in the console, see [SMS Templates](https://help.aliyun.com/zh/sms/user-guide/message-templates/?spm=a2c4g.11186623.0.0.35a947464Itaxp).
     * ### QPS Limit
     * The single-user QPS limit for this interface is 1000 times/second. Exceeding this limit will result in API throttling, which may impact your business. Please make calls reasonably.
     *
     * @param request - UpdateSmsTemplateRequest
     *
     * @returns UpdateSmsTemplateResponse
     *
     * @param UpdateSmsTemplateRequest $request
     *
     * @return UpdateSmsTemplateResponse
     */
    public function updateSmsTemplate($request)
    {
        $runtime = new RuntimeOptions([]);

        return $this->updateSmsTemplateWithOptions($request, $runtime);
    }
}
