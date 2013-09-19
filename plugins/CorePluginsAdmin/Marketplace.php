<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package CorePluginsAdmin
 */
namespace Piwik\Plugins\CorePluginsAdmin;

use Piwik\Date;
use Piwik\Piwik;
use Piwik\PluginsManager;

/**
 *
 * @package CorePluginsAdmin
 */
class Marketplace
{
    /**
     * @var MarketplaceApiClient
     */
    private $client;
    private static $pluginUpdates = null;
    private static $themeUpdates  = null;

    public function __construct()
    {
        $this->client = new MarketplaceApiClient();
    }

    public function searchPlugins($query, $sort, $themesOnly)
    {
        if ($themesOnly) {
            $plugins = $this->client->searchForThemes('', $query, $sort);
        } else {
            $plugins = $this->client->searchForPlugins('', $query, $sort);
        }

        $dateFormat = Piwik_Translate('CoreHome_ShortDateFormatWithYear');

        foreach ($plugins as $plugin) {
            $plugin->canBeUpdated = $this->hasPluginUpdate($plugin);
            $plugin->isInstalled  = PluginsManager::getInstance()->isPluginLoaded($plugin->name);
            $plugin->lastUpdated  = Date::factory($plugin->lastUpdated)->getLocalized($dateFormat);
        }

        return $plugins;
    }

    private function hasPluginUpdate($plugin)
    {
        if (empty($plugin)) {
            return false;
        }

        $pluginsHavingUpdate = $this->getPluginsHavingUpdate($plugin->isTheme);

        foreach ($pluginsHavingUpdate as $pluginHavingUpdate) {
            if ($plugin->name == $pluginHavingUpdate->name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param bool $themesOnly
     * @return array
     */
    public function getPluginsHavingUpdate($themesOnly)
    {
        if ($themesOnly && !is_null(static::$themeUpdates)) {
            return static::$themeUpdates;
        } else if (!$themesOnly && !is_null(static::$pluginUpdates)) {
            return static::$pluginUpdates;
        }

        $pluginsHavingUpdate = $this->fetchPluginsHavingUpdate($themesOnly);

        if ($themesOnly) {
            static::$themeUpdates  = $pluginsHavingUpdate;
        } else {
            static::$pluginUpdates = $pluginsHavingUpdate;
        }

        return $pluginsHavingUpdate;
    }

    /**
     * @param $themesOnly
     * @return array
     */
    public function fetchPluginsHavingUpdate($themesOnly)
    {
        $pluginManager = PluginsManager::getInstance();
        $loadedPlugins = $pluginManager->getLoadedPlugins();

        try {
            if ($themesOnly) {
                $pluginsHavingUpdate = $this->client->getInfoOfThemesHavingUpdate($loadedPlugins);
                return $pluginsHavingUpdate;
            } else {
                $pluginsHavingUpdate = $this->client->getInfoOfPluginsHavingUpdate($loadedPlugins);
                return $pluginsHavingUpdate;
            }

        } catch (\Exception $e) {
            $pluginsHavingUpdate = array();
        }

        foreach ($pluginsHavingUpdate as $updatePlugin) {
            foreach ($loadedPlugins as $loadedPlugin) {

                if ($loadedPlugin->getPluginName() == $updatePlugin->name) {
                    $updatePlugin->currentVersion = $loadedPlugin->getVersion();
                    $updatePlugin->isActivated = $pluginManager->isPluginActivated($updatePlugin->name);
                    break;

                }
            }

        }
        return $pluginsHavingUpdate;
    }

}