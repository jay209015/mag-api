<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/6/15
 * Time: 2:53 AM
 */

namespace FileTest\Service;
use File\Service\BasicAuthenticationService;
use FileTest\Bootstrap;
use Zend\Http\PhpEnvironment\Request as PhpRequest;

class BasicAuthenticationServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $mockClients;
    protected $request;
    protected $sm;

    protected function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();

        $this->mockClients = array(
            'previewApiClient' => array("secret" => "previewApiClient")
        );

        putenv('APPLICATION_ENV=local');

        parent::setUp();

    }

    public function testAuthenticateMethodReturnsFalseWhenAuthorizationHeaderIsMissing()
    {
        $BasicAuthenticationService = new BasicAuthenticationService(new PhpRequest(), $this->mockClients);
        $this->assertFalse($BasicAuthenticationService->authenticate());
    }

    public function testAuthenticateMethodReturnsFalseWhenPrefixBasicIsNotFound()
    {
        $request = new PhpRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Basi cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $BasicAuthenticationService = new BasicAuthenticationService($request, $this->mockClients);
        $this->assertFalse($BasicAuthenticationService->authenticate());
    }

    public function testAuthenticateMethodReturnsFalseWhenClientIsNotValid()
    {
        $request = new PhpRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudHM6cHJldmlld0FwaUNsaWVudA==');

        $BasicAuthenticationService = new BasicAuthenticationService($request, $this->mockClients);
        $this->assertFalse($BasicAuthenticationService->authenticate());
    }


    public function testAuthenticateMethodReturnsFalseWhenClientSecretIsInvalid()
    {
        $request = new PhpRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50cw==');

        $BasicAuthenticationService = new BasicAuthenticationService($request, $this->mockClients);
        $this->assertFalse($BasicAuthenticationService->authenticate());
    }

    public function testAuthenticateMethodReturnsTrueWhenClientCredentialIsValid()
    {
        $request = new PhpRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $BasicAuthenticationService = new BasicAuthenticationService($request, $this->mockClients);
        $this->assertTrue($BasicAuthenticationService->authenticate());
    }


    public function testExtractClientCredential()
    {
        $request = new PhpRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $BasicAuthenticationService = new BasicAuthenticationService($request, $this->mockClients);
        $expectedResult =  array(
            "clientId" => 'previewApiClient',
            "secret" => 'previewApiClient',
        );

        $actualResult = $BasicAuthenticationService->extractClientCredential('Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->assertEquals($expectedResult, $actualResult);
    }

} 