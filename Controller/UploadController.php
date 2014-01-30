<?php 
namespace Sopinet\UploadMagicBundle\Controller;

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
			
			$em->remove($entity);
			$em->flush();
		} else {
			$delete = "ko";
		}
		
		return $this->render('SopinetUploadMagicBundle:Upload:delete.html.twig', array('delete' => $delete));
	}
}
?>