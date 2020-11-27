<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HomeContent;
use App\AboutUs;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    private $id;

    public function __construct()
    {
        $this->id = 1001;
    }

    /** Home Content Function Starts Here **/
    public function edit()
    {
        $row = HomeContent::first();

        /* Send Banner Image With Full Path */
        $row->banner_image_disp = $row && $row->banner_img ? asset(Config::get("constants.home_content_folder") . $row->banner_img) : '';

        /* Send Video With Full Path */
        $row->video_file_disp = $row && $row->video_file ? asset(Config::get("constants.home_content_folder") . $row->video_file) : '';

        /* Send Video Poster With Full Path */
        $row->video_file_poster_disp = $row && $row->video_file_poster ? asset(Config::get("constants.home_content_folder") . $row->video_file_poster) : '';

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $row
        ], 200);
    }

    private function processImage($file, $type, $destination)
    {
        $new_file_name = "";
        $new_file_name = $type . "-" . time() . "-" . $file;
        $source = public_path(Config::get("constants.temp_folder")) . $file;
        $destination = public_path($destination) . $new_file_name;
        rename($source, $destination);
        return $new_file_name;
    }


    public function save(Request $request)
    {
        /* Validate Input Text Data */
        $this->validate($request, [
            "banner_status" => ['required', 'boolean'],
            "banner_heading" => ['required', 'max:255'],
            "banner_text" => ['required', 'max:255'],
            "banner_button_text" => ['required', 'max:255'],
            "banner_button_link" => ['required', 'max:255'],
            "disclosure" => ['nullable'],
            "video_status" => ['required', 'boolean'],
            "video_title" => ['required', 'max:255'],
            "video_button_text" => ['required', 'max:255'],
            "video_button_link" => ['required', 'max:255'],
            "video_type" => ['required', 'max:10'],
            "about_status" => ['required', 'boolean'],
            "about_heading" => ['required', 'max:255'],
            "about_text" => ['required'],
            "about_button_text" => ['required', 'max:255'],
            "about_button_link" => ['required', 'max:255'],
            "etf_status" => ['required', 'boolean'],
            "etf_heading" => ['required', 'max:255'],
            "news_status" => ['required', 'boolean'],
            "news_heading" => ['required', 'max:255'],
            "contact_status" => ['required', 'boolean'],
            "contact_heading" => ['required', 'max:255'],
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

        /* Validate Video If Embed */
        if ($request->video_type == "EMBED") {
            if (empty($request->video_embed)) {
                return response()->json([
                    'code'    => 422,
                    'message' => "The given data was invalid.",
                    'errors'  => ['video_embed' => "The embed video field is required."]

                ], 422);
            }
        }

        /* Validate Video If File */
        if ($request->video_type == "FILE") {
            if (empty($request->video_file) && empty($request->video_file_disp)) {
                return response()->json([
                    'code'    => 422,
                    'message' => "The given data was invalid.",
                    'errors'  => ['video_file' => "The video field is required."]

                ], 422);
            }

            if (empty($request->video_file_poster) && empty($request->video_file_poster_disp)) {
                return response()->json([
                    'code'    => 422,
                    'message' => "The given data was invalid.",
                    'errors'  => ['video_file_poster' => "The video poster field is required."]

                ], 422);
            }
        } else {
            $request->video_file = NULL;
            $request->video_file_poster = NULL;
        }


        /* Update Or Create Text Data */
        $content = HomeContent::updateOrCreate(['id' => $this->id], [
            "banner_status" => $request->banner_status,
            "banner_heading" => $request->banner_heading,
            "banner_text" => $request->banner_text,
            "banner_button_text" => $request->banner_button_text,
            "banner_button_link" => $request->banner_button_link,
            "disclosure" => $request->disclosure,
            "video_status" => $request->video_status,
            "video_title" => $request->video_title,
            "video_button_text" => $request->video_button_text,
            "video_button_link" => $request->video_button_link,
            "video_type" => $request->video_type,
            "about_status" => $request->about_status,
            "about_heading" => $request->about_heading,
            "about_text" => $request->about_text,
            "about_button_text" => $request->about_button_text,
            "about_button_link" => $request->about_button_link,
            "etf_status" => $request->etf_status,
            "etf_heading" => $request->etf_heading,
            "news_status" => $request->news_status,
            "news_heading" => $request->news_heading,
            "contact_status" => $request->contact_status,
            "contact_heading" => $request->contact_heading,
            "meta_title" => $request->meta_title,
            "meta_keyword" => $request->meta_keyword,
            "meta_description" => $request->meta_description,
            "video_embed" => $request->video_embed,
        ]);

        /* Upload Banner Image */
        if (!empty($request->banner_image)) {
            $file_name = $this->processImage($request->banner_image, 'BANNER_', Config::get("constants.home_content_folder"));
            $content->update([
                "banner_img" => $file_name
            ]);
        }

        /* Upload Video */
        if (!empty(request("video_file"))) {
            $file_name = $this->processImage($request->video_file, 'HOME_VIDEO_', Config::get("constants.home_content_folder"));
            $content->update([
                "video_file" => $file_name
            ]);
        }

        /* Upload Video Poster*/
        if (!empty(request("video_file_poster"))) {
            $file_name = $this->processImage($request->video_file_poster, 'HOME_VIDEO_POSTER_', Config::get("constants.home_content_folder"));
            $content->update([
                "video_file_poster" => $file_name
            ]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $content
        ], 200);
    }


    public function removeFile(Request $request)
    {
        $field = $request->field;
        $stype = $request->stype;

        if (!empty($field)) {
            if (File::exists(public_path(Config::get("constants.temp_folder")) . $field)) {
                File::delete(public_path(Config::get("constants.temp_folder")) . $field);
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => 1
            ], 200);
        } else {
            $res = 0;
            $content = HomeContent::where('id', $this->id)->first();
            if (!empty($content)) {
                if ($stype == 'banner_image') {
                    $file = $content->banner_img ? $content->banner_img : '';
                }

                if (!empty($file)) {
                    if (File::exists(public_path(Config::get("constants.home_content_folder")) . $file)) {
                        File::delete(public_path(Config::get("constants.home_content_folder")) . $file);
                    }
                }

                if ($stype == 'banner_image') {
                    $res = $content->update(['banner_img' => '']);
                }
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => ($res == true ? 1 : 0)
            ], 200);
        }
    }
    /** Home Content Function Ends Here **/

    /** About Us Function Starts Here **/
    public function aboutUsEdit()
    {
        $row = AboutUs::first();

        /* Send Banner With Full Path */
        $row->banner_image_disp = $row->banner_img ? asset(Config::get("constants.aboutus_folder") . $row->banner_img) : '';

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $row
        ], 200);
    }

    public function saveAboutUs(Request $request)
    {
        /* Validate Text Data */
        $this->validate($request, [
            "title" => ['required', 'max:255'],
            "why_cobot_text" => ['required'],
            "our_approach_text" => ['required'],
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
        $about = AboutUs::updateOrCreate(['id' => $this->id], [
            "title" => $request->title,
            "why_cobot_text" => $request->why_cobot_text,
            "our_approach_text" => $request->our_approach_text,
            'disclosure' => $request->disclosure,
            "meta_title" => $request->meta_title,
            "meta_keyword" => $request->meta_keyword,
            "meta_description" => $request->meta_description
        ]);

        /* Upload Banner Image */
        if (!empty($request->banner_image)) {
            $file_name = $this->processImage($request->banner_image, 'BANNER_', Config::get("constants.aboutus_folder"));
            $about->update([
                "banner_img" => $file_name
            ]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $about
        ], 200);
    }

    public function removeAboutUsBanner()
    {
        if (!empty(request('field'))) {
            if (File::exists(public_path(Config::get("constants.temp_folder")) . request('field'))) {
                File::delete(public_path(Config::get("constants.temp_folder")) . request('field'));
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => 1
            ], 200);
        } else {
            $res = 0;
            $content = AboutUs::where('id', $this->id)->first();
            if (!empty($content)) {
                $file = $content->banner_img ? $content->banner_img : '';
                if (!empty($file)) {
                    if (File::exists(public_path(Config::get("constants.aboutus_folder")) . $file)) {
                        File::delete(public_path(Config::get("constants.aboutus_folder")) . $file);
                    }
                }
                $res = $content->update(['banner_img' => '']);
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => ($res == true ? 1 : 0)
            ], 200);
        }
    }
    /** About Us Function Ends Here **/
}
