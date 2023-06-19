<?php

namespace App\Models;

use App\Http\Resources\NotificationResource;
use App\Traits\SerializeDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotification;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use HasFactory;
    use SerializeDate;
    use HasTranslations;

    public $translatable = ['title', 'body'];

    public $table = 'notifications';

    protected $fillable = [
        'title',
        'body',
        'schedule',
        'sent_at',
        'type',
    ];

    protected $casts = [
        'schedule' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public const TYPE_SELECT = [
        1 => 'Group',
        2 => 'Personal',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['read']);
    }

    public function send()
    {
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setContentAvailable(0);
            $optionBuilder->setMutableContent(1);

            $notificationBuilder = new PayloadNotificationBuilder($this->title);
            $notificationBuilder->setBody($this->body);
            $notificationBuilder->setSound('default');
            $notificationBuilder->setIcon('ic_launcher');
            $notificationBuilder->setClickAction([
                $this->type == 2 ? 'SUBSCRIPTION' : 'HOME' 
            ]);

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['notification' => json_encode(new NotificationResource($this))]);

            $option = $optionBuilder->build();
            $fcm_notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $users_id = $this->users->pluck('id')->toArray();
            $tokens = FirebaseToken::where('user_id', $users_id)->pluck('fcm_token')->whereNotNull()->unique()->toArray();

            if (count($tokens)) {
                $downstreamResponse = FCM::sendTo($tokens, $option, $fcm_notification, $data);
                // info( $downstreamResponse->tokensWithError() );

                $deletes = $downstreamResponse->tokensToDelete();
                if (!empty($deletes)) {
                    FirebaseToken::whereIn('fcm_token', $deletes)->delete();
                }

                $edits = $downstreamResponse->tokensToModify();
                if (!empty($edits)) {
                    $tokens = FirebaseToken::whereIn('fcm_token', array_keys($edits))->get();
                    $new_tokens = $tokens->map(function ($item) use ($edits) {
                        return ['user_id' => $item->user_id, 'fcm_token' => $edits[$item->fcm_token]];
                    });
                    FirebaseToken::whereIn('id', $tokens->pluck('id')->toArray())->delete();
                    FirebaseToken::insert($new_tokens->toArray());
                }
            }

            $this->update(['sent_at' => now()]);

            return true;
        } catch (\Exception $e) {
            info($e);
            return false;
        }
    }

}
