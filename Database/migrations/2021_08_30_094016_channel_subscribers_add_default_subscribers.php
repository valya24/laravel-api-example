<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ChannelSubscribersAddDefaultSubscribers extends Migration
{
    /**
     * Users should not unsubscribe from this user
     * @deprecated see config('app.user_nixon_id')
     */
    private const R_Nixon_Id = 1;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \UserFeed\Models\Channel::updateOrCreate(
            ['user_id' => static::R_Nixon_Id],
            ['is_enabled' => 1],
        );

        User::select('id')->whereDoesntHave('channels', static function ($query) {
            $query->whereId(static::R_Nixon_Id);
        })->chunkById(200, function (\Illuminate\Database\Eloquent\Collection $obUsers) {
            DB::table('channel_subscribers')->insert(
                $obUsers
                    ->map(fn($obUser) => ['channel_id' => static::R_Nixon_Id, 'subscriber_id' => $obUser->getKey()])
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
