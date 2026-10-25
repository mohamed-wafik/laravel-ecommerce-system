<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settings) {}

    public function index()
    {
        return $this->success($this->settings->all());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'tax_rate' => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'delivery_fee_standard' => ['sometimes', 'numeric', 'min:0'],
            'delivery_fee_express' => ['sometimes', 'numeric', 'min:0'],
            'free_delivery_threshold' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'store_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $this->settings->setMany($data);

        return $this->success($this->settings->all(), 'Settings updated');
    }
}
