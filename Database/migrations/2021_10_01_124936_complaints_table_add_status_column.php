<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComplaintsTableAddStatusColumn extends Migration
{
    /**
     *
     */
    private const TABLE = 'complaints';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function ($table) {
            $table->string('status', 30)
                ->after('channel_post_id')
                ->default(\UserFeed\Enums\Complaint::NEW());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(static::TABLE, function ($table) {
            $table->dropColumn('status');
        });
    }
}
