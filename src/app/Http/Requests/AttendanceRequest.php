<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in' => 'required',
            'clock_out' => 'required',
            'note' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $in = strtotime($this->clock_in);
            $out = strtotime($this->clock_out);

            if ($in >= $out) {
                $validator->errors()->add('clock', '出勤時間もしくは退勤時間が不適切な値です');
            }

            if ($this->break_start) {
                foreach ($this->break_start as $i => $start) {
                    $end = $this->break_end[$i] ?? null;

                    if ($start && $end) {
                        $s = strtotime($start);
                        $e = strtotime($end);

                        if ($s < $in || $s > $out) {
                            $validator->errors()->add('break', '休憩時間が不適切な値です');
                        }

                        if ($e > $out) {
                            $validator->errors()->add('break', '休憩時間もしくは退勤時間が不適切な値です');
                        }
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }
}
