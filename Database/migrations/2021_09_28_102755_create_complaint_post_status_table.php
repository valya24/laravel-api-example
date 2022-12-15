<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use UserFeed\Models\ComplaintRequest;
use UserFeed\Models\ChannelPost;

class CreateComplaintPostStatusTable extends Migration
{
    private const REQUEST_TABLE = 'complaint_requests';
    private const POSTS_TABLE = 'channel_posts';
    private const CHUNK_LIMIT = 20;


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::REQUEST_TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_post_id')->unsigned();
            $table->string('status', 30)->nullable();
            $table->string('delete_reason')->nullable();
            $table->timestamps();

            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
                ->onDelete('cascade');
        });

        if (!Schema::hasColumn(static::POSTS_TABLE, 'complaint_status')) {
            return;
        }

        DB::transaction(static function () {
            DB::table(static::POSTS_TABLE)
                ->whereNotNull('complaint_status')
                ->chunkById(static::CHUNK_LIMIT, function (\Illuminate\Support\Collection $obPosts) {
                    ComplaintRequest::insert(
                            $obPosts->map(static fn($obPost) => [
                                'channel_post_id' => $obPost->id,
                                'status' => $obPost->complaint_status,
                                'delete_reason' => $obPost->delete_reason
                            ])
                            ->all()
                    );
                });

            Schema::table(static::POSTS_TABLE, function (Blueprint $table) {
                $table->dropColumn('complaint_status');
                $table->dropColumn('delete_reason');
            });

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(static function () {

            Schema::table(static::POSTS_TABLE, function ($table) {
                $table->string('complaint_status', 30)->after('seo')->nullable();
                $table->string('delete_reason')->after('complaint_status')->nullable();
            });

            DB::table(static::REQUEST_TABLE)
                ->chunkById(static::CHUNK_LIMIT, function ($obComplaintRequests) {
                    foreach ($obComplaintRequests as $obComplaintRequest) {

                        $obChannelPost = ChannelPost::withTrashed()->find($obComplaintRequest->channel_post_id);
                        $obChannelPost->complaint_status = $obComplaintRequest->status;
                        $obChannelPost->delete_reason = $obComplaintRequest->delete_reason;
                        $obChannelPost->save();
                    }
                });

            Schema::table(static::REQUEST_TABLE, function (Blueprint $table) {
                $table->dropColumn('status');
                $table->dropColumn('delete_reason');
            });

        });

        Schema::dropIfExists(static::REQUEST_TABLE);
    }
}
