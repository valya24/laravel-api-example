<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TemporaryUploadUserIdTable extends Migration
{
    private const TABLE = 'temporary_uploads';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
            $table->dropForeign('temporary_uploads_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
