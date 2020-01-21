<?php


namespace App\Controller\Home;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_login');
        }

        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        return $this->render('pages/index.html.twig', ['tasks' => $tasks, 'user' => $user]);
    }
}