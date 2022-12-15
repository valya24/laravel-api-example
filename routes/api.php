<?php
declare(strict_types=1);

Route::prefix('userfeed')
    ->group(function () {

        Route::group(['middleware' => 'useApiGuard'], function () {
            Route::apiResource('channel', 'ChannelController')->only('show');
            Route::apiResource('channel.post', 'ChannelPostController')->only('index', 'show');
            Route::get('post/{idOrSlug}', 'ChannelPostController@postByIdOrSlug');
            Route::get('feed', 'ChannelPostController@getFeeds');
        });

        Route::middleware(['auth:api'])
            ->group(function () {

                Route::apiResource('channel', 'ChannelController')->only('update');
                Route::apiResource('favorite-channels', 'MyFavoriteChannelsController')->only('index');
                Route::apiResource('channel.post', 'ChannelPostController')->only('store', 'update', 'destroy');
                Route::post('post/image', 'TemporaryUploadController');


                Route::post('channel/{channelId}/subscribe', 'SubscribeController@subscribe');
                Route::delete('channel/{channelId}/subscribe', 'SubscribeController@unsubscribe');

                Route::post('channel/{channelId}/mute', 'SubscribeController@mute');
                Route::delete('channel/{channelId}/mute', 'SubscribeController@unMute');

                Route::apiResource('post.complaint', 'ComplaintController')->only('store');

                Route::post('user/{user_id}/verify', 'VerifyController@verify');
                Route::delete('user/{user_id}/unverify', 'VerifyController@unverify');

                Route::post('post/like', 'LikeController@like');
                Route::post('post/dislike', 'LikeController@dislike');

                //Admin
                Route::apiResource('complaint-post', 'ComplaintPostController')->only('index', 'show', 'update', 'destroy');
                Route::apiResource('complaint-type', 'ComplaintTypeController')->only('index');
                Route::apiResource('complaint-post.complaint', 'ComplaintPostComplaintController')->only('index');
                Route::apiResource('complaint', 'ComplaintController')->only('store');
            });

    });
