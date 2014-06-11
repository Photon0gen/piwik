<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Actions\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\Actions\Columns\DestinationPage;

class GetPageUrlsFollowingSiteSearch extends GetPageTitlesFollowingSiteSearch
{
    protected function init()
    {
        parent::init();
        $this->dimension     = new DestinationPage();
        $this->name          = Piwik::translate('Actions_WidgetPageUrlsFollowingSearch');
        $this->documentation = Piwik::translate('Actions_SiteSearchFollowingPagesDoc') . '<br/>' . Piwik::translate('General_UsePlusMinusIconsDocumentation');
        $this->metrics       = array('nb_hits_following_search', 'nb_hits');
        $this->order = 18;
        $this->widgetTitle  = 'Actions_WidgetPageUrlsFollowingSearch';
    }

    public function configureView(ViewDataTable $view)
    {
        $title = Piwik::translate('Actions_WidgetPageTitlesFollowingSearch');

        $this->configureViewForUrlAndTitle($view, $title);
    }
}