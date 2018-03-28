<?php
declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;
use App\Models\WebApi\TwitterApi;
use App\Models\WebApi\GoogleApi;

class TwitterUserHandler implements RequestHandlerInterface
{
    private $twitterApi;
    private $googleApi;
    private $containerName;
    private $router;
    private $template;


    public function __construct(Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        string $containerName)
    {
        $this->twitterApi = new TwitterApi;
        $this->googleApi = new GoogleApi;
        $this->router        = $router;
        $this->template      = $template;
        $this->containerName = $containerName;
    }


    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data=$request->getParsedBody();
        $twitter_user = $data['twitter_user'];
        $user_info = $this->twitterApi->getUserInfo($twitter_user);
        if(!isset($user_info->name)){
            $data['user_name']='Nao Encontrado';
            $data['result']=false;
            return new HtmlResponse($this->template->render('app::home-page', ["data"=>$data]));
        }
        $data['result']=true;
        $data['user_name']=$user_info->name;
        $data['screen_name']=$user_info->screen_name;
        $data['location']=$user_info->location;            
        $data['address']=$this->googleApi->returnAddressListGoogle($user_info->location);
        $data['localidade']=$user_info->location;
        $data['link']='https://www.google.com/maps/place/'.urlencode($user_info->location);
        if($data['address']['result']){
            $data['localidade']=$data['address'][0]['logradouro'];
            $data['link']='https://www.google.com/maps/place/'.urlencode($data['address'][0]['logradouro']);
        }
        return new HtmlResponse($this->template->render('app::home-page', ["data"=>$data]));
        
    }
}
