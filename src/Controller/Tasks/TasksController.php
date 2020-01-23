<?php


namespace App\Controller\Tasks;


use App\Entity\Task;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    /**
     * @Route("/showTask/{id}", name="showTask")
     */
    public function showTask($id)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        return $this->render('tasks/showTask.html.twig', array('task' => $task));
    }

    /**
     * @Route("/tasks", name="addTasks")
     */
    public function addTask()
    {
        $entitymanager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $task = new Task();
        $task->setTitle('TaskName 1');
        $task->setDescription('Some quick example text to build on the card title and make up the bulk of the card\'s content.');
        $date = new \DateTime('@' . strtotime('now'));
        $task->setDate($date);
        $task->setUserId($user->getId());
        $entitymanager->persist($task);
        $entitymanager->flush();

        return new Response('Saved new Task with the id ' . $task->getId());
    }

    /**
     * @return Response
     * @Route("/newtask", name="newtask")
     */
    public function newTask(Request $request)
    {
        $task = new Task();
        $task->setTitle('Add Task');
        $task->setDescription('Enter your Description here');
        $task->setDate(new \DateTime('now'));
        $task->setIsdone(false);
        $task->setUserId($this->getUser()->getId());

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'), 'label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render('tasks/newTask.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/finishedTask", name="finishedTasks")
     */
    public function finishedTasks()
    {
        $user = $this->getUser();
        $user_id = $user->getId();
        $task = $this->getDoctrine()->getRepository(Task::class)->getAllFinishedTasks($user_id);

        return $this->render('tasks/doneTask.html.twig', array('tasks' => $task));
    }

    /**
     * @Route("/deleteTask/{id}", name="deleteTask")
     */
    public function deleteTask($id)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        if ($task) {
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->remove($task);
            $entitymanager->flush();

            return $this->redirectToRoute('index');
        }
    }

    /**
     * @Route("/isdoneTask/{id}", name="isdoneTask")
     */
    public function isdoneTask($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if ($task) {
            $task->setIsdone(true);
            $entityManager->flush();
            return $this->redirectToRoute("finishedTasks");
        }
    }

    /**
     * @Route("/editTask/{id}", name="editTask")
     * @Method({"GET", "POST"})
     */
    public function editTask(Request $request, $id)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('date', DateType::class)
            ->add('isdone', CheckboxType::class)
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'), 'label' => 'Save Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render('tasks/editTask.html.twig', array('form' => $form->createView()));
    }
}