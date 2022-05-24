<?php 

declare(strict_types=1);

namespace App\Handler\Users;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PDO;
use App\Handler\Mailer\MailHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsersHandler implements RequestHandlerInterface
{
    private $logger;
    private $twig;
    private $container;
    private $flash;
    private $pdo;
    private $mailer;

    public function __construct(LoggerInterface $logger, \Twig\Environment $twig, ContainerInterface $container, \Slim\Flash\Messages $flash, MailHandler $mailer, PDO $pdo)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->container = $container;
        $this->flash = $flash;
        $this->pdo = $pdo;
        $this->mailer = $mailer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $response = new Response();
        return $response;
    }

    public function saveUser(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        $stmt = $this->pdo->prepare("SELECT max(id) as id FROM terms_polices WHERE type = 'T' and status = 'A' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        try 
        {
            if ( empty(trim($data['name'])) || empty(trim($data['identifier'])) || empty(trim($data['email'])) || empty(trim($data['password'])) || empty(trim($data['confirm_password'])) || $data['terms_conditions'] != $terms[0]['id'] )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.');

            if ( filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false )
                throw new \Exception('Informe um e-mail válido.');

            if ( strlen(trim($data['password'])) < 6 || strlen(trim($data['confirm_password'])) < 6 )
                throw new \Exception('A senha deve ter pelo menos 6 caracteres.');

            if ( $data['password'] != $data['confirm_password'] )
                throw new \Exception('As senhas devem ser iguais.');

            if ( $this->checkIdentifierExist($data['identifier']) === true )
                throw new \Exception('CPF já cadastrado.');

            if ( $this->checkEmailExist($data['email']) === true )
                throw new \Exception('E-mail já cadastrado. Por favor, forneça outro e-mail válido.');          
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formLogin')); 
        }

        try
        {
            $createdAt = sha1(date("dmyhis"));
            $token = sha1($createdAt.sha1($data["email"]).sha1($data["identifier"]).sha1($this->container->get('settings')['secret_key']));

            $sql = "INSERT INTO 
                        users (name, 
                                email, 
                                password, 
                                identifier,
                                token)
					VALUES (:userName, 
							:userEmail, 
						    :userPassword, 
							:userIdentifier,
                            :token)";	
            $arrData = [
                'userName' => trim($data['name']),
                'userEmail' => strtolower(trim($data['email'])),
                'userPassword' => sha1($data['password']),
                'userIdentifier' => trim(str_replace([".","-"], "", $data['identifier'])),
                'token' => $token,
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao cadastrar o usuário - users");
                throw new \Exception("Não foi possível cadastrar a conta.");
            }
                

            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :userEmail AND identifier = :userIdentifier LIMIT 1");
            $stmt->execute(['userEmail' =>strtolower(trim($data['email'])), 'userIdentifier' => trim(str_replace([".","-"], "", $data['identifier'])) ]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']) )
            {
                $this->logger->error("Erro ao recuperar informações do usuário para vincular o  termo durnate o cadastro");
                throw new \Exception("Não foi possível cadastrar a conta.");
            }

            $sql = "INSERT INTO 
                        users_has_terms_polices (users_id, 
                        terms_polices_id)
					VALUES (:userId, 
							:termsId)";	
            $arrData = [
                'userId' => $userData[0]['id'],
                'termsId' => $data['terms_conditions'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao vincular termos e condições ao usuário");
                throw new \Exception("Não foi possível cadastrar a conta.");
            }


            $url = $this->container->get('settings')['site_url'].$routeParser->urlFor('activateUser', ['token' => $token]);

            $html = null;
            $html.= 'Olá '.strtoupper($data['name']).'<br><br>';
            $html.= 'Clique no link abaixo para ativar sua conta:<br><br>';
            $html.= '<a href="'.$url.'">ATIVAR MINHA CONTA</a><br><br>';
            $html.= 'Caso não consiga acessar o link acima, copie e cole no seu navegador a url abaixo:<br><br>';
            $html.= $url.'<br><br>';
            
            $dataMail = [
                'subject' => 'Gestor de Banca | FourTr4de - Ativar Conta',
                'to' => strtolower(trim($data['email'])),
                'html' => $html,
            ];

            $this->mailer->sendEmail($dataMail);

            $this->flash->addMessage('success', 'Cadastro realizado com sucesso. Confira seu e-amail.');

            return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('formLogin'));
        }
        catch ( \Execption $e )
        {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE email = :userEmail AND identifier = :userIdentifier");
            $stmt->execute(['userEmail' => strtolower(trim($data['email'])), 'userIdentifier' => trim(str_replace([".","-"], "", $data['identifier'])) ]);

            $this->flash->addMessage('error', $e->getMessage());

            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formLogin')); 
        }
    }

    public function getUser(ServerRequestInterface $request): ResponseInterface
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        try
        {
            $key = $this->container->get('settings')['secret_key'];
            $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));
            
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :userToken AND id = :userId AND status = 'A' LIMIT 1");
            $stmt->execute(['userToken' => $decoded->user, 'userId' => $_SESSION['user']['id']]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( empty($userData[0]['id']) )
                throw new \Exception("Não foi possível localizar o cadastro.");

            $args = array(
                'pageTitle' => "Minha Conta",
                'pageData' => $_SESSION['pageData'],
                'actionEditAccount' => $routeParser->urlFor('actionEditAccount'),
                'userData' => $userData[0],
            );
            $response->getBody()->write(
                $this->twig->render('restricted/my-account.twig', $args)
            );
            return $response;
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());

            return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('logoutAccount'));
        }
    }

    public function checkEmailExist($email) 
    {
        if ( empty($_SESSION['user']['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :userEmail LIMIT 1");
            $stmt->execute(['userEmail' => $email ]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :userEmail AND id <> :userId LIMIT 1");
            $stmt->execute(['userEmail' => $email, 'userId' => $_SESSION['user']['id'] ]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if ( empty($userData[0]['email']) )
            return false;

        return true;
    }

    public function checkIdentifierExist($identifier) 
    {
        $stmt = $this->pdo->prepare("SELECT identifier FROM users WHERE identifier = :userIdentifier LIMIT 1");
        $stmt->execute(['userIdentifier' => trim(str_replace([".","-"], "", $identifier)) ]);
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ( empty($userData[0]['identifier']) )
            return false;

        return true;
    }


    public function forgotPassword(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        try 
        {
            if ( empty($data['identifierForgotPassword']) )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.');

            $sql = "SELECT name, email, token FROM users WHERE identifier = :userIdentifier LIMIT 1";
            $arrData = ['userIdentifier' => trim(str_replace([".","-"], "", $data['identifierForgotPassword']))];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $logger->error("Erro ao recuperar informações do usuário - formulário recuperar senha");
                throw new \Exception("Não foi possível localizar a conta.");
            }

            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['email']))
            {
                throw new \Exception("Não possível localizar e-mail do CPF informado.");
            }

            $url = $this->container->get('settings')['site_url'].$routeParser->urlFor('formResetPassword', ['token' => $userData[0]['token']]);

            $html = "";
            $html.= 'Olá '.$userData[0]['name'].'<br><br>';
            $html.= 'Clique no link abaixo para definir uma senha nova:<br><br>';
            $html.= '<a href="'.$url.'">'.$url.'</a><br><br>';		
            
            $dataMail = [
                'subject' => 'Gestor de Banca | FourTr4de - Redefinir Senha',
                'to' => $userData[0]['email'],
                'html' => $html,
            ];

            $this->mailer->sendEmail($dataMail);

            $subEmail = substr_replace($userData[0]['email'], "****", 0, 4);
            $subEmail = substr_replace($subEmail, "*****", -1, 5);

            $this->flash->addMessage('success', 'Acesse o e-mail '.$subEmail.' e siga as instruções.');
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }

        return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('formLogin')); 
    }

    public function formResetPassword(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        $token = $route->getArgument('token');

        try
        {
            if ( empty($token) )
                throw new \Exception("Não foi possível localizar a conta.");

            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar o cadastro.");

            $args = [];
            $args['actionResetPassword'] = $routeParser->urlFor('actionResetPassword', ['token' => $token]);
            $args['descriptionTerms'] = $_SESSION['descriptionTerms'];
            $args['descriptionPolices'] = $_SESSION['descriptionPolices'];

            $response->getBody()->write(
                $this->twig->render('public/reset-password.twig', $args)
            );
            return $response;
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());

            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formLogin')); 
        }
    }

    public function saveNewPassword(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        $data = $request->getParsedBody();

        $token = $route->getArgument('token');

        try{
            if ( empty($token) || empty($data['identifier']) || empty($data['password']) || empty($data['passwordConfirm']))
            {
                throw new \Exception("Preencha todos os campos corretamente.");
            }

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :token AND identifier = :userIdentifier LIMIT 1");
            $stmt->execute(['token' => $token, 'userIdentifier' => trim(str_replace([".","-"], "", $data['identifier']))]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar a conta.");

            
            $createdAt = sha1(date("dmyhis"));
            $newToken = sha1($createdAt.sha1($userData[0]["email"]).sha1($data["identifier"]).sha1($this->container->get('settings')['secret_key']));

            $sql = "
            UPDATE 
                users 
            SET
                password = :userPassword, 
                token = :newToken,
                updated_at = now()
            WHERE
                token = :token AND 
                identifier = :userIdentifier";	
            $arrData = [
                'userPassword' => sha1($data['password']),
                'newToken' => $newToken,
                'token' => $token,
                'userIdentifier' => trim(str_replace([".","-"], "", $data['identifier'])),
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao redefinir a senha do usuário");
                throw new \Exception("Não foi possível aletrar a senha.");
            }

            $this->flash->addMessage('success', 'Senha alterada com sucesso.');

            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formLogin')); 
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());

            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formResetPassword', ['token' => $token])); 
        }
    }

    public function activateUser(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        $data = $request->getParsedBody();

        $token = $route->getArgument('token');

        try{
            if ( empty($token) )
            {
                throw new \Exception("Não foi possível recuperar as informações.");
            }

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar a conta.");

            
            $createdAt = sha1(date("dmyhis"));
            $newToken = sha1($createdAt.sha1($userData[0]["email"]).sha1($userData[0]["identifier"]).sha1($this->container->get('settings')['secret_key']));

            $sql = "
            UPDATE 
                users 
            SET
                verified = true,
                token = :newToken,
                updated_at = now()
            WHERE
                token = :token";	
            $arrData = [
                'newToken' => $newToken,
                'token' => $token,
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao verificar a conta do usuário");
                throw new \Exception("Não foi possível verificar a sua conta.");
            }

            $this->flash->addMessage('success', 'Conta verificada com sucesso.');


        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('formLogin')); 
    }

    public function resendActivation(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        $data = $request->getParsedBody();

        $token = $route->getArgument('token');

        try{
            if ( empty($token) )
            {
                throw new \Exception("Não foi possível recuperar as informações.");
            }

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar a conta.");

            $url = $this->container->get('settings')['site_url'].$routeParser->urlFor('activateUser', ['token' => $userData[0]['token']]);

            $html = null;
            $html.= 'Olá '.strtoupper($userData[0]['name']).'<br><br>';
            $html.= 'Clique no link abaixo para ativar sua conta:<br><br>';
            $html.= '<a href="'.$url.'">ATIVAR MINHA CONTA</a><br><br>';
            $html.= 'Caso não consiga acessar o link acima, copie e cole no seu navegador a url abaixo:<br><br>';
            $html.= $url.'<br><br>';		
            
            $dataMail = [
                'subject' => 'Gestor de Banca | FourTr4de - Ativar conta',
                'to' => $userData[0]['email'],
                'html' => $html,
            ];

            $this->mailer->sendEmail($dataMail);

            $subEmail = substr_replace($userData[0]['email'], "****", 0, 4);
            $subEmail = substr_replace($subEmail, "*****", -1, 5);

            $this->flash->addMessage('success', 'Acesse o e-mail '.$subEmail.' e siga as instruções.');
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('formLogin'));
    }

    public function formNoActiveUser(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        $data = $request->getParsedBody();

        $token = $route->getArgument('token');

        try{
            if ( empty($token) )
            {
                throw new \Exception("Não foi possível recuperar as informações.");
            }

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar a conta.");

            $args = [];
            $args['actionResendActivation'] = $routeParser->urlFor('actionResendActivation', ['token' => $token]);
            $args['descriptionTerms'] = $_SESSION['descriptionTerms'];
            $args['descriptionPolices'] = $_SESSION['descriptionPolices'];
            $args['urlLogin'] = $routeParser->urlFor('formLogin');
    
            $response->getBody()->write(
                $this->twig->render('public/resend-activation.twig', $args)
            );
            return $response;
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());

            return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('formLogin'));
        }
    }


    public function editUser(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $route = $routeContext->getRoute();

        try{
            $key = $this->container->get('settings')['secret_key'];
            $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE token = :userToken AND id = :userId AND status = 'A' LIMIT 1");
            $stmt->execute(['userToken' => $decoded->user, 'userId' => $_SESSION['user']['id']]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( empty($userData[0]['id']))
                throw new \Exception("Não foi possível localizar a conta.");

            $data = $request->getParsedBody();

            if ( empty(trim($data['name'])) || empty(trim($data['identifier'])) || empty(trim($data['email'])) )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.');

            if ( filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false )
                throw new \Exception('Informe um e-mail válido.');

            if ( !empty(trim($data['password'])) )
            {
                if ( strlen(trim($data['password'])) < 6 || strlen(trim($data['confirm_password'])) < 6 )
                    throw new \Exception('A senha deve ter pelo menos 6 caracteres.');

                if ( $data['password'] != $data['confirm_password'] )
                    throw new \Exception('As senhas devem ser iguais.');
            }

            if ( $this->checkEmailExist($data['email']) === true )
                throw new \Exception('E-mail já cadastrado. Por favor, forneça outro e-mail válido.');  
            
            $createdAt = sha1(date("dmyhis"));
            $newToken = sha1($createdAt.sha1($data["email"]).sha1($userData[0]["identifier"]).sha1($this->container->get('settings')['secret_key']));

            $sql = "
            UPDATE 
                users 
            SET
                name = :userName,
                email = :userEmail,
                password = :userPassword,
                token = :newToken,
                updated_at = now()
            WHERE
                token = :token AND
                id = :userId";	
            $arrData = [
                'userName' => trim($data['name']),
                'userEmail' => strtolower(trim($data['email'])),
                'userPassword' => $data['password'] ? sha1($data['password']) : $userData[0]['password'],
                'newToken' => $newToken,
                'token' => $decoded->user,
                'userId' => $_SESSION['user']['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao verificar a conta do usuário");
                throw new \Exception("Não foi possível editar a sua conta.");
            }


            $tokenToJwt = array(
                "user" => $newToken,
                "name" => ucwords(strtolower($data['name'])),
                "iss" => $this->container->get('settings')['site_url'],
                "aud" => $this->container->get('settings')['site_url'],
            );

            $_SESSION['user']['name'] = ucwords(strtolower($data['name']));

            $jwt = JWT::encode($tokenToJwt, $key, 'HS256');

            setcookie("token", $jwt, 0, "/");

            $this->flash->addMessage('success', 'Informações alteradas com sucesso.');

        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('formAccount')); 
    }

    
}
