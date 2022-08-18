<?php

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ResizeImageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'album_id' => ['exists:albums,id'],
            'image' => ['required'],
            'w' => ['required', 'regex:/^\d+(\.\d+)?%?$/'], // 50, 50%, 50.123, 50.123%
            'h' => ['regex:/^\d+(\.\d+)?%?$/'], // 50, 50%, 50.123, 50.123%
        ];

        $image = $this->all()['image'] ?? false;
        if ($image && $image instanceof UploadedFile) {
            $rules['image'][] = 'image';
        } else {
            $rules['image'][] = 'url';
        }

        return $rules;
    }
}
