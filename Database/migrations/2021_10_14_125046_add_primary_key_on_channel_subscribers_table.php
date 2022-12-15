<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AddPrimaryKeyOnChannelSubscribersTable extends Migration
{
    private const TABLE = 'channel_subscribers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table(static::TABLE)->truncate();

        Schema::table(static::TABLE, function(\Illuminate\Database\Schema\Blueprint  $obTable) {
           $obTable->primary(['channel_id', 'subscriber_id']);
        });

        User::select('id')
            ->whereDoesntHave('channels', static function ($query) {
                $query->whereId(config('app.user_nixon_id'));
            })->chunkById(500, static function (\Illuminate\Database\Eloquent\Collection $obUsers) {
                DB::table('channel_subscribers')->insert(
                    $obUsers->map(static fn($obUser) => [
                            'channel_id' => config('app.user_nixon_id'),
                            'subscriber_id' => $obUser->getKey()
                        ])
                        ->all()
                );
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
