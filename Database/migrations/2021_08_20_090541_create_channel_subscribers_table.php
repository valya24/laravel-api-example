<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelSubscribersTable extends Migration
{
    private const TABLE = 'channel_subscribers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('user_id')
                ->on('feed_channels')
                ->onDelete('cascade');

            $table->foreign('subscriber_id')
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
        Schema::dropIfExists('channel_subscribers');
    }
}
