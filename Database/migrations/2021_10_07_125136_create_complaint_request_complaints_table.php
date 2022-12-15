<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintRequestComplaintsTable extends Migration
{
    /**
     *
     */
    private const TABLE = 'complaint_request_complaints';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('complaint_request_id');
            $table->unsignedBigInteger('complaint_id');

            $table->primary(['complaint_request_id', 'complaint_id'], 'crc_primary');
            $table->foreign('complaint_request_id')
                ->references('id')
                ->on('complaint_requests')
                ->onDelete('cascade');
            $table->foreign('complaint_id')
                ->references('id')
                ->on('complaints')
                ->onDelete('cascade');
        });

        Schema::table('complaints', function(Blueprint $table) {
            $table->dropForeign('complaints_channel_post_id_foreign');

            $table->unsignedBigInteger('channel_post_id')->nullable()->change();
            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
                ->onDelete('set null');
        });

        Schema::table('complaint_requests', function(Blueprint $table) {
            $table->dropForeign('complaint_requests_channel_post_id_foreign');
            $table->unsignedBigInteger('channel_post_id')->nullable()->change();
            $table->foreign('channel_post_id')
                ->references('id')
                ->on('channel_posts')
                ->onDelete('set null');
        });

        $complaintRequests = \UserFeed\Models\ComplaintRequest::get();
        $complaintRequests->each(function(\UserFeed\Models\ComplaintRequest $complaintRequest) {
            $complaintInPost = \UserFeed\Models\Complaint::where('channel_post_id', $complaintRequest->channel_post_id)
                ->get();
            $complaintRequest->complaints()->sync($complaintInPost);
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
