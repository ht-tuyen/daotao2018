<?php

namespace backend\models;

/**
 * Interface SettingInterface
 * @package backend\models
 *
 */
interface SettingsInterface
{

    /**
     * Gets a combined map of all the settings.
     * @return array
     */
    public function getSettings();

    /**
     * Saves a setting
     *
     * @param $key
     * @param $value
     * @param $type
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function setSetting($key, $value, $type);

    /**
     * Deletes a settings
     *
     * @param $key
     * @return boolean True on success, false on error
     */
    public function deleteSetting($key);

    /**
     * Deletes all settings! Be careful!
     * @return boolean True on success, false on error
     */
    public function deleteAllSettings();

    /**
     * Activates a setting
     *
     * @param $key
     * @return boolean True on success, false on error
     */
    public function activateSetting($key);

    /**
     * Deactivates a setting
     *
     * @param $key
     * @return boolean True on success, false on error
     */
    public function deactivateSetting($key);

}