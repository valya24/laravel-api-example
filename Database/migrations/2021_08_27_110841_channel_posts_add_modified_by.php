<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChannelPostsAddModifiedBy extends Migration
{
    private const TABLE = 'channel_posts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function ($table) {
            $table->unsignedBigInteger('modified_by')->after('user_id')->nullable();

            $table->foreign('modified_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
            $table->dropForeign('channel_posts_modified_by_foreign');
            $table->dropColumn('modified_by');
        });
    }
}
