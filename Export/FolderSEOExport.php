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
use Thelia\Model\FolderQuery;
use Thelia\Model\Map\FolderI18nTableMap;
use Thelia\Model\Map\FolderTableMap;

/**
 * Class FolderSEOExport
 * @package ImportExportSEO\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class FolderSEOExport extends AbstractExport
{
    const FILE_NAME = 'folder_seo';

    protected $orderAndAliases = [
        'folder_ID' => 'id',
        'folder_TITLE' => 'title',
        'folder_seo_META_TITLE' => 'meta_title',
        'folder_seo_META_DESCRIPTION' => 'meta_description',
        'folder_seo_META_KEYWORDS' => 'meta_keywords',
    ];

    public function getData()
    {
        $locale = $this->language->getLocale();

        $folderJoin = new Join(FolderTableMap::ID, FolderI18nTableMap::ID, Criteria::LEFT_JOIN);

        $query = FolderQuery::create()
            ->addSelfSelectColumns()
            ->addJoinObject($folderJoin, 'folder_join')
            ->addJoinCondition('folder_join', FolderI18nTableMap::LOCALE . ' = ?', $locale, null, \PDO::PARAM_STR)
            ->addAsColumn('folder_ID', FolderI18nTableMap::ID)
            ->addAsColumn('folder_TITLE', FolderI18nTableMap::TITLE)
            ->addAsColumn('folder_seo_META_TITLE', FolderI18nTableMap::META_TITLE)
            ->addAsColumn('folder_seo_META_DESCRIPTION', FolderI18nTableMap::META_DESCRIPTION)
            ->addAsColumn('folder_seo_META_KEYWORDS', FolderI18nTableMap::META_KEYWORDS)
        ;

        return $query;
    }
}