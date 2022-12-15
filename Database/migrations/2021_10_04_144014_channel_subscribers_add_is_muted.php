<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChannelSubscribersAddIsMuted extends Migration
{
    private const TABLE = 'channel_subscribers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->boolean('is_muted')->after('subscriber_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->dropColumn('is_muted');
        });
    }
}
