<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fourtr4de | Gestor de Banca</title>
	<meta name="author" content="David Grzyb">
    <meta name="description" content="">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/index.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: {
            sans: ['Inter', 'sans-serif'],
            },
        }
        }
    }
    </script>

    <style>
        .body-bg {
            background-color: #18181b;
            background-image: linear-gradient(#18181b 0%, #3f3f46 74%);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>

</head>
<body class="body-bg min-h-screen pt-12 md:pt-10 pb-6 px-2 md:px-0" style="font-family:'Lato',sans-serif;">
    <header class="max-w-lg mx-auto text-center">
    <div class="flex items-center justify-center">
            <img src="logo_2.png" class="w-[310px]"></div>
            <!-- <h1 class="text-4xl font-bold text-gray-100 text-center">Fourtr4de</h1> -->
        <!-- <a href="#">
            <h1 class="text-4xl font-bold text-gray-100 text-center">Fourtr4de</h1>
        </a> -->
    </header>

    <main class="bg-zinc-700 max-w-lg mx-auto p-8 md:p-12 my-10 rounded-lg shadow-2xl">
        <section>
            <h3 class="text-gray-100 font-bold text-2xl">Gestor de Banca</h3>
        </section>

        <section class="mt-10">
            <form class="flex flex-col" method="POST" action="painel.php">
                <div class="mb-6 pt-3 rounded bg-gray-200">
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-3" for="email">E-mail</label>
                    <input type="text" id="email" class="bg-white rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-blue-600 transition duration-500 px-3 pb-3">
                </div>
                <div class="mb-6 pt-3 rounded bg-gray-200">
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-3" for="password">Senha</label>
                    <input type="password" id="password" class="bg-white rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-blue-600 transition duration-500 px-3 pb-3">
                </div>
                <div class="flex justify-end">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" class="text-sm text-gray-100 hover:text-gray-100 hover:underline mb-6">Esqueceu a senha?</a>
                </div>
                <button class="bg-blue-600 hover:bg-bule-700 text-white font-bold py-2 rounded shadow-lg hover:shadow-xl transition duration-200" type="submit">Entrar</button>
                <div class="w-full text-center mt-3">
                    <button class="bg-zinc-700 text-white font-bold py-0 px-3 rounded" type="submit">
                        <img src="icon-google.png" class="w-8 h-8">
                    </button>
                    <button class="bg-zinc-700 text-white font-bold py-0 px-3 rounded" type="submit">
                        <img src="icon-facebook.png" class="w-9 h-9">
                    </button>
                </div>
            </form>
        </section>
    </main>

    <div class="max-w-lg mx-auto text-center mt-12 mb-6">
        <p class="text-gray-100">Não tem cadastro? <a href="#" data-bs-toggle="modal" data-bs-target="#myAccountModal" class="font-bold hover:underline">Clique aqui</a>.</p>
    </div>

    <footer class="max-w-lg mx-auto flex justify-center text-gray-100">
        <a href="#" class="hover:underline">Contato</a>
        <span class="mx-3">•</span>
        <a href="#" data-bs-toggle="modal" data-bs-target="#policesModal" class="hover:underline">Política de Privacidade</a>
    </footer>

    <!-- Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="myAccountModal" tabindex="-1" aria-labelledby="myAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="myAccountModalLabel">
                    Cadastre-se
                    </h5>
                    <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="modal-body relative p-4">
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nome</label>
                            <input type="email" name="name" id="name"  class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">E-mail</label>
                            <input type="email" name="email" id="email" placeholder="email@email.com" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Senha</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        </div>
                        <div>
                            <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Confirmar senha</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        </div>
                    </form>
                </div>
                <div  class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button"
                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                    Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="forgotPasswordModalLabel">
                    Recuperar senha
                    </h5>
                    <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="modal-body relative p-4">
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">E-mail</label>
                            <input type="email" name="email" id="email" placeholder="email@email.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        </div>
                    </form>
                </div>
                <div  class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button"
                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                    Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="policesModal" tabindex="-1" aria-labelledby="policesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="policesModalLabel">
                    Política de Privacidade
                    </h5>
                    <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="modal-body relative p-4">
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                        <div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris hendrerit dapibus turpis non pharetra. Vestibulum enim ante, maximus sed commodo ac, mattis ut libero. Quisque sit amet pellentesque eros. Sed congue mollis tellus non suscipit. In hac habitasse platea dictumst. Duis eleifend elementum sapien ac porta. Nam justo lectus, porta at libero nec, vulputate posuere elit. Quisque consectetur elementum tempor. Donec vitae sodales nunc. Praesent nec sem ac urna convallis volutpat vitae sit amet mi. Aenean eu ullamcorper ipsum.</p>
                            <p>Integer ac finibus lorem, et semper elit. Proin vitae risus eu dolor dignissim ultrices. Aliquam erat volutpat. Nunc vestibulum ex non lacus bibendum vehicula in id massa. Pellentesque aliquam placerat lacinia. Integer quam risus, laoreet sit amet libero eu, molestie feugiat arcu. Ut a interdum orci, sed ultricies lorem. Vestibulum pulvinar purus eros, vitae interdum metus aliquet in. Donec tincidunt maximus est, in convallis turpis accumsan vitae. In in maximus dui. Maecenas semper, neque sit amet mattis tincidunt, arcu diam dignissim sapien, at lacinia sapien massa eget ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;</p>
                            <p>Nam sed felis dolor. Sed viverra id ligula quis finibus. Etiam vel vestibulum eros, nec venenatis mi. Curabitur hendrerit eget velit eget congue. Fusce quis enim ante. Sed nibh justo, consequat sit amet ultrices a, feugiat non felis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras pretium vitae enim vitae pellentesque. Curabitur leo magna, ultricies sit amet nisl quis, consectetur iaculis libero. Nunc et faucibus nulla, at rutrum purus. Aenean ullamcorper eros tellus, in suscipit neque aliquet vestibulum.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

</body>
</html>