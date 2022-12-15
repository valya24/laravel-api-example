<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChannelPostsAddStatusColumn extends Migration
{
    private const TABLE = 'channel_posts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->string('complaint_status', 30)->after('seo')->nullable();
            $table->string('delete_reason')->after('complaint_status')->nullable();
            $table->softDeletes();
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
            $table->dropColumn('delete_reason');
            $table->dropColumn('complaint_status');
            $table->dropSoftDeletes();
        });
    }
}
