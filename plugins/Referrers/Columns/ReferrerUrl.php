<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Referrers\Columns;

use Piwik\Tracker\Request;

class ReferrerUrl extends Base
{
    protected $fieldName = 'referer_url';
    protected $fieldType = 'TEXT NOT NULL';

    public function getName()
    {
        return '';
    }

    public function onNewVisit(Request $request, $visit)
    {
        $referrerUrl = $request->getParam('urlref');
        $currentUrl  = $request->getParam('url');

        $information = $this->getReferrerInformation($referrerUrl, $currentUrl, $request->getIdSite());

        return $information['referer_url'];
    }
}