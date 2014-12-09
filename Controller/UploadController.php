<?php
namespace Sopinet\Bundle\UploadMagicBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\RouteRedirectView;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @Route("/upload")
 */
class UploadController extends Controller
{
    /**
     * @Post("/delete", name="upload_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $delete = "";

        $id = $request->get('id');
        $entityString = $request->get('entity');
        $ent_a = explode("_", $entityString);

        eval("\$reEntity = \$em->getRepository('".$ent_a[0].$ent_a[1].":".$ent_a[2]."');");

        $entity = $reEntity->findOneById( $id );

        if($entity != null){

            $delete = "ok";

            // Borra el archivo
            $fs = new Filesystem();
            $fs->remove($entity->getPath());

            // Borra el registro en la base de datos
            $em->remove($entity);
            $em->flush();
        } else {
            $delete = "ko";
        }

        return $this->render('SopinetUploadMagicBundle:Upload:delete.html.twig', array('delete' => $delete));
    }

    /**
     * @Route("/preview/{id}/{entity}", name="upload_preview", options={"expose"=true})
     */
    public function previewAction($id, $entity)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $ent_a = explode("_", $entity);

        $reEntity = $em->getRepository($ent_a[0].$ent_a[1].":".$ent_a[2]);

        $entity = $reEntity->findOneById( $id );

        if($entity != null){

            // Previsualizar el archivo
            $file = $entity->getPath();
            $response = new BinaryFileResponse($file);

            return $response;
        }
    }


    /**
     * @Post("/addFile", name="upload_addfile")
     */
    public function addFileAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $delete = "";
        $id = $request->get('id');
        $name = $request->get('name');
        $entityString = $request->get('entity');
        $ent_a = explode("_", $entityString);

        $reEntity = $em->getRepository($ent_a[0].$ent_a[1].":".$ent_a[2]);

        $entity = $reEntity->findOneById( $id );

        if($entity != null){

            // Guardamos el título

            if($entity->getTitle() == null){
                $entity->setTitle($name);
            }

            // Guardamos el registro en la base de datos
            $em->persist($entity);
            $em->flush();

        } else {

        }

        return $this->render('SopinetUploadMagicBundle:Upload:delete.html.twig', array('delete' => $delete));
    }

    /**
     * @Post("/saveType", name="uploadmagic_savetype")
     */
    public function saveType(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $id = $request->get('id');
        $entityString = $request->get('entity');
        $format = $request->get('format');
        $text = $request->get('text');
        $ent_a = explode("_", $entityString);

        eval("\$reEntity = \$em->getRepository('".$ent_a[0].$ent_a[1].":".$ent_a[2]."');");

        $entity = $reEntity->findOneById( $id );

        if($entity != null){

            $save = "ok";

            $entity->setTitle($text);
            $entity->setFormat($format);

            $em->persist($entity);
            $em->flush();
        } else {
            $save = "ko";
        }

        return $this->render('SopinetUploadMagicBundle:Upload:delete.html.twig', array('delete' => $save));
    }
}
?>
