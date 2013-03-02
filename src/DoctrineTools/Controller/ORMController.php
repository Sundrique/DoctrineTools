<?php

namespace DoctrineTools\Controller;

use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Request as HttpRequest;

class ORMController extends AbstractActionController {

	public function generateEntitiesAction() {
		$config = $this->getConfiguration();

		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$cmf = new DisconnectedClassMetadataFactory();
		$cmf->setEntityManager($em);
		$metadatas = $cmf->getAllMetadata();

		// Process destination directory
		$destPath = $config['dir'];

		if (!file_exists($destPath)) {
			throw new \InvalidArgumentException(
				sprintf("Entities destination directory '<info>%s</info>' does not exist.", $destPath)
			);
		}

		if (!is_writable($destPath)) {
			throw new \InvalidArgumentException(
				sprintf("Entities destination directory '<info>%s</info>' does not have write permissions.", $destPath)
			);
		}

		if (count($metadatas)) {
			// Create EntityGenerator
			$entityGenerator = new EntityGenerator();

			$entityGenerator->setGenerateAnnotations(true);//$input->getOption('generate-annotations'));
			$entityGenerator->setGenerateStubMethods(true);//$input->getOption('generate-methods'));
			$entityGenerator->setRegenerateEntityIfExists(true);//$input->getOption('regenerate-entities'));
			/*$entityGenerator->setUpdateEntityIfExists($input->getOption('update-entities'));
			$entityGenerator->setNumSpaces($input->getOption('num-spaces'));*/

			/*if (($extend = $input->getOption('extend')) !== null) {
				$entityGenerator->setClassToExtend($extend);
			}*/

			// Generating Entities
			$entityGenerator->generate($metadatas, $destPath);

			// Outputting information message
			//$output->writeln(PHP_EOL . sprintf('Entity classes generated to "<info>%s</INFO>"', $destPath));
		} else {
			//$output->writeln('No Metadata Classes to process.');
		}
	}

	public function getConfiguration() {
		$config = $this->getServiceLocator()->get('Config');
		$ormConfig = $config['doctrinetools']['orm'];
		if ($dir = $this->getRequest()->getParam('dir', false)) {
			$ormConfig['dir'] = $dir;
		}
		if ($namespace = $this->getRequest()->getParam('namespace', false)) {
			$ormConfig['namespace'] = $namespace;
		}
		return $ormConfig;
	}

}