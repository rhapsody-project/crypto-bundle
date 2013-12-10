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
namespace Rhapsody\CryptoBundle;

use Rhapsody\CryptoBundle\Mapping\DBAL\TypeInstaller as DBALTypeInstaller;
use Rhapsody\CryptoBundle\Mapping\MongoDB\TypeInstaller as MongoDBTypeInstaller;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler\AddSecurityVotersPass;

/**
 * Bundle.
 *
 * @author Sean.Quinn
 * @since 1.0
 */
class RhapsodyCryptoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function boot() {
    	$enableDbalTypes = $this->container->getParameter('rhapsody.crypto.enable_dbal_types');
    	if ($enableDbalTypes === true) {
    		$installer = new DBALTypeInstaller($this->container);
    		$installer->installTypes();
    	}

    	$enableMongoDbTypes = $this->container->getParameter('rhapsody.crypto.enable_mongodb_types');
    	if ($enableMongoDbTypes === true) {
    		$installer = new MongoDBTypeInstaller($this->container);
    		$installer->installTypes();
    	}
    }
}
