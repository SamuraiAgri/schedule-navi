<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 回答作成リクエストのバリデーション
 */
class StoreResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_name' => ['required', 'string', 'min:1', 'max:100'],
            'participant_email' => ['nullable', 'email', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['boolean'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => ['nullable', 'string', 'in:ok,ng,maybe'],
        ];
    }

    public function messages(): array
    {
        return [
            'participant_name.required' => 'お名前を入力してください',
            'participant_name.min' => 'お名前を入力してください',
            'participant_name.max' => 'お名前は100文字以内で入力してください',
            'participant_email.email' => '正しいメールアドレス形式で入力してください',
            'comment.max' => 'コメントは1000文字以内で入力してください',
            'answers.required' => '少なくとも1つの日程に回答してください',
            'answers.min' => '少なくとも1つの日程に回答してください',
            'answers.*.in' => '無効な回答です',
        ];
    }

    public function attributes(): array
    {
        return [
            'participant_name' => 'お名前',
            'participant_email' => 'メールアドレス',
            'comment' => 'コメント',
            'answers' => '回答',
        ];
    }

    /**
     * バリデーション後の処理
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $answers = $this->input('answers', []);
            $hasAnswer = collect($answers)->filter(fn($v) => $v !== null)->isNotEmpty();
            
            if (!$hasAnswer) {
                $validator->errors()->add('answers', '少なくとも1つの日程に回答してください');
            }
        });
    }
}
