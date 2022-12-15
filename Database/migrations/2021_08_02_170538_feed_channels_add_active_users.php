<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use App\Classes\Helper\DealHelper;


class FeedChannelsAddActiveUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DealHelper::getDealsWithMostActiveUsers();
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
