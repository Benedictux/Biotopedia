<?php
// src/Biotopedia/CoreBundle/Controller/ContactController.php

namespace Biotopedia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('BiotopediaCoreBundle:Contact:index.html.twig');
    }
}
