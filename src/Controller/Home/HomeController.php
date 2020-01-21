<?php


namespace App\Controller\Home;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //services
    //transactions an Concurrency
    //twig
    //translation
    //yamel
    //security
    //filesystem
    //finder
    //property_access
    //cach

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        $maxTasksValue = $this->getDoctrine()->getRepository(Task::class)->getNumberOfTasks();
        $numberOfFinishedTasks = $this->getDoctrine()->getRepository(Task::class)->getAllFinishedTasks();
        $procent = ($numberOfFinishedTasks / $maxTasksValue) * 100 ;

        return $this->render('pages/index.html.twig',
            [
                'tasks' => $tasks,
                'user' => $user,
                'maxValue' => $maxTasksValue,
                'numberOfFinishedTasks' => $numberOfFinishedTasks,
                'procent' => $procent
            ]);
    }
}