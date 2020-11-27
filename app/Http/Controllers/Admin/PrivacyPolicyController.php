<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PrivacyPolicy;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PrivacyPolicyController extends Controller
{
    private $id;

    public function __construct()
    {
        $this->id = 1001;
    }

    private function processImage($file, $type, $destination)
    {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type . "-" . time() . "-" . $file;
        $SOURCE_PATH = public_path(Config::get("constants.temp_folder")) . $file;
        $DESTINATION_PATH = public_path($destination) . $NEW_FILE_NAME;
        rename($SOURCE_PATH, $DESTINATION_PATH);
        return $NEW_FILE_NAME;
    }

    public function edit()
    {
        $row = PrivacyPolicy::first();

        /* Send Banner Image With Full Path */
        $row->banner_image_disp = $row->banner_img ? asset(Config::get("constants.privacy_policy_folder") . $row->banner_img) : '';

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $row
        ], 200);
    }

    public function save(Request $request)
    {
        /* Validate Input Text Data */
        $this->validate($request, [
            "title" => ['required', 'max:255'],
            "content" => ['required'],
            "disclosure" => ['nullable'],
            "meta_title" => ['nullable', 'max:100'],
            "meta_keyword" => ['nullable', 'max:200'],
            "meta_description" => ['nullable', 'max:200'],
        ]);

        /* Validate Banner Image */
        if (empty($request->banner_image_disp) && empty($request->banner_image)) {
            return response()->json([
                'code'    => 422,
                'message' => "The given data was invalid.",
                'errors'  => ['banner_image' => "The banner image field is required."]

            ], 422);
        }

        /* Update Or Create Text Data */
        $privacy = PrivacyPolicy::updateOrCreate(['id' => $this->id], [
            "title" => $request->title,
            "content" => $request->content,
            "disclosure" => $request->disclosure,
            "meta_title" => $request->meta_title,
            "meta_keyword" => $request->meta_keyword,
            "meta_description" => $request->meta_description
        ]);

        /* Upload Banner Image */
        if (!empty($request->banner_image)) {
            $file_name = $this->processImage($request->banner_image, 'privacy_policy', Config::get("constants.privacy_policy_folder"));
            $privacy->update([
                "banner_img" => $file_name
            ]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $privacy
        ], 200);
    }

    public function removeBanner()
    {
        $policy = PrivacyPolicy::first();
        if (!empty($policy)) {
            if (!empty($policy->banner_img) && File::exists(public_path(Config::get("constants.privacy_policy_folder")) . $policy->banner_img))
                File::delete(public_path(Config::get("constants.privacy_policy_folder")) . $policy->banner_img);

            $policy->update(['banner_img' => null]);
        }

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
}
