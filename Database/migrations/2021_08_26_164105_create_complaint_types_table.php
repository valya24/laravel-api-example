<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTypesTable extends Migration
{
    private const TABLE = 'complaint_types';

    /**
     * @var array $arXssClean
     */
    protected array $arDefaultComplains = [
        'spam',
        'violence',
        'pornography',
        'unauthorized_trade',
        'other'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
        });

        \UserFeed\Models\ComplaintType::insert(
            array_map(
                fn($arItem) => ['name' => $arItem],
                $this->arDefaultComplains
            )
        );

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
