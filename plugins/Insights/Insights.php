<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Insights;

/**
 */
class Insights extends \Piwik\Plugin
{
    /**
     * @see Piwik\Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'ViewDataTable.addViewDataTable' => 'getAvailableVisualizations'
        );
    }

    public function getAvailableVisualizations(&$visualizations)
    {
        $visualizations[] = __NAMESPACE__ . '\\Visualizations\\Insight';
    }

    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = "plugins/Insights/stylesheets/insightVisualization.less";
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = "plugins/Insights/javascripts/insightsDataTable.js";
    }

}
