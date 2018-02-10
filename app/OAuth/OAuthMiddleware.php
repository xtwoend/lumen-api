<?php

namespace App\OAuth;

use App\OAuth\Client;
use App\OAuth\Token;
use App\OAuth\User;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

/**
* 
*/
class OAuthMiddleware
{
    protected $server;

    public function __construct()
    {
        $this->server = app()->make(ResourceServer::class);
    }

    public function validateAuthenticatedRequest(Request $request)
    {
        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            
            // If the access token is valid we will retrieve the user according to the user ID
            // associated with the token. We will use the provider implementation which may
            // be used to retrieve users from Eloquent. Next, we'll be ready to continue.
            $user = User::find(
                $psr->getAttribute('oauth_user_id')
            );

            if (! $user) {
                return;
            }

            // Next, we will assign a token instance to this user which the developers may use
            // to determine if the token has a given scope, etc. This will be useful during
            // authorization such as within the developer's Laravel model policy classes.
            $token = Token::find(
                $psr->getAttribute('oauth_access_token_id')
            );

            $clientId = $psr->getAttribute('oauth_client_id');

            // Finally, we will verify if the client that issued this token is still valid and
            // its tokens may still be used. If not, we will bail out since we don't want a
            // user to be able to send access tokens for deleted or revoked applications.
            $client = Client::find($clientId);
            if (is_null($client) &&  $client->revoked ) {
                return;
            }

            return $token ? $user->withAccessToken($token) : null;
        } catch (OAuthServerException $e) {
            return;
        }
    }

    public function validateAuthenticatedClientRequest($request)
    {
        # code...
    }
}