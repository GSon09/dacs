<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\PhoneVerification;
use App\Mail\PhoneOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        return view('index');
    }

    /**
     * Show the account edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('user.account', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();

        $data = $request->only(['name', 'phone', 'address']);

        try {
            $old = $user->only(array_keys($data));
            $user->fill($data);
            $user->save();

            // Log changed fields
            $changes = [];
            foreach ($data as $k => $v) {
                if (array_key_exists($k, $old) && $old[$k] !== $v) {
                    $changes[$k] = ['old' => $old[$k], 'new' => $v];
                }
            }
            if (!empty($changes)) {
                \Log::info('User profile updated', ['user_id' => $user->id, 'changes' => $changes]);
            }

            return redirect()->route('user.account.edit')->with('success', 'Cập nhật thành công');
        } catch (\Throwable $e) {
            \Log::error('Failed to update user profile', ['user_id' => $user->id ?? null, 'error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Có lỗi khi cập nhật, vui lòng thử lại sau.']);
        }
    }

    /**
     * Send OTP to user's email to verify new phone number.
     */
    public function sendPhoneOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{9,15}$/'],
        ]);

        $user = auth()->user();
        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));

        // generate 6-digit code
        $code = rand(100000, 999999);

        $expiresAt = Carbon::now()->addMinutes(10);

        $pv = PhoneVerification::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => (string)$code,
            'expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($user->email)->send(new PhoneOtpMail($code));
            return redirect()->route('user.account.edit')->with('success', 'Mã xác thực đã được gửi tới email của bạn.');
        } catch (\Throwable $e) {
            \Log::error('Failed to send phone OTP', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Không thể gửi mã xác thực. Vui lòng thử lại sau.']);
        }
    }

    /**
     * Verify OTP and update phone if valid.
     */
    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{9,15}$/'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $code = $request->input('code');

        $pv = PhoneVerification::where('user_id', $user->id)
            ->where('phone', $phone)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>=', Carbon::now())
            ->latest()
            ->first();

        if (!$pv) {
            return back()->withErrors(['general' => 'Mã xác thực không hợp lệ hoặc đã hết hạn.']);
        }

        try {
            $pv->used = true;
            $pv->save();

            $oldPhone = $user->phone;
            $user->phone = $phone;
            $user->save();

            \Log::info('User phone updated via OTP', ['user_id' => $user->id, 'old' => $oldPhone, 'new' => $phone]);

            return redirect()->route('user.account.edit')->with('success', 'Số điện thoại đã được cập nhật.');
        } catch (\Throwable $e) {
            \Log::error('Failed to verify phone OTP', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Không thể cập nhật số điện thoại, vui lòng thử lại sau.']);
        }
    }
}
