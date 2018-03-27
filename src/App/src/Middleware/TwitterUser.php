<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Models\WebApi\TwitterApi;
use Exception;

class TwitterUser implements MiddlewareInterface
{
    protected $twitterApi;
    protected $helper;


    public function __construct()
    {
        $this->twitterApi = new TwitterApi;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data=$request->getParsedBody();
        $twitter_user = $data['twitter_user'];
        try {
            $user_info = $this->twitterApi->getUserInfo($twitter_user);
            var_dump($user_info);
            return new HtmlResponse($this->template->render('app::home-page', $user_info));
        } catch (Exception $e) {
            return $response->withStatus(400);
        }
        
    }
}
