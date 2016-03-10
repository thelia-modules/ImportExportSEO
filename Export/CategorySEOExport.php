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
use Thelia\Model\CategoryQuery;
use Thelia\Model\Map\CategoryI18nTableMap;
use Thelia\Model\Map\CategoryTableMap;

/**
 * Class CategorySEOExport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class CategorySEOExport extends AbstractExport
{
    const FILE_NAME = 'category_seo';

    protected $orderAndAliases = [
        'category_ID' => 'id',
        'category_TITLE' => 'title',
        'category_seo_META_TITLE' => 'meta_title',
        'category_seo_META_DESCRIPTION' => 'meta_description',
        'category_seo_META_KEYWORDS' => 'meta_keywords',
    ];

    public function getData()
    {
        $locale = $this->language->getLocale();

        $categoryJoin = new Join(CategoryTableMap::ID, CategoryI18nTableMap::ID, Criteria::LEFT_JOIN);

        $query = CategoryQuery::create()
            ->addSelfSelectColumns()
            ->addJoinObject($categoryJoin, 'category_join')
            ->addJoinCondition('category_join', CategoryI18nTableMap::LOCALE . ' = ?', $locale, null, \PDO::PARAM_STR)
            ->addAsColumn('category_ID', CategoryI18nTableMap::ID)
            ->addAsColumn('category_TITLE', CategoryI18nTableMap::TITLE)
            ->addAsColumn('category_seo_META_TITLE', CategoryI18nTableMap::META_TITLE)
            ->addAsColumn('category_seo_META_DESCRIPTION', CategoryI18nTableMap::META_DESCRIPTION)
            ->addAsColumn('category_seo_META_KEYWORDS', CategoryI18nTableMap::META_KEYWORDS)
        ;

        return $query;
    }
}