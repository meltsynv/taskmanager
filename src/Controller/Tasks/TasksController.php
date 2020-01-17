<?php


namespace App\Controller\Tasks;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findAll();
        return $this->render('tasks/index.html.twig', array('tasks' => $task));
    }

    /**
     * @Route("/tasks", name="addTasks")
     */
    public function addTask()
    {
        $entitymanager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle('TaskName 1');
        $task->setDescription('Some quick example text to build on the card title and make up the bulk of the card\'s content.');
        $date = new \DateTime('@' . strtotime('now'));
        $task->setDate($date);
        $entitymanager->persist($task);
        $entitymanager->flush();

        return new Response('Saved new Task with the id ' . $task->getId());
    }
}