<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    private const TABLE = 'complaints';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('type_id');
            $table->string('description', 50)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('channel_post_id');
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('complaint_types')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
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
        Schema::dropIfExists(static::TABLE);
    }
}
