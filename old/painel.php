<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fourtr4de | Gestor de Banca</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <!--Replace with your tailwind.css once created-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
    <script src="https://kit.fontawesome.com/ff21fbcac4.js" crossorigin="anonymous"></script>


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

    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>

    <style>
    .shim-green {
        position: relative;
        overflow: hidden;
        background-color: rgba(0, 255, 0, 0.7);
    }
    .shim-green::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translateX(-100%);
        background-image: linear-gradient(
            90deg,
            rgba(233, 233, 233, 1) 0,
            rgba(233, 233, 233, 0.9) 50%,
            rgba(233, 233, 233, 0.8) 100%
        );
    }
    .body-bg {
        background-color: #18181b;
        background-image: linear-gradient(#18181b 0%, #3f3f46 74%);
    }

    /*Footer open/load animation*/
    .alert-footer {
      -webkit-animation: slide-in-bottom 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
      animation: slide-in-bottom 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
    }

    /*Footer close animation*/
    .alert-footer input:checked~* {
      -webkit-animation: slide-out-bottom 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
      animation: slide-out-bottom 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
    }

    @-webkit-keyframes slide-in-bottom {
      0% {
        -webkit-transform: translateY(1000px);
        transform: translateY(1000px);
        opacity: 0
      }

      100% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
        opacity: 1
      }
    }

    @keyframes slide-in-bottom {
      0% {
        -webkit-transform: translateY(1000px);
        transform: translateY(1000px);
        opacity: 0
      }

      100% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
        opacity: 1
      }
    }

    @-webkit-keyframes slide-out-bottom {
      0% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
        opacity: 1
      }

      100% {
        -webkit-transform: translateY(1000px);
        transform: translateY(1000px);
        opacity: 0
      }
    }

    @keyframes slide-out-bottom {
      0% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
        opacity: 1
      }

      100% {
        -webkit-transform: translateY(1000px);
        transform: translateY(1000px);
        opacity: 0
      }
    }
    </style>


</head>

<body class="body-bg font-sans leading-normal tracking-normal">

    <nav id="header" class="bg-zinc-800 fixed w-full z-10 top-0 shadow">

        <div class="w-full container mx-auto flex flex-wrap items-center mt-0 pt-3 pb-3 md:pb-0">

            <div class="w-1/4 pl-2">
                <div class="pl-0 text-left uppercase hidden md:block">
                    <a class="text-gray-100 text-base xl:text-xl no-underline hover:no-underline font-bold" href="#">
                        Gestor de Banca
                    </a>
                </div>
                
                <div class="block pr-4 visible md:invisible">
                    <button id="nav-toggle" class="lg:hidden bg-gray-100 flex items-center px-3 py-2 border rounded text-gray-500 border-gray-500 hover:text-gray-500 hover:border-gray-500 appearance-none focus:outline-none">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <title>Menu</title>
                            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                        </svg>
                    </button>
                </div>
                
            </div>

            
            <div class="w-2/4 pl-0 text-center uppercase visible md:invisible">
                    <a class="text-gray-100 text-base xl:text-xl no-underline hover:no-underline font-bold" href="#">
                        Gestor de Banca
                    </a>
            </div>

            

            <div class="w-1/4 pr-0">
                <div class="flex relative inline-block float-right">
                    <div class="relative text-sm">
                        <button id="userButton" class="flex items-center focus:outline-none mr-2">
                            <img class="w-8 h-8 rounded-full mr-0 md:mr-1" src="http://i.pravatar.cc/300" alt="Avatar of User"> <span class="hidden md:inline-block text-gray-100">Ol??, Nome do Usu??rio</span>
                            <span class="pl-2 text-gray-100"><i class="fas fa-caret-down"></i></span>
                        </button>
                        <div id="userMenu" class="bg-white rounded shadow-md mt-2 absolute mt-12 top-0 right-0 min-w-full overflow-auto z-30 invisible">
                            <ul class="list-reset">
                                <li data-bs-toggle="modal" data-bs-target="#myAccountModal"><a href="#" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Minha conta</a></li>
                                <li>
                                    <hr class="border-t mx-2 border-gray-400">
                                </li>
                                <li><a href="/" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Sair</a></li>
                            </ul>
                        </div>
                    </div>


                    <!-- <div class="block lg:hidden pr-4">
                        <button id="nav-toggle" class="bg-gray-100 flex items-center px-3 py-2 border rounded text-gray-500 border-gray-500 hover:text-gray-500 hover:border-gray-500 appearance-none focus:outline-none">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div> -->
                </div>

            </div>


            <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-zinc-800 z-20" id="nav-content">
                <ul class="list-reset lg:flex flex-1 items-center px-4 md:px-1">
                    <li class="mr-6 my-2 md:my-0" data-bs-toggle="modal" data-bs-target="#projectionModal">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-100 no-underline hover:text-gray-500">
                            <i class="fas fa-chart-line fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Proje????o</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-100 no-underline hover:text-gray-500">
                            <i class="fa fa-money-bill-transfer fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Saques</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0" data-bs-toggle="modal" data-bs-target="#operationModal">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-100 no-underline hover:text-gray-500">
                            <i class="fa fa-receipt fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm" >Opera????es</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0" data-bs-toggle="modal" data-bs-target="#configModal">
                        <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-100 no-underline hover:text-gray-500">
                            <i class="fa fa-cog fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Configura????es da Banca</span>
                        </a>
                    </li>
                    
                </ul>


                <!-- <div class="relative pull-right pl-4 pr-4 md:pr-2">
                    <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option disabled>Carteiras</option>
                        <option>betfair</option>
                        <option selected>Bet365</option>
                        <option>Betnacional</option>
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                </div> -->

            </div>

        </div>
    </nav>

    <!--Container-->
    <div class="container w-full mx-auto pt-20">

        <div class="w-full px-4 py-0 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

            <!--Console Content-->

            <div class="flex flex-wrap">
                <div class="w-full md:w-1/2 xl:w-1/3 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 h-full border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-1 text-left md:text-left">
                                <h5 class="font-bold uppercase text-gray-500">Valor da Banca <a href="#"><span class="text-blue-600" data-bs-toggle="modal" data-bs-target="#balanceCurrentModal"><i class="fa-solid fa-pen-to-square"></i></span></a></h5>
                                <h3 class="font-bold text-3xl">R$ <?=number_format(rand(30,10000),2,",",".")?> <span class="align-top text-sm font-semibold text-white mt-10 px-1.5 bg-green-500 rounded-full"><?=rand(1,100)?>%</span></h3>
                            </div>
                        </div>
                        <div class="w-full">
                            <canvas id="chartjs-0" width="389" height="128"></canvas>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <div class="w-1/2 md:w-1/2 xl:w-1/3 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 h-full border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-1 text-left md:text-left">
                                <h5 class="font-bold uppercase text-gray-500">Lucro do dia</h5>
                                <h3 class="font-bold text-md md:text-3xl">R$ 3.708,99</h3>
                            </div>
                        </div>
                        <div class="w-full">
                            <canvas id="chartjs-1" width="389" height="128"></canvas>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <div class="w-1/2 md:w-1/2 xl:w-1/3 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 h-full border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-1 text-left md:text-left">
                                <h5 class="font-bold uppercase text-gray-500">Lucro total</h5>
                                <h3 class="font-bold text-md md:text-3xl">R$ <?=number_format(rand(10,10000),2,",",".")?></h3>
                            </div>
                        </div>
                        <div class="w-full">
                            <canvas id="chartjs-2" width="389" height="128"></canvas>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <!-- <div class="w-full md:w-1/2 xl:w-1/3 p-3"> -->
                    <!--Metric Card-->
                    <!-- <div class="bg-gray-200 border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4">
                                <div class="rounded p-3 bg-green-600"><i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-right md:text-center">
                                <h5 class="font-bold uppercase text-gray-500">Banca - inicial <a href="#"><span class="text-blue-600" data-bs-toggle="modal" data-bs-target="#balanceStartModal"><i class="fa-solid fa-rotate"></i></span></a></h5>
                                <h3 class="font-bold text-3xl">R$ 1047,00 <span class="text-red-500"><i class="fas fa-caret-down"></i></span></h3>
                            </div>
                        </div>
                    </div> -->
                    <!--/Metric Card-->
                <!-- </div>     -->
            </div>

            <div class="flex flex-wrap">
                <div class="w-1/2 md:w-1/4 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 border rounded shadow p-2 h-24 md:h-full">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4 hidden md:block">
                                <div class="rounded p-3 bg-green-600"><i class="fas fa-percent fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-center md:text-center">
                                <h5 class="font-bold uppercase text-gray-500">Margem de lucro</h5>
                                <h3 class="font-bold text-xl md:text-3xl"><?=rand(2,20)?>%</h3>
                            </div>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <div class="w-1/2 md:w-1/4 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 border rounded shadow p-2 h-24 md:h-full">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4 hidden md:block">
                                <div class="rounded p-3 bg-blue-600"><i class="fas fa-tasks fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-center md:text-center">
                                <h5 class="font-bold uppercase text-gray-500">Meta do dia</h5>
                                <h3 class="font-bold text-xl md:text-3xl">R$ <?=number_format(rand(30,200),2,",",".")?></h3>
                                <div class="relative w-full bg-red-500 rounded h-2 mt-2">
                                    <div style="width: <?=rand(1,100)?>%" class="absolute top-0 h-2 rounded shim-green"></div>
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <div class="w-1/2 md:w-1/4 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 border rounded shadow p-2 h-24 md:h-full">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4 hidden md:block">
                                <div class="rounded p-3 bg-red-600"><i class="fas fa-sack-xmark fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-center md:text-center">
                                <h5 class="font-bold uppercase text-gray-500">Stop Loss</h5>
                                <h3 class="font-bold text-xl md:text-3xl">R$ <?=number_format(rand(0,10000),2,",",".")?></h3>
                            </div>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>

                <div class="w-1/2 md:w-1/4 p-3">
                    <!--Metric Card-->
                    <div class="bg-gray-200 border rounded shadow p-2 h-24 md:h-full">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4 hidden md:block">
                                <div class="rounded p-3 bg-green-600"><i class="fas fa-sack-dollar fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-center md:text-center">
                                <h5 class="font-bold uppercase text-gray-500">Stop Win</h5>
                                <h3 class="font-bold text-xl md:text-3xl">R$ <?=number_format(rand(0,10000),2,",",".")?></h3>
                            </div>
                        </div>
                    </div>
                    <!--/Metric Card-->
                </div>
                
            </div>

            <!--Divider-->
            <hr class="border-b-5 border-gray-100 my-8 mx-4">

            <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full md:w-1/2 p-3">
                    <!--Template Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="bg-gray-200 border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Gest??o das Apostas</h5>
                        </div>
                        <div class="p-2">
                            <table class="w-full p-5 text-gray-700 text-center text-sm md:text-md">
                                <thead>
                                    <tr class="bg-gray-200 text-blue-900">
                                        <th>Porcentagem</th>
                                        <th>Valor da aposta</th>
                                        <th>Greens</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>0,5%</td>
                                        <td>R$ 5,00</td>
                                        <td>20</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>1%</td>
                                        <td>R$ 10,00</td>
                                        <td>15</td>
                                    </tr>
                                    <tr>
                                        <td>1,5%</td>
                                        <td>R$ 20,00</td>
                                        <td>14</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>2%</td>
                                        <td>R$ 80,00</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>3,5%</td>
                                        <td>R$ 80,00</td>
                                        <td>8</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>4%</td>
                                        <td>R$ 80,00</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>7%</td>
                                        <td>R$ 80,00</td>
                                        <td>2</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>8%</td>
                                        <td>R$ 80,00</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/Template Card-->
                </div>


                <div class="w-full md:w-1/2 p-3">
                    <!--Graph Card-->
                    <div class="bg-white border rounded shadow">
                        <div class="bg-gray-200 border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Saques</h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-7" class="" width="undefined" height="75"></canvas>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>



                <!-- <div class="w-full md:w-1/2 p-3"> -->
                    <!--Graph Card-->
                    <!-- <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Saques</h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-0" class="chartjs" width="undefined" height="undefined"></canvas>
                            
                        </div>
                    </div> -->
                    <!--/Graph Card-->
                <!-- </div> -->


            </div>

            <!--/ Console Content-->

        </div>


        <div id="operation-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center">
            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-end p-2">
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="operation-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        </div>
                        <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white">Em breve</h3>
                    <!-- <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valor</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required="">
                    </div>
                    
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Salvar</button> -->

                    </form>
                </div>
            </div>
        </div>
        

        <div id="reports-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center">
            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-end p-2">
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="reports-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        </div>
                        <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white">Em breve</h3>
                    <!-- <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valor</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required="">
                    </div>
                    
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Salvar</button> -->

                    </form>
                </div>
            </div>
        </div>

    </div>
    <!--/container-->

    <!-- Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="myAccountModal" tabindex="-1" aria-labelledby="myAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="myAccountModalLabel">
                Minha Conta
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
                        <input type="text" name="name" id="name"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="email@email.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Senha</label>
                        <input type="password" name="password" id="password" placeholder="????????????????????????" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                    </div>
                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Confirmar senha</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="????????????????????????" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                    </div>
                    <!-- <div>
                        <label for="newletter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Confirmar senha</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="????????????????????????" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                    </div> -->
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                        Encerrar sua conta conosco? <a href="#" class="text-blue-700 hover:underline dark:text-blue-500">Clique aqui</a>
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
    id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="withdrawalModalLabel">
                Informar Saque
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
                        <label for="withdrawal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valor</label>
                        <input type="number" min="0.00" max="1000000.00" step="0.01" name="withdrawal" id="withdrawal" placeholder="R$100,00" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required="">
                        <div class="text-sm text-gray-500 mt-1">Informe o valor que foi sacado da sua conta.</div>
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
    id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="configModalLabel">
                Configura????es da Banca
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
                        <label for="yield" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Procentagem de lucro</label>
                        <input type="number" min="0.00" max="1000000.00" step="0.01" name="yield" placeholder="8%" id="yield" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        <!-- <div class="text-sm text-gray-500 mt-1">Aplicada sobre o valor da banca do dia.</div> -->
                    </div>
                    <div>
                        <label for="stop_win" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Porcentagem para stop win</label>
                        <input type="number" min="0.00" max="100.00" step="0.01" name="stop_win" id="stop_win" placeholder="30%" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        <!-- <div class="text-sm text-gray-500 mt-1">Aplicada sobre o valor da banca do dia.</div> -->
                    </div>
                    <div>
                        <label for="stop_loss" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Porcentagem para stop loss</label>
                        <input type="number" min="0.00" max="100.00" step="0.01" name="stop_loss" id="stop_loss" placeholder="20%" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required="">
                        <!-- <div class="text-sm text-gray-500 mt-1">Aplicada sobre o valor da banca do dia.</div> -->
                    </div>
                    <div>
                        <div class="text-sm text-red-600 mt-1 font-bold">Os valores ser??o aplicados sobre o primeiro valor da banca informado no dia.</div>
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
    id="balanceCurrentModal" tabindex="-1" aria-labelledby="balanceCurrentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="balanceCurrentModalLabel">
                Atualizar valor da banca
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
                        <label for="bank_balance_current" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valor</label>
                        <input type="number" min="0.00" max="1000000.00" step="0.01" name="bank_balance_current" id="bank_balance_current" placeholder="R$100,00" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required="">
                        <div class="text-sm text-gray-500 mt-1">Valor da banca ap??s cada opera????o.</div>
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


    <!-- <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="balanceStartModal" tabindex="-1" aria-labelledby="balanceStartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div
                class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                <h5 class="text-xl font-medium leading-normal text-gray-800" id="balanceStartModalLabel">
                Banca - In??cio do dia
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
                        <label for="bank_balance_start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Valor</label>
                        <input type="number" min="0.00" max="1000000.00" step="0.01" name="bank_balance_start" id="bank_balance_start" placeholder="R$100,00" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required="">
                        <div class="text-sm text-gray-500 mt-1">Valor referente ao in??cio das opera????es no dia. N??o recomendamos a troca desse valor no mesmo dia.</div>
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
    </div> -->

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="projectionModal" tabindex="-1" aria-labelledby="projectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="projectionModalLabel">
                    Planejamento
                    </h5>
                    <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-bs-dismiss="modal" aria-label="Close">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="modal-body relative p-4">
                    <!-- <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#"> -->
                        <div>
                            <table class="w-full p-5 text-gray-700 text-center text-sm md:text-md">
                                <thead>
                                    <tr class="bg-gray-200 text-blue-900">
                                        <th>Dia</th>
                                        <th>Banca</th>
                                        <th>Resultado</th>
                                        <th>Lucro</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1??</td>
                                        <td>R$ 500,00</td>
                                        <td>R$ 530,00</td>
                                        <td>R$ 30,00</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>2??</td>
                                        <td>R$ 530,00</td>
                                        <td>R$ 561,80</td>
                                        <td>R$ 31,80</td>
                                    </tr>
                                    <tr>
                                        <td>3??</td>
                                        <td>R$ 561,80</td>
                                        <td>R$ 595,51</td>
                                        <td>R$ 33,71</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>4??</td>
                                        <td>R$ 595,51</td>
                                        <td>R$ 631,24</td>
                                        <td>R$ 35,73</td>
                                    </tr>
                                    <tr>
                                        <td>5??</td>
                                        <td>R$ 631,24</td>
                                        <td>R$ 669,11</td>
                                        <td>R$ 37,87</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>6??</td>
                                        <td>R$ 631,24</td>
                                        <td>R$ 709,26</td>
                                        <td>R$ 40,15</td>
                                    </tr>
                                    <tr>
                                        <td>7??</td>
                                        <td>R$ 709,26</td>
                                        <td>R$ 751,82</td>
                                        <td>R$ 42,56</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>8??</td>
                                        <td>R$ 751,82</td>
                                        <td>R$ 796,92</td>
                                        <td>R$ 45,11</td>
                                    </tr>
                                    <tr>
                                        <td>9??</td>
                                        <td>R$ 751,82</td>
                                        <td>R$ 844,74</td>
                                        <td>R$ 47,82</td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td>10??</td>
                                        <td>R$ 844,74</td>
                                        <td>R$ 895,42</td>
                                        <td>R$ 50,68</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Valores calculados com base na sua banca atual e na margem definida de lucro.</div>
                    <!-- </form> -->
                </div>
                <!-- <div  class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button"
                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                    Salvar
                    </button>
                </div> -->
            </div>
        </div>
    </div>


    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="operationModal" tabindex="-1" aria-labelledby="operationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="operationModalLabel">
                    Informar Opera????o
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
                            <div class="text-sm text-gray-500 mt-1">Em breve</div>
                        </div>
                    </form>
                </div>
                <!-- <div  class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button"
                    class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                    Salvar
                    </button>
                </div> -->
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <footer class="bg-zinc-800 border-t border-gray-400 shadow">
        <div class="container max-w-md mx-auto flex py-8">

            <div class="w-full mx-auto flex flex-wrap">
                <div class="flex w-full md:w-1/2 ">
                    <div class="px-8">
                        <h3 class="font-bold font-bold text-gray-100">Quem somos</h3>
                        <p class="py-4 text-gray-400 text-sm">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vel mi ut felis tempus commodo nec.
                        </p>
                    </div>
                </div>

                <div class="flex w-full md:w-1/2">
                    <div class="px-8">
                        <h3 class="font-bold font-bold text-gray-100">Contato</h3>
                        <ul class="list-reset items-center text-sm pt-3">
                            <li>
                                <span class="text-gray-100"><i class="fa-brands fa-instagram"></i></span> <a class="inline-block text-gray-400 no-underline hover:text-gray-100 hover:no-underline py-1" href="https://www.instagram.com/fourtr4de/">Intagram</a>
                            </li>
                            <li>
                                <span class="text-gray-100"><i class="fa-brands fa-telegram"></i></span> <a class="inline-block text-gray-400 no-underline hover:text-gray-100 hover:no-underline py-1" href="#">Telagram</a>
                            </li>
                            <li>
                                <span class="text-gray-100"><i class="fa-brands fa-whatsapp"></i></span> <a class="inline-block text-gray-400 no-underline hover:text-gray-100 hover:no-underline py-1" href="#">Whatsapp</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <div class="alert-footer w-full fixed bottom-0">
        <input type="checkbox" class="hidden" id="footeralert">
        <div class="flex items-center justify-between w-full p-2 bg-red-500 shadow text-white ">
            N??o localizamos nenhum registro referente ao valor atual da sua banca para este dia.<br/><br/>Informe o valor atual da sua banca para que possamos atualizar os seus par??metros.
            <label class="close cursor-pointer" title="close" for="footeralert">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
            </label>
        </div>
    </div>

</body>

</html>

<script>
new Chart(document.getElementById("chartjs-0"), {
    "type": "line",
    "data": {
        "labels": ["10/04/2022", "11/04/2022", "12/04/2022", "13/04/2022", "14/04/2022", "15/04/2022", "16/04/2022", "17/04/2022"],
        "datasets": [{
            "label": "",
            "data": [<?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>],
            "borderColor": "hsl(209, 100%, 42%)",
            "lineTension": 0.1,
            "fill": true,
            "backgroundColor": "hsl(183, 0%, 84%)",
            "borderWidth": 2,
            // "tension": 0,
            "pointRadius": 1,
            "pointHoverRadius": 3,
        }]
    },
    "options": {
        "scales": {
            "y": {
                "display": false,
                "beginAtZero": true,
            },
            "x": {
                "display": false,
            },
        },
        "plugins": {
            "legend": {
                "display": false,
            },
            "tooltip": {
                "callbacks": {
                // "title": () => true, // Disable tooltip title
                "label": (context) => ('R$ '+context.parsed.y),
                },
            },
        },
    }
});
</script>

<script>
new Chart(document.getElementById("chartjs-1"), {
    "type": "line",
    "data": {
        "labels": ["10/04/2022", "11/04/2022", "12/04/2022", "13/04/2022", "14/04/2022", "15/04/2022", "16/04/2022", "17/04/2022"],
        "datasets": [{
            "label": "",
            "data": [<?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>],
            "borderColor": "hsl(209, 100%, 42%)",
            "lineTension": 0.1,
            "fill": true,
            "backgroundColor": "hsl(183, 0%, 84%)",
            "borderWidth": 2,
            // "tension": 0,
            "pointRadius": 1,
            "pointHoverRadius": 3,
        }]
    },
    "options": {
        "scales": {
            "y": {
                "display": false,
                "beginAtZero": true,
            },
            "x": {
                "display": false,
            },
        },
        "plugins": {
            "legend": {
                "display": false,
            },
            "tooltip": {
                "callbacks": {
                // "title": () => true, // Disable tooltip title
                "label": (context) => ('R$ '+context.parsed.y),
                },
            },
        },
    }
});
</script>

<script>
new Chart(document.getElementById("chartjs-2"), {
    "type": "line",
    "data": {
        "labels": ["10/04/2022", "11/04/2022", "12/04/2022", "13/04/2022", "14/04/2022", "15/04/2022", "16/04/2022", "17/04/2022"],
        "datasets": [{
            "label": "",
            "data": [<?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>, <?=rand(100,1000)?>],
            "borderColor": "hsl(209, 100%, 42%)",
            "lineTension": 0.1,
            "fill": true,
            "backgroundColor": "hsl(183, 0%, 84%)",
            "borderWidth": 2,
            // "tension": 0,
            "pointRadius": 1,
            "pointHoverRadius": 3,
        }]
    },
    "options": {
        "scales": {
            "y": {
                "display": false,
                "beginAtZero": true,
            },
            "x": {
                "display": false,
            },
        },
        "plugins": {
            "legend": {
                "display": false,
            },
            "tooltip": {
                "callbacks": {
                // "title": () => true, // Disable tooltip title
                "label": (context) => ('R$ '+context.parsed.y),
                },
            },
        },
    }
});
</script>

<script>
new Chart(document.getElementById("chartjs-7"), {
    "type": "line",
    "data": {
        "labels": ["January", "February", "March", "April", "May", "June", "July"],
        "datasets": [{
            "label": "",
            "data": [<?=rand(100,300)?>, <?=rand(1000,3000)?>, <?=rand(1000,3000)?>, <?=rand(1000,3000)?>, <?=rand(1000,3000)?>, <?=rand(1000,3000)?>, <?=rand(1000,3000)?>],
            "borderColor": "rgb(75, 192, 192)",
            "lineTension": 0.1,
            "fill": true,
            
        }]
    },
    "options": {
        "scales": {
            "y": {
                "display": false,
                "beginAtZero": true,
            },
            "x": {
                "display": true,
            },
        },
        "plugins": {
            "legend": {
                "display": false,
            },
            "tooltip": {
                "callbacks": {
                // "title": () => true, // Disable tooltip title
                "label": (context) => ('R$ '+context.parsed.y),
                },
            },
        },
    }
});
</script>

<script>
/*Toggle dropdown list*/
/*https://gist.github.com/slavapas/593e8e50cf4cc16ac972afcbad4f70c8*/

var userMenuDiv = document.getElementById("userMenu");
var userMenu = document.getElementById("userButton");

var navMenuDiv = document.getElementById("nav-content");
var navMenu = document.getElementById("nav-toggle");

document.onclick = check;

function check(e) {
    var target = (e && e.target) || (event && event.srcElement);

    //User Menu
    if (!checkParent(target, userMenuDiv)) {
        // click NOT on the menu
        if (checkParent(target, userMenu)) {
            // click on the link
            if (userMenuDiv.classList.contains("invisible")) {
                userMenuDiv.classList.remove("invisible");
            } else { userMenuDiv.classList.add("invisible"); }
        } else {
            // click both outside link and outside menu, hide menu
            userMenuDiv.classList.add("invisible");
        }
    }

    //Nav Menu
    if (!checkParent(target, navMenuDiv)) {
        // click NOT on the menu
        if (checkParent(target, navMenu)) {
            // click on the link
            if (navMenuDiv.classList.contains("hidden")) {
                navMenuDiv.classList.remove("hidden");
            } else { navMenuDiv.classList.add("hidden"); }
        } else {
            // click both outside link and outside menu, hide menu
            navMenuDiv.classList.add("hidden");
        }
    }

}

function checkParent(t, elm) {
    while (t.parentNode) {
        if (t == elm) { return true; }
        t = t.parentNode;
    }
    return false;
}
</script>
