<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelPostsTable extends Migration
{
    private const TABLE = 'channel_posts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('channel_id')->index() ;
            $table->unsignedBigInteger('user_id')->index();
            $table->string('title');
            $table->string('short_description');
            $table->longText('description');
            $table->json('seo')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('user_id')
                ->on('feed_channels')
                ->onDelete('cascade');

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
        Schema::dropIfExists(static::TABLE);
    }
}
