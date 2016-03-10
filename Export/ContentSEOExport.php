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
use Thelia\Model\ContentQuery;
use Thelia\Model\Map\ContentI18nTableMap;
use Thelia\Model\Map\ContentTableMap;

/**
 * Class ContentSEOExport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class ContentSEOExport extends AbstractExport
{
    const FILE_NAME = 'content_seo';

    protected $orderAndAliases = [
        'content_ID' => 'id',
        'content_TITLE' => 'title',
        'content_seo_META_TITLE' => 'meta_title',
        'content_seo_META_DESCRIPTION' => 'meta_description',
        'content_seo_META_KEYWORDS' => 'meta_keywords',
    ];

    public function getData()
    {
        $locale = $this->language->getLocale();

        $contentJoin = new Join(ContentTableMap::ID, ContentI18nTableMap::ID, Criteria::LEFT_JOIN);

        $query = ContentQuery::create()
            ->addSelfSelectColumns()
            ->addJoinObject($contentJoin, 'content_join')
            ->addJoinCondition('content_join', ContentI18nTableMap::LOCALE . ' = ?', $locale, null, \PDO::PARAM_STR)
            ->addAsColumn('content_ID', ContentI18nTableMap::ID)
            ->addAsColumn('content_TITLE', ContentI18nTableMap::TITLE)
            ->addAsColumn('content_seo_META_TITLE', ContentI18nTableMap::META_TITLE)
            ->addAsColumn('content_seo_META_DESCRIPTION', ContentI18nTableMap::META_DESCRIPTION)
            ->addAsColumn('content_seo_META_KEYWORDS', ContentI18nTableMap::META_KEYWORDS)
        ;

        return $query;
    }
}