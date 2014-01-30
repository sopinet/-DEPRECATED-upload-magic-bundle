<?php 
namespace Sopinet\Bundle\UploadMagicBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class UploadListener
{
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onUpload(PostPersistEvent $event)
    {
    	$request = $event->getRequest();
    	
    	$ent_t = $request->get('entity');
    	$ent_a = explode("_", $ent_t);
    	
    	$id = $request->get('id');
    	
    	eval("\$reEntity = \$this->doctrine->getRepository('".$ent_a[0].$ent_a[1].":".$ent_a[2]."');");    	    	
    	if ($id == 0) {
    		eval("\$entity = new \\".$ent_a[0]."\\".$ent_a[1]."\\Entity\\".$ent_a[2]."();");
    	} else {
			$entity = $reEntity->findOneById($id);
			if ($entity == null) {
				eval("\$entity = new \\".$ent_a[0]."\\".$ent_a[1]."\\Entity\\".$ent_a[2]."();");				
			}    		
    	}
    	
    	foreach($request->request->all() as $key => $value) {
    		$temp = explode("_", $key);
    		if ($temp[0] == "add") {
    			if (count($temp) == 2) {
    				eval("\$entity->set".ucfirst($temp[1])."('".$value."');");
    			} else {    				
    				eval("\$reTemp = \$this->doctrine->getRepository('".$temp[1].$temp[2].":".$temp[3]."');");
    				$easig = $reTemp->findOneById($value);
    				if ($easig == null) {
    					eval("\$entity->set".ucfirst($temp[3])."(null);");
    				} else {
    					eval("\$entity->set".ucfirst($temp[3])."(\$easig);");
    				}
    			}
    		}
    	}
    	$entity->setPath($event->getFile()->getRealPath());
    	$entity->setDir($event->getType());
    	$this->doctrine->getManager()->persist($entity);
    	$this->doctrine->getManager()->flush();
    	//$entity = new \iCofrade\BaseBundle\Entity\Image();
    	// echo $entity->getPath();
    	//$gallery = $request->get('gallery');
    	// $gallery = $request->get('gallery');

    	//$log = new Logger('name');
    	//$log->pushHandler(new StreamHandler('/var/www/icofrade/your.log', Logger::WARNING));
    	
    	// $log->addError($request->getUriForPath($event->getFile()));
    	// add records to the log
    	// Variable $log->addWarning("Gallery: "+$gallery);
    	// $log->addError($event->getType());
    	// $log->addError($event->getFile());
    	// $log->addError($event->getFile()->getRealPath());
    	// $log->addError($event->getFile()->getUrl());
    	
        // ldd($request);
    	$response = $event->getResponse();
    	
        $response['id'] = $entity->getId();
        $response['entity'] = $request->get('entity');
    }
}
?>
