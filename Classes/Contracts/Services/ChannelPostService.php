<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use UserFeed\Classes\Dto\ChannelPostShowDto;
use UserFeed\Classes\Dto\ChannelPostsShowDto;
use UserFeed\Classes\Dto\ChannelPostStoreDto;
use UserFeed\Classes\Dto\ChannelPostUpdateDto;
use UserFeed\Models\Channel;
use UserFeed\Models\ChannelPost;

/**
 * Interface ChannelPostService
 * @package UserFeed\Classes\Contracts\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ChannelPostService
{
    /**
     * @param User $obUser
     * @param ChannelPostStoreDto $obPostStoreDto
     * @return ChannelPost
     */
    public function store(User $obUser, ChannelPostStoreDto $obPostStoreDto): ChannelPost;

    /**
     * @param ChannelPostsShowDto $obChannelPostsShowDto
     * @return Collection|null
     */
    public function getChannelPosts(ChannelPostsShowDto $obChannelPostsShowDto): ?Collection;

    /**
     * @param ChannelPostShowDto $obChannelPostShowDto
     * @return ChannelPost|null
     */
    public function show(ChannelPostShowDto $obChannelPostShowDto): ?ChannelPost;

    /**
     * @param User|null $obUser
     * @param int $iOffset
     * @return Collection|null
     */
    public function getFeeds(int $iOffset, User $obUser): ?Collection;


    /**
     * @param string $sIdOrSlug
     * @param User|null $obUser
     * @return ChannelPost|null
     */
    public function getByIdOrSlug(string $sIdOrSlug, ?User $obUser = null): ?ChannelPost;

    /**
     * @param User $obUser
     * @param ChannelPostUpdateDto $obPostUpdateDto
     * @param int $iChannelPostId
     * @return ChannelPost
     */
    public function update(User $obUser, ChannelPostUpdateDto $obPostUpdateDto, int $iChannelPostId): ChannelPost;

    /**
     * @param ChannelPost $obChannelPost
     * @param User|null $obUser
     * @return bool;
     */
    public function destroy(ChannelPost $obChannelPost, ?User $obUser = null): bool;

    /**
     * @param int $iChannelPostId
     * @return bool
     */
    public function destroyById(int $iChannelPostId): bool;

}
