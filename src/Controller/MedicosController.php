<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Medico;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\MedicoFactory;

class MedicosController extends AbstractController {

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MedicoFactory
     */

    private $medicoFacotry;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFacotry)
    {
        $this->entityManager = $entityManager;
        $this->medicoFacotry = $medicoFacotry;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request) : Response {

        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFacotry->criarMedico($corpoRequisicao);

        $this->entityManager->persist($medico);
        $this->entityManager->flush(); // Envia par ao banco

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscarTodos() : Response {

        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicosList = $repositorioDeMedicos->findAll();
        
        return new JsonResponse($medicosList);

    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function buscarUm(Request $request) : Response {

        $id = $request->get('id');
        $medico = $this->buscaMedico($id);
        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);

    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualiza(int $id, Request $request) : Response {

        $corpoRequisicao = $request->getContent();
        $medicoEnviado = $this->medicoFacotry->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);
        
        if(is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }


        $medicoExistente->crm = $medicoEnviado->crm;
        $medicoExistente->nome = $medicoEnviado->nome;

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);

    }

    public function buscaMedico(int $id) {

        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioDeMedicos->find($id);

    
        return $medico;
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function excluir(int $id) : Response {

        $medico = $this->buscaMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);

    }


}