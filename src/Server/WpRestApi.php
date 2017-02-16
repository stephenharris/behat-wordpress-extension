<?php

namespace PaulGibbs\WordpressBehatExtension\Server;

use League\OAuth1\Client\Server\Server;
use League\OAuth1\Client\Server\User;
use League\OAuth1\Client\Credentials\TokenCredentials;

class WpRestApi extends Server
{

        /**
         * request, authorize, access
         */
    private $oauth1_routes = null;

    private $root;

        /**
     * Create a new server instance.
     *
     * @param ClientCredentialsInterface|array $clientCredentials
     * @param SignatureInterface               $signature
     */
    public function __construct($clientCredentials, SignatureInterface $signature = null, $root = null)
    {
        $this->root = $root;
        parent::__construct($clientCredentials, $signature);
    }

    private function getOauthRoute($route)
    {

        if ($this->oauth1_routes === null) {
            $client = $this->createHttpClient();

            $res = $client->request('GET', $this->root, []);
            $discover = json_decode($res->getBody(), true);

            if (200 !=  $res->getStatusCode() || empty($discover['authentication'])) {
                throw new \Exception('Could not discover authentication routes');
            }

            if (empty($discover['authentication']['oauth1'])) {
                throw new \Exception('Could not discover oauth1 routes');
            }

            $this->oauth1_routes = $discover['authentication']['oauth1'];
        }

        if (! isset($this->oauth1_routes[$route])) {
            throw new \Exception("Oauth1 route for $route not found");
        }

        return $this->oauth1_routes[$route];
    }
    /**
     * {@inheritDoc}
     */
    public function urlTemporaryCredentials()
    {
        return $this->getOauthRoute('request');
    }

    /**
     * {@inheritDoc}
     */
    public function urlAuthorization()
    {
        return $this->getOauthRoute('authorize');
    }

    /**
     * {@inheritDoc}
     */
    public function urlTokenCredentials()
    {
        return $this->getOauthRoute('access');
    }

    /**
     * {@inheritDoc}
     */
    public function urlUserDetails()
    {
        return rtrim($this->root, '/') . '/wp/v2/users/me';
    }

    /**
     * {@inheritDoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
                $user = new User();
                $user->uid = $data['id'];
                $user->nickname = $data['slug'];
                $user->name = $data['name'];
                $user->imageUrl = array_shift($data['avatar_urls']);
                return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
                return $data['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
                return;
    }

    /**
     * {@inheritDoc}
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
                return $data['name'];
    }
}
