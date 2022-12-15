<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyComplaintRequestsTable extends Migration
{
    private const TABLE = 'complaint_requests';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->after('channel_post_id')
                ->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->dropForeign('complaint_requests_channel_post_id_foreign');

            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
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
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->dropForeign('complaint_requests_user_id_foreign');
            $table->dropColumn('user_id');

            $table->dropForeign('complaint_requests_channel_post_id_foreign');
            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
                ->onDelete('cascade');
        });
    }
}
