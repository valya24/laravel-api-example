<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyComplaintTypesTable extends Migration
{
    private const TABLE = 'complaint_types';

    /**
     * @var array $arXssClean
     */
    protected array $arDefaultComplains = [
        'spam' => 'Спам',
        'violence' => 'Насилие',
        'pornography' => 'Порнография',
        'unauthorized_trade' => 'Несанкционированная торговля',
        'other' => 'Другое'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->renameColumn('name', 'type');
        });

        Schema::table(static::TABLE, function (Blueprint $table) {
            $table->string('type', 60)->change();
            $table->boolean('has_free')->default(false)->after('type');
            $table->string('name', 50)->after('type');
        });

        \UserFeed\Models\ComplaintType::all()
            ->each(function(\UserFeed\Models\ComplaintType $complaintType) {
                $complaintType->name = $this->arDefaultComplains[$complaintType->type];
                $complaintType->has_free = $complaintType->type === 'other';
                $complaintType->save();
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
            $table->dropColumn('name', 'has_free');
            $table->renameColumn('type', 'name');
        });
    }
}
