<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use App\Models\WebApi\TwitterApi;
use Exception;

class TwitterUser
{
    protected $twitterApi;
    protected $helper;


    public function __construct(TwitterApi $twitterApi)
    {
        $this->twitterApi = $twitterApi;
    }


    public function process(ServerRequestInterface $request)
    {
        $twitter_user = $request->getParsedBody();
        try {
            $user_info = $this->twitterApi->getUserInfo($twitter_user);
            var_dump($user_info);
            return new HtmlResponse($this->template->render('app::home-page', $user_info));
        } catch (Exception $e) {
            return $response->withStatus(400);
        }
        
    }
}
