<?php

namespace Server\Others\Observers;

use Server\Models\Admin;

class ColdObserver extends BaseObserver
{

    public function created($data)
    {

        $classArr = explode('\\', get_class($data));
        $class = strtoupper($classArr[count($classArr) - 1]);

        $new = $data->address ?? $data->link ?? $data->email ?? $data->direction ?? "";
        $body = $class . ' CREATED ==> ' . $new;

        $admin = Admin::where('id', 1)->first();
        $this->sender->sendEmail([$admin->email], $body, $body);
    }

    public function updated($data)
    {

        $classArr = explode('\\', get_class($data));
        $class = strtoupper($classArr[count($classArr) - 1]);

        $new = $data->address ?? $data->link ?? $data->email ?? $data->direction ?? "";
        $body = $class . ' UPDATED ==> ' . $new;

        $admin = Admin::where('id', 1)->first();
        $this->sender->sendEmail([$admin->email], $body, $body);
    }

    public function deleted($data)
    {

        $classArr = explode('\\', get_class($data));
        $class = strtoupper($classArr[count($classArr) - 1]);

        $new = $data->address ?? $data->link ?? $data->email ?? $data->direction ?? "";
        $body = $class . ' DELETED ==> ' . $new;

        $admin = Admin::where('id', 1)->first();
        $this->sender->sendEmail([$admin->email], $body, $body);
    }
}
