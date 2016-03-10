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
use Thelia\Core\Translation\Translator;
use Thelia\ImportExport\Import\AbstractImport;
use Thelia\Model\CategoryQuery;

/**
 * Class CategorySEOImport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class CategorySEOImport extends AbstractImport
{
    protected $mandatoryColumns = [
        'id'
    ];

    public function importData(array $data)
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        $category = CategoryQuery::create()->findPk($data['id']);
        $id = $category->getId();

        if ($category === null) {
            return Translator::getInstance()->trans(
                'The category id %id doesn\'t exist',
                [
                    '%id' => $data['id']
                ]
            );
        } else {

            $locale = $this->language->getLocale();

            $updateSeoEvent = new UpdateSeoEvent($id);

            $updateSeoEvent
                ->setLocale($locale)
                ->setMetaTitle($data['meta_title'])
                ->setMetaDescription($data['meta_description'])
                ->setMetaKeywords($data['meta_keywords'])
            ;

            $eventDispatcher->dispatch(TheliaEvents::CATEGORY_UPDATE_SEO, $updateSeoEvent);
            $this->importedRows++;

        }
    }
}