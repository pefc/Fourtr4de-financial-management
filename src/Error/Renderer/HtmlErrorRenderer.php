<?php
 
declare(strict_types=1);
 
namespace App\Error\Renderer;
 
use Slim\Exception\HttpNotFoundException;
// use Slim\Interfaces\ErrorRendererInterface;
use Throwable;
 
final class HtmlErrorRenderer 
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $title = '500 - Erro interno';
        $message = 'Ocorreu um erro ao acessar está página.';
 
        if ($exception instanceof HttpNotFoundException) {
            $title = '404 - Página não encontrada';
            $message = 'Está página não foi encontrada.';
        }
 
        return $this->renderHtmlPage($title, $message);
    }
 
    public function renderHtmlPage(string $title = '', string $message = ''): string
    {
        $title = htmlentities($title, ENT_COMPAT|ENT_HTML5, 'utf-8');
        $message = htmlentities($message, ENT_COMPAT|ENT_HTML5, 'utf-8');
 
        return <<<EOT
<!DOCTYPE html>
<html>
<head>
  <title>$title</title>
  <link rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-default.css">
</head>
<body>
  <h1>$title</h1>
  <p>$message</p>
</body>
</html>
EOT;
    }
}