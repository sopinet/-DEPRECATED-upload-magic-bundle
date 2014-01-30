<?php

namespace Sopinet\UploadMagicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SopinetUploadMagicBundle:Default:index.html.twig', array('name' => $name));
    }
}
