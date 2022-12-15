<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComplaintsMakeComplaintTypeNullable extends Migration
{
    private const TABLE = 'complaints';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function ($table) {

            $table->dropForeign('complaints_type_id_foreign');

            $table->unsignedInteger('type_id')->nullable()->change();

            $table->foreign('type_id')
                ->references('id')
                ->on('complaint_types')
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
        //
    }
}
