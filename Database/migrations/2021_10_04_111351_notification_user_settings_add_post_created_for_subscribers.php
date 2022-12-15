<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationUserSettingsAddPostCreatedForSubscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\NotificationUserSettings::cursor()->each(function (\App\Models\NotificationUserSettings $obNotificationSetting) {
            $obNotificationSetting->settings = array_merge(['post_created' => true], $obNotificationSetting->settings);
            $obNotificationSetting->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\NotificationUserSettings::cursor()->each(function (\App\Models\NotificationUserSettings $obNotificationSetting) {
            $arSettings = $obNotificationSetting->settings;
            unset($arSettings['post_created']);
            $obNotificationSetting->settings = $arSettings;
            $obNotificationSetting->save();
        });
    }
}
