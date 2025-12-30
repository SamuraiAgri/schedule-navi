<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * イベント更新リクエストのバリデーション
 */
class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'notify_email' => ['nullable', 'email', 'max:255'],
            'is_time_required' => ['boolean'],
            'is_anonymous_allowed' => ['boolean'],
            'deadline_at' => ['nullable', 'date'],
            'dates' => ['required', 'array', 'min:1', 'max:30'],
            'dates.*.date' => ['required', 'date'],
            'dates.*.time_from' => ['nullable', 'date_format:H:i'],
            'dates.*.time_to' => ['nullable', 'date_format:H:i', 'after:dates.*.time_from'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'イベント名を入力してください',
            'title.max' => 'イベント名は255文字以内で入力してください',
            'description.max' => '説明文は5000文字以内で入力してください',
            'notify_email.email' => '正しいメールアドレス形式で入力してください',
            'deadline_at.date' => '正しい日時形式で入力してください',
            'dates.required' => '候補日程を1つ以上追加してください',
            'dates.min' => '候補日程を1つ以上追加してください',
            'dates.max' => '候補日程は30個までです',
            'dates.*.date.required' => '日付を選択してください',
            'dates.*.date.date' => '正しい日付形式で入力してください',
            'dates.*.time_from.date_format' => '正しい時刻形式（HH:MM）で入力してください',
            'dates.*.time_to.date_format' => '正しい時刻形式（HH:MM）で入力してください',
            'dates.*.time_to.after' => '終了時刻は開始時刻より後にしてください',
        ];
    }
}
