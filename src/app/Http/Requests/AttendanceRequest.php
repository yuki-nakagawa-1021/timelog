<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clock_in' => ['required'],
            'clock_out' => ['required'],
            'note' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $in = strtotime($this->clock_in);
            $out = strtotime($this->clock_out);

            if ($in && $out && $in >= $out) {
                $validator->errors()->add(
                    'clock',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            if ($this->break_start && $this->break_end) {
                foreach ($this->break_start as $i => $start) {
                    $end = $this->break_end[$i] ?? null;

                    if (!$start || !$end) {
                        continue;
                    }

                    $s = strtotime($start);
                    $e = strtotime($end);

                    if ($s < $in || $s > $out) {
                        $validator->errors()->add(
                            'break',
                            '休憩時間が不適切な値です'
                        );
                    }

                    if ($e > $out) {
                        $validator->errors()->add(
                            'break',
                            '休憩時間もしくは退勤時間が不適切な値です'
                        );
                    }
                }
            }
        });
    }
}