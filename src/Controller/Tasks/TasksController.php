<?php


namespace App\Controller\Tasks;


use App\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
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

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'), 'label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $task = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render('tasks/newTask.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/editTask/{id}", name="editTask")
     * @Method({"GET", "POST"})
     */
    public function editTask(Request $request, $id)
    {
        $task = new Task();
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('date', DateType::class)
            ->add('isdone', CheckboxType::class)
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'), 'label' => 'Save Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render('tasks/editTask.html.twig', array('form' => $form->createView()));
    }
}

















