<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use PaulGibbs\WordpressBehatExtension\Server\WpRestApi;

/**
 * Provides step definitions for managing plugins and themes.
 */
class RestApiContext extends RawWordpressContext
{

    private $client;

    private $tokenCredentials;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->root = 'http://behat.dev/wp-json';
        $this->server = new WpRestApi(array(
                        'identifier' => 'qCH0oHWlAmEn',
                        'secret' => 'ioJeWBbMcSw2GXFJmMwpcxTpqpGhVvlel75mtKfyr0CTEE7v',
                        'callback_uri' => "oob"
                    ), null, 'http://behat.dev/wp-json');
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @Given /^(?:I )?authenticate via oauth 1$/
     */
    public function iAuthenticateViaOauth1()
    {
            $temporary = $this->server->getTemporaryCredentials();
            $url = $this->server->getAuthorizationUrl($temporary);

            $this->visitPath($url);
            $page = $this->getSession()->getPage();
            //TODO Assume's we are logged-in...
            $page->findButton('authorize')->click();

            //TODO Better way...
            $verification_token = $page->find('css', '#login p code')->getText();

            $this->tokenCredentials = $this->server->getTokenCredentials(
                $temporary,
                $temporary->getIdentifier(),
                $verification_token
            );
            //var_dump( $server->getUserUid($tokenCredentials) );
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
            $res = $this->client->request($method, $url, [
                'headers' => $this->server->getHeaders($this->tokenCredentials, $method, $url),
            ]);

            //error_log( $res->getStatusCode() );
            //var_dump( $res->getHeader('content-type') );
            //echo $res->getBody();
    }
}
