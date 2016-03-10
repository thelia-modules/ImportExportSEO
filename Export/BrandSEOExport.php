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

namespace ImportExportSEO\Export;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\ImportExport\Export\AbstractExport;
use Thelia\Model\BrandQuery;
use Thelia\Model\Map\BrandI18nTableMap;
use Thelia\Model\Map\BrandTableMap;

/**
 * Class BrandSEOExport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class BrandSEOExport extends AbstractExport
{
    const FILE_NAME = 'brand_seo';

    protected $orderAndAliases = [
        'brand_ID' => 'id',
        'brand_TITLE' => 'title',
        'brand_seo_META_TITLE' => 'meta_title',
        'brand_seo_META_DESCRIPTION' => 'meta_description',
        'brand_seo_META_KEYWORDS' => 'meta_keywords',
    ];

    public function getData()
    {
        $locale = $this->language->getLocale();

        $brandJoin = new Join(BrandTableMap::ID, BrandI18nTableMap::ID, Criteria::LEFT_JOIN);

        $query = BrandQuery::create()
            ->addSelfSelectColumns()
            ->addJoinObject($brandJoin, 'brand_join')
            ->addJoinCondition('brand_join', BrandI18nTableMap::LOCALE . ' = ?', $locale, null, \PDO::PARAM_STR)
            ->addAsColumn('brand_ID', BrandI18nTableMap::ID)
            ->addAsColumn('brand_TITLE', BrandI18nTableMap::TITLE)
            ->addAsColumn('brand_seo_META_TITLE', BrandI18nTableMap::META_TITLE)
            ->addAsColumn('brand_seo_META_DESCRIPTION', BrandI18nTableMap::META_DESCRIPTION)
            ->addAsColumn('brand_seo_META_KEYWORDS', BrandI18nTableMap::META_KEYWORDS)
        ;

        return $query;
    }
}