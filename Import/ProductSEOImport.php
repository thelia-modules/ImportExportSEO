<?php

/*************************************************************************************/
/*      This file is part of the ImportExportSEO package.                           */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ImportExportSEO\Import;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\UpdateSeoEvent;
use Thelia\ImportExport\Import\AbstractImport;
use Thelia\Model\ProductQuery;
use Thelia\Core\Translation\Translator;

/**
 * Class ProductSEOImport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class ProductSEOImport extends AbstractImport
{
    protected $mandatoryColumns = [
        'ref',
        'visible',
    ];

    /**
     * @param array $data
     * @return string
     */
    public function importData(array $data)
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        $product = ProductQuery::create()->findOneByRef($data['ref']);
        $id = $product->getId();

        if ($product === null) {
            return Translator::getInstance()->trans(
                'The product ref %ref doesn\'t exist',
                [
                    '%ref' => $data['ref']
                ]
            );
        } else {

            $locale = $this->language->getLocale();

            $updateSeoEvent = new UpdateSeoEvent($id);

            $updateSeoEvent
                ->setLocale($locale)
                ->setMetaTitle($data['page_title'])
                ->setMetaDescription($data['meta_description'])
                ->setMetaKeywords($data['meta_keywords'])
                ->setUrl($data['url'])
            ;

            $eventDispatcher->dispatch(TheliaEvents::PRODUCT_UPDATE_SEO, $updateSeoEvent);
            $this->importedRows++;

        }
    }
}