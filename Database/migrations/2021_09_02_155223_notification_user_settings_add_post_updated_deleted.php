<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationUserSettingsAddPostUpdatedDeleted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\NotificationUserSettings::cursor()->each(function (\App\Models\NotificationUserSettings $obNotificationSetting) {
            $obNotificationSetting->settings = array_merge(['post_updated' => true, 'post_deleted' => true], $obNotificationSetting->settings);
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
            unset($arSettings['post_updated'], $arSettings['post_deleted']);
            $obNotificationSetting->settings = $arSettings;
            $obNotificationSetting->save();
        });
    }
}
