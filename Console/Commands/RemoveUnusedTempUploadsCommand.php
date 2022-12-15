<?php
declare(strict_types=1);

namespace UserFeed\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use UserFeed\Models\TemporaryUpload;


/**
 * Class CheckFinishDealCommand
 * @package UserFeed\Console\Commands
 */
class RemoveUnusedTempUploadsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'temp:remove';

    /**
     * @var string
     */
    protected $description = 'Remove not used temporary files';

    /**
     *
     */
    private const CHUNK = 250;

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->output->writeln('Deleting...');

        $obTempFilesBuilder = TemporaryUpload::where('created_at', '<', Carbon::parse('-24 hours'));
        $obTempFilesBuilder->chunkById(static::CHUNK, static function(Collection $obTempFiles) {
            \DB::transaction(function() use($obTempFiles) {
                $obTempFiles->each(static function(TemporaryUpload  $obTemporaryFile) {
                    if ($mediaFile = $obTemporaryFile->getFirstMedia('editor')) {
                        $mediaFile->delete();
                    }

                    $obTemporaryFile->delete();
                });
            });
        });

        $this->output->writeln('Finished!');
    }
}
