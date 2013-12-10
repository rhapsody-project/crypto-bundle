<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Rhapsody\CryptoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RhapsodyCryptoExtension extends Extension
{
	// TODO: http://symfony.com/doc/current/components/dependency_injection/tags.html

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    	if (!array_filter($configs)) {
    		return;
    	}

    	$loader = new Loader\XmlFileLoader(
    			$container,
    			new FileLocator(__DIR__.'/../Resources/config')
    	);
    	$loader->load('crypto.xml');
    	$cryptoConfig = new Configuration();
    	$config = $this->processConfiguration($cryptoConfig, $configs);

    	// ** Set parameters for the types that are enabled...
    	$container->setParameter('rhapsody.crypto.enable_dbal_types', $config['enable_dbal_types']);
    	$container->setParameter('rhapsody.crypto.enable_mongodb_types', $config['enable_mongodb_types']);

    	$secureHashConfig = $config['secure_hash'];
    	$container->setParameter('rhapsody.crypto.secure_hash.algorithm', $secureHashConfig['algorithm']);
    	$container->setParameter('rhapsody.crypto.secure_hash.application_key', $secureHashConfig['application_key']);
    	$container->setParameter('rhapsody.crypto.secure_hash.bundle_key', $secureHashConfig['bundle_key']);
    	$container->setParameter('rhapsody.crypto.secure_hash.first_pass', $secureHashConfig['first_pass']);
    	$container->setParameter('rhapsody.crypto.secure_hash.second_pass', $secureHashConfig['second_pass']);
    	$container->setParameter('rhapsody.crypto.secure_hash.base64_encoding', $secureHashConfig['base64_encoding']);
    }
}
