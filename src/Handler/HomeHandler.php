<?php 

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Hybridauth\Hybridauth;
use Hybridauth\Exception\Exception;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;
use Slim\Flash\Messages;
// use PDO;

class HomeHandler implements RequestHandlerInterface
{
    private $logger;
    private $twig;
    private $container;
    private $flash;
    // private $pdo;
    private $mailer;

    public function __construct(LoggerInterface $logger, \Twig\Environment $twig, ContainerInterface $container, \Slim\Flash\Messages $flash, MailHandler $mailer)//, PDO $pdo,)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->container = $container;
        $this->flash = $flash;
        // $this->pdo = $pdo;
        $this->mailer = $mailer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $response = new Response();

        $bankrollDayBefore = rand(30,10000);
        $bankroll = rand(1,10000);
        $percentBankrollYield = ((($bankroll*100)/$bankrollDayBefore)-100);
        
        $colorPercentBankrollYield = "yellow";
        if ( $percentBankrollYield > 0)
            $colorPercentBankrollYield = "green";
        elseif ( $percentBankrollYield < 0)
            $colorPercentBankrollYield = "red";


        $dailyYieldBefore = rand(0,500);
        $dailyYield = rand(0,500);
        $colorDailyYield = "yellow";
        $narrowDailyYield = "minus";
        if ( $dailyYield > $dailyYieldBefore)
        {
            $colorDailyYield = "green";
            $narrowDailyYield = "caret-up";
        }
        elseif ( $dailyYield < $dailyYieldBefore)
        {
            $colorDailyYield = "red";
            $narrowDailyYield = "caret-down";
        }


        $accumulatedYieldBefore = rand(30,10000);
        $accumulatedYield = rand(30,10000);
        $colorAccumulatedYield = "yellow";
        $narrowAccumulatedYield = "minus";
        if ( $accumulatedYield > $accumulatedYieldBefore)
        {
            $colorAccumulatedYield = "green";
            $narrowAccumulatedYield = "caret-up";
        }
        elseif ( $accumulatedYield < $accumulatedYieldBefore)
        {
            $colorAccumulatedYield = "red";
            $narrowAccumulatedYield = "caret-down";
        }

        $profitMargin = rand(2,20);

        $goal = ($bankroll*$profitMargin)/100;

        $percenteGoal = ($dailyYield*100)/$goal;
        if ($percenteGoal >= 100)
            $percenteGoal = 100;

        $stopWin = (($bankrollDayBefore*0.3)+$bankrollDayBefore);

        $stopLoss = ($bankrollDayBefore-($bankrollDayBefore*0.2));

        $args = [
            'bankroll' => number_format($bankroll,2,",","."),

            'percentBankrollYield' => number_format($percentBankrollYield,2,",","."),
            'colorPercentBankrollYield' => $colorPercentBankrollYield,

            'dailyYield' => number_format($dailyYield,2,",","."),
            'colorDailyYield' => $colorDailyYield,
            'narrowDailyYield' => $narrowDailyYield,

            'accumulatedYield' => number_format($accumulatedYield,2,",","."),
            'colorAccumulatedYield' => $colorAccumulatedYield,
            'narrowAccumulatedYield' => $narrowAccumulatedYield,

            'profitMargin' => $profitMargin,

            'goal' => number_format($goal,2,",","."),
            'percenteGoal' => round($percenteGoal,2),
            
            'stopWin' => number_format($stopWin,2,",","."),
            'stopLoss' => number_format($stopLoss,2,",","."),
        ];

        $response->getBody()->write(
            $this->twig->render('home.twig', $args)
        );
        return $response;
    }
}