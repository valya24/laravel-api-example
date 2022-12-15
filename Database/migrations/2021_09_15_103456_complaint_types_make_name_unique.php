<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComplaintTypesMakeNameUnique extends Migration
{
    private const TABLE = 'complaint_types';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function ($table) {
            $table->unique('name')->change();
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
            $table->dropUnique('complaint_types_name_unique');
        });
    }
}
