<?php

return [
    'Models' => [
        'User',
        'Car',
        'Station',
        'Service',
        'Company',
        'Request',
        'Plan',
        'Subscription',
        'Banner',
        'Notification',
        'VerificationCode',
        'FcmToken',
        'Settings',
    ],
    'Dashboard' => [
        'Users' => ['Admins', 'Stations', 'Customers'] + ['Cars', 'Subscriptions'],
        'Stations' => [] + ['Services'],
        'Requests',
        'Plans' => [] + ['Subscriptions'],
        'Banners',
        'Notifications',
        'Settings',
    ],
    'Apis' => [
        'Auth' => ['login', 'socialLogin', 'register', 'verify', 'resendVerifictaionCode', 'requestPasswordReset', 'resetPassword', 'getTermsAndConditions'],
        'User' => ['profile', 'update', 'changePassword', 'Notifications', 'markNotificationAsRead', 'AddCar', 'listCars'],
        'Station' => ['getFilters', 'index', 'details'],
        'Subscription' => ['getPlans', 'subscribe', 'getMySubscriptions'],
        'Request' => ['getMyRequests'],
        'Home' => ['Home', 'contactUs']
    ],

    'associate employee users with stations',
    'find users using car plate or qr code when adding requests (with active subscription)',
    'banners related models',
    'handle restricted deletes',
    'notification edits',
    'fix global search',
    'change image uploader package',
];

function renamefiles($from, $to)
{
    collect(glob(base_path("database/migrations/*.php")))->each(fn($file) => rename($file, str_replace($from, $to, $file)));
}
