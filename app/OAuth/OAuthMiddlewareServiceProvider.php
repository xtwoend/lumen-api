<?php

namespace App\OAuth;

use App\OAuth\Bridge\AccessTokenRepository;
use App\OAuth\TokenRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\ResourceServer;


/**
* 
*/
class OAuthMiddlewareServiceProvider extends ServiceProvider
{
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServerResource();
    }

    /**
     * [boot description]
     * @return [type] [description]
     */
    public function boot()
    {
        # code...
    }

    public function registerServerResource()
    {
        $this->app->singleton(ResourceServer::class, function () {
            $accessTokenRepository = new AccessTokenRepository(new TokenRepository());
            $publicKeyPath = config('config.oauth.public_key');      
            return new ResourceServer($accessTokenRepository, $publicKeyPath);
        });
    }
}