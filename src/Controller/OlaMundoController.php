<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class OlaMundoController {

        /**
         * @Route("/ola")
         */
        public function olaMundoAction(Request $request): Response {

            $pathinfo = $request->getPathInfo();

            $parametro = $request->query->get('parametro');
            $query = $request->query->all();

            return new JsonResponse(['Mensagem' => 'Olá Mundo', 
                                     'PathInfo' => $pathinfo,
                                    'parametro' => $parametro,
                                    'all' => $query]);
        }
        
}