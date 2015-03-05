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
    	
	// Check for AppBundle in Symfony 2.6
	if (count($ent_a) == 2) {
		$repository_ent_a = $ent_a[0] . ":" . $ent_a[1];
		$entity_ent_a = $ent_a[0]."\\Entity\\".$ent_a[1];
	} else if (count($ent_a) == 3) {
		$repository_ent_a = $ent_a[0].$ent_a[1] . ":" . $ent_a[2];
		$entity_ent_a = $ent_a[0]."\\".$ent_a[1]."\\Entity\\".$ent_a[2];
	}

    	eval("\$reEntity = \$this->doctrine->getRepository('".$repository_ent_a."');");    	    	
    	if ($id == 0) {
    		eval("\$entity = new \\".$entity_ent_a."();");
    	} else {
			$entity = $reEntity->findOneById($id);
			if ($entity == null) {
				eval("\$entity = new \\".$entity_ent_a."();");				
			}    		
    	}
    	
    	foreach($request->request->all() as $key => $value) {
    		$temp = explode("_", $key);
    		if ($temp[0] == "add") {
    			if (count($temp) == 2) {
    				eval("\$entity->set".ucfirst($temp[1])."('".$value."');");
    			} else if (count($temp) == 3) {
    				eval("\$reTemp = \$this->doctrine->getRepository('".$temp[1].":".$temp[2]."');");
    				$easig = $reTemp->findOneById($value);
    				if ($easig == null) {
    					eval("\$entity->set".ucfirst($temp[2])."(null);");
    				} else {
    					eval("\$entity->set".ucfirst($temp[2])."(\$easig);");
    				}
    			} else if (count($temp) == 4) {
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

    	$response = $event->getResponse();
    	
        $response['id'] = $entity->getId();
        $response['entity'] = $request->get('entity');
    }
}
?>
